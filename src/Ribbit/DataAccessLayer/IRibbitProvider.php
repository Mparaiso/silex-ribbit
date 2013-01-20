<?php

namespace Ribbit\DataAccessLayer;

use Ribbit\Entity\Ribbit;
use Ribbit\Entity\User;

/**
 * FR : persiste les ribbits vers la base de données
 */
interface IRibbitProvider{
    function findByUser(User $user);
    function create(Ribbit $ribbit);
    function findAll();
}
