<?php
header('Access-Control-Allow-Origin: *');
include "../../database/database.php";
include "../../helper/helper_jwt.php";
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

    $sql = "SELECT user_id,username,first_name,last_name,nick_name FROM `users` WHERE `username` = '{$body["username"]}' AND `password` = '{$password_encryp}' ";

    $result = mysqli_query($database->getConnection(), $sql);

    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $user_data = mysqli_fetch_assoc($result);
            $return["status"] = true;
            $return["data"] = [
                "user_info"=>$user_data,
                "token"=>encode_jwt($user_data)
            ];
        } else {
            $return["status"] = false;
            $return["message"] = "Password is incorrect";
        }
    } else {
        $return["status"] = false;
        $return["message"] = mysqli_error($database->getConnection());
    }
} else {
    $return["status"] = "Method not allow";
}


header('Content-Type: application/json');

echo json_encode($return);
