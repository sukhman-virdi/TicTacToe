<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

// GET requests — load tables
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';

    if ($action === 'all') {
        $result = mysqli_query($conn,
            "SELECT AchievementID, Title, Description 
             FROM Achievement 
             ORDER BY AchievementID"
        );
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        echo json_encode($rows);

    } else if ($action === 'awarded') {
        $result = mysqli_query($conn,
            "SELECT gu.Username, a.Title, aw.AwardDate
             FROM Awarded aw
             JOIN GameUser gu ON aw.UserID = gu.UserID
             JOIN Achievement a ON aw.AchievementID = a.AchievementID
             ORDER BY aw.AwardDate DESC"
        );
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        echo json_encode($rows);

    } else if ($action === 'edits') {
        $result = mysqli_query($conn,
            "SELECT a.Name AS AdminName, ac.Title AS Achievement, e.EditDate
             FROM Edits e
             JOIN Admin a ON e.AdminID = a.AdminID
             JOIN Achievement ac ON e.AchievementID = ac.AchievementID
             ORDER BY e.EditDate DESC"
        );
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        echo json_encode($rows);

    } else {
        echo json_encode(['error' => 'Invalid action']);
    }

// POST requests — add, update, delete
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action        = $_POST['action'] ?? '';
    $adminID       = $_SESSION['adminID'] ?? 1; // fallback to 1 for testing

    if ($action === 'update') {
        $id    = $_POST['AchievementID'];
        $title = $_POST['Title'];
        $desc  = $_POST['Description'];

        // Check achievement exists
        $check = mysqli_prepare($conn,
            "SELECT AchievementID FROM Achievement WHERE AchievementID = ?"
        );
        mysqli_stmt_bind_param($check, 'i', $id);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) === 0) {
            echo json_encode(['error' => "No achievement found with ID $id"]);
            exit;
        }

        // Update achievement
        $stmt = mysqli_prepare($conn,
            "UPDATE Achievement SET Title = ?, Description = ? WHERE AchievementID = ?"
        );
        mysqli_stmt_bind_param($stmt, 'ssi', $title, $desc, $id);

        if (mysqli_stmt_execute($stmt)) {
            // Log the edit
            $log = mysqli_prepare($conn,
                "INSERT INTO Edits (AdminID, AchievementID, EditDate) VALUES (?, ?, CURRENT_DATE)"
            );
            mysqli_stmt_bind_param($log, 'ii', $adminID, $id);
            mysqli_stmt_execute($log);

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Failed to update achievement']);
        }

    } else if ($action === 'add') {
        $id    = $_POST['AchievementID'];
        $title = $_POST['Title'];
        $desc  = $_POST['Description'];

        // Check if ID already exists
        $check = mysqli_prepare($conn,
            "SELECT AchievementID FROM Achievement WHERE AchievementID = ?"
        );
        mysqli_stmt_bind_param($check, 'i', $id);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            echo json_encode(['error' => 'Achievement ID already exists']);
            exit;
        }

        // Check if title already exists
        $checkTitle = mysqli_prepare($conn,
            "SELECT AchievementID FROM Achievement WHERE Title = ?"
        );
        mysqli_stmt_bind_param($checkTitle, 's', $title);
        mysqli_stmt_execute($checkTitle);
        mysqli_stmt_store_result($checkTitle);

        if (mysqli_stmt_num_rows($checkTitle) > 0) {
            echo json_encode(['error' => 'Achievement title already exists']);
            exit;
        }

        // Insert new achievement
        $stmt = mysqli_prepare($conn,
            "INSERT INTO Achievement (AchievementID, Title, Description) VALUES (?, ?, ?)"
        );
        mysqli_stmt_bind_param($stmt, 'iss', $id, $title, $desc);

        if (mysqli_stmt_execute($stmt)) {
            // Log the edit
            $log = mysqli_prepare($conn,
                "INSERT INTO Edits (AdminID, AchievementID, EditDate) VALUES (?, ?, CURRENT_DATE)"
            );
            mysqli_stmt_bind_param($log, 'ii', $adminID, $id);
            mysqli_stmt_execute($log);

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Failed to add achievement']);
        }

    } else if ($action === 'delete') {
        $id = $_POST['AchievementID'];

        // Check achievement exists
        $check = mysqli_prepare($conn,
            "SELECT AchievementID FROM Achievement WHERE AchievementID = ?"
        );
        mysqli_stmt_bind_param($check, 'i', $id);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) === 0) {
            echo json_encode(['error' => "No achievement found with ID $id"]);
            exit;
        }

        // Delete achievement (cascade handles related records)
        $stmt = mysqli_prepare($conn,
            "DELETE FROM Achievement WHERE AchievementID = ?"
        );
        mysqli_stmt_bind_param($stmt, 'i', $id);

        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Failed to delete achievement']);
        }

    } else {
        echo json_encode(['error' => 'Invalid action']);
    }
}

mysqli_close($conn);
?>