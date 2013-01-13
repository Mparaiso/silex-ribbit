<?php

/**
 * @author M.PARAISO 
 */

namespace Ribbit\DAL {

    use Ribbit\DTO\User;
    
    /**
     * manages access to users repository
     */
    class UserProvider {

        /**
         * transform a users record into an array
         * @param array $record
         * return Ribbit\DTO\User
         */
        function recordToUser(array $record) {
            $user = new User();
            $user->username = $record['username'];
            $user->name = $record["name"];
            $user->passwordDigest = $record["password_digest"];
            $user->avatarUrl = $record["avatar_url"];
            $user->email = $record["email"];
            $user->createdAt = $record["created_at"];
            $user->updatedAt = $record["updated_at"];
            $user->lastLogin = $record["last_login"];
            return $user;
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

    }

}