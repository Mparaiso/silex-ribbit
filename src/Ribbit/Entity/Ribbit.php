<?php
namespace Ribbit\Entity;

/**
 * @Entity @Table(name="ribbits")
 */

use Ribbit\Entity\User;

class Ribbit{
    /** @Id @Column(type="integer") @GeneratedValue */
    protected $id;
    /** @ManyToOne(targetEntity="User",inversedBy="ribbits") */
    protected $user;
    /** @Column(type="string") */
    protected $ribbit;
    /** @Column(type="datetime") */
    protected $createAt;

    function setUser(User $user){
        $user->addRibbit($this);
        $this->user = $user;
    }
    
}