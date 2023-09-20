<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Repository\TournamentRepository;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: TournamentRepository::class)]
#[Gedmo\SoftDeleteable(hardDelete: false)]
class Tournament
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $title;

    /**
     * @var ArrayCollection<int, Meeting>
     */
    #[ORM\OneToMany(targetEntity: Meeting::class, cascade: ['persist', 'remove'], mappedBy: 'tournament')]
    #[ORM\OrderBy(['meetingDate' => 'ASC'])]
    private Collection $meetings;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private CarbonImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Gedmo\Timestampable]
    private CarbonImmutable $updatedAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private CarbonImmutable $deletedAt;

    public function __construct(string $title)
    {
        $this->title = $title;
        $this->meetings = new ArrayCollection();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCreatedAt(): CarbonImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): CarbonImmutable
    {
        return $this->updatedAt;
    }

    public function getFirstMeetingDate(): ?CarbonImmutable
    {
        $firstMeeting = $this->meetings->first();
        return ($firstMeeting) ? $firstMeeting->getMeetingDate() : null;
    }

    public function getLastMeetingDate(): ?CarbonImmutable
    {
        $lastMeeting = $this->meetings->last();
        return ($lastMeeting) ? $lastMeeting->getMeetingDate() : null;
    }

    /**
     * @param ArrayCollection<int, Meeting> $meetings
     */
    public function setMeetings(ArrayCollection $meetings): void
    {
        $this->meetings = $meetings;
    }

    public function getMeetings(): array
    {
        return $this->meetings->toArray();
    }
}