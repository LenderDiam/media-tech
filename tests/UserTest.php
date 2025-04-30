<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserRoles(): void
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);

        $this->assertContains('ROLE_USER', $user->getRoles(), 'User should have the ROLE_USER role');
    }

    public function testApprovedUsers(): void
    {
        $admin = new User();
        $admin->setFirstname('Admin')->setLastname('Admin')->setRoles(['ROLE_ADMIN']);

        $user = new User();
        $user->setFirstname('John')->setLastname('Doe');
        $user->setApprovedBy($admin);

        $admin->addApprovedUser($user);

        $this->assertCount(1, $admin->getApprovedUsers(), 'Admin should have 1 approved user');
        $this->assertSame($user, $admin->getApprovedUsers()[0], 'The approved user should match the added user');
    }

    public function testRejectedUsers(): void
    {
        $admin = new User();
        $admin->setFirstname('Admin')->setLastname('Admin')->setRoles(['ROLE_ADMIN']);

        $user = new User();
        $user->setFirstname('Jane')->setLastname('Smith');
        $user->setRejectedBy($admin);

        $admin->addRejectedUser($user);

        $this->assertCount(1, $admin->getRejectedUsers(), 'Admin should have 1 rejected user');
        $this->assertSame($user, $admin->getRejectedUsers()[0], 'The rejected user should match the added user');
    }
}