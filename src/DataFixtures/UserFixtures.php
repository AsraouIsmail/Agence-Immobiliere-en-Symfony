<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    public function __construct(UserPasswordHasherInterface $encoder) {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager): void
    {
       $user = new User();
       $user->setUsername('ismaildemo');

       $user->setPassword($this->encoder->hashPassword($user, 'ismaildemo'));

       $user->setRoles(['ROLE_ADMIN']);

       $manager->persist($user);

        $manager->flush();
    }
}
