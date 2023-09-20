<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controller;

use App\Application\UseCase\TeamUseCase;
use App\Infrastructure\Http\Dto\CreateTeamDto;
use App\Repository\MeetingsRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TeamController extends BaseController
{
    public function __construct(
        private readonly TeamUseCase $teamUseCase,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        parent::__construct($serializer, $validator);
    }

    #[Route('/team', name: 'create_team', methods: 'POST')]
    public function create(Request $request): JsonResponse
    {
        /** @var CreateTeamDto $dto */
        $dto = $this->getValidDtoFromJson($request->getContent(), CreateTeamDto::class);
        $team = $this->teamUseCase->createTeam($dto);
        return new JsonResponse([
            'id' => $team->getId(),
            'name' => $team->getName(),
            'createdAt' => $team->getCreatedAt(),
            'updatedAt' => $team->getUpdatedAt(),
        ], Response::HTTP_OK);
    }

    #[Route('/team/{id<\d+>}', name: 'delete_team', methods: 'DELETE')]
    public function delete(int $id): JsonResponse
    {
        $this->teamUseCase->deleteTeam($id);
        return new JsonResponse([], Response::HTTP_OK);
    }

    #[Route('/teams', name: 'teams_list', methods: 'GET')]
    public function list(): Response
    {
        return $this->render('teams_list.html.twig', [
            'teams' => $this->teamUseCase->getTeamsList(),
        ]);
    }
}