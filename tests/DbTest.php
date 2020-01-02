<?php

namespace App\Tests;

use App\Entity\Role;
use App\Entity\User;
use App\Entity\Settings;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DbTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testCreateUser()
    {
        $userRepo = $this->em->getRepository(User::class);
        $roleRepo = $this->em->getRepository(Role::class);
        
        $user = new User();
        $user->setUsername('Test');
        $user->setPlainPassword('test');
        $user->setEmail('test@test.io');

        $role = $roleRepo->findOneBy(['code' => 'ROLE_USER']);

        $settings = new Settings();
        $settings->setUser($user);

        $user->setSettings($settings);
        $user->setRole($role);

        $this->em->persist($user);
        $this->em->persist($settings);

        $this->em->flush();

        $testUser = $userRepo->findOneBy([], ['id' => 'DESC']);
        
        $this->assertSame($user, $testUser);

        if ($testUser === $user) {
            $this->em->remove($testUser);
            $this->em->flush();
        }
        
        $this->em->close();
        $this->em = null;
    }
}
