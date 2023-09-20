<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Repository\TeamRepository;
use Carbon\CarbonImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Table(name: 'teams')]
#[ORM\Entity(repositoryClass: TeamRepository::class)]
#[ORM\UniqueConstraint('teams', ['name', 'deleted_at'])]
#[UniqueEntity(fields: ['name', 'deletedAt'], ignoreNull: false)]
#[Gedmo\SoftDeleteable(hardDelete: false)]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private CarbonImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Gedmo\Timestampable]
    private CarbonImmutable $updatedAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private CarbonImmutable $deletedAt;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCreatedAt(): CarbonImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): CarbonImmutable
    {
        return $this->updatedAt;
    }

}