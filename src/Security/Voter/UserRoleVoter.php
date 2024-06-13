<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRoleVoter extends Voter
{
    public const ALL_ROLE = 'ALL_ROLE';
    public const ROLE_WITHOUT_ADMIN = 'ROLE_WITHOUT_ADMIN';

    public function __construct(private readonly Security $security)
    {
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::ALL_ROLE, self::ROLE_WITHOUT_ADMIN]);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::ALL_ROLE:
                if ($this->security->isGranted('ROLE_SUPER_ADMIN') ||
                    $this->security->isGranted('ROLE_USER_MANAGEMENT') ||
                    $this->security->isGranted('ROLE_ADMIN')
                ) {
                    return true;
                }

                return false;
            case self::ROLE_WITHOUT_ADMIN:
                if ($this->security->isGranted('ROLE_SUPER_ADMIN') ||
                    $this->security->isGranted('ROLE_USER_MANAGEMENT')
                ) {
                    return true;
                }

                return false;
        }

        return false;
    }
}
