<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
      $user = new User();
      $user->setEmail('karlo@example.com');
      $user->setPassword('test');
      $manager->persist($user);

      $user2 = new User();
      $user2->setEmail('karlo1@example.com');
      $user2->setPassword('test');
      $manager->persist($user2);

      $manager->flush();

      $this->setReference('karlo', $user);
      $this->setReference('karlo1', $user2);
    }
}
