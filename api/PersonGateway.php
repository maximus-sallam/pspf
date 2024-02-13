<?php
namespace API;

use PDO;
use PDOException;

class PersonGateway {

    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        try {
            $statement = "
                SELECT 
                    id, firstname, lastname, firstparent_id, secondparent_id
                FROM
                    person;
            ";
        } catch (PDOException $e) {
            error_log($e->getMessage());
            // Send a response to the client
            header('Content-Type: application/json');
            echo json_encode(['error' => 'An error occurred. Please try again later.']);
            exit;
        }
        try {
            $statement = $this->db->query($statement);
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            // Send a response to the client
            header('Content-Type: application/json');
            echo json_encode(['error' => 'An error occurred. Please try again later.']);
            exit;
        }
    }

    public function find($id)
    {
        $statement = "
            SELECT 
                id, firstname, lastname, firstparent_id, secondparent_id
            FROM
                person
            WHERE id = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($id));
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            // Send a response to the client
            header('Content-Type: application/json');
            echo json_encode(['error' => 'An error occurred. Please try again later.']);
            exit;
        }
    }

    public function insert(Array $input)
    {
        $statement = "
            INSERT INTO person 
                (firstname, lastname, firstparent_id, secondparent_id)
            VALUES
                (:firstname, :lastname, :firstparent_id, :secondparent_id);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'firstname' => $input['firstname'],
                'lastname'  => $input['lastname'],
                'firstparent_id' => $input['firstparent_id'] ?? null,
                'secondparent_id' => $input['secondparent_id'] ?? null,
            ));
            return $statement->rowCount();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            // Send a response to the client
            header('Content-Type: application/json');
            echo json_encode(['error' => 'An error occurred. Please try again later.']);
            exit;
        }
    }

    public function update($id, Array $input)
    {
        $statement = "
            UPDATE person
            SET 
                firstname = :firstname,
                lastname  = :lastname,
                firstparent_id = :firstparent_id,
                secondparent_id = :secondparent_id
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => (int) $id,
                'firstname' => $input['firstname'],
                'lastname'  => $input['lastname'],
                'firstparent_id' => $input['firstparent_id'] ?? null,
                'secondparent_id' => $input['secondparent_id'] ?? null,
            ));
            return $statement->rowCount();
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function delete($id)
    {
        $statement = "
            DELETE FROM person
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('id' => $id));
            return $statement->rowCount();
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }
}