<?php

/**
 * @author M.PARAISO 
 */

namespace Ribbit\DataAccessLayer;

use Ribbit\Entity\User as User;
use \Doctrine\ORM\EntityManager;

/**
 * manages access to users repository
 */
class DoctrineUserProvider implements IUserProvider {

    /**
     *
     * @var EntityManager $em
     */
    protected $em;

    /**
     *
     * @var class $user_class
     */
    protected $user_class;

    public function __construct(EntityManager $em, $user_class = "Ribbit\Entity\User") {
        $this->em = $em;
        $this->user_class = $user_class;
    }

    public function create(User $user) {
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }
    
    public function update(User $user){
        $this->em->flush($user);
        return $user;
    }

    /**
     * 
     * @return \Ribbit\Entity\User[]
     */
    function findAll(){
        return $this->em->getRepository("Ribbit\Entity\User")->findAll();
    }
    
    public function getByUsername($username) {
        return $this->em->getRepository($this->user_class)->findOneBy(array("username" => $username));
    }

    public function getByEmail($email){
        return $this->em->getRepository($this->user_class)->findOneBy(array("email"=>$email));
    }

    public function getById($id){
        return $this->em->find("Ribbit\Entity\User",$id);
    }

    function getFollowerCount(User $user){
        return count($user->getFollowers());
    }
    
    function getFollowers(User $user){
        return $user->getFollowers();
    }
}

