<?php

namespace Ribbit\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * @Entity @Table(name="users")
 * @HasLifecycleCallbacks
 * @note @doctrine HasLifecyleCallback active la validation
 * */
class User {

    /** @Id @Column(type="integer") @GeneratedValue * */
    protected $id;

    /** @Column(type="string") * */
    protected $username;

    /** @Column(type="string") * */
    protected $name;

    /** @Column(type="string",nullable=TRUE) * */
    protected $email;

    /** @Column(type="string",nullable=TRUE) * */
    protected $avatarUrl;

    /** @Column(type="string",nullable=TRUE) * */
    protected $password;

    /** @Column(type="string") * */
    protected $passwordDigest;

    /** @Column(type="string") * */
    protected $salt;

    /** @Column(type="datetime") * */
    protected $createdAt;

    /** @Column(type="datetime") * */
    protected $updatedAt;

    /** @Column(type="datetime",nullable=TRUE) * */
    protected $lastLogin;

    /** @ManyToMany(targetEntity="Role") * */
    protected $roles = null;

    function getId() {
        return $this->id;
    }

    function getUsername() {
        return $this->username;
    }

    function getName() {
        return $this->name;
    }

    function getEmail() {
        return $this->email;
    }

    function getAvatarUrl() {
        return $this->avatarUrl;
    }

    function getRoles() {
        return $this->roles;
    }

    function getPassword() {
        return $this->password;
    }

    function getPasswordDigest() {
        return $this->passwordDigest;
    }

    function getSalt() {
        return $this->salt;
    }

    function getCreatedAt() {
        return $this->createdAt;
    }

    function getUpdatedAt() {
        return $this->updatedAt;
    }

    function getLastLogin() {
        return $this->lastLogin;
    }

    function setUsername($val) {
        $this->username = $val;
    }

    function setName($val) {
        $this->name = $val;
    }

    function setEmail($val) {
        $this->email = $val;
    }

    function setAvatarUrl($val) {
        $this->avatarUrl = $val;
    }

    function setRoles($val) {
        $this->roles = $val;
    }

    function setPassword($val) {
        $this->password = $val;
    }

    function setPasswordDigest($val) {
        $this->passwordDigest = $val;
    }

    function setSalt($val) {
        $this->salt = $val;
    }

    function setCreatedAt($val) {
        $this->createdAt = $val;
    }

    function setUpdatedAt($val) {
        $this->updatedAt = $val;
    }

    function setLastLogin($val) {
        $this->lastLogin = $val;
    }

    function __construct() {
        $this->roles = new ArrayCollection();
    }

    function addRole($role) {
        $this->roles[] = $role;
    }

    /**
     * @PrePersist @PreUpdate
     */
    function validate() {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)):
            throw new ValidatorException("email '$this->email' is not valide");
        endif;
    }

    /**
     * @PrePersist @PreUpdate
     */
    function createAvatarUrl() {
        $this->avatarUrl =
                "http://www.gravatar.com/avatar/#"
                . md5($this->email)
                . "?s=50";
    }

}