<?php

namespace Ribbit\DataAccessLayer;

use Ribbit\Entity\Ribbit;
use Doctrine\ORM\EntityManager;
use Ribbit\Entity\User;

class DoctrineRibbitProvider implements IRibbitProvider {

    /**
     * 
     * @param Doctrine\ORM\EntityManager $em
     */
    protected $em;

    function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function create(Ribbit $ribbit) {
        $this->em->persist($ribbit);
        $this->em->flush();
        return $ribbit;
    }

    public function findByUser(User $user) {
        return $this->em->getRepository("Ribbit\Entity\Ribbit")
                        ->findBy(array("user" => $user),array("createdAt"=>"DESC"));
    }

    public function findAll() {
        return $this->em->getRepository("Ribbit\Entity\Ribbit")
                ->findBy(array(),array("createdAt"=>"DESC"));
    }

}