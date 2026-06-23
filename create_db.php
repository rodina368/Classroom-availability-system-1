<?php
$dbh = new PDO('mysql:host=127.0.0.1', 'root', '');
$dbh->exec('CREATE DATABASE IF NOT EXISTS classroom_ava_system;');
echo "Database created.\n";
