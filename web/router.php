<?php

if (isset($_SERVER['SCRIPT_FILENAME'])) {
    return false;
} else {
    require 'index_dev.php';
}
