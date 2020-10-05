<?php

use App\Entity\User;
use Behat\Behat\Context\Context;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DoctrineContext implements Context
{
	/** @var SchemaTool */
	protected $schemaTool;

	/** @var Registry */
	protected $doctrine;

	/** @var KernelInterface */
	protected $kernel;

	/** @var UserPasswordEncoderInterface */
	protected $encoder;

	/** @var EntityManagerInterface */
	protected $em;

	/**
	 * DoctrineContext constructor.
	 *
	 * @param Registry $doctrine
	 * @param KernelInterface $kernel
	 * @param UserPasswordEncoderInterface $encoder
	 * @param EntityManagerInterface $em
	 */
	public function __construct(
		Registry $doctrine,
		KernelInterface $kernel,
		UserPasswordEncoderInterface $encoder,
		EntityManagerInterface $em
	) {
		$this->doctrine = $doctrine;
		$this->em = $em;
		$this->schemaTool = new SchemaTool($this->em);
		$this->kernel = $kernel;
		$this->encoder = $encoder;
	}

	/**
	 * @BeforeScenario
	 *
	 * @throws ToolsException
	 */
	public function clearDatabase(): void
	{
		$this->schemaTool->dropSchema($this->doctrine->getManager()->getMetadataFactory()->getAllMetadata());
		$this->schemaTool->createSchema($this->doctrine->getManager()->getMetadataFactory()->getAllMetadata());
	}

	/**
	 * @Given I load my user
	 */
	public function iLoadMyUser(): void
	{
		$user = $this->createUser();
		$this->em->persist($user);
		$this->em->flush();
	}

	/**
	 * @return User
	 */
	public function createUser(): User
	{
		$user = new User();
		$user->setFirstName("Damien")
			->setLastName("Riviere")
			->setSlug("damien-riviere")
			->setCreatedAt(new DateTime())
			->setRoles(["ROLE_ADMIN"])
			->setPassword($this->encoder->encodePassword($user, "password"))
			->setEmail("damien@gmail.com")
		;

		return $user;
	}
}
