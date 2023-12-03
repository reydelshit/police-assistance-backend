<?php


include 'DBconnect.php';
$objDB = new DbConnect();
$conn = $objDB->connect();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":


        if (isset($_GET['police_id'])) {
            $police_id_spe = $_GET['police_id'];
            $sql = "SELECT * FROM police WHERE police_id = :police_id";
        }


        if (!isset($_GET['police_id'])) {
            $sql = " SELECT * FROM police ORDER BY police_id DESC";
        }


        if (isset($sql)) {
            $stmt = $conn->prepare($sql);

            if (isset($police_id_spe)) {
                $stmt->bindParam(':police_id', $police_id_spe);
            }

            $stmt->execute();
            $police = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($police);
        }

        break;



    case "POST":
        $police = json_decode(file_get_contents('php://input'));
        $sql = "INSERT INTO police (police_name, image, assigned_location, phone_number) VALUES (:police_name, :image, :assigned_location, :phone_number)";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':police_name', $police->police_name);
        $stmt->bindParam(':image', $police->image);
        $stmt->bindParam(':assigned_location', $police->assigned_location);
        $stmt->bindParam(':phone_number', $police->phone_number);




        if ($stmt->execute()) {

            $response = [
                "status" => "success",
                "message" => "police successfully"
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "police failed"
            ];
        }

        echo json_encode($response);
        break;

    case "PUT":
        $police = json_decode(file_get_contents('php://input'));
        $sql = "UPDATE police SET police_name= :police_name, description = :description, expiration_date = :expiration_date, racks = :racks
                    WHERE police_id = :police_id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':police_id', $police->police_id);
        $stmt->bindParam(':police_name', $police->police_name);
        $stmt->bindParam(':description', $police->description);
        $stmt->bindParam(':expiration_date', $police->expiration_date);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':racks', $police->racks);


        if ($stmt->execute()) {

            $response = [
                "status" => "success",
                "message" => "police updated successfully"
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "police update failed"
            ];
        }

        echo json_encode($response);
        break;

    case "DELETE":
        $police = json_decode(file_get_contents('php://input'));
        $sql = "DELETE FROM police WHERE police_id = :police_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':police_id', $police->police_id);

        if ($stmt->execute()) {
            $response = [
                "status" => "success",
                "message" => "police deleted successfully"
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "police delete failed"
            ];
        }

        echo json_encode($response);
        break;
}
