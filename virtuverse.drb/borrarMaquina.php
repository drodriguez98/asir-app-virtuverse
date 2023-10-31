<?php session_start(); ?>

<?php include ('inc/funciones.php'); ?>
<?php include ('inc/bbdd.php'); ?>

<?php

	$titulo1 = 'Borrar máquina';
	$titulo2 = 'Borrar máquina';

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

	$outputCMD = executarComandoSSH("sudo scripts/borrarContainer.sh $nome", true);

	if (trim($outputCMD) != "OK") {

        	echo "<h2 class='pagination justify-content-center'>No hemos podido borrar la máquina: <br> $outputCMD</p>";

	} else {

        	echo "<h2 class='pagination justify-content-center'>La máquina se ha borrado correctamente</p>";

                $maquinaDesactivadaBBDD = desactivarMaquinaBBDD($idMaquina);

                if ($maquinaDesactivadaBBDD == 0) {

                	echo "<h2 class='pagination justify-content-center'>No hemos podido borrar la máquina en la base de datos</p>";

		} else {

                        echo "<h2 class='pagination justify-content-center'>La máquina se ha borrado correctamente en la base de datos</p>";

                }

	}

?>
                   </div>

          </section>

<?php include ('inc/footer.php');
