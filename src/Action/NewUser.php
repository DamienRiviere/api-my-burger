<?php

namespace App\Action;

use App\Domain\Common\Exception\ValidationException;
use App\Domain\Service\Validator;
use App\Domain\User\UserDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Responder\JsonResponder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

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

    /** @var SerializerInterface */
    protected $serializer;

    /** @var UserRepository */
    protected $userRepository;

    /**
     * NewUser constructor.
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     * @param Validator $validator
     * @param SerializerInterface $serializer
     */
    public function __construct(
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $encoder,
        Validator $validator,
        SerializerInterface $serializer
    ) {
        $this->em = $em;
        $this->encoder = $encoder;
        $this->validator = $validator;
        $this->serializer = $serializer;
    }

    /**
     * @param Request $request
     * @param JsonResponder $responder
     * @return Response
     * @throws ValidationException
     */
    public function __invoke(Request $request, JsonResponder $responder)
    {
        $dto = $this->serializer->deserialize($request->getContent(), UserDTO::class, "json");
        $this->validator->validate($dto);
        $user = new User($dto->getEmail(), $dto->getFirstName(), $dto->getLastName());
        $user->setPassword($this->encoder->encodePassword($user, $dto->getPassword()));
        $this->em->persist($user);
        $this->em->flush();

        return $responder(null, Response::HTTP_CREATED);
    }
}
