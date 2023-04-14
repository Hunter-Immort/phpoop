<?php
class User {
    private $conn;

    public $id;
    public $firstname;
    public $lastname;
    public $email;

    public function __construct($db) {
        $this->conn = $db;
    }

    function create() {
        $query = "INSERT INTO users SET firstname=:firstname, lastname=:lastname, email=:email";
        $stmt = $this->conn->prepare($query);

        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->email=htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(":firstname", $this->firstname);
        $stmt->bindParam(":lastname", $this->lastname);
        $stmt->bindParam(":email", $this->email);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    function emailExists() {
        $query = "SELECT id FROM users WHERE email = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);

        $this->email=htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        $num = $stmt->rowCount();

        if($num > 0) {
            return true;
        }

        return false;
    }

    function validateFields() {
        $errors = array();

        if(empty($this->firstname)) {
            $errors[] = "First name is required";
        }

        if(empty($this->lastname)) {
            $errors[] = "Last name is required";
        }

        if(empty($this->email)) {
            $errors[] = "Email is required";
        } elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        } elseif($this->emailExists()) {
            $errors[] = "Email already exists";
        }

        return $errors;
    }
}
