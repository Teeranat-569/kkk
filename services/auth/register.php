<?php

include "../../database/database.php";
include "../../helper/cors.php";
cors();
$return = array();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();

    //receive JSON POST
    $json = file_get_contents('php://input');
    // Converts it into a PHP object
    $body = json_decode($json, true);


    $password_encryp = md5(md5($body["password"]));

    $sql = "INSERT INTO users (username, password, first_name,last_name,nick_name,age)
            VALUES (
                '{$body["username"]}', 
                '{$password_encryp}', 
                '{$body["first_name"]}',
                '{$body["last_name"]}',
                '{$body["nick_name"]}',
                '{$body["age"]}')";

    if (mysqli_query($database->getConnection(), $sql)) {
        $return["status"] = true;
        $return["message"] = "Register Success!!!";
    } else {
        $return["status"] = false;
        $return["message"] = mysqli_error($database->getConnection());
    }
} else {
    $return["status"] = "Method not allow";
}


header('Content-Type: application/json');
echo json_encode($return);