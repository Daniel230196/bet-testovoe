<?php

namespace App\Repository;

use App\Application\Spi\MeetingsRepositoryInterface;
use App\Domain\Entity\Meeting;
use App\Domain\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<Meeting>
 *
 * @method Meeting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Meeting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Meeting[]    findAll()
 * @method Meeting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeetingsRepository extends ServiceEntityRepository implements MeetingsRepositoryInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
        ManagerRegistry $registry
    ) {
        parent::__construct($registry, Meeting::class);
    }

    /**
     * @param Team $team
     * @return array<int, Meeting>
     */
    public function findByTeam(Team $team): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.firstTeam = :team')
            ->orWhere('m.secondTeam = :team')
            ->setParameter('team', $team)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param array<int, Meeting> $meetings
     * @return void
     */
    public function removeMeetings(array $meetings): void
    {
        try {
            foreach ($meetings as $meeting) {
                $this->getEntityManager()->remove($meeting);
            }
            $this->getEntityManager()->flush();
        } catch (\Throwable $t) {
            $this->logger->error('Ошибка удаления встречи', ['error' => $t]);
            throw $t;
        }
    }
}
