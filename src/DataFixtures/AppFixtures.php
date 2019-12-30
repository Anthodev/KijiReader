<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $role = new Role();
        $role->setName('User');
        $role->setCode('ROLE_USER');
        $role->setCreatedAt(new \DateTime());
        $manager->persist($role);

        $role = new Role();
        $role->setName('Admin');
        $role->setCode('ROLE_ADMIN');
        $role->setCreatedAt(new \DateTime());
        $manager->persist($role);

        $manager->flush();
        $manager->clear();
    }
}
