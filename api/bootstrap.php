<?php
require __DIR__ . '/../vendor/autoload.php';
require 'DatabaseConnector.php';
require 'PersonGateway.php';
require 'PersonController.php';

use Dotenv\Dotenv;
use API\DatabaseConnector;

$dotenv = new DotEnv(__DIR__);
$dotenv->load();

$dbConnection = (new DatabaseConnector())->getConnection();
