<?php
include 'db.php';

$id = $_GET['id'];

$query = "
    SELECT
        a.Title,
        a.Description,
        aw.AwardDate
    FROM Awarded aw
    JOIN Achievement a ON aw.AchievementID = a.AchievementID
    WHERE aw.UserID = ?
    ORDER BY aw.AwardDate DESC
";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$achievements = [];
while ($row = mysqli_fetch_assoc($result)) {
    $achievements[] = $row;
}

header('Content-Type: application/json');
echo json_encode($achievements);

mysqli_close($conn);
?>