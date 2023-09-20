<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Dto;

use App\Infrastructure\Validation\TeamNotExistsByName;
use Symfony\Component\Validator\Constraints as Assert;

class CreateTeamDto
{
    #[Assert\NotBlank(allowNull: false)]
    #[Assert\Length(min: 3, max: 255)]
    #[TeamNotExistsByName]
    public ?string $name;
}