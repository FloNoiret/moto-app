<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        $christ = new User($this->passwordHasher);
        $christ->setUsername("N0tChris")->setPassword("123");
        $manager->persist($christ);

        $anna = new User($this->passwordHasher);
        $anna->setUsername("Anna")->setPassword("azerty");
        $manager->persist($anna);
        
        $manager->flush();
    }
}
