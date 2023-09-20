<?php

declare(strict_types=1);

namespace App\Application\Spi;

use App\Domain\Entity\Tournament;

interface TournamentsRepositoryInterface
{
    public function save(Tournament $tournament): void;

    /**
     * @return array<int, Tournament>
     */
    public function findAll();

    public function findOneByTitle(string $title): ?Tournament;
}