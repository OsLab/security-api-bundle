<?php

/*
 * This file is part of the OsLabSecurityApiBundle package.
 *
 * (c) OsLab <https://github.com/OsLab>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OsLab\SecurityApiBundle\Security\User;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Provider in memory strategy.
 *
 * @author Michael COULLERET <michael.coulleret@gmail.com>
 * @author Florent DESPIERRES <orions07@gmail.com>
 */
class InMemoryApiUserProvider implements UserProviderInterface
{
    /**
     * @var array
     */
    private $users;

    /**
     * Constructor.
     *
     * @param array $users The list of given users in security
     */
    public function __construct(array $users = [])
    {
        $this->users = $users;
    }

    /**
     * Returns a username for a given api key.
     *
     * @param string $apiKey the api key
     *
     * @return string|null
     */
    public function getUsernameByApiKey($apiKey)
    {
        foreach ($this->users as $user) {
            if ($apiKey === $user->getPassword()) {
                return $user->getUsername();
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        $user = $this->getUser($username);

        return new User($username, null, $user->getRoles());
    }

    /**
     * Adds a new User to the provider.
     *
     * @param UserInterface $user A UserInterface instance
     *
     * @throws \LogicException
     */
    public function createUser(UserInterface $user)
    {
        if (isset($this->users[strtolower($user->getUsername())])) {
            throw new \LogicException('Another user with the same username already exists.');
        }

        $this->users[strtolower($user->getUsername())] = $user;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }

    /**
     * Returns the user by given username.
     *
     * @param string $username the username
     *
     * @return User
     */
    private function getUser($username)
    {
        if (!isset($this->users[strtolower($username)])) {
            $exception = new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
            $exception->setUsername($username);

            throw $exception;
        }

        return $this->users[strtolower($username)];
    }
}
