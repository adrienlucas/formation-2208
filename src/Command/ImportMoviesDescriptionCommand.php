<?php

namespace App\Command;

use App\Gateway\OmdbGateway;
use App\Message\EnrichMovieMessage;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:import-movies-description',
    description: 'Import missing movies description from Omdb API.',
)]
class ImportMoviesDescriptionCommand extends Command
{

    public function __construct(
        private readonly MovieRepository $movieRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly MessageBusInterface $bus,
    )
    {
        parent::__construct();
    }

    public function configure()
    {
        $this->addOption(
            'limit', 'l',
            InputOption::VALUE_REQUIRED,
            'Limit the number of movies to enrich.',
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $limit = $input->getOption('limit');
        if($limit === null) {
            $limit = $io->ask(
                'Do you want to limit the number of movies to enrich ?',
                'no',
                function($limit): ?int {
                    if($limit === 'no' || $limit == (int) $limit) {
                        return $limit === null ? null : (int) $limit;
                    }
                    throw new \Exception('Limit is invalid.');
                }
            );
        }

        $movies = $this->movieRepository->findBy(
            ['description' => null],
            limit: $limit
        );

        $io->writeln(sprintf('<info>Will be processing %d movies.</info>', count($movies)));

        $io->progressStart(count($movies));

        foreach($movies as $movie) {
            $io->progressAdvance();

            $this->bus->dispatch(new EnrichMovieMessage($movie));
        }

        $io->progressFinish();

        $this->entityManager->flush();

        $io->success(sprintf('You have a enriched movies.'));

        return Command::SUCCESS;
    }
}
