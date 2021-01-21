<?php

namespace App\Domain\User;

use App\Entity\User;
use App\Domain\Service\Pagination;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class UserHATEOAS
{

    /** @var UrlGeneratorInterface */
    protected $urlGenerator;

    /** @var Security */
    protected $security;

    /**
     * UserNormalizer constructor.
     * @param UrlGeneratorInterface $urlGenerator
     * @param Security $security
     */
    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        Security $security
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->security = $security;
    }

    /**
     * @param array $data
     * @param User $user
     * @return array
     */
    public function getRessourcesLink(array $data, User $user): array
    {
        $data = $this->getSelfLink($data, $user);

        if (in_array('ROLE_ADMIN', $this->security->getUser()->getRoles(), true)) {
            $data = $this->getDeleteLink($data, $user);
        }

        return $data;
    }

    /**
     * @param array $data
     * @param array $options
     * @return array
     */
    public function getPagesLink(array $data, array $options): array
    {
        $data = $this->getFirstPageLink($data);
        $data = $this->getLastPageLink($data, $options['pagination']);

        if ($options['page'] < $options['totalPage']) {
            $data = $this->getNextPageLink($data, $options['pagination']);
        }

        if ($options['page'] >= 2) {
            $data = $this->getPreviousPageLink($data, $options['pagination']);
        }

        return $data;
    }

    /**
     * @param array $data
     * @param User $user
     * @return array
     */
    public function getSelfLink(array $data, User $user): array
    {
        $data['_link']['self']['href'] = $this->urlGenerator->generate(
            User::SHOW_USER_LIST,
            [
                'uuid' => $user->getUuidEncoded(),
                'slug' => $user->getSlug()
            ]
        );

        return $data;
    }

    /**
     * @param array $data
     * @param User $user
     * @return array
     */
    public function getDeleteLink(array $data, User $user): array
    {
        $data['_link']['delete']['href'] = $this->urlGenerator->generate(
            User::DELETE_USER,
            [
                'uuid' => $user->getUuidEncoded(),
                'slug' => $user->getSlug()
            ]
        );

        return $data;
    }

     /**
     * @param array $data
     * @return array
     */
    public function getFirstPageLink(array $data): array
    {
        $data['_link']['first']['href'] = $this->urlGenerator->generate(
            User::SHOW_USER_LIST,
            [
                'page' => 1
            ]
        );

        return $data;
    }

    /**
     * @param array $data
     * @param Pagination $pagination
     * @return array
     */
    public function getLastPageLink(array $data, Pagination $pagination): array
    {
        $data['_link']['last']['href'] = $this->urlGenerator->generate(
            User::SHOW_USER_LIST,
            [
                'page' => $pagination->getLastPage()
            ]
        );

        return $data;
    }

    /**
     * @param array $data
     * @param Pagination $pagination
     * @return array
     */
    public function getNextPageLink(array $data, Pagination $pagination): array
    {
        $data['_link']['next']['href'] = $this->urlGenerator->generate(
            User::SHOW_USER_LIST,
            [
                'page' => $pagination->getNextPage()
            ]
        );

        return $data;
    }

    /**
     * @param array $data
     * @param Pagination $pagination
     * @return array
     */
    public function getPreviousPageLink(array $data, Pagination $pagination): array
    {
        $data['_link']['prev']['href'] = $this->urlGenerator->generate(
            User::SHOW_USER_LIST,
            [
                'page' => $pagination->getPreviousPage()
            ]
        );

        return $data;
    }
}
