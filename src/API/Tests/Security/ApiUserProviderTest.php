<?php

namespace Siqu\CMS\API\Tests\Security;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Siqu\CMS\API\Security\ApiUserProvider;
use Siqu\CMS\API\Security\Credentials;
use Siqu\CMS\Core\Entity\CMSUser;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class ApiUserProviderTest
 * @package Siqu\CMS\API\Tests\Security
 */
class ApiUserProviderTest extends TestCase
{
    /** @var UserPasswordEncoderInterface|MockObject */
    private $encoder;
    /** @var EntityManagerInterface|MockObject */
    private $entityManager;
    /** @var ApiUserProvider */
    private $provider;
    /** @var ObjectRepository|MockObject */
    private $repository;

    /**
     * Should create correct instance.
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(
            ApiUserProvider::class,
            $this->provider
        );
    }

    /**
     * Should return username and set last login
     */
    public function testGetUsernameForCredentials(): void
    {
        $user = new CMSUser();
        $user->setUsername('username');
        $this->repository
            ->method('findOneBy')
            ->willReturn($user);

        $credentials = new Credentials(
            'username',
            'password'
        );
        $this->encoder
            ->method('isPasswordValid')
            ->with(
                $user,
                'password'
            )
            ->willReturn(true);

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $this->assertEquals(
            'username',
            $this->provider->getUsernameForCredentials($credentials)
        );
        $this->assertNotNull($user->getLastLogin());
    }

    /**
     * Should return username and not set last login
     */
    public function testGetUsernameForCredentialsExceptionDuringFlush(): void
    {
        $user = new CMSUser();
        $user->setUsername('username');
        $this->repository
            ->method('findOneBy')
            ->willReturn($user);

        $credentials = new Credentials(
            'username',
            'password'
        );
        $this->encoder
            ->method('isPasswordValid')
            ->with(
                $user,
                'password'
            )
            ->willReturn(true);

        $this->entityManager
            ->expects($this->once())
            ->method('flush')
            ->willThrowException(
                new OptimisticLockException(
                    '',
                    $user
                )
            );

        $this->assertEquals(
            'username',
            $this->provider->getUsernameForCredentials($credentials)
        );
        $this->assertNull($user->getLastLogin());
    }

    /**
     * Should return null
     */
    public function testGetUsernameForCredentialsInvalidPassword(): void
    {
        $user = new CMSUser();
        $this->repository
            ->method('findOneBy')
            ->willReturn($user);

        $credentials = new Credentials(
            'username',
            'password'
        );
        $this->encoder
            ->method('isPasswordValid')
            ->with(
                $user,
                'password'
            )
            ->willReturn(false);

        $this->assertNull($this->provider->getUsernameForCredentials($credentials));
    }

    /**
     * Should return user
     */
    public function testLoadUserByUsername(): void
    {
        $user = new CMSUser();
        $this->repository
            ->method('findOneBy')
            ->with(
                [
                    'username' => 'username'
                ]
            )
            ->willReturn($user);

        $readUser = $this->provider->loadUserByUsername('username');
        $this->assertEquals(
            $user,
            $readUser
        );
    }

    /**
     * Should throw exception
     *
     * @expectedException Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testLoadUserByUsernameInvalid(): void
    {
        $this->repository
            ->method('findOneBy')
            ->with(
                [
                    'username' => 'invalid'
                ]
            )
            ->willReturn(null);

        $this->provider->loadUserByUsername('invalid');
    }

    /**
     * Should return user
     */
    public function testRefreshUser(): void
    {
        $user = new CMSUser();
        $user->setUsername('username');
        $this->repository
            ->method('findOneBy')
            ->with(
                [
                    'username' => 'username'
                ]
            )
            ->willReturn($user);

        $refreshedUser = $this->provider->refreshUser($user);

        $this->assertEquals(
            $user,
            $refreshedUser
        );
    }

    /**
     * Should throw exception.
     *
     * @expectedException Symfony\Component\Security\Core\Exception\UnsupportedUserException
     */
    public function testRefreshUserInvalidUser(): void
    {
        /** @var UserInterface|MockObject $user */
        $user = $this->getMockBuilder(UserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->provider->refreshUser($user);
    }

    /**
     * Should return true.
     */
    public function testSupportsClass(): void
    {
        $this->assertTrue($this->provider->supportsClass(CMSUser::class));
    }

    /**
     * Should return false.
     */
    public function testSupportsClassInvalid(): void
    {
        $this->assertFalse($this->provider->supportsClass(\stdClass::class));
    }

    /**
     * Setup tests.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->repository = $this->getMockBuilder(ObjectRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->entityManager
            ->method('getRepository')
            ->willReturn($this->repository);

        $this->encoder = $this->getMockBuilder(UserPasswordEncoderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->provider = new ApiUserProvider(
            $this->entityManager,
            $this->encoder
        );
    }
}
