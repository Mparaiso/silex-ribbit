<?php

namespace Ribbit\Entity;

/**
 * @Entity @Table(name="roles")
 * */
class Role {

    /** @Id @Column(type="integer") @GeneratedValue * */
    protected $id;

    /** @Column(type="string",unique=TRUE) * */
    protected $title;

    function getId() {
        return $this->id;
    }

    function getTitle() {
        return $this->title;
    }

    function setTitle($val) {
        $this->title = $val;
    }

    function __toString(){
        return $this->title;
    }

}