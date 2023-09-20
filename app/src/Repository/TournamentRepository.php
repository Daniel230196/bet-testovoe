<?php

namespace App\Repository;

use App\Application\Spi\TournamentsRepositoryInterface;
use App\Domain\Entity\Tournament;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<Tournament>
 *
 * @method Tournament|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tournament|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tournament[]    findAll()
 * @method Tournament[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TournamentRepository extends ServiceEntityRepository implements TournamentsRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly LoggerInterface $logger
    )
    {
        parent::__construct($registry, Tournament::class);
    }

    public function save(Tournament $tournament): void
    {
        try {
            $this->getEntityManager()->persist($tournament);
            $this->getEntityManager()->flush();
        } catch (\Throwable $t) {
            $this->logger->error('Ошибка сохранения команды', ['previous_exception' => $t]);
            throw $t;
        }
    }

    public function findOneByTitle(string $title): ?Tournament
    {
        return $this->findOneBy(['title' => $title], ['createdAt' => 'DESC']);
    }
}
