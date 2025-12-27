<?php

$host = 'localhost';
$admin = 'root';
$pass= '';
$db='db_saw-topsis';

$koneksi = new mysqli($host, $admin, $pass, $db);

if ($koneksi->connect_error) {
    die('Connect Error (' . $koneksi->connect_error . ')' . $koneksi->connect_error);
}


