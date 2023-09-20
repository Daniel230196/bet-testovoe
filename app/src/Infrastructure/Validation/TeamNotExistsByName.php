<?php

declare(strict_types=1);

namespace App\Infrastructure\Validation;


use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class TeamNotExistsByName extends Constraint
{
    public string $message = 'Команда с таким именем уже существует';
}