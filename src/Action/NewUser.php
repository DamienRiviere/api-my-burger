<?php

namespace App\Action;

use App\Domain\Service\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class NewUser
 * @package App\Action
 * @Route("/api/users", name="api_new_user", methods={"POST"})
 */
final class NewUser
{

    /** @var EntityManagerInterface */
    protected $em;

    /** @var UserPasswordEncoderInterface */
    protected $encoder;

    /** @var Validator */
    protected $validator;

    public function __invoke(Request $request)
    {
    }
}
