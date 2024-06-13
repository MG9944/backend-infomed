<?php

namespace App\Repository;

use App\Entity\Patient;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Patient>
 *
 * @method Patient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Patient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Patient[]    findAll()
 * @method Patient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PatientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Patient::class);
    }

    public function add(Patient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Patient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Patient[]
     */
    public function findAllByMedicalCenter(User $doctor, array $sorting = [])
    {
        $fields = array_keys($this->getClassMetadata()->fieldMappings);
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.medicalCenter = :USER')
            ->setParameters(
                [
                    'USER' => $doctor->getMedicalCenter()->getId(),
                ]
            );

        foreach ($fields as $field) {
            if (isset($sorting[$field])) {
                $qb->addOrderBy('p.' . $field, 'ASC');
            }
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Patient[]
     */
    public function findAllByPatientsInMedicalCenterPatientCard(User $doctor, array $sorting = [])
    {
        $fields = array_keys($this->getClassMetadata()->fieldMappings);
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.medicalCenter = :USER')
            ->setParameters(
                [
                    'USER' => $doctor->getMedicalCenter()->getId(),
                ]
            );

        foreach ($fields as $field) {
            if (isset($sorting[$field])) {
                $qb->addOrderBy('p.' . $field, 'ASC');
            }
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Patient[]
     */
    public function getPatients()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('p')
            ->from($this->_entityName, 'p')
            ->orderBy('p.lastname');

        return $qb->getQuery()->getResult();
    }
}
