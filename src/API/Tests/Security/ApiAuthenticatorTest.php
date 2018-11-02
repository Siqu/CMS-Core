<?php

namespace Siqu\CMS\API\Tests\Security;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Siqu\CMS\API\Security\ApiAuthenticator;
use Siqu\CMS\API\Security\ApiUserProvider;
use Siqu\CMS\API\Security\Credentials;
use Siqu\CMS\Core\Entity\CMSUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class ApiAuthenticatorTest
 * @package Siqu\CMS\API\Tests\Security
 */
class ApiAuthenticatorTest extends TestCase
{
    /** @var ApiAuthenticator */
    private $authenticator;

    /**
     * Should set user
     */
    public function testAuthenticate(): void
    {
        $user = new CMSUser();
        $credentials = new Credentials('username', 'password');
        $token = new PreAuthenticatedToken('user', $credentials, 'key');

        /** @var UserProviderInterface|MockObject $provider */
        $provider = $this->getMockBuilder(ApiUserProvider::class)
            ->disableOriginalConstructor()
            ->getMock();
        $provider
            ->expects($this->once())
            ->method('getUsernameForCredentials')
            ->willReturn('username');
        $provider
            ->expects($this->once())
            ->method('loadUserByUsername')
            ->with('username')
            ->willReturn($user);

        $token = $this->authenticator->authenticateToken($token, $provider, 'key');

        $this->assertEquals($user, $token->getUser());
        $this->assertEquals($credentials, $token->getCredentials());
        $this->assertEquals('key', $token->getProviderKey());

        $roles = $token->getRoles();
        /** @var Role $role */
        $role = $roles[0];
        $this->assertEquals('ROLE_USER', $role->getRole());
    }

    /**
     * Should throw exception.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testAuthenticateTokenInvalidUserProvider(): void
    {
        /** @var TokenInterface|MockObject $token */
        $token = $this->getMockBuilder(TokenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        /** @var UserProviderInterface|MockObject $provider */
        $provider = $this->getMockBuilder(UserProviderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->authenticator->authenticateToken($token, $provider, 'key');
    }

    /**
     * Should throw exception.
     *
     * @expectedException Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException
     */
    public function testAuthenticateTokenNoUsername(): void
    {
        $credentials = new Credentials('username', 'password');
        $token = new PreAuthenticatedToken('user', $credentials, 'key');
        /** @var ApiUserProvider|MockObject $provider */
        $provider = $this->getMockBuilder(ApiUserProvider::class)
            ->disableOriginalConstructor()
            ->getMock();
        $provider
            ->method('getUsernameForCredentials')
            ->willReturn(null);
        $this->authenticator->authenticateToken($token, $provider, 'key');
    }

    /**
     * Should return correct instance.
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(ApiAuthenticator::class, $this->authenticator);
    }

    /**
     * Should create proper token.
     */
    public function testCreateToken(): void
    {
        $request = new Request();
        $request->headers->set('PHP_AUTH_USER', 'username');
        $request->headers->set('PHP_AUTH_PW', 'password');

        $token = $this->authenticator->createToken($request, 'key');

        $this->assertEquals('anon.', $token->getUser());

        /** @var Credentials $credentials */
        $credentials = $token->getCredentials();

        $this->assertEquals('username', $credentials->getUsername());
        $this->assertEquals('password', $credentials->getPassword());

        $this->assertEquals('key', $token->getProviderKey());
    }

    /**
     * Should throw exception
     *
     * @expectedException Symfony\Component\Security\Core\Exception\BadCredentialsException
     */
    public function testCreateTokenNoCredentials(): void
    {
        $request = new Request();

        $this->authenticator->createToken($request, 'key');
    }

    /**
     * Should throw exception
     *
     * @expectedException Symfony\Component\Security\Core\Exception\BadCredentialsException
     */
    public function testCreateTokenNoPassword(): void
    {
        $request = new Request();
        $request->headers->set('PHP_AUTH_USER', 'username');

        $this->authenticator->createToken($request, 'key');
    }

    /**
     * Should throw exception
     *
     * @expectedException Symfony\Component\Security\Core\Exception\BadCredentialsException
     */
    public function testCreateTokenNoUser(): void
    {
        $request = new Request();
        $request->headers->set('PHP_AUTH_PW', 'password');

        $this->authenticator->createToken($request, 'key');
    }

    /**
     * Should return true
     */
    public function testSupportsToken(): void
    {
        $token = new PreAuthenticatedToken('user', 'credentials', 'key');
        $this->assertTrue($this->authenticator->supportsToken($token, 'key'));
    }

    /**
     * Should return false
     */
    public function testSupportsTokenInvalidProviderKey(): void
    {
        $token = new PreAuthenticatedToken('user', 'credentials', 'key');
        $this->assertFalse($this->authenticator->supportsToken($token, 'invalid_key'));
    }

    /**
     * Should return false
     */
    public function testSupportsTokenInvalidToken(): void
    {
        /** @var TokenInterface|MockObject $token */
        $token = $this->getMockBuilder(TokenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->assertFalse($this->authenticator->supportsToken($token, 'key'));
    }

    /**
     * Setup tests.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->authenticator = new ApiAuthenticator();
    }
}
