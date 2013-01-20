<?php

namespace Ribbit\DataAccessLayer;

use Ribbit\Entity\User;

interface IUserProvider {

    /**
     * 
     * @param string $username
     * @return User user
     */
    function getByUsername($username);
    function getByEmail($email);
    /**
     * @return User
     * @param User $user
     */
    function create(User $user);
    /**
     * @return User[]
     */
    function get();
}

