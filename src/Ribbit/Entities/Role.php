<?php

namespace Ribbit\Entities;

    
class Role 
{
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    /** @Column(type="string") **/
    protected $title;
    /** @Column(type="integer") **/
    protected $ref;

    function getTitle(){return $this->title;}
    function getRef(){return $this->ref;}

    function setTitle($val){ $this->itle=$val;}
    function setRef($val){ $this->ref=$val;}
}