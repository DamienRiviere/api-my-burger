<?php

namespace App\Domain\Common\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class UserVoter extends Voter
{

    protected const DELETE = 'delete';

    protected function supports($attribute, $subject): bool
    {
        if (!in_array($attribute, [self::DELETE])) {
             return false;
        }

        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if (!in_array("ROLE_ADMIN", $user->getRoles(), true)) {
            return false;
        }

        return true;
    }
}
