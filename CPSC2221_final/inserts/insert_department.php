<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "tictactoe";

// Getting values
$Level      = $_POST['Level'];
$Department = $_POST['Department'];

// Input validation
if (empty($Level) || empty($Department)) {
	echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>";
	echo "<div class='container mt-4'>";
	echo "<div class='alert alert-danger'>Error: All fields are required.</div>";
	echo "<a class='btn btn-secondary' href='insert_department.html'>Go Back</a></div>";
	exit;
}

if (!is_numeric($Level)) {
	echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>";
	echo "<div class='container mt-4'>";
	echo "<div class='alert alert-danger'>Error: Level must be a number.</div>";
	echo "<a class='btn btn-secondary' href='insert_department.html'>Go Back</a></div>";
	exit;
}

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
} else {
	echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>";
	echo "<div class='container mt-4'>";
	echo "Connection Successful! <br>";
}

// Construct the query
$query = "INSERT INTO Department VALUES('$Level', '$Department')";

// Execute the query
if ($conn->query($query) === TRUE) {
	echo "New department inserted successfully! <br><br>";

	// Show inserted record as table
	$result = $conn->query("SELECT * FROM Department WHERE Level = '$Level'");
	echo "<table class='table table-bordered table-striped mt-3'>";
	echo "<tr><th>Level</th><th>Department</th></tr>";
	while ($row = $result->fetch_assoc()) {
		echo "<tr>
			<td>" . $row["Level"] . "</td>
			<td>" . $row["Department"] . "</td>
		</tr>";
	}
	echo "</table>";
} else {
	echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
}

$conn->close();

echo "</div><br><a class='btn btn-secondary' href='insert_department.html'>Insert Another Department</a> | <a class='btn btn-secondary' href='../home.html'>Main Menu</a>";
?>
