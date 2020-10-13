<?php

namespace App\Repository;

use App\Domain\Doctrine\UuidEncoder;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    use RepositoryUuidFinderTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     * @param UserInterface $user
     * @param string $newEncodedPassword
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @param string $encodedUuid
     * @param string $slug
     * @return mixed
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     */
    public function findOneByEncodedUuid(string $encodedUuid, string $slug)
    {
        $query = $this->createQueryBuilder('u')
            ->where('u.uuid = :uuid')
            ->andWhere('u.slug = :slug')
            ->setParameters(
                new ArrayCollection(
                    [
                        new Parameter('uuid', UuidEncoder::decode($encodedUuid)),
                        new Parameter('slug', $slug)
                    ]
                )
            )
            ->getQuery()
            ->getOneOrNullResult()
        ;

        if ($query instanceof User) {
            return $query;
        }

        throw new EntityNotFoundException("Utilisateur introuvable !");
    }
}
