<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$database = "astral_flights";


$connection = new mysqli($servername, $username, $password, $database);


if ($connection->connect_error) {
    die(json_encode(['error' => 'Database connection failed: ' . $connection->connect_error]));
}


$seat = $connection->real_escape_string($_GET['seat']);
$departure_date = $connection->real_escape_string($_GET['departure_date']);
$fleet = $connection->real_escape_string($_GET['fleet']);


$seat_check_query = "SELECT * FROM bookings WHERE seat_position = ? AND departure_date = ? AND fleet = ?";
$stmt = $connection->prepare($seat_check_query);
$stmt->bind_param("sss", $seat, $departure_date, $fleet);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['available' => false]);
} else {
    echo json_encode(['available' => true]);
}

$stmt->close();
$connection->close();
?>
