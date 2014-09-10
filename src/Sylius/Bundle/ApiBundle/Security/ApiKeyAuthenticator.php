<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\ApiBundle\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class ApiKeyAuthenticator implements SimplePreAuthenticatorInterface, AuthenticationFailureHandlerInterface
{
    /**
     * @var string[]
     */
    private $unsecuredRoutes = [];

    /**
     * @var ApiKeyUserProvider
     */
    private $userProvider;

    public function __construct(ApiKeyUserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    /**
     * {@inheritdoc}
     *
     * @throws BadCredentialsException
     */
    public function createToken(Request $request, $providerKey)
    {
        if (!in_array($request->attributes->get('_route'), $this->unsecuredRoutes, true) && (!$request->headers->has('API-Authorization') && !$request->query->has('api_key'))) {
            throw new BadCredentialsException('No API key found.');
        }

        return new PreAuthenticatedToken(
            'anon.',
            $request->headers->get('API-Authorization', $request->query->get('api_key', 'anon.')),
            $providerKey
        );
    }

    /**
     * {@inheritdoc}
     *
     * @throws AuthenticationException
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        $apiKey = $token->getCredentials();
        if ('anon.' === $apiKey) {
            return $token;
        }

        $user = $this->userProvider->loadUserByApiKey($apiKey);
        if (!$user) {
            throw new AuthenticationException(sprintf('API Key "%s" does not exist.', $apiKey));
        }

        return new PreAuthenticatedToken(
            $user,
            $apiKey,
            $providerKey,
            $user->getRoles()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $providerKey === $token->getProviderKey();
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse(['status' => 'error', 'message' => $exception->getMessage() ?: 'Authentication Failed.'], 403);
    }
}
