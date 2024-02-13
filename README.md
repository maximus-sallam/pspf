# API Overview (Work in Progress)

This API leverages JWT (JSON Web Tokens) for secure authentication, enabling operations on `person` records such as retrieval, creation, update, and deletion. This project is currently in development for running specific functions on a local server. The term `person` will be replaced with the applicable functions in the final iteration.

## Authentication

Authentication is managed through JWT. Each request to the API must include a valid JWT in the `Authorization` header.

**Generate a JWT**:
Run the `TokenGenerator.php` script to generate a JWT and save a secret key in the `.env` file for token verification. This script prints a JWT, which is then used for client authentication.

## Setup Instructions

### Clone the Repository Contents

To set up the API locally, first clone the contents of this repository into your web server's DocumentRoot.

```bash
$ git clone https://github.com/your-repo-url/pspf.git .
```

### Install Dependencies
After cloning, install the required dependencies with Composer:

```bash
$ composer install
```

### Dependencies include:
# vlucas/phpdotenv for .env file management.
# ext-pdo for database interaction.
# lcobucci/jwt for JWT authentication.

### Generate JWT
Generate a JWT and a secret key for the .env file by running:

```bash
$ php TokenGenerator.php
```
This script will output a JWT for client use and update the .env file with the secret key.

### API Usage
Below are example curl commands to interact with the API. Replace <Your-JWT> with the token generated by TokenGenerator.php.

# Return All Records
```bash
$ curl -X GET http://testing.maximus-sallam.com/person \
-H "Authorization: <Your-JWT>"
```

# Return a Specific Record ID
```bash
$ curl -X GET http://testing.maximus-sallam.com/person/{id}  \
-H "Authorization: <Your-JWT>"
```

# Create a New Record

```bash
curl -X POST http://testing.maximus-sallam.com/person \
-H "Content-Type: application/json" \
-H "Authorization: <Your-JWT>" \
-d '{
      "firstname": "Jane",
      "lastname": "Fonda",
      "firstparent_id": null,
      "secondparent_id": null
    }'
```

# Update an Existing Record

```bash
$ curl -X PUT http://testing.maximus-sallam.com/person/{id} \
-H "Content-Type: application/json" \
-H "Authorization: <Your-JWT>" \
-d '{
      "firstname": "Jane",
      "lastname": "Fonda",
      "firstparent_id": null,
      "secondparent_id": null
    }'
```

# Delete an Existing Record

```bash
$ curl -X DELETE http://testing.maximus-sallam.com/person/{id} \
-H "Authorization: <Your-JWT>"
```

Note: This API is a work in progress, intended for running functions on another server locally. The person path will be replaced with specific functions in the final version.
