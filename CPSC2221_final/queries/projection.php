<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "tictactoe";
 
// Getting selected fields
$fields = isset($_POST['fields']) ? $_POST['fields'] : [];
 
// Input validation
if (empty($fields)) {
	echo "Error: Please select at least one field. <br>";
	echo "<a class='btn btn-secondary mt-2' href='projection.html'>Go Back</a>";
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
 
// Build the field list from selected checkboxes
$fieldList = implode(", ", $fields);
 
// Construct the query
$query = "SELECT $fieldList FROM GameUser";
 
echo "<h2>Projection Query Result</h2>";
echo "<b>Query: </b> SELECT $fieldList FROM GameUser <br><br>";
 
$result = $conn->query($query);
 
if ($result->num_rows > 0) {
	echo "<table class='table table-bordered table-striped mt-3'>";
	// Print headers
	echo "<tr>";
	foreach ($fields as $field) {
		echo "<th>" . $field . "</th>";
	}
	echo "</tr>";
	// Print rows
	while ($row = $result->fetch_assoc()) {
		echo "<tr>";
		foreach ($fields as $field) {
			echo "<td>" . $row[$field] . "</td>";
		}
		echo "</tr>";
	}
	echo "</table>";
} else {
	echo "0 results";
}
 
$conn->close();
 
echo "</div><br><a class='btn btn-secondary' href='projection.html'>Run Another Projection</a> | <a href='../index.php'>Main Menu</a>";
?>
