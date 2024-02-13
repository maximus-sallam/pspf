<?php
global $dbConnection;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use API\PersonController;

require "api/bootstrap.php";

// send some CORS headers so the API can be called from anywhere
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$url = parse_url($_SERVER["REQUEST_URI"]);
$url = explode( "/", trim($url["path"], "/"));

// all endpoints start with /person
// everything else results in a 404 Not Found
if (empty($url[0]) or ($url[0] !== "person")) {
    header("HTTP/1.1 404 Not Found");
    http_response_code(404);
    echo json_encode(["message" => "404 Not Found"]);
    exit();
}

// the user id is, of course, optional and must be a number:
$userId = !isset($url[1]) ? 0 : (int)$url[1];

// authenticate the request:
try {
    if (!authenticate()) {
        header("HTTP/1.1 401 Unauthorized");
        http_response_code(401);
        echo json_encode(["message" => "Unauthorized"]);
        exit();
    }
} catch (Exception $e) {
}

$requestMethod = $_SERVER["REQUEST_METHOD"];

// pass the request method and user ID to the PersonController and process the HTTP request:
$controller = new PersonController($dbConnection, $requestMethod, $userId);
$controller->processRequest();

function authenticate(): bool
{
    // Accessing the Authorization header
    $authHeader = $_SERVER["HTTP_AUTHORIZATION"] ?? '';

    // Directly capture the token without expecting a "Bearer" prefix
    if (empty($authHeader)) {
        http_response_code(401); // Unauthorized
        echo json_encode(["message" => "No Authorization header found."]);
        return false;
    }

    // Now, $authHeader is expected to be the token itself
    $tokenString = $authHeader;

    try {
        // Initialize the JWT configuration using your secret
        $config = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText(base64_decode(getenv("SECRET")))
        );

        // Parse the token string into a token object
        $token = $config->parser()->parse($tokenString);

        // Define your constraints based on your token's requirements
        $constraints = [
            new SignedWith($config->signer(), $config->signingKey()),
        ];

        // Validate the token with the given constraints
        if ($config->validator()->validate($token, ...$constraints)) {
            // Token is valid
            return true;
        } else {
            // Token is invalid
            throw new Exception("Invalid token.");
        }
    } catch (Exception $e) {
        // Handle parsing errors or invalid tokens
        http_response_code(401); // Unauthorized
        echo json_encode(["message" => "Unauthorized" . $e->getMessage()]);
        return false;
    }
}