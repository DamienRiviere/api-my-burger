<?php

use App\Entity\User;
use Behat\Behat\Context\Context;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Doctrine\Persistence\ObjectManager;
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

	/**
	 * DoctrineContext constructor.
	 *
	 * @param Registry $doctrine
	 * @param KernelInterface $kernel
	 * @param UserPasswordEncoderInterface $encoder
	 */
	public function __construct(
		Registry $doctrine,
		KernelInterface $kernel,
		UserPasswordEncoderInterface $encoder
	) {
		$this->doctrine = $doctrine;
		$this->schemaTool = new SchemaTool($this->doctrine->getManager());
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
	 * @return ObjectManager
	 */
	public function getManager(): ObjectManager
	{
		return $this->doctrine->getManager();
	}

	/**
	 * @Given I load my user
	 */
	public function iLoadMyUser(): void
	{
		$user = $this->createUser();
		$this->getManager()->persist($user);
		$this->getManager()->flush();
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
