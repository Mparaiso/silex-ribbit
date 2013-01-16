<?php

namespace Ribbit\DTO {
    
    /**
     * Description of User
     *
     * @author M.Paraiso
     */
    
    use Symfony\Component\Security\Core\User\UserInterface;
    
    class User extends Base implements UserInterface{
        public $id;
        public $username;
        public $name;
        public $email;
        public $password;
        public $passwordDigest;
        public $avatarUrl;
        /**
         * @var array $roles
         */
        public $roles;
        public $salt;
        public $lastLogin;
        
        public function eraseCredentials() {
            
        }

        public function getPassword() {
            return $this->passwordDigest;
        }

        public function getRoles() {
            return $this->roles;
        }

        public function getSalt() {
            return $this->salt;
        }

        public function getUsername() {
            return $this->username;
        }       
    }

}

