<?php
session_start();

$allowed = ['player', 'manager'];
if (!empty($_POST['role']) && in_array($_POST['role'], $allowed)) {
	$_SESSION['role'] = $_POST['role'];
}
?>
