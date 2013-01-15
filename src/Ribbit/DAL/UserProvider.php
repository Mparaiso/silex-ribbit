<?php

/**
 * @author M.PARAISO 
 */

namespace Ribbit\DAL;

use \Ribbit\DTO\User;
use \Doctrine\DBAL\Connection;

/**
 * manages access to users repository
 */
class UserProvider implements IUserProvider {

    /**
     *
     * @var Doctrine\DBAL\Connection $connection
     */
    protected $connection;
    protected $table;

    public function __construct(Connection $connection, $table = 'users') {
        $this->connection = $connection;
        $this->table = $table;
    }

    /**
     * transform a users record into an array
     * @param array $record
     * return Ribbit\DTO\User
     */
    function recordToUser(array $record = null) {
        if ($record) {
            $user = new User();
            $user->id = $record["id"];
            $user->username = $record['username'];
            $user->name = $record["name"];
            $user->passwordDigest = $record["password_digest"];
            $user->roles = array($record["role"]);
            $user->salt = $record["salt"];
            $user->avatarUrl = $record["avatar_url"];
            $user->email = $record["email"];
            $user->lastLogin = $record["last_login"];
            return $user;
        }
    }

    /**
     * transform a collection of users record to a collection of Users
     * @param array $records
     * @return array
     */
    function recordsToUsers(array $records) {
        $users = array();
        foreach ($records as $record) {
            array_push($users, $this->recordToUser($record));
        }
        return $users;
    }

    function getByUsername($username) {
        $record = $this->connection
                ->fetchAssoc("SELECT id,username,email,name,avatar_url,"
                . " password_digest,salt,last_login,"
                . " roles.title as role from $this->table "
                . " JOIN roles ON roles.id = users.role_id "
                . " WHERE username = ?", array($username));
        return $this->recordToUser($record);
    }

    function getById($id) {
        $record = $this->connection->fetchAssoc("SELECT id,username,email,"
                . "name,avatar_url,"
                . " password_digest,salt,last_login,"
                . "roles.title as role from $this->table "
                . " JOIN roles on roles.id = users.role_id WHERE id = ?"
                , array($id));
        return $this->recordToUser($record);
    }

    public function create(User $user) {
        $affectedRows = $this->connection->insert($this->table, array(
            "username" => $user->username,
            "password_digest" => $user->passwordDigest,
            "name" => $user->name,
            "email" => $user->email,
            "avatar_url" => $user->avatar_url,
            "salt" => $user->salt,
            "role_id" => $this->getRoleByTitle($user->roles[0])->title,
                ));
        $lastInsertedId = $this->connection->lastInsertId();
    }

    public function getRoleByTitle($title) {
        
    }

}

