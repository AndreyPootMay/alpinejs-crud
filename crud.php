<?php

include 'DBConnect.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET, POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if (isset($_GET["view"])) {
    $query = DbConnect::connection()->prepare(
        "SELECT * FROM employees WHERE active = 1 AND id = :id"
    );

    $query->bindParam(':id', $_GET['view'], PDO::PARAM_STR);

    $query->execute();

    echo json_encode($query->fetch(PDO::FETCH_ASSOC));
    exit();
}

if (isset($_GET["delete"])) {
    $query = DbConnect::connection()->prepare(
        "UPDATE employees SET active = 0 WHERE id = :id"
    );

    $query->bindParam(':id', $_GET['delete'], PDO::PARAM_INT, 1);

    if (!$query->execute()) {
        print_r($query->errorInfo());
        echo json_encode(["success" => 0]);
        exit();
    }

    echo json_encode(["success" => 1]);
    exit();
}

if (isset($_GET["create"])) {
    $data = json_decode(file_get_contents("php://input"), true);
    $name = $data['name'];
    $email = $data['email'];

    if (($name != "") && ($email != "")) {
        $query = DbConnect::connection()->prepare(
            "INSERT INTO employees (`name`, `email`) VALUES (:name, :email)"
        );

        $query->bindParam(':name', $data['name'], PDO::PARAM_STR, 25);
        $query->bindParam(':email', $data['email'], PDO::PARAM_STR, 100);

        if (!$query->execute()) {
            echo json_encode(["success" => 0]);
            exit();
        }

        echo json_encode(["success" => 1]);
        exit();
    }
}

if (isset($_GET["update"])) {
    $data = json_decode(file_get_contents("php://input"), true);

    $id = (isset($data['id'])) ? $data['id'] : $_GET["update"];
    $name = $data['name'];
    $email = $data['email'];

    $query = DbConnect::connection()->prepare(
        "UPDATE employees SET name = :name, email = :email WHERE id = :id"
    );

    $query->bindParam(':id', $data['id'], PDO::PARAM_INT, 1);
    $query->bindParam(':name', $data['name'], PDO::PARAM_STR, 25);
    $query->bindParam(':email', $data['email'], PDO::PARAM_STR, 100);

    if (!$query->execute()) {
        echo json_encode(["success" => 0]);
        exit();
    }

    echo json_encode(["success" => 1]);
    exit();
}

$query = DbConnect::connection()->prepare(
    "SELECT * FROM employees WHERE active = 1"
);
$query->execute();

$employees = $query->fetchAll(PDO::FETCH_ASSOC);

if (sizeof($employees) > 0) {
    echo json_encode($employees);
} else {
    echo json_encode([["success" => 0]]);
}
