<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "astral_flights";


$connection = new mysqli($servername, $username, $password, $database);


if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $destination = $connection->real_escape_string($_POST['destination']);
    $fleet = $connection->real_escape_string($_POST['fleet']);
    $selected_seat = $connection->real_escape_string($_POST['selected_seat']);
    $departure_date = $connection->real_escape_string($_POST['departure_date']);
    $return_date = $connection->real_escape_string($_POST['return_date']);
    $flight_duration = $connection->real_escape_string($_POST['flight_duration']);
    $meal_preference = $connection->real_escape_string($_POST['meal_preference']);
    $accessibility_needs = $connection->real_escape_string($_POST['accessibility_needs']);
    $baggage_allowance = $connection->real_escape_string($_POST['baggage_allowance']);
    $entertainment_choices = $connection->real_escape_string($_POST['entertainment_choices']);
    $additional_services = $connection->real_escape_string($_POST['additional_services']);
    $insurance_options = $connection->real_escape_string($_POST['insurance_options']);
    $additional_comments = $connection->real_escape_string($_POST['additional_comments']);

    
    $seat_check_query = "SELECT * FROM bookings WHERE seat_position = ? AND departure_date = ? AND fleet = ?";
    $stmt = $connection->prepare($seat_check_query);
    $stmt->bind_param("sss", $selected_seat, $departure_date, $fleet);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<div class='alert alert-danger' role='alert'>Sorry, the seat $selected_seat is already booked for this flight!</div>";
    } else {
        
        $stmt = $connection->prepare("INSERT INTO bookings 
            (destination, fleet, seat_class, seat_position, departure_date, return_date, flight_duration, meal_preference, accessibility_needs, baggage_allowance, entertainment_choices, additional_services, insurance_options, additional_comments) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("ssssssssssssss", $destination, $fleet, $seat_class, $selected_seat, $departure_date, $return_date, $flight_duration, $meal_preference, $accessibility_needs, $baggage_allowance, $entertainment_choices, $additional_services, $insurance_options, $additional_comments);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success' role='alert'>Booking successfully submitted!</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }
    
    $connection->close();
}
?>
