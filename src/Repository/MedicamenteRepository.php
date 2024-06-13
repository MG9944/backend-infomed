<?php

namespace App\Repository;

use App\Entity\Medicamente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Medicamente>
 *
 * @method Medicamente|null find($id, $lockMode = null, $lockVersion = null)
 * @method Medicamente|null findOneBy(array $criteria, array $orderBy = null)
 * @method Medicamente[]    findAll()
 * @method Medicamente[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MedicamenteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Medicamente::class);
    }

    public function add(Medicamente $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Medicamente $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Medicamente[]
     */
    public function findAllSorted(array $sorting = [])
    {
        $fields = array_keys($this->getClassMetadata()->fieldMappings);
        $qb = $this->createQueryBuilder('m');

        foreach ($fields as $field) {
            if (isset($sorting[$field])) {
                $qb->addOrderBy('m.' . $field, 'ASC');
            }
        }

        return $qb->getQuery()->getResult();
    }
}
