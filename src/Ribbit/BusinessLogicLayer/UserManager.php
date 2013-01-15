<?php

/**
 * @author M.Paraiso
 */

namespace Ribbit\BusinessLogicLayer;

use Ribbit\Entity\User;
use Ribbit\DataAccessLayer\IUserProvider;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Silex\Application;

class UserManager implements UserProviderInterface {

    /**
     * @var Ribbit\DAL\IUserProvider $userProvider
     */
    protected $userProvider;

    function __construct(IUserProvider $userProvider, Application $app) {
        $this->userProvider = $userProvider;
        $this->$app = $app;
    }

    /**
     * @return User user
     * @param string $username
     */
    public function loadUserByUsername($username) {
        return $this->userProvider->getByUsername($username);
    }

    public function refreshUser(UserInterface $user) {
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * 
     * @param User $user
     * @return bool
     */
    public function supportsClass(User $user) {
        return is_a($user, "User");
    }

}

