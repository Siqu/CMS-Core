<?php

namespace Siqu\CMS\API\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;

/**
 * Class ApiAuthenticator
 * @package Siqu\CMS\API\Security
 */
class ApiAuthenticator implements SimplePreAuthenticatorInterface
{
    /**
     * Authenticate the token.
     *
     * @param TokenInterface $token
     * @param UserProviderInterface $userProvider
     * @param $providerKey
     * @return PreAuthenticatedToken
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        if (!$userProvider instanceof ApiUserProvider) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The user provider must be an instance of ApiKeyUserProvider (%s was given)',
                    get_class($userProvider)
                )
            );
        }

        $credentials = $token->getCredentials();
        $username = $userProvider->getUsernameForCredentials($credentials);

        if (!$username) {
            throw new CustomUserMessageAuthenticationException(
                sprintf('Could not authenticate user "%s"', $username)
            );
        }

        $user = $userProvider->loadUserByUsername($username);

        return new PreAuthenticatedToken(
            $user,
            $credentials,
            $providerKey,
            $user->getRoles()
        );
    }

    /**
     * Create a api key token.
     *
     * @param Request $request
     * @param string $providerKey
     * @return PreAuthenticatedToken
     */
    public function createToken(Request $request, $providerKey): PreAuthenticatedToken
    {
        $username = $request->getUser();
        $password = $request->getPassword();

        if (!$username || !$password) {
            throw new BadCredentialsException();
        }

        $credentials = new Credentials($username, $password);

        return new PreAuthenticatedToken(
            'anon.',
            $credentials,
            $providerKey
        );
    }

    /**
     * Check if authenticator supports the given token.
     *
     * @param TokenInterface $token
     * @param string $providerKey
     * @return bool
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }
}