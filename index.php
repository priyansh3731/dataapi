<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

include 'DbConnect.php';
$objDb = new DbConnect;
$conn = $objDb->connect();

$method = $_SERVER['REQUEST_METHOD'];
switch($method) {
    case "GET":
        $sql = "SELECT * FROM akshitdata";
        $path = explode('/', $_SERVER['REQUEST_URI']);
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
        break;
    case "POST":
        $user=json_decode(file_get_contents('php://input'));
        $sql = "INSERT INTO akshitdata(id,awb,firmname,quantity,rtype,sku,category,suborder_id) VALUES(null,:awb,:firmname,:quantity,:rtype,:sku,:category,:suborder_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':awb', $user->awb);
        $stmt->bindParam(':category', $user->category);
        $stmt->bindParam(':firmname', $user->firmname);
        $stmt->bindParam(':quantity', $user->quantity);
        $stmt->bindParam(':rtype', $user->rtype);
        $stmt->bindParam(':sku', $user->sku);
        $stmt->bindParam(':suborder_id', $user->suborder_id);


        if($stmt->execute()) {
            $response = ['status' => 1, 'message' => 'Record created successfully.'];
        } else {
            $response = ['status' => 0, 'message' => 'Failed to create record.'];
        }
        echo json_encode($response);
        break;
    }