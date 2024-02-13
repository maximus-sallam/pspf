<?php

function base64url_encode($data): bool|string
{
    $b64 = base64_encode($data);
    $url = strtr($b64, '+/', '-_');
    return rtrim($url, '=');
}

try {
    $token = base64_encode(random_bytes(16));
} catch (Exception $e) {
    print($e->getMessage());
}

try {
    // Generate a secret
    $secret = base64_encode(random_bytes(32));

    // Define the environment variable line to add to the .env file
    $envLine = "SECRET=" . $secret . PHP_EOL;

    // File path to the .env file
    $filePath = __DIR__ . '/api/.env';

    // Read the current contents of the .env file
    $envContents = file_get_contents($filePath);
    if ($envContents === false) {
        throw new Exception("Failed to read .env file");
    }

    // Pattern to find the SECRET line
    $pattern = '/^SECRET=.*$/m';

    // Replacement string with the new secret
    $replacement = "SECRET=$secret";

    // Replace the existing SECRET line with the new secret
    $updatedEnvContents = preg_replace($pattern, $replacement, $envContents);

    // Write the updated contents back to the .env file
    if (file_put_contents($filePath, $updatedEnvContents) === false) {
        throw new Exception("Failed to write to .env file");
    }

    echo "Secret updated in .env file.\n";
} catch (Exception $e) {
    print($e->getMessage());
}

// RFC-defined structure
$header = [
    "alg" => "HS256",
    "typ" => "JWT"
];

$payload = [
    "token" => $token,
    "stamp" => date("c")
];

$jwt = sprintf(
    "%s.%s",
    base64url_encode(json_encode($header)),
    base64url_encode(json_encode($payload))
);

$signature = base64url_encode(
    hash_hmac('SHA256',
        $jwt,
        base64_decode($secret),
        true));

$jwt = sprintf(
    "%s.%s",
    $jwt,
    $signature
);

var_dump($jwt);