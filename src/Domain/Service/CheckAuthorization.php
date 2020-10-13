<?php

namespace App\Domain\Service;

use App\Domain\Common\Exception\AuthorizationException;

final class CheckAuthorization
{

    public const ACCESS_MESSAGE = 'Vous n\'êtes pas autorisé à accéder à cette ressource !';
    public const CREATE_MESSAGE = 'Vous n\'êtes pas autorisé à créer cette ressource !';
    public const DELETE_MESSAGE = 'Vous n\'êtes pas autorisé à supprimer cette ressource !';

    /**
     * @param bool $authorization
     * @param string $typeToCheck
     * @throws AuthorizationException
     */
    public function check(bool $authorization, string $typeToCheck): void
    {
        if (!$authorization) {
            switch ($typeToCheck) {
                case "access":
                    throw new AuthorizationException(self::ACCESS_MESSAGE);
                case "delete":
                    throw new AuthorizationException(self::DELETE_MESSAGE);
                case "create":
                    throw new AuthorizationException(self::CREATE_MESSAGE);
            }
        }
    }
}
