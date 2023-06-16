<?php

namespace App\DataFixtures;

use App\Entity\Todo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TodoFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $todo = new Todo();
        $todo->setTitle('Shower');
        $todo->setDescription('Shampoo hair, clean face, and wash teeth');
        $todo->setOwner($this->getReference('karlo'));
        $manager->persist($todo);

        $todo2 = new Todo();
        $todo2->setTitle('Shower');
        $todo2->setDescription('Clean face, and wash teeth');
        $todo2->setOwner($this->getReference('karlo1'));
        $manager->persist($todo2);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
