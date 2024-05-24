<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setLastname("Doe");
        $user->setFirstname("John");
        $user->setPosition("admin");
        $user->setDescription("admin");
        $user->setEmail("admin@admin.com");
        $user->setRoles(["ROLE_ADMIN"]);
        $hashedPassword = $this->passwordHasher->hashPassword($user, "password");
        $user->setPassword($hashedPassword);
        $manager->persist($user);

        $manager->flush();
    }
}
