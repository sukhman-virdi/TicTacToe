<?php
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pass = $_POST['pass'] ?? '';

    $conn = @mysqli_connect('localhost', 'root', $pass, 'tictactoe');

    if (!$conn) {
        $msg = '❌ Wrong password.';
    } else {
        // Run SQL file
        $sql = file_get_contents(__DIR__ . '/create_tables.sql');
        mysqli_multi_query($conn, $sql);
        while (mysqli_more_results($conn) && mysqli_next_result($conn));

        // Add password to db.php if not already there
        $dbPhp = file_get_contents(__DIR__ . '/db.php');
        if (!str_contains($dbPhp, "'$pass'")) {
            $dbPhp = str_replace(
                '$passwords = [',
                "\$passwords = ['$pass', ",
                $dbPhp
            );
            file_put_contents(__DIR__ . '/db.php', $dbPhp);
        }

        $msg = '✅ Done! <a href="home.html">Go to app →</a>';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Setup</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 400px; margin: 80px auto; }
        input { padding: 8px; width: 100%; margin: 10px 0; box-sizing: border-box; }
        button { padding: 10px 20px; background: #4a90e2; color: white; border: none; cursor: pointer; border-radius: 4px; }
    </style>
</head>
<body>
    <h2>🎮 Tictactoe Setup</h2>
    <form method="POST">
        <label>MySQL root password (leave blank if none):</label>
        <input type="password" name="pass">
        <button type="submit">Run Setup</button>
    </form>
    <?php if ($msg) echo "<p>$msg</p>"; ?>
</body>
</html>