<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Spi\MeetingsRepositoryInterface;
use App\Application\Spi\TeamRepositoryInterface;
use App\Domain\Entity\Team;
use App\Infrastructure\Http\Dto\CreateTeamDto;

class TeamUseCase
{
    public function __construct(
        private readonly TeamRepositoryInterface $teamRepository,
        private readonly MeetingsRepositoryInterface $meetingsRepository
    ) {

    }

    public function createTeam(CreateTeamDto $dto): Team
    {
        $team = new Team($dto->name);
        $this->teamRepository->save($team);

        return $team;
    }

    public function deleteTeam(int $id)
    {
        $targetTeam = $this->teamRepository->find($id);
        if ($targetTeam === null) {
            return;
        }

        $meetings = $this->meetingsRepository->findByTeam($targetTeam);
        $this->teamRepository->delete($targetTeam);
        $this->meetingsRepository->removeMeetings($meetings);
    }

    /**
     * @return array<int, Team>
     */
    public function  getTeamsList(): array
    {
        return $this->teamRepository->findAll();
    }
}