<?php

namespace App\Domain\Entity;

use App\Repository\MeetingsRepository;
use Carbon\CarbonImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: MeetingsRepository::class)]
#[ORM\UniqueConstraint('tournament_team_meetings', fields: ['tournament', 'firstTeam', 'secondTeam'])]
class Meeting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Tournament::class, inversedBy: 'meetings')]
    private Tournament $tournament;

    #[ORM\ManyToOne(targetEntity: Team::class)]
    #[ORM\JoinColumn(name: 'first_team_id', referencedColumnName: 'id')]
    private ?Team $firstTeam;

    #[ORM\ManyToOne(targetEntity: Team::class)]
    #[ORM\JoinColumn(name: 'second_team_id', referencedColumnName: 'id')]
    private ?Team $secondTeam;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private CarbonImmutable $meetingDate;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private CarbonImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Gedmo\Timestampable]
    private CarbonImmutable $updatedAt;

    public function __construct(Team $firstTeam, ?Team $secondTeam, CarbonImmutable $meetingDate, Tournament $tournament)
    {
        $this->firstTeam = $firstTeam;
        $this->secondTeam = $secondTeam;
        $this->meetingDate = $meetingDate;
        $this->tournament = $tournament;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstTeam(): ?Team
    {
        return $this->firstTeam;
    }

    public function setFirstTeam(object $firstTeam): static
    {
        $this->firstTeam = $firstTeam;

        return $this;
    }

    public function getSecondTeam(): ?object
    {
        return $this->secondTeam;
    }

    public function setSecondTeam(object $secondTeam): static
    {
        $this->secondTeam = $secondTeam;

        return $this;
    }

    public function getMeetingDate(): CarbonImmutable
    {
        return $this->meetingDate;
    }
}
