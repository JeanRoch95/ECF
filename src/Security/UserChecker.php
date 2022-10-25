<?php

namespace App\Security;

use App\Entity\UserStructure as AppUserStructure;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{

    public function checkPreAuth(UserInterface $user): void
    {
        if($user->isIsVerified() == 0){
            throw new CustomUserMessageAccountStatusException('Veuillez activer votre compte');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUserStructure) {
            return;
        }
    }
}