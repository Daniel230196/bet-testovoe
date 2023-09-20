<?php

namespace App\Repository;

use App\Application\Spi\TeamRepositoryInterface;
use App\Domain\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<Team>
 *
 * @method Team|null find($id, $lockMode = null, $lockVersion = null)
 * @method Team|null findOneBy(array $criteria, array $orderBy = null)
 * @method Team[]    findAll()
 * @method Team[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamRepository extends ServiceEntityRepository implements TeamRepositoryInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
        ManagerRegistry $registry
    ) {
        parent::__construct($registry, Team::class);
    }

    public function persist(Team $team): void
    {
        $this->getEntityManager()->persist($team);
    }

    public function save(Team $team): void
    {
        try {
            $this->getEntityManager()->persist($team);
            $this->getEntityManager()->flush();
        } catch (\Throwable $t) {
            $this->logger->error('Ошибка сохранения команды', ['previous_exception' => $t]);
            throw $t;
        }
    }

    public function delete(Team $team): void
    {
        try {
            $this->getEntityManager()->remove($team);
            $this->getEntityManager()->flush();
        } catch (\Throwable $t) {
            $this->logger->error('Ошибка удаления команды', ['previous_exception' => $t]);
            throw $t;
        }
    }
}
