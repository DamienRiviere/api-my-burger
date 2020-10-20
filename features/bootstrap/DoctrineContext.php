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
	 * @When  I load my user
	 */
	public function iLoadMyUser(): void
	{
		$user = new User("marc@gmail.com", "Marc", "Dupont");
		$user->setPassword($this->encoder->encodePassword($user, "password"));
		$user->setRoles(['ROLE_USER']);
		$this->em->persist($user);
		$this->em->flush();
	}

	/**
	 * @When I load my users
	 */
	public function iLoadMyUsers(): void
	{
		for ($i = 0; $i < 50; $i++) {
			$faker = Faker\Factory::create('fr-Fr');
			$user = new User($faker->email, $faker->firstName, $faker->lastName);
			$user->setPassword($this->encoder->encodePassword($user, "password"));
			$user->setRoles(['ROLE_USER']);
			$this->em->persist($user);
			$this->em->flush();
		}
	}

	/**
	 * @When I load my admin
	 */
	public function iLoadMyAdmin(): void
	{
		$admin = new User("admin@gmail.com", "Admin", "Admin");
		$admin->setPassword($this->encoder->encodePassword($admin, "password"));
		$admin->setRoles(['ROLE_ADMIN']);
		$this->em->persist($admin);
		$this->em->flush();
	}

	/**
	 * @When I need parameter :parameter from user :email
	 * @param string $parameter
	 * @param string $email
	 * @return string
	 */
	public function getUserParameter(string $parameter, string $email): string
	{
		$userRepository = $this->doctrine->getRepository(User::class);
		$user = $userRepository->findOneBy(['email' => $email]);

		return $user->{'get' . $parameter}();
	}
}
