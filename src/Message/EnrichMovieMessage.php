<?php

namespace App\Message;

use App\Entity\Movie;

final class EnrichMovieMessage
{
    public function __construct(
        public readonly Movie $movie
    )
    {
    }
}
