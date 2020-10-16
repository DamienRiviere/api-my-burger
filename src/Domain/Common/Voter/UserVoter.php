<?php

namespace App\Domain\Common\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class UserVoter extends Voter
{

    protected const DELETE_USER = 'deleteUser';
    protected const SHOW_USER = 'showUser';

    protected function supports($attribute, $subject): bool
    {
        if (!in_array($attribute, [self::DELETE_USER, self::SHOW_USER])) {
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

        switch ($attribute) {
            case self::DELETE_USER:
                return $this->canDeleteUser($user);
            case self::SHOW_USER:
                return $this->canShowUser($user, $subject);
        }

        return true;
    }

    private function canDeleteUser(User $user): bool
    {
        if (in_array("ROLE_ADMIN", $user->getRoles(), true)) {
            return true;
        }

        return false;
    }

    private function canShowUser(User $user, User $subject): bool
    {
        if (in_array("ROLE_ADMIN", $user->getRoles(), true)) {
            return true;
        }

        if ($user === $subject) {
            return true;
        }

        return false;
    }
}
