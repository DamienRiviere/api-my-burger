<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    /** @var UserPasswordEncoderInterface */
    protected $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = $this->createAdminUser();
        $test = $this->createUser();
        $faker = Factory::create('fr-FR');

        for ($i = 0; $i < 50; $i++) {
            $user = new User($faker->email, $faker->firstName, $faker->lastName);
            $user->setPassword($this->encoder->encodePassword($user, "password"));
            $user->setRoles(['ROLE_USER']);

            $manager->persist($user);
        }

        $manager->persist($admin);
        $manager->persist($test);
        $manager->flush();
    }

    public function createAdminUser(): User
    {
        $admin = new User("admin@gmail.com", "Admin", "Admin");
        $admin->setPassword($this->encoder->encodePassword($admin, "password"));
        $admin->setRoles(["ROLE_ADMIN"]);

        return $admin;
    }

    public function createUser(): User
    {
        $user = new User("user@gmail.com", "Utilisateur", "DeTest");
        $user->setPassword($this->encoder->encodePassword($user, "password"));
        $user->setRoles(['ROLE_USER']);

        return $user;
    }
}
