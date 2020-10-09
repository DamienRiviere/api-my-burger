<?php

namespace App\Action;

use App\Repository\UserRepository;
use App\Responder\JsonResponder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DeleteUser
 * @package App\Action
 * @Route("/api/users/{uuid}/{slug}", name="api_delete_user", methods={"DELETE"})
 */
final class DeleteUser
{

    /** @var UserRepository */
    protected $userRepository;

    /** @var EntityManagerInterface */
    protected $em;

    /**
     * DeleteUser constructor.
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
    }


    public function __invoke(string $uuid, JsonResponder $responder)
    {
        $user = $this->userRepository->findOneByEncodedUuid($uuid);
        $this->em->remove($user);
        $this->em->flush();

        return $responder(null, Response::HTTP_NO_CONTENT);
    }
}
