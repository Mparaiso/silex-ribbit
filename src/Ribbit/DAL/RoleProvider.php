<?php

namespace Ribbit\DAL {

    use Doctrine\DBAL\Connection;
    use Ribbit\DTO\Role;

    class RoleProvider implements IRoleProvider {
        /**
         * @var Doctrine\DBAL\Connection $connection
         */
        protected $connection;
        protected $table;

        function __construct(Connection $connection, $table = "roles") {
            $this->connection = $connection;
            $this->table = $table;
        }

        public function getRoles() {
            $records = $this->connection->fetchAll("SELECT * from roles ");
            return $records;
        }

    }

}