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
    function getById($id);
    /**
     * @return \Ribbit\Entity\Use
     * @param \Ribbit\Entity\Use $user
     */
    function create(User $user);

    /**
     * @return \Ribbit\Entity\User 
     * @param \Ribbit\Entity\User $user
     */
    function update(User $user);

    /**
     * @return User[]
     */
    function findAll();
}

