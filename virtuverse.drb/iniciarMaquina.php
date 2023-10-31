<?php session_start(); ?>

<?php include ('inc/funciones.php'); ?>
<?php include ('inc/bbdd.php'); ?>

<?php

        $titulo1 = 'Iniciar máquina';
        $titulo2 = 'Iniciar máquina';

        $idMaquina = recoge('idMaquina');

        if (!isset($_SESSION['user'])) {

                header("Location: login.php");

        }

        include('inc/header.php');

?>

<section class="py-5">

        <div class="container px-4 px-lg-5 mt-5">

<?php

        $datosMaquina = seleccionarMaquina($idMaquina);
        $nome = $datosMaquina['nome'];

        $outputCMD = executarComandoSSH("sudo scripts/iniciarContainer.sh $nome", true);

        if (trim($outputCMD) != "OK") {

        	echo "<h2 class='pagination justify-content-center'>No hemos podido iniciar la máquina: <br> $outputCMD</p>";

	} else {

        	echo "<h2 class='pagination justify-content-center'>La máquina se ha iniciado correctamente</p>";

                $estadoCambiadoBBDD = cambiarEstado0BBDD($idMaquina);

                if ($estadoCambiadoBBDD == 0) {

			echo "<h2 class='pagination justify-content-center'>No hemos podido actualizar el estado en la base de datos</p>";

                } else {

                        echo "<h2 class='pagination justify-content-center'>El estado se ha actualizado en la base de datos</p>";

                }

	}

	header("Location: index.php");

?>
