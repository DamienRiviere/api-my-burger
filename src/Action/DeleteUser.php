<?php

namespace App\Action;

use App\Domain\Common\Exception\AuthorizationException;
use App\Domain\Service\CheckAuthorization;
use App\Repository\UserRepository;
use App\Responder\JsonResponder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

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

    /** @var AuthorizationCheckerInterface */
    protected $authorization;

    /** @var CheckAuthorization */
    protected $checkAuthorization;

    /**
     * DeleteUser constructor.
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $em
     * @param AuthorizationCheckerInterface $authorization
     * @param CheckAuthorization $checkAuthorization
     */
    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $em,
        AuthorizationCheckerInterface $authorization,
        CheckAuthorization $checkAuthorization
    ) {
        $this->userRepository = $userRepository;
        $this->em = $em;
        $this->authorization = $authorization;
        $this->checkAuthorization = $checkAuthorization;
    }


    /**
     * @param string $uuid
     * @param string $slug
     * @param JsonResponder $responder
     * @return Response
     * @throws AuthorizationException
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     */
    public function __invoke(string $uuid, string $slug, JsonResponder $responder)
    {
        $user = $this->userRepository->findOneByEncodedUuid($uuid, $slug);
        $authorization = $this->authorization->isGranted('delete', $user);
        $this->checkAuthorization->check($authorization, 'delete');
        $this->em->remove($user);
        $this->em->flush();

        return $responder(null, Response::HTTP_NO_CONTENT);
    }
}
