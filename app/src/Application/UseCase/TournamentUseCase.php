<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Spi\TeamRepositoryInterface;
use App\Application\Spi\TournamentsRepositoryInterface;
use App\Domain\Entity\Meeting;
use App\Domain\Entity\Team;
use App\Domain\Entity\Tournament;
use App\Infrastructure\Http\Dto\CreateTournamentDto;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;

class TournamentUseCase
{
    private const MAX_MEETINGS_PER_DAY = 4;

    public function __construct(
        private readonly TeamRepositoryInterface $teamRepository,
        private readonly TournamentsRepositoryInterface $tournamentsRepository
    ) {
    }

    public function generateTournament(CreateTournamentDto $dto): Tournament
    {
        $teams = $this->getTeamsForTournament($dto);
        if (count($teams) < 2) {
            throw new \LogicException('Недостаточно команд для турнира');
        }

        [$firstGroup, $secondGroup] = $this->prepareTeamGroups($teams);
        rsort($secondGroup);

        $tournament = new Tournament($dto->title);
        $firstGroupTeamsCount = count($firstGroup);
        $meetings = new ArrayCollection();
        $meetingDate = $dto->startsAt ?? new \DateTime();
        $meetingDate->setTime(9, 0);
        $tournamentDatesMap = $this->buildDateTournamentMap(count($teams), $firstGroupTeamsCount, $meetingDate);


        for ($roundNumber = 1; $roundNumber <= count($teams) - 1; $roundNumber++) {

            for ($teamIndex = 0; $teamIndex < $firstGroupTeamsCount; $teamIndex++) {

                $firstTeam = $firstGroup[$teamIndex];
                $secondTeam = $secondGroup[$teamIndex];

                if (null !== $secondTeam && null !== $firstTeam) {
                    $meetingDate = $this->calculateMeetingDate(
                        $firstTeam->getId(),
                        $secondTeam->getId(),
                        $tournamentDatesMap
                    );
                    $meetingDate->modify('+2 hours');
                    $meetings[] = new Meeting(
                        $firstTeam,
                        $secondTeam,
                        CarbonImmutable::instance($meetingDate),
                        $tournament
                    );
                }
            }
            $temp = array_shift($firstGroup);
            array_unshift($firstGroup, array_shift($secondGroup));
            array_unshift($firstGroup, $temp);
            $secondGroup[] = array_pop($firstGroup);
        }
        $tournament->setMeetings($meetings);
        $this->tournamentsRepository->save($tournament);

        return $tournament;
    }

    public function getTournamentRepresentation(string $tournamentTitle): array
    {
        $tournament = $this->tournamentsRepository->findOneByTitle($tournamentTitle);
        if (!$tournament) {
            return [];
        }

        return [
            'title' => $tournament->getTitle(),
            'dateFrom' => $tournament->getFirstMeetingDate(),
            'dateTo' => $tournament->getLastMeetingDate(),
            'meetings' => $tournament->getMeetings(),
        ];
    }

    /**
     * @return array<int, Tournament>
     */
    public function getAllTournaments(): array
    {
        return $this->tournamentsRepository->findAll();
    }

    /**
     * @param array<int, Team> $teams
     * @return array<int, array<int, Team>>
     */
    private function prepareTeamGroups(array $teams): array
    {
        [$firstGroup, $secondGroup] = array_chunk($teams, (int)ceil(count($teams)/2));
        if (count($secondGroup) !== count($firstGroup)) {
            // участник-заглушка для нечетного количества команд
            $secondGroup[] = null;
        }

        return [$firstGroup, $secondGroup];
    }

    /**
     * @param CreateTournamentDto $dto
     * @return array<int, Team>
     */
    private function getTeamsForTournament(CreateTournamentDto $dto): array
    {
        return count($dto->teamNames) > 0
            ? $this->teamRepository->findBy(['name' => $dto->teamNames])
            : $this->teamRepository->findAll();
    }

    private function buildDateTournamentMap(int $teamsCount, int $firstGroupTeamsCount, \DateTime $initialDate): array
    {
        $tournamentDaysCount = (int)ceil((($teamsCount) * $firstGroupTeamsCount) / self::MAX_MEETINGS_PER_DAY);
        $dateForMapping = clone $initialDate;
        $result = [];
        for ($i = 0; $i < $tournamentDaysCount; $i++) {
            $result[$dateForMapping->format('d/m/y')] = [
                'count_matches' => 0,
                'played_team_ids' => [],
                'date' => $dateForMapping,
            ];

            $dateForMapping = (clone $dateForMapping)->modify('+1 day');
        }

        return $result;
    }

    /**
     * @param int $firstTeamId
     * @param int $secondTeamId
     * @param array<string, int|array|\DateTime> $teamMeetingsMap
     * @return \DateTime
     */
    private function calculateMeetingDate(int $firstTeamId, int $secondTeamId, array &$teamMeetingsMap): \DateTime
    {
        $availableDates = array_filter($teamMeetingsMap, function (array $value) use($firstTeamId, $secondTeamId) {
            return $value['count_matches'] !== self::MAX_MEETINGS_PER_DAY
                && !in_array($firstTeamId, $value['played_team_ids'], true)
                && !in_array($secondTeamId, $value['played_team_ids'], true);
        });

        $targetMapping = current($availableDates);
        $targetDatePlayedTeams = $targetMapping['played_team_ids'];
        if (!is_array($targetDatePlayedTeams)) {
            throw new \LogicException();
        }
        $targetDate = $targetMapping['date'];
        $teamMeetingsMap[$targetDate->format('d/m/y')]['played_team_ids'][] = $firstTeamId;
        $teamMeetingsMap[$targetDate->format('d/m/y')]['played_team_ids'][] = $secondTeamId;
        $teamMeetingsMap[$targetDate->format('d/m/y')]['count_matches']++;

        return $targetDate;
    }
}