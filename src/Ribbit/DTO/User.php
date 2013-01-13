<?php

namespace Ribbit\DTO {

    /**
     * Description of User
     *
     * @author M.Paraiso
     */
    class User extends Base {

        protected $username;
        protected $name;
        protected $email;
        protected $passwordDigest;
        protected $avatarUrl;
        
        function getUsername() {
            return $this->username;
        }

        function setUsername($val) {
            $this->username = $val;
        }

        function getName() {
            return $this->name;
        }

        function setName($val) {
            return $this->name = $val;
        }

        function getEmail() {
            return $this->email;
        }

        function setEmail($val) {
            $this->email = $val;
        }

        function getPasswordDigest() {
            return $this->passwordDigest;
        }

        function setPasswordDigest($val) {
            $this->passwordDigest = $val;
        }
        
        function getAvatarUrl() {
            return $this->avatarUrl;
        }

        function setAvatarUrl($val) {
            $this->avatarUrl = $val;
        }

    }

}

