<?php

namespace App\Repository;

use App\Entity\Illness;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Illness>
 *
 * @method Illness|null find($id, $lockMode = null, $lockVersion = null)
 * @method Illness|null findOneBy(array $criteria, array $orderBy = null)
 * @method Illness[]    findAll()
 * @method Illness[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IllnessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Illness::class);
    }

    public function add(Illness $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Illness $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Illness[]
     */
    public function findAllSorted(array $sorting = [])
    {
        $fields = array_keys($this->getClassMetadata()->fieldMappings);
        $qb = $this->createQueryBuilder('i');

        foreach ($fields as $field) {
            if (isset($sorting[$field])) {
                $qb->addOrderBy('i.' . $field, 'ASC');
            }
        }

        return $qb->getQuery()->getResult();
    }
}
