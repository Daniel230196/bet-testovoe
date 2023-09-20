<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controller;

use App\Application\UseCase\TeamUseCase;
use App\Application\UseCase\TournamentUseCase;
use App\Infrastructure\Http\Dto\CreateTournamentDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TournamentController extends BaseController
{

    public function __construct(
        private readonly TournamentUseCase $tournamentUseCase,
        private readonly TeamUseCase $teamUseCase,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        parent::__construct($serializer, $validator);
    }

    #[Route('/', 'index', methods: 'GET')]
    public function index(): Response
    {
        return $this->render('tournaments_list.html.twig', [
            'tournaments' => $this->tournamentUseCase->getAllTournaments(),
            'teams' => $this->teamUseCase->getTeamsList(),
        ]);
    }

    #[Route('/tournament/{slug}', name: 'tournaments_list', methods: 'GET')]
    public function tournamentByName(string $slug): Response
    {
        return $this->render('tournament.html.twig', [
            'tournament' => $this->tournamentUseCase->getTournamentRepresentation($slug),
        ]);
    }

    #[Route('/tournament', name: 'create_tournament', methods: 'POST')]
    public function create(Request $request): JsonResponse
    {
        try {
            $dto = $this->getValidDtoFromJson($request->getContent(), CreateTournamentDto::class);
            $this->tournamentUseCase->generateTournament($dto);
        } catch (\Throwable $t) {
            return new JsonResponse(['success' => false, 'message' => $t->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        return new JsonResponse(['success' => true], Response::HTTP_OK);
    }
}
