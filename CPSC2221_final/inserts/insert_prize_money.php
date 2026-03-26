<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "tictactoe";

$Difficulty = $_POST['Difficulty'];
$PrizeMoney = $_POST['PrizeMoney'];

if (empty($Difficulty) || empty($PrizeMoney)) {
	echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>";
	echo "<div class='container mt-4'>";
	echo "<div class='alert alert-danger'>Error: All fields are required.</div>";
	echo "<a class='btn btn-secondary' href='insert_prize_money.html'>Go Back</a></div>";
	exit;
}

if (!is_numeric($Difficulty) || !is_numeric($PrizeMoney)) {
	echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>";
	echo "<div class='container mt-4'>";
	echo "<div class='alert alert-danger'>Error: Difficulty and Prize Money must be numbers.</div>";
	echo "<a class='btn btn-secondary' href='insert_prize_money.html'>Go Back</a></div>";
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

$query = "INSERT INTO PrizeMoney VALUES('$Difficulty', '$PrizeMoney')";

if ($conn->query($query) === TRUE) {
	echo "New prize money level inserted successfully! <br><br>";

	$result = $conn->query("SELECT * FROM PrizeMoney ORDER BY Difficulty");
	echo "<b>All Prize Money Levels:</b><br>";
	echo "<table class='table table-bordered table-striped mt-3'>";
	echo "<tr><th>Difficulty Level</th><th>Prize Money ($)</th></tr>";
	while ($row = $result->fetch_assoc()) {
		echo "<tr>
			<td>" . $row["Difficulty"] . "</td>
			<td>$" . $row["PrizeMoney"] . "</td>
		</tr>";
	}
	echo "</table>";
} else {
	echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
}

$conn->close();

echo "</div><br><a class='btn btn-secondary' href='insert_prize_money.html'>Insert Another</a> | <a class='btn btn-secondary' href='../home.html'>Main Menu</a>";
?>
