<?php

namespace App\Domain\User;

use App\Domain\Service\Pagination;
use App\Entity\User;
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

    /** @var UserHATEOAS */
    protected $hateoas;

    /**
     * UserNormalizer constructor.
     * @param ObjectNormalizer $normalizer
     * @param UserHATEOAS $hateoas
     */
    public function __construct(
        ObjectNormalizer $normalizer,
        UserHATEOAS $hateoas
    ) {
        $this->normalizer = $normalizer;
        $this->hateoas = $hateoas;
    }

    /**
     * @param mixed $data
     * @param null $format
     * @param array $context
     * @return bool
     * @phpstan-ignore-next-line
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
     * @phpstan-ignore-next-line
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);
        $data = $this->hateoas->getDeleteLink($data, $object);

        return $data;
    }
}
