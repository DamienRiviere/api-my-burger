<?php

namespace App\Action;

use App\Domain\Service\CheckAuthorization;
use App\Repository\UserRepository;
use App\Responder\JsonResponder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ShowUser
 * @package App\Action
 * @Route("/api/users/{uuid}/{slug}", name="api_show_user", methods={"GET"})
 */
final class ShowUser
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
     * ShowUser constructor.
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

    public function __invoke(string $uuid, string $slug, JsonResponder $responder): Response
    {
        $user = $this->userRepository->findOneByEncodedUuid($uuid, $slug);
        $authorization = $this->authorization->isGranted('showUser', $user);
        $this->checkAuthorization->check($authorization, 'access');
        $data = $this->serializer->serialize($user, 'json', ['groups' => ['showUser']]);

        return $responder($data, Response::HTTP_OK);
    }
}
