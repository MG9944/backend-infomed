<?php

namespace App\Security;

use App\Entity\User;
use App\Exception\AccountDisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            return;
        }

        if (!$user->isIsActive()) {
            throw AccountDisabledException::problemAccount();
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
    }
}
