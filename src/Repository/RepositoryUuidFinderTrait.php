<?php

namespace App\Repository;

use App\Domain\Doctrine\UuidEncoder;

/**
 * Trait RepositoryUuidFinderTrait
 * @package App\Repository
 */
trait RepositoryUuidFinderTrait
{

    /**
     * @var UuidEncoder
     */
    protected $uuidEncoder;

    /**
     * @param string $encodedUuid
     * @return mixed
     */
    public function findOneByEncodedUuid(string $encodedUuid)
    {
        return $this->findOneBy([
            'uuid' => $this->uuidEncoder->decode($encodedUuid)
        ]);
    }
}
