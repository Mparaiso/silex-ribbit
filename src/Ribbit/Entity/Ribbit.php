<?php

namespace Ribbit\Entity;

/**
 * @Entity @Table(name="ribbits")
 */
use Ribbit\Entity\User;

class Ribbit {

    /** @Id @Column(type="integer") @GeneratedValue */
    protected $id;

    /** @ManyToOne(targetEntity="User",inversedBy="ribbits") */
    protected $user;

    /** @Column(type="string") */
    protected $ribbit;

    /** @Column(type="datetime")
     * @var \Datetime $createAt
     */
    protected $createAt;

    function setUser(User $user) {
        $user->addRibbit($this);
        $this->user = $user;
    }

    function setRibbit($text) {
        $this->ribbit = $text;
        return $this;
    }
    
    function setCreatedAt(\DateTime $datetime){
        $this->createAt = $datetime;
        return $this;
    }

    function getId() {
        return $this->id;
    }

    function getUser() {
        return $this->user;
    }

    function getRibbit() {
        return $this->ribbit;
    }
    
    function getCreatedAt(){
        return $this->createAt;
    }

}