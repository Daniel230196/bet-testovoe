<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Dto;

use App\Infrastructure\Validation\AllTeamsExistsByNames;
use Symfony\Component\Validator\Constraints as Assert;

class CreateTournamentDto
{
    #[Assert\NotBlank(allowNull: false)]
    #[Assert\Length(min: 3, max: 255)]
    public string $title;

    #[Assert\All(
        constraints: [new Assert\NotBlank(), new Assert\Length(min:3, max: 255)]
    )]
    #[AllTeamsExistsByNames]
    public array $teamNames;

    public ?\DateTime $startsAt = null;
}