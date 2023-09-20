<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseController extends AbstractController
{
    private const JSON_FORMAT = 'json';

    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator
    ) {
    }
    protected function getValidDtoFromJson(string $requestContent, string $targetClass): object
    {
        $dto = $this->serializer->deserialize($requestContent, $targetClass, self::JSON_FORMAT);

        $violations = $this->validator->validate($dto);
        if (count($violations) > 0) {
            $message = '';
            foreach ($violations as $violation) {
                $message .= $violation->getMessage();
            }
            throw new BadRequestException($message);
        }

        return $dto;
    }
}