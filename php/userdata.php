<?php
// Database configuration
$servername = "localhost"; // Usually localhost
$username = "root";        // MySQL username
$password = "sau@1015";            // MySQL password (if any)
$dbname = "parking_lot_info"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $owner = $conn->real_escape_string($_POST['owner']);
    $car_details = $conn->real_escape_string($_POST['car-details']);
    $license_plate = $conn->real_escape_string($_POST['license-plate']);

    // Insert data into database
    $sql = "INSERT INTO parked_cars (owner, car_details, license_plate) VALUES ('$owner', '$car_details', '$license_plate')";

    if ($conn->query($sql) === TRUE) {
        echo "New car added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch all parked cars from database
$sql = "SELECT * FROM parked_cars";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Lot Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <div class="header">
            <h1>Parking Lot Management System</h1>
        </div>

        <!-- Parking Form -->
        <div class="form-container">
            <h3>Parking Form</h3>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <label for="owner">Car Owner/Driver:</label>
                <input type="text" id="owner" name="owner" placeholder="Enter owner/driver name" required>

                <label for="car-details">Car Brand/Model/Color:</label>
                <input type="text" id="car-details" name="car-details" placeholder="Enter car brand, model, color" required>

                <label for="license-plate">License Plate:</label>
                <input type="text" id="license-plate" name="license-plate" placeholder="Enter license plate number" required>

                <button type="submit">Save</button>
            </form>
        </div>

        <!-- List of Parked Cars -->
        <div class="table-container">
            <h3>List of Parked Cars</h3>
            <table>
                <thead>
                    <tr>
                        <th>Owner</th>
                        <th>Car</th>
                        <th>License Plate</th>
                        <th>Entry Date</th>
                        <th>Exit Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['owner']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['car_details']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['license_plate']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['entry_date']) . "</td>";
                            echo "<td>" . ($row['exit_date'] ? htmlspecialchars($row['exit_date']) : '--') . "</td>";
                            echo "<td><button onclick=\"removeCar('" . $row['id'] . "')\">Remove</button></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No cars parked</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function removeCar(id) {
            if (confirm("Are you sure you want to remove this car?")) {
                window.location.href = "remove.php?id=" + id;
            }
        }
    </script>

</body>
</html>

<?php
$conn->close();
?>
