<?php session_start(); ?>

<?php

    $titulo1 = 'Máquina...';
    $titulo2 = 'Máquina...'; 

    if (!isset($_SESSION['user'])) { 
        
        header("Location: login.php");
    
    } else {

        include('inc/header.php');
    
    }
       
?>