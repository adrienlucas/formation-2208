<?php

namespace App\Command;

use App\Gateway\OmdbGateway;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-movies-description',
    description: 'Import missing movies description from Omdb API.',
)]
class ImportMoviesDescriptionCommand extends Command
{

    public function __construct(
        private readonly MovieRepository $movieRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly OmdbGateway $omdbGateway,
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

        if($input->hasOption('limit')) {
            $limit = (int) $input->getOption('limit');
        } else {
            $limit = null;
        }

        $movies = $this->movieRepository->findBy(
            ['description' => null],
            limit: $limit
        );

        $io->writeln(sprintf('<info>Will be processing %d movies.</info>', count($movies)));

        $io->progressStart(count($movies));

        $actualCount = 0;
        foreach($movies as $movie) {
            $io->progressAdvance();
            $movieDescription = $this->omdbGateway->getDescriptionByMovie($movie);

            if(!empty($movieDescription)) {
                $actualCount++;
                $movie->setDescription($movieDescription);
            }
        }
        $io->progressFinish();

        $this->entityManager->flush();

        $io->success(sprintf('You have a enriched %d movies.', $actualCount));

        return Command::SUCCESS;
    }
}