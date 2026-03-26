<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "tictactoe";

$AdminID        = $_POST['AdminID'];
$UserID         = $_POST['UserID'];
$Admin_Comments = $_POST['Admin_Comments'];

if (empty($AdminID) || empty($UserID) || empty($Admin_Comments)) {
	echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>";
	echo "<div class='container mt-4'>";
	echo "<div class='alert alert-danger'>Error: All fields are required.</div>";
	echo "<a class='btn btn-secondary' href='insert_oversees.html'>Go Back</a></div>";
	exit;
}

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
} else {
	echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>";
	echo "<div class='container mt-4'>";
	echo "Connection Successful! <br>";
}

$query = "INSERT INTO Oversees VALUES('$AdminID', '$UserID', '$Admin_Comments')";

if ($conn->query($query) === TRUE) {
	echo "Oversees record inserted successfully! <br><br>";

	$result = $conn->query("SELECT a.Name AS AdminName, gu.Username, o.Admin_Comments
	                         FROM Oversees o
	                         JOIN Admin a ON o.AdminID = a.AdminID
	                         JOIN GameUser gu ON o.UserID = gu.UserID
	                         WHERE o.AdminID = '$AdminID' AND o.UserID = '$UserID'");
	echo "<table class='table table-bordered table-striped mt-3'>";
	echo "<tr><th>Admin Name</th><th>Username</th><th>Admin Comments</th></tr>";
	while ($row = $result->fetch_assoc()) {
		echo "<tr>
			<td>" . $row["AdminName"] . "</td>
			<td>" . $row["Username"] . "</td>
			<td>" . $row["Admin_Comments"] . "</td>
		</tr>";
	}
	echo "</table>";
} else {
	echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
}

$conn->close();

echo "</div><br><a class='btn btn-secondary' href='insert_oversees.html'>Insert Another</a> | <a class='btn btn-secondary' href='../home.html'>Main Menu</a>";
?>
