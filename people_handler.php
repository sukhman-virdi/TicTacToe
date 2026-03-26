<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

// GET requests — load tables
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';

    if ($action === 'all') {
        $result = mysqli_query($conn,
            "SELECT GameUser.UserID, GameUser.Username, GameUser.Email, Oversees.AdminID, Oversees.Admin_Comments
             FROM GameUser
             LEFT JOIN Oversees ON Oversees.UserID = GameUser.UserID
             ORDER BY GameUser.UserID"
        );
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        echo json_encode($rows);
    }
    if ($action === 'all_reviewed') {
        $result = mysqli_query($conn, "SELECT u.UserID, u.Username, u.Email, o.AdminID, o.Admin_Comments
        FROM GameUser u
        LEFT JOIN Oversees o ON o.UserID = u.UserID
        WHERE NOT EXISTS (
        SELECT *
        FROM Admin a
        WHERE NOT EXISTS (
            SELECT o2.AdminID
            FROM Oversees o2
            WHERE o2.UserID = u.UserID
                AND o2.AdminID = a.AdminID
                AND o2.Admin_Comments IS NOT NULL
                AND o2.Admin_Comments <> ''
        )
        )
        ORDER BY u.UserID;");
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        echo json_encode($rows);
    }else if($action === 'unjoin'){
        $result = mysqli_query($conn,
            "SELECT GameUser.UserID, GameUser.Username, GameUser.Email
             FROM GameUser
             ORDER BY GameUser.UserID"
        );
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        echo json_encode($rows);
    }

// POST requests — add, update, delete
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action        = $_POST['action'] ?? '';

    if ($action === 'update') {
        $adminID   = $_POST['AdminID'];
        $userID = $_POST['UserID'];
        $comm  = $_POST['Comments'];
        if(trim($comm) === ''){
            $check = mysqli_prepare($conn,
            "SELECT Admin_Comments
                FROM Oversees
                WHERE AdminID = ? and UserID = ?"
            );
            mysqli_stmt_bind_param($check, 'ii', $adminID, $userID);
            mysqli_stmt_execute($check);
            mysqli_stmt_store_result($check);

            if (mysqli_stmt_num_rows($check) != 0) {
            $stmt = mysqli_prepare($conn,
                "DELETE FROM Oversees WHERE UserID = ? and AdminID = ?"
            );
            mysqli_stmt_bind_param($stmt, 'ii', $userID, $adminID);

            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['error' => 'Failed to delete user']);
            }
                exit;
            }
        }
        //Check achievement exists
        $check = mysqli_prepare($conn,
            "SELECT Admin_Comments
                FROM Oversees
                WHERE AdminID = ? and UserID = ?"
        );
        mysqli_stmt_bind_param($check, 'ii', $adminID, $userID);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) === 0) {
            $stmt = mysqli_prepare($conn,
            "INSERT INTO Oversees (AdminID, UserID, Admin_Comments) VALUES (?, ?, ?)"
            );
            mysqli_stmt_bind_param($stmt, 'iis',  $adminID, $userID, $comm);

            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['error' => 'Failed to update achievement']);
            }
            exit;
        }

        // Update achievement
        $stmt = mysqli_prepare($conn,
            "UPDATE Oversees SET Admin_Comments = ? WHERE AdminID = ? and UserID = ?"
        );
        mysqli_stmt_bind_param($stmt, 'sii',$comm,  $adminID, $userID);

        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Failed to update achievement']);
        }

    } else if ($action === 'delete') {
        $deleteid = $_POST['UserID'];

        // Check achievement exists
        $check = mysqli_prepare($conn,
            "SELECT UserID FROM GameUser WHERE UserID = ?"
        );
        mysqli_stmt_bind_param($check, 'i', $deleteid);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) === 0) {
            echo json_encode(['error' => "No user found with ID $deleteid"]);
            exit;
        }

        // Delete achievement (cascade handles related records)
        $stmt = mysqli_prepare($conn,
            "DELETE FROM GameUser WHERE UserID = ?"
        );
        mysqli_stmt_bind_param($stmt, 'i', $deleteid);

        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Failed to delete user']);
        }

    } else {
        echo json_encode(['error' => 'Invalid action']);
    }
}

mysqli_close($conn);
?>