<?php
global $dbConnection;

use API\PersonController;
use API\PersonGateway;

require "api/bootstrap.php";

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");

print_r($_SERVER['REQUEST_URI'], "\n");

$url = parse_url($_SERVER['REQUEST_URI']);
$url = explode( '/', trim($url['path'], '/'));

// all endpoints start with /person
// everything else results in a 404 Not Found
if (empty($url[0]) or ($url[0] !== 'person')) {
    http_response_code(404);
    echo json_encode(["message" => "404 Not Found"]);
    exit();
}

// the user id is, of course, optional and must be a number:
$userId = !isset($url[1]) ? 0 : (int)$url[1];

$requestMethod = $_SERVER["REQUEST_METHOD"];

// pass the request method and user ID to the PersonController and process the HTTP request:
$controller = new PersonController($dbConnection, $requestMethod, $userId);
$controller->processRequest();

$personGateway = new PersonGateway($dbConnection);
