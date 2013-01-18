<?php

namespace Ribbit\DataAccessLayer;

use Doctrine\ORM\EntityManager;
use Ribbit\Entity\Role;

class DoctrineRoleProvider implements IRoleProvider{

    /**
     * @var \Doctrine\ORM\EntityManager $em
     */
    protected $em;

    /**
     * @var class $role_class
     */
    protected $role_class;

    function __construct(EntityManager $em,$role_class="Ribbit\Entity\Role"){
        $this->em = $em;
        $this->role_class = $role_class;
    }

    function getByTitle($title){
        return $this->em->getRepository($this->role_class)->findOneBy(array("title"=>$title));
    }
}