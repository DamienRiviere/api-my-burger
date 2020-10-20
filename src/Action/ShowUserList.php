<?php

namespace App\Action;

use App\Domain\Common\Exception\AuthorizationException;
use App\Domain\Service\CheckAuthorization;
use App\Repository\UserRepository;
use App\Responder\JsonResponder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ShowUserList
 * @package App\Action
 * @Route("/api/users", name="api_show_user_list", methods={"GET"})
 */
final class ShowUserList
{

    /** @var UserRepository */
    protected $userRepository;

    /** @var SerializerInterface */
    protected $serializer;

    /** @var AuthorizationCheckerInterface */
    protected $authorization;

    /** @var CheckAuthorization */
    protected $checkAuthorization;

    /**
     * ShowUserList constructor.
     * @param UserRepository $userRepository
     * @param SerializerInterface $serializer
     * @param AuthorizationCheckerInterface $authorization
     * @param CheckAuthorization $checkAuthorization
     */
    public function __construct(
        UserRepository $userRepository,
        SerializerInterface $serializer,
        AuthorizationCheckerInterface $authorization,
        CheckAuthorization $checkAuthorization
    ) {
        $this->userRepository = $userRepository;
        $this->serializer = $serializer;
        $this->authorization = $authorization;
        $this->checkAuthorization = $checkAuthorization;
    }

    /**
     * @param JsonResponder $responder
     * @return Response
     * @throws AuthorizationException
     */
    public function __invoke(JsonResponder $responder): Response
    {
        $authorization = $this->authorization->isGranted('showUserList');
        $this->checkAuthorization->check($authorization, 'access');
        $users = $this->userRepository->findAll();
        $data = $this->serializer->serialize($users, 'json', ['groups' => ['showUser']]);

        return $responder($data, Response::HTTP_OK);
    }
}
