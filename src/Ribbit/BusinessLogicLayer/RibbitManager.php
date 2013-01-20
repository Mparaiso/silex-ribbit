<?php

/**
 * @author M.Paraiso
 */

namespace Ribbit\BusinessLogicLayer;

use Ribbit\Entity\Ribbit;
use Ribbit\Entity\User;
use Ribbit\DataAccessLayer\IRibbitProvider;

class RibbitManager {
    /**
     * @var \Ribbit\DataAccessLayer\IRibbitProvider $ribbitProvider
     */
    protected $ribbitProvider;
    
    public function __construct(IRibbitProvider $ribbitProvider) {
        $this->ribbitProvider = $ribbitProvider;
    }
    
    public function create(Ribbit $ribbit){
        $ribbit->setCreatedAt(new \DateTime("now"));
        return $this->ribbitProvider->create($ribbit);
    }
    
    function findAll(){
        return $this->ribbitProvider->findAll();
    }
    
    public function findByUser(User $user){
        return $this->ribbitProvider->findByUser($user);
    }
}