<?php

namespace App\Action;

use App\Responder\JsonResponder;
use App\Repository\UserRepository;
use App\Domain\Helper\PaginationHelper;
use App\Domain\Service\CheckAuthorization;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Domain\Common\Exception\AuthorizationException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

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
     * @param Request $request
     * @param JsonResponder $responder
     * @return Response
     * @throws AuthorizationException
     */
    public function __invoke(Request $request, JsonResponder $responder): Response
    {
        $authorization = $this->authorization->isGranted('showUserList');
        $this->checkAuthorization->check($authorization, 'access');

        $usersAndTotalPage = $this->userRepository->findUsersAndTotalPage();
        $page = PaginationHelper::checkPage($request, $usersAndTotalPage['totalPage']);
        $usersPaginated = $this->userRepository->findUserPaginated($page);

        $data = $this->serializer->serialize(
            $usersPaginated,
            'json',
            [
                'groups' => ['showUser'],
                'page' => $page,
                'users' => $usersAndTotalPage['users'],
                'totalPage' => $usersAndTotalPage['totalPage']
            ]
        );

        return $responder($data, Response::HTTP_OK);
    }
}
