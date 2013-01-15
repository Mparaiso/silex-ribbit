<?php

namespace Ribbit\DAL;

use \Ribbit\DTO\User;

interface IUserProvider {

    /**
     * 
     * @param string $username
     * @return User user
     */
    function getByUsername($username);

    /**
     * @return \Ribbit\DTO\User
     * @param \Ribbit\DTO\User $user
     */
    function create(User $user);
}

