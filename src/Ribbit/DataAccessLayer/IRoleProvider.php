<?php

namespace Ribbit\DataAccessLayer;

interface IRoleProvider{
    function getByTitle($title);
}