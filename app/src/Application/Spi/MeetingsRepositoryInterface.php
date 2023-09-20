<?php

declare(strict_types=1);

namespace App\Application\Spi;

use App\Domain\Entity\Meeting;
use App\Domain\Entity\Team;

interface MeetingsRepositoryInterface
{
    /**
     * @param Team $team
     * @return array<int, Meeting>
     */
    public function findByTeam(Team $team): array;

    /**
     * @param array<int, Meeting> $meetings
     * @return void
     */
    public function removeMeetings(array $meetings): void;
}