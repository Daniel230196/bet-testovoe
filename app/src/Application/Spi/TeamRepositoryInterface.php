<?php

declare(strict_types=1);

namespace App\Application\Spi;

use App\Domain\Entity\Team;

interface TeamRepositoryInterface
{
    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param $limit
     * @param $offset
     * @return Team[]
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    /**
     * @return array<int, Team>
     */
    public function findAll();

    public function persist(Team $team): void;

    public function save(Team $team): void;

    public function delete(Team $team): void;
}