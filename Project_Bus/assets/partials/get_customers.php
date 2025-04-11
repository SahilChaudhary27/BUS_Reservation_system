// get_customers.php

// Database connection
<?php
require 'get_customers.php';
$host = "localhost";
$username = "root";
$password = "";
$database = "sbtbsphp";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search query from the AJAX request
$search_query = $_GET['query'];

// Query the database for customers matching the search query
$sql = "SELECT customer_id, customer_name, customer_phone FROM customers WHERE customer_id LIKE '%$search_query%'";

$result = mysqli_query($conn, $sql);

// Create an array to store the customer data
$customers = array();

// Loop through the result and add each customer to the array
while ($row = mysqli_fetch_assoc($result)) {
    $customers[] = $row;
}

// Close the database connection
$conn->close();

// Return the customer data in JSON format
echo json_encode($customers);
?>