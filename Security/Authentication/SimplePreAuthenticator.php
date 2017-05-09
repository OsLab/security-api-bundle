<?php

/*
 * This file is part of the OsLabSecurityApiBundle package.
 *
 * (c) OsLab <https://github.com/OsLab>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OsLab\SecurityApiBundle\Security\Authentication;

use OsLab\SecurityApiBundle\Security\User\InMemoryApiUserProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;

/**
 * Class SimplePreAuthenticator.
 *
 * @author Michael COULLERET <michael@coulleret.pro>
 * @author Florent DESPIERRES <orions07@gmail.com>
 */
class SimplePreAuthenticator implements SimplePreAuthenticatorInterface
{
    /**
     * @var string
     */
    protected $keyName;

    /**
     * @var string
     */
    protected $method;

    /**
     * Constructor.
     *
     * @param string $keyName
     * @param string $method
     */
    public function __construct($keyName, $method)
    {
        $this->keyName = $keyName;
        $this->method  = $method;
    }

    /**
     * {@inheritdoc}
     */
    public function createToken(Request $request, $providerKey)
    {
        $apiKey = null;

        if ($this->method === 'header') {
            $apiKey = $request->headers->get($this->keyName);
        } elseif ($this->method === 'query') {
            $apiKey = $request->query->get($this->keyName);
        }

        if (isset($apiKey) === false) {
            throw new AccessDeniedException(sprintf('The key "%s" is not provided', $this->keyName));
        }

        return new PreAuthenticatedToken(
            'anon.',
            $apiKey,
            $providerKey
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() == $providerKey;
    }

    /**
     * {@inheritdoc}
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        if (!$userProvider instanceof InMemoryApiUserProvider) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The user provider must be an instance of %s; (%s was given).',
                    InMemoryApiUserProvider::class,
                    get_class($userProvider)
                )
            );
        }

        $apiKey   = $token->getCredentials();
        $username = $userProvider->getUsernameByApiKey($apiKey);

        if (isset($username) === false && strlen($username) <= 0) {
            $exception = new UsernameNotFoundException(sprintf('API Key "%s" does not exist.', $apiKey));
            $exception->setUsername($username);

            throw $exception;
        }

        $user = $userProvider->loadUserByUsername($username);

        return new PreAuthenticatedToken(
            $user,
            $apiKey,
            $providerKey,
            $user->getRoles()
        );
    }
}
