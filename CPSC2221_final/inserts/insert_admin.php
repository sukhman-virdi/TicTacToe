<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "tictactoe";

// Getting values
$AdminID  = $_POST['AdminID'];
$Name     = $_POST['Name'];
$Email    = $_POST['Email'];
$Password = $_POST['Password'];
$Level    = $_POST['Level'];

// Input validation
if (empty($AdminID) || empty($Name) || empty($Email) || empty($Password) || empty($Level)) {
	echo "Error: All fields are required. <br>";
	echo "<a class='btn btn-secondary mt-2' href='insert_admin.html'>Go Back</a>";
	exit;
}

if (!in_array($Level, ['1','2','3','4','5'])) {
	echo "Error: Level must be between 1 and 5. <br>";
	echo "<a class='btn btn-secondary mt-2' href='insert_admin.html'>Go Back</a>";
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
$query = "INSERT INTO Admin VALUES('$AdminID', '$Name', '$Email', '$Password', '$Level')";

// Execute the query
if ($conn->query($query) === TRUE) {
	echo "New admin inserted successfully! <br><br>";

	// Show inserted record as table
	$result = $conn->query("SELECT * FROM Admin WHERE AdminID = '$AdminID'");
	echo "<table class='table table-bordered table-striped mt-3'>";
	echo "<tr><th>AdminID</th><th>Name</th><th>Email</th><th>Password</th><th>Level</th></tr>";
	while ($row = $result->fetch_assoc()) {
		echo "<tr>
			<td>" . $row["AdminID"] . "</td>
			<td>" . $row["Name"] . "</td>
			<td>" . $row["Email"] . "</td>
			<td>" . $row["Password"] . "</td>
			<td>" . $row["Level"] . "</td>
		</tr>";
	}
	echo "</table>";
} else {
	echo "Error: " . $conn->error;
}

$conn->close();

echo "</div><br><a class='btn btn-secondary' href='insert_admin.html'>Insert Another Admin</a> | <a class='btn btn-secondary' href='../home.html'>Main Menu</a>";
?>
