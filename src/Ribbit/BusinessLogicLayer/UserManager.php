<?php

/**
 * @author M.Paraiso
 */

namespace Ribbit\BusinessLogicLayer;

use Ribbit\Entity\User;
use Ribbit\DataAccessLayer\IUserProvider;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class UserManager implements UserProviderInterface {

    /**
     * @var \Ribbit\DAL\IUserProvider $userProvider
     */
    protected $userProvider;

    /**
     * @var \Symfony\Component\Security\Core\Encoder\EncoderFactory
     */
    protected $encoderFactory;
    protected $logger;

    function __construct(IUserProvider $userProvider, EncoderFactory $encoderFactory) {
        $this->userProvider = $userProvider;
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @return User user
     * @param string $username
     */
    public function loadUserByUsername($username) {
        return $this->userProvider->getByUsername($username);
    }

    public function getByEmail($email) {
        return $this->userProvider->getByEmail($email);
    }

    public function refreshUser(UserInterface $user) {
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * 
     * @param User $user
     * @return bool
     */
    public function supportsClass($user) {
        return is_a($user, "User");
    }

    public function register(User $user) {
        $this->setUserSalt($user);
        $password = $this->encodePassword($user);
        $user->setPassword($password);
        $user->setCreatedAt(new \DateTime('now'));
        $user->setUpdatedAt(new \DateTime('now'));
        $user->eraseCredentials();
        return $this->userProvider->create($user);
    }

    function encodePassword(User $user) {
        return $this->encoderFactory->getEncoder($user)
                        ->encodePassword($user->getPassword(), $user->getSalt());
    }

    function setUserSalt(User $user) {
        $user->setSalt(md5(time()));
    }

}

