<?php

namespace App\Dto\Response\Transformer;

use App\Dto\Response\UserDto;
use App\Entity\User;

class UserDtoTransformer extends AbstractResponseDtoTransformer
{
    /**
     * @param User $user
     */
    public function transformFromObject($user): UserDto
    {
        $roles = $user->getRoles();
        asort($roles);

        return new UserDto(
            $user->getId(),
            $user->getEmail(),
            $user->getFirstname(),
            $user->getLastname(),
            $user->getPhoneNumber(),
            $user->getSpecialisation()->getName(),
            $user->getMedicalCenter()->getName(),
            $user->isIsActive(),
            $roles
        );
    }
}
