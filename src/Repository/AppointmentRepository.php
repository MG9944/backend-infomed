<?php

namespace App\Repository;

use App\Entity\Appointment;
use App\Entity\Patient;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Appointment>
 *
 * @method Appointment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Appointment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Appointment[]    findAll()
 * @method Appointment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppointmentRepository extends ServiceEntityRepository
{
    public const APPOINTMENT_NEW = 1;
    public const APPOINTMENT_EDIT = 2;
    public const APPOINTMENT_CANCELED = 3;
    public const APPOINTMENT_TAKEN = 4;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointment::class);
    }

    public function add(Appointment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Appointment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return array|Appointment[]
     */
    public function getAllByDoctorAndDate(User $user, DateTime $dateTime): array
    {
        $dateStart = clone $dateTime;
        $dateStart->setDate($dateTime->format('Y'), $dateTime->format('m'), '01');
        $dateStart->setTime(0, 0, 0, 0);

        $dateEnd = clone $dateTime;
        $dateEnd->setDate($dateTime->format('Y'), $dateTime->format('m'), $dateTime->format('t'));;
        $dateEnd->setTime(0, 0, 0, 0);


        return $this->createQueryBuilder('a')
            ->andWhere('a.user = :USER')
            ->andWhere(':DATE_START <= a.appointmentDate')
            ->andWhere(':DATE_END >= a.appointmentDate')
            ->andWhere('a.status IN(:STATUS)')
            ->orderBy('a.appointmentDate', 'ASC')
            ->setParameters(
                [
                    'USER' => $user,
                    'DATE_START' => $dateStart,
                    'DATE_END' => $dateEnd,
                    'STATUS' => [AppointmentRepository::APPOINTMENT_NEW, AppointmentRepository::APPOINTMENT_EDIT],
                ]
            )
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array|Appointment[]
     */
    public function getAppointmentsByPatientAndDate(Patient $patient, DateTime $dateTime)
    {
        $dateStart = clone $dateTime;
        $dateStart->setDate($dateTime->format('Y'), $dateTime->format('m'), $dateTime->format('d'));
        $dateStart->setTime(0, 0, 0, 0);

        $dateEnd = clone $dateTime;
        $dateEnd->setDate($dateTime->format('Y'), $dateTime->format('m'), $dateTime->format('d'));
        $dateEnd->setTime(23, 59, 59, 59);

        return $this->createQueryBuilder('a')
            ->join('App\Entity\Patient', 'p', 'WITH', 'a.idPatient = p.id')
            ->where('a.appointmentDate BETWEEN :dateStart AND :dateEnd')
            ->andWhere('p.id = :USER')
            ->andWhere('a.status IN (:STATUS)')
            ->setParameters(
                [
                    'USER' => $patient,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                    'STATUS' => [AppointmentRepository::APPOINTMENT_NEW, AppointmentRepository::APPOINTMENT_EDIT],
                ]
            )
            ->getQuery()
            ->getResult();
    }

    public function getAllByDate(User $user): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('a.user = :USER')
            ->andWhere('e.status IN(:STATUS)')
            ->setParameters(
                [
                    'USER' => $user,
                    'STATUS' => [self::APPOINTMENT_NEW, self::APPOINTMENT_EDIT],
                ]
            )
            ->getQuery()
            ->getResult();
    }
}
