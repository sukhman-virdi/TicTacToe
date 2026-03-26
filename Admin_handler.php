<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

// GET requests — load tables
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';

    if ($action === 'all') {
        $result = mysqli_query($conn,
            "SELECT AdminID, Name, Email, Level 
             FROM Admin 
             ORDER BY AdminID"
        );
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        echo json_encode($rows);

    } else if ($action === 'projection') {
        $fields = $_GET['fields'] ?? '';

        $allowed = ['AdminID', 'Name', 'Email', 'Level'];

        $selected = explode(',', $fields);

        $validFields = array_intersect($selected, $allowed);

        if (count($validFields) === 0) {
            echo json_encode(['error' => 'No valid fields selected']);
            exit;
        }
        $fieldList = implode(',', $validFields);

        $result = mysqli_query($conn, "SELECT $fieldList FROM Admin ORDER BY AdminID");
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
        $id    = $_POST['AdminID'];
        $level = $_POST['Level'];

        // Check admin exists
        $check = mysqli_prepare($conn,
            "SELECT AdminID FROM Admin WHERE AdminID = ?"
        );
        mysqli_stmt_bind_param($check, 'i', $id);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) === 0) {
            echo json_encode(['error' => "No Admin found with ID $id"]);
            exit;
        }

        // Update admin
        $stmt = mysqli_prepare($conn,
            "UPDATE Admin SET Level = ? WHERE AdminID = ?"
        );
        mysqli_stmt_bind_param($stmt, 'ii', $level, $id);

        if (mysqli_stmt_execute($stmt)) {

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Failed to update admin']);
        }

    }else if ($action === 'delete') {
        $id = $_POST['AdminID'];

        // Check admin exists
        $check = mysqli_prepare($conn,
            "SELECT AdminID FROM Admin WHERE AdminID = ?"
        );
        mysqli_stmt_bind_param($check, 'i', $id);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) === 0) {
            echo json_encode(['error' => "No admin found with ID $id"]);
            exit;
        }

        // Delete admin (cascade handles related records)
        $stmt = mysqli_prepare($conn,
            "DELETE FROM Admin WHERE AdminID = ?"
        );
        mysqli_stmt_bind_param($stmt, 'i', $id);

        if (mysqli_stmt_execute($stmt)) {
            if($id==$_SESSION['adminID']){
                session_destroy();
                    echo json_encode([
                        'success' => true,
                        'selfDeleted' => true
                    ]);
            }else{
                echo json_encode(['success' => true, 'selfDeleted' => false]);
            }
        } else {
            echo json_encode(['error' => 'Failed to delete admin']);
        }

    } else {
        echo json_encode(['error' => 'Invalid action']);
    }
}

mysqli_close($conn);
?>