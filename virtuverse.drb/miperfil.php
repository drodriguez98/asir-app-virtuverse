<?php
	session_start();

	include("inc/bbdd.php");
	include("inc/funciones.php");

	$titulo1 = 'Mi perfil';
	$titulo2 = 'Datos de mi perfil';

	if (!isset($_SESSION['user'])) {
		header("Location: login.php");
	}

	$nick = $_SESSION['user'];
	$usuario = seleccionar_usuario($nick);

	include("inc/header.php");

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
                                    <th scope="col">Nick</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php foreach ($usuario as $usu) { ?>

                                    <tr>

                                        <td><?= $usu['nome'] ?></td>
                                        <td><?= $usu['nick'] ?></td>

                                    </tr>

                                <?php } ?>

                            </tbody>

                        </table>

                    </div>

                    <div class="card-footer text-center">

                        <a href="editarperfil.php?nick=<?=$nick?>" class="btn btn-light">Editar datos</a>
                        <a href="cambiarpassword.php?nick=<?=$nick?>" class="btn btn-light">Cambiar contraseña</a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<?php include("inc/footer.php"); ?>
