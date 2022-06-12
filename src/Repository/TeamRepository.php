<?php

namespace App\Repository;

use _PHPStan_3a7a22dbd\Nette\Neon\Exception;
use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use function Doctrine\ORM\QueryBuilder;

/**
 * @method Team|null find($id, $lockMode = null, $lockVersion = null)
 * @method Team|null findOneBy(array $criteria, array $orderBy = null)
 * @method Team[]    findAll()
 * @method Team[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    public function persist(Team $team)
    {
        $em = $this->getEntityManager();
        $em->persist($team);
        $em->flush();
    }

    public function remove(Team $team)
    {
        $em = $this->getEntityManager();
        $em->remove($team);
        $em->flush();
    }

    public function getTeams(int $page = 1, ?string $name = null, int $limit = 50)
    {
        $firstResult = $page == 1 ? 0 : $limit * $page;
        $qb = $this->_em->createQueryBuilder();
        $qb->select('t')->from(Team::class, 't')
            ->setFirstResult($firstResult)
            ->setMaxResults($limit);
        if ($name) {
            $qb->andWhere($qb->expr()->like('t.name', ':name'))
                ->setParameters(['name' => $name . '%']);
        }
        return $qb->getQuery()->getResult();
    }


    // /**
    //  * @return TeamDto[] Returns an array of TeamDto objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TeamDto
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
