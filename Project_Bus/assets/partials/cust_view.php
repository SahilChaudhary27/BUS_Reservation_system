<?php
session_start(); // Start the session

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "sbtbsphp";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["submit"])) {
        // Retrieve form data directly
        $customer_id = $_POST["cid"];
        $customer_name = $_POST["cname"];
        $customer_phone = $_POST["cphone"];
        $route_source = $_POST["sourceSearch"];
        $route_destination = $_POST["destinationSearch"];
        $route = $route_source . " &rarr; " . $route_destination;
        $booked_seat = $_POST["seatInput"];
        $amount = $_POST["bookAmount"];

        // Insert the booking directly without checking if it exists
        $sql = "INSERT INTO `bookings` (`customer_id`,`customer_route`, `booked_amount`, `booked_seat`, `booking_created`) 
                VALUES ('$customer_id', '$route', '$amount', '$booked_seat', current_timestamp());";

        // Execute the query
        $result = mysqli_query($conn, $sql);
        
        // Check if the query was successful
        if ($result) {
            // Show success alert
            echo '<div class="my-0 alert alert-success alert-dismissible fade show" role="alert">
                  <strong>Successful!</strong> Booking Added
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
            
            
        } else {
            // Show error alert
            echo '<div class="my-0 alert alert-danger alert-dismissible fade show" role="alert">
                  <strong>Error!</strong> Booking failed.
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings</title>
        <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/d8cfbe84b9.js" crossorigin="anonymous"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <!-- External CSS -->
    <?php
        require __DIR__ . '/../styles/admin.php';
        require __DIR__ . '/../styles/admin-options.php';
        require '_functions.php';

        $page="booking";
    ?>
        <script src="C:/xamp/htdocs/Project_Bus/assets/scripts/admin_booking.js"></script>
        <script src="C:/xamp/htdocs/Project_Bus/assets/scripts/booking.js"></script>
</head>
<body>

    <!-- All Modals Here -->
    <!-- Add Booking Modal -->
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Make Bookings</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addBookingForm" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
                            <!-- Passing Route JSON -->
                            <input type="hidden" id="routeJson" name="routeJson" value='<?php echo $routeJson; ?>'>
                            <!-- Passing Customer JSON -->
                            <input type="hidden" id="customerJson" name="customerJson" value='<?php echo $customerJson; ?>'>
                            <!-- Passing Seat JSON -->
                            <input type="hidden" id="seatJson" name="seatJson" value='<?php echo $seatJson; ?>'>

                            <div class="mb-3">
                                <label for="cid" name="cid" class="form-label">Customer ID</label>
                                <!-- Search Functionality -->
                                <div class="searchQuery">
                                    <input type="text" class="form-control searchInput" id="cid" name="cid" maxlength="9">
                                    <div class="sugg">
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="cname" class="form-label">Customer Name</label>
                                <input type="text" class="form-control" id="cname" name="cname" maxlength="50">
                            </div>
                            <div class="mb-3">
                                <label for="cphone" class="form-label">Contact Number</label>
                                <input type="tel" class="form-control" id="cphone" name="cphone" maxlength="10">
                            </div>
                            <div class="mb-3">
                                <label for="routeSearch" class="form-label">Route</label>
                                <!-- Search Functionality -->
                                <div class="searchQuery">
                                    <input type="text" class="form-control searchInput" id="routeSearch" name="routeSearch">
                                    <div class="sugg">
                                    </div>
                                </div>
                            </div>
                            <!-- Send the route_id -->
                            <input type="hidden" name="route_id" id="route_id">
                            <!-- Send the departure timing too -->
                            <input type="hidden" name="dep_timing" id="dep_timing">

                            <div class="mb-3">
                                <label for="sourceSearch" class="form-label">Source</label>
                                <!-- Search Functionality -->
                                <div class="searchQuery">
                                    <input type="text" class="form-control searchInput" id="sourceSearch" name="sourceSearch">
                                    <div class="sugg">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="destinationSearch" class="form-label">Destination</label>
                                <!-- Search Functionality -->
                                <div class="searchQuery">
                                    <input type="text" class="form-control searchInput" id="destinationSearch" name="destinationSearch">
                                    <div class="sugg">
                                    </div>
                                </div>
                            </div>
                            <!-- Seats Diagram -->
                            <div class="mb-3">
                                <table id="seatsDiagram">
                                <tr>
                                    <td id="seat-1" data-name="1">1</td>
                                    <td id="seat-2" data-name="2">2</td>
                                    <td id="seat-3" data-name="3">3</td>
                                    <td id="seat-4" data-name="4">4</td>
                                    <td id="seat-5" data-name="5">5</td>
                                    <td id="seat-6" data-name="6">6</td>
                                    <td id="seat-7" data-name="7">7</td>
                                    <td id="seat-8" data-name="8">8</td>
                                    <td id="seat-9" data-name="9">9</td>
                                    <td id="seat-10" data-name="10">10</td>
                                </tr>
                                <tr>
                                    <td id="seat-11" data-name="11">11</td>
                                    <td id="seat-12" data-name="12">12</td>
                                    <td id="seat-131" data-name="13">13</td>
                                    <td id="seat-14" data-name="14">14</td>
                                    <td id="seat-15" data-name="15">15</td>
                                    <td id="seat-16" data-name="16">16</td>
                                    <td id="seat-17" data-name="17">17</td>
                                    <td id="seat-18" data-name="18">18</td>
                                    <td id="seat-19" data-name="19">19</td>
                                    <td id="seat-20" data-name="20">20</td>
                                </tr>
                                <tr>
                                        <td class="space">&nbsp;</td>
                                        <td class="space">&nbsp;</td>
                                        <td class="space">&nbsp;</td>
                                        <td class="space">&nbsp;</td>
                                        <td class="space">&nbsp;</td>
                                        <td class="space">&nbsp;</td>
                                        <td class="space">&nbsp;</td>
                                        <td class="space">&nbsp;</td>
                                        <td class="space">&nbsp;</td>
                                        <td class="space">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td id="seat-21" data-name="21">21</td>
                                    <td id="seat-22" data-name="22">22</td>
                                    <td id="seat-23" data-name="23">23</td>
                                    <td id="seat-24" data-name="24">24</td>
                                    <td id="seat-25" data-name="25">25</td>
                                    <td id="seat-26" data-name="26">26</td>
                                    <td id="seat-27" data-name="27">27</td>
                                    <td class="space">&nbsp;</td>
                                    <td id="seat-28" data-name="28">28</td>
                                    <td id="seat-29" data-name="29">29</td>
                                </tr>
                                <tr>
                                    <td id="seat-30" data-name="30">30</td>
                                    <td id="seat-31" data-name="31">31</td>
                                    <td id="seat-32" data-name="32">32</td>
                                    <td id="seat-33" data-name="33">33</td>
                                    <td id="seat-34" data-name="34">34</td>
                                    <td id="seat-35" data-name="35">35</td>
                                    <td id="seat-36" data-name="36">36</td>
                                    <td class="space">&nbsp;</td>
                                    <td id="seat-37" data-name="37">37</td>
                                    <td id="seat-38" data-name="38">38</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="row g-3 align-items-center mb-3">
                                <div class="col-auto">
                                    <label for="seatInput" class="col-form-label">Seat Number</label>
                                </div>
                                <div class="col-auto">
                                    <input type="text" id="seatInput" class="form-control" name="seatInput">
                                </div>
                                <div class="col-auto">
                                    <span id="seatInfo" class="form-text">
                                    Select from the above figure, Maximum 1 seat.
                                    </span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="bookAmount" class="form-label">Total Amount</label>
                                <input type="text" class="form-control" id="bookAmount" name="bookAmount">
                            </div>
                            <button type="submit" class="btn btn-success" name="submit">Submit</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <!-- Add Anything -->
                    </div>
                    </div>
                </div>
</script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>$(document).ready(function () {
    // Variable to track the selected seat
    let selectedSeat = null;

    // When a seat is clicked
    $("#seatsDiagram td").click(function () {
        // Get the seat number from the data attribute
        const seatNumber = $(this).data("name");

        // Deselect the previously selected seat, if any
        if (selectedSeat) {
            $("#seat-" + selectedSeat).removeClass("selected-seat");
        }

        // Mark the new seat as selected
        selectedSeat = seatNumber;
        $(this).addClass("selected-seat");

        // Update the seatInput field with the selected seat number
        $("#seatInput").val(seatNumber);
    });

    // CSS to style the selected seat (you can add this in a <style> tag or your CSS file)
    $("<style>")
        .prop("type", "text/css")
        .html(`
            .selected-seat {
                background-color: #ffffff;
                color: #FFFF00;
            }
        `)
        .appendTo("head");
});

</script>


</body>
</html>