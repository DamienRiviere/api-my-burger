<?php

namespace App\Domain\User;

use App\Entity\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class UserNormalizer implements ContextAwareNormalizerInterface
{

    /** @var ObjectNormalizer */
    protected $normalizer;

    /** @var UrlGeneratorInterface */
    protected $urlGenerator;

    /** @var Security */
    protected $security;

    /**
     * UserNormalizer constructor.
     * @param ObjectNormalizer $normalizer
     * @param UrlGeneratorInterface $urlGenerator
     * @param Security $security
     */
    public function __construct(
        ObjectNormalizer $normalizer,
        UrlGeneratorInterface $urlGenerator,
        Security $security
    ) {
        $this->normalizer = $normalizer;
        $this->urlGenerator = $urlGenerator;
        $this->security = $security;
    }

    /**
     * @param mixed $data
     * @param null $format
     * @param array $context
     * @return bool
     */
    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof User && in_array('showUser', $context['groups'], true);
    }

    /**
     * @param mixed $object
     * @param null $format
     * @param array $context
     * @return array
     * @throws ExceptionInterface
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);

        if (in_array('ROLE_ADMIN', $this->security->getUser()->getRoles(), true)) {
            $data = $this->getLinkDelete($data, $object);
        }

        return $data;
    }

    /**
     * @param array $data
     * @param User $user
     * @return array
     */
    public function getLinkDelete(array $data, User $user): array
    {
        $data['_link']['delete']['href'] = $this->urlGenerator->generate(
            'api_delete_user',
            [
                'uuid' => $user->getUuidEncoded(),
                'slug' => $user->getSlug()
            ]
        );

        return $data;
    }
}
