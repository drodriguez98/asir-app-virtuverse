<?php session_start(); ?>

<?php include ('inc/bbdd.php'); ?>

<?php

	$titulo1 = 'Logout';
	$titulo2 = 'Hasta la próxima';

	unset($_SESSION['user']);
	header("Location: index.php");

?>

<?php include ('inc/header.php'); ?>

<?php echo "<br>"; ?>
