<?php
include "../../database/database.php";
include "../../helper/helper_jwt.php";
include "../../helper/cors.php";
cors();
$return = array();



if ($_SERVER["REQUEST_METHOD"] == "PUT") {

    $headers = apache_request_headers();

    if (isset($headers['Authorization'])) {
        $token = $headers['Authorization'];
        try {
            if (decode_jwt($token)) {

                $database = new Database();
                //receive JSON POST
                $json = file_get_contents('php://input');
                // Converts it into a PHP object
                $body = json_decode($json, true);

                $sql = "
                INSERT INTO `projects`(`project_name`, `project_start`, `project_end`) VALUES (
                    '{$body["project_name"]}',
                    '{$body["project_start"]}',
                    '{$body["project_end"]}'
                )
                ";

                $result = mysqli_query($database->getConnection(), $sql);

                if ($result) {
                    $return["status"] = true;
                    $return["message"] = "Create Project Successfully";
                } else {
                    $return["status"] = false;
                    $return["message"] = mysqli_error($database->getConnection());
                }
            } else {
                $return["status"] = false;
                $return["message"] = "Token incorrect";
            }
        } catch (Throwable $err) {
            $return["status"] = false;
            $return["message"] = "Token incorrect";
        }
    } else {
        $return["status"] = false;
        $return["message"] = "Token Not Found";
    }
} else {
    $return["status"] = "Method not allow";
}


header('Content-Type: application/json');
echo json_encode($return);