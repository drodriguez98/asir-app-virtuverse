<?php session_start(); ?>

<?php include ('inc/funciones.php'); ?>
<?php include ('inc/bbdd.php'); ?>

<?php

	$idMaquina = recoge('idMaquina');

        $titulo1 = 'Detalles de la máquina '. $idMaquina;
        $titulo2 = 'Detalles de la máquina '. $idMaquina;

        if (!isset($_SESSION['user'])) {

                header("Location: login.php");

        }

        include('inc/header.php');

?>

<section class="py-5">

        <div class="container px-4 px-lg-5 mt-5">

        <div class="row justify-content-center">

            <div class="col-md-8">

                <div class="card">

                    <div class="card-body">

                        <table class="table table-striped text-center">

                            <thead>

                                <tr>

                                    <th scope="col">Nombre</th>
                                    <th scope="col">Descripción</th>
				    <th scope="col">Estado</th>
				    <th scope="col">Rede</th>
				    <th scope="col">Dirección IP</th>

                                </tr>

                            </thead>

                            <tbody>

				<?php

				$datosMaquina = seleccionarMaquina2($idMaquina);

                                foreach ($datosMaquina as $datos) { ?>

					<tr>

        					<td><?php echo $datos['nomeMaquina']; ?> </td>
        					<td><?php echo $datos['descripcion']; ?> </td>
        					<td><?php if ($datos['estado'] == 0) { echo "Detenida"; } else { echo "Iniciada"; }  ?> </td>
        					<td><?php echo $datos['nomeNetwork']; ?> </td>
						<td><?php echo $datos['ip']; ?> </td>

					</tr>

                                <?php } ?>

                            </tbody>

                        </table>

                    </div>

		    <div class="card-footer text-center">

			<a class="btn btn-light" href="editarMaquina.php?idMaquina=<?=$idMaquina?>""><img src="img/edit.png" width="25" height="25"></a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<?php include("inc/footer.php"); ?>
