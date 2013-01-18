<?php

namespace Ribbit\BusinessLogicLayer;

use Ribbit\Entity\Role;
use Ribbit\DataAccessLayer\IRoleProvider;

class RoleManager {

    /**
     * @var \Ribbit\DataAccessLayer\IRoleProvider $roleProvider
     */
    protected $roleProvider;

    public function __construct(IRoleProvider $roleProvider) {
        $this->roleProvider = $roleProvider;
    }

    public function getByTitle($title) {
        return $this->roleProvider->getByTitle($title);
    }

}