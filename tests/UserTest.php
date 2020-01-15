<?php

namespace App\Tests;

use App\Entity\Role;
use App\Entity\User;
use App\Entity\Settings;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testCreateUser()
    {
        $user = new User();
        $settings = new Settings();

        $user->setUsername('test');
        $user->setEmail('test@test.io');
        $user->setPlainPassword('test');
        $user->setSettings($settings);

        $userRepository = $this->createMock(ObjectRepository::class);
        $userRepository->expects($this->any())
            ->method('find')
            ->willReturn($user);

        $settingsRepository = $this->createMock(ObjectRepository::class);
        $settingsRepository->expects($this->any())
            ->method('find')
            ->willReturn($settings);

        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($userRepository);

        $this->assertEquals(true, $user->getSettings()->getDisplayUnread());
    }
}
