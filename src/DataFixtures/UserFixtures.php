<?php

namespace App\DataFixtures;

use App\Entity\Appointment;
use App\Entity\Illness;
use App\Entity\MedicalCenter;
use App\Entity\Medicamente;
use App\Entity\Patient;
use App\Entity\Specialisation;
use App\Entity\User;
use App\Security\DataChecker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, private readonly DataChecker $dataChecker)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $medicalCenter = new MedicalCenter();
        $medicalCenter->setName('Przychodnia Gdańska');
        $medicalCenter->setAddress('ul.Gdańska 23/2');
        $medicalCenter->setPostCode('83-020');
        $medicalCenter->setCity('Pruszcz Gdański');
        $medicalCenter->setNip('123456789');
        $manager->persist($medicalCenter);
        $manager->flush();

        $specialisations = ['Alergolog', 'Dermatolog', 'Kardiolog', 'Endokrynolog',
            'Gastrolog', 'Nefrolog', 'Urolog', 'Pulmolog', 'Neurolog', ];

        for ($i = 0; $i < 9; ++$i) {
            $specialisation = new Specialisation();
            $specialisation->setName($specialisations[$i]);
            $manager->persist($specialisation);
            $manager->flush();
        }

        $user = new User();
        $user->setFirstname($faker->firstName);
        $user->setLastname($faker->lastName);
        $user->setEmail('admin@test.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'admin1234'));
        $user->setRoles(['ROLE_ADMIN', 'ROLE_DOCTOR']);
        $user->setIsActive(true);
        $user->setPhoneNumber('+48510332182');
        $user->setMedicalCenter($medicalCenter);
        $user->setSpecialisation($specialisation);
        $manager->persist($user);
        $manager->flush();

        $user2 = new User();
        $user2->setFirstname($faker->firstName);
        $user2->setLastname($faker->lastName);
        $user2->setEmail('doctor@test.com');
        $user2->setPassword($this->passwordEncoder->encodePassword($user, 'admin1234'));
        $user2->setRoles(['ROLE_DOCTOR']);
        $user2->setIsActive(true);
        $user2->setMedicalCenter($medicalCenter);
        $user2->setSpecialisation($specialisation);
        $manager->persist($user2);
        $manager->flush();

        $patient = new Patient();
        $patient->setPesel($this->dataChecker->encrypt('12345678901'));
        $patient->setFirstname($faker->firstName);
        $patient->setLastname($faker->lastName);
        $patient->setAddress('ul.Gdańska 41/12');
        $patient->setPostCode('83-000');
        $patient->setCity('Gdańsk');
        $patient->setPhoneNumber('+48123456789');
        $patient->setMedicalCenter($medicalCenter);
        $manager->persist($patient);
        $manager->flush();

        $patient2 = new Patient();
        $patient2->setPesel($this->dataChecker->encrypt('12345678911'));
        $patient2->setFirstname($faker->firstName);
        $patient2->setLastname($faker->lastName);
        $patient2->setAddress('ul.Gdańska 123/1');
        $patient2->setPostCode('83-000');
        $patient2->setCity('Gdańsk');
        $patient2->setPhoneNumber('+48123456789');
        $patient2->setMedicalCenter($medicalCenter);
        $manager->persist($patient2);
        $manager->flush();



        $appointment = new Appointment();
        $appointment->setAppointmentDate(new \DateTime('2023-02-05 8:15'));
        $appointment->setDiagnosis('Ból brzucha');
        $appointment->setStatus(1);
        $appointment->setIdPatient($patient);
        $appointment->setUser($user);
        $manager->persist($appointment);
        $manager->flush();

        $appointment2 = new Appointment();
        $appointment2->setAppointmentDate(new \DateTime('2023-02-05 8:05'));
        $appointment2->setDiagnosis('Ból głowy');
        $appointment2->setStatus(1);
        $appointment2->setIdPatient($patient2);
        $appointment2->setUser($user);
        $manager->persist($appointment2);
        $manager->flush();




        $medicamente = new Medicamente();
        $medicamente->setName('Apap');
        $medicamente->setCategory('Lek przeciwbólowy');
        $medicamente->setAtcCode('M05BA04');
        $medicamente->setFullName('Apap, 500 mg, tabletki powlekane, 50 szt.');
        $medicamente->setFigure('Tabletki');
        $medicamente->setPackageContents('50 szt.');
        $manager->persist($medicamente);
        $manager->flush();

        $medicamente2 = new Medicamente();
        $medicamente2->setName('Cholinex');
        $medicamente2->setCategory('Ból gardła');
        $medicamente2->setAtcCode('F16AA34');
        $medicamente2->setFullName('Cholinex, 150 mg, pastylki, 32 szt.');
        $medicamente2->setFigure('Pastylki');
        $medicamente2->setPackageContents('32 szt');
        $manager->persist($medicamente2);
        $manager->flush();

        $medicamente3 = new Medicamente();
        $medicamente3->setName('4Flex');
        $medicamente3->setCategory('Lek przeciwbólowy');
        $medicamente3->setAtcCode('N45HF18');
        $medicamente3->setFullName('4 Flex PureGel - żel na bóle stawów 100g');
        $medicamente3->setFigure('Żel');
        $medicamente3->setPackageContents('100g');
        $manager->persist($medicamente3);
        $manager->flush();

        $medicamente4 = new Medicamente();
        $medicamente4->setName('Aflofarm');
        $medicamente4->setCategory('Lek przeciwbólowy');
        $medicamente4->setAtcCode('AA12A04');
        $medicamente4->setFullName('Krople żołądkowe, 35 g (Aflofarm)');
        $medicamente4->setFigure('Krople');
        $medicamente4->setPackageContents('35 g');
        $manager->persist($medicamente4);
        $manager->flush();

        $illness = new Illness();
        $illness->setName('Ból pleców');
        $illness->setCategory('Ogólne');
        $illness->addMedicamente($medicamente3);
        $manager->persist($illness);
        $manager->flush();

        $illness2 = new Illness();
        $illness2->setName('Ból gardła');
        $illness2->setCategory('Ogólne');
        $illness2->addMedicamente($medicamente2);
        $manager->persist($illness2);
        $manager->flush();

        $illness3 = new Illness();
        $illness3->setName('Wysoka temperatura');
        $illness3->setCategory('Ogólne');
        $illness3->addMedicamente($medicamente);
        $manager->persist($illness3);
        $manager->flush();

    }
}
