<?php

namespace App\Security;

use App\Entity\User;
use App\Exception\ChangePasswordException;
use App\Exception\CreateDoctorException;
use App\Repository\UserRepository;
use App\Service\EmailService;
use App\Service\SMSService;
use App\Utils\GenerateRandomCode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserChangePassword
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EntityManagerInterface $entityManager,
        private readonly EmailService $emailService,
        private readonly UserRepository $userRepository,
    ) {
    }

    public function changePassword(array $data, User $user): void
    {
        try {
            if (!$this->passwordHasher->isPasswordValid($user, $data['old_password'])) {
                throw ChangePasswordException::oldPasswordNotCorrect();
            }

            if ($data['new_password'] !== $data['repeated_new_password']) {
                throw ChangePasswordException::newPasswordNotCorrect();
            }

            if ($data['new_password'] === $data['old_password']) {
                throw ChangePasswordException::passwordNotCorrect();
            }
            $user->setPassword($this->passwordHasher->hashPassword($user, $data['new_password']));
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->emailService->sendEmailChangePassword($user->getEmail(), $data['new_password']);
        } catch (\Exception $e) {
            throw new ChangePasswordException($e->getMessage());
        }
    }

        public function setNewPassword(array $data, string $email): void
        {
            try {
                if (!$user = $this->userRepository->findOneBy(['email' => $email])) {
                    throw CreateDoctorException::doctorNotFound();
                }

                if ($user->getPassword() == $this->passwordHasher->isPasswordValid($user, $data['new_password'])) {
                    throw ChangePasswordException::passwordNotCorrect();
                }

                if ($data['new_password'] !== $data['repeated_new_password']) {
                    throw ChangePasswordException::newPasswordNotCorrect();
                }

                $user->setPassword($this->passwordHasher->hashPassword($user, $data['new_password']));
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $this->emailService->sendEmailChangePassword($user->getEmail(), $data['new_password']);
            } catch (\Exception $e) {
                throw new ChangePasswordException($e->getMessage());
            }
        }
}
