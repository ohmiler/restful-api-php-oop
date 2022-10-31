<?php 

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
    header("Access-Control-Allow-Headers: X-Requested-With");
    header("Content-Type: application/json");

    require_once("db.php");
    $user = new Database();

    $api = $_SERVER["REQUEST_METHOD"];

    $id = intval($_GET['id'] ?? '');

    // GET All data or Single data
    if ($api == "GET") {
        if ($id != 0) {
            $data = $user->fetch($id);
        } else {
            $data = $user->fetchAll();
        }
        echo json_encode($data);
    }

    // Add new data
    if ($api == "POST") {
        $name = $user->test_input($_POST['name']);
        $email = $user->test_input($_POST['email']);
        $phone = $user->test_input($_POST['phone']);

        if ($user->insert($name, $email, $phone)) {
            echo $user->message("User added successfully", false);
        } else {
            echo $user->message("Failed to add an user", true);
        }
    }

    // Update data
    if ($api == 'PUT') {
        parse_str(file_get_contents('php://input'), $post_input);

        $name = $user->test_input($post_input['name']);
        $email = $user->test_input($post_input['email']);
        $phone = $user->test_input($post_input['phone']);

        if ($id != null) {
            if ($user->update($name, $email, $phone, $id)) {
                echo $user->message("User updated successfully", false);
            } else {
                echo $user->message("Failed to update and user", true);
            }
        } else {
            echo $user->message("User not found!", true);
        }
    }

    // Delete an user from database
    if ($api == "DELETE") {
        if ($id != null) {
            if ($user->delete($id)) {
                echo $user->message("User deleted successfully", false);
            } else {
                echo $user->message("Failed to delete an user", true);
            }
        } else {
            echo $user->message("User not found!", true);
        }
    }

?>