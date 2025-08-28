<?php
require "db-config.php";

/**
 * The dbConnect function connects PHP with MySQL server.
 * @return mysqli object on success, or exits on failure.
 */
function dbConnect() {
    $mysqli = new mysqli('localhost', USERNAME,PASSWORD, DATABASE);

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    return $mysqli;
}