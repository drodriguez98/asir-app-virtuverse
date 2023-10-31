<?php session_start(); ?>

<?php include ('inc/funciones.php'); ?>
<?php include ('inc/bbdd.php'); ?>

<?php

    $titulo1 = 'Editar perfil';
    $titulo2 = 'Editar perfil';

    if (!isset($_SESSION['user'])) {

        header("Location: login.php");

    }

    $nick = $_SESSION['user'];

?>

<?php include('inc/header.php'); ?>

<section class="py-5">

	<div class="container px-4 px-lg-5 mt-5">

		<?php

			function mostrarFormulario ($nick, $nome) { ?>

				<form method="get" class="form-center">

                    <br>

                    <div class="mb-3 text-center" style="width: 300px; margin: 0 auto;">

                        <label for="nick" class="form-label"><strong>Nick</label></strong>
                        <input type="text" class="form-control text-center" id="nick" name="nick" value="<?=$nick ?>" readonly style="width: 100%; padding: 10px; font-size: 16px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;"/>

                    </div>

                    <br>

			<div class="mb-3 text-center" style="width: 300px; margin: 0 auto;">

				<label for="nome" class="form-label"><strong>Nome</strong></label>
				<input type="text" class="form-control text-center" id="nome" name="nome" value="<?=$nome ?>" style="width: 100%; padding: 10px; font-size: 16px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;"/>

			</div>

                    <br>

                    <ul class="pagination justify-content-center">

			<button type="submit" class="btn btn-primary" name="btn-enviar">Enviar</button>

                    </ul>

				</form>

<?php } ?>

<?php

    if (!isset($_REQUEST['btn-enviar'])) {

		$usuario = seleccionar_usuario2($nick);

        $nome = $usuario ['nome'];

        mostrarFormulario ($nick, $nome);

    } else {

        $usuario = seleccionar_usuario2($nick);
        $idUsuario = $usuario['idUsuario'];

        $nick = recoge('nick');
        $nome = recoge('nome');

        $errores = "";

        if ($nick == "") {

            $errores.= "<li>Debes introducir un novo nick</li>";

        }

        if ($nome == "") {

            $errores.= "<li>Debes introducir un novo nome</li>";

        }

        if ($errores != "") {

			echo "<div class='alert alert-danger' role= 'alert'>";

				echo "<ul>$errores</ul>";
				echo "</hr>";

			echo "</div>";

            mostrarFormulario ($nick, $nome);

        }

        else {

            $editado = editar_usuario ($idUsuario, $nick, $nome);

			if ($editado) {

?>

				<div class="alert alert-success text-center" role= "alert">

					<h2>Datos actualizados correctamente</h2>

				</div>

<?php

			} else {

?>

				<div class="alert alert-danger text-center" role= "alert">

					<p>No hemos podido actualizar los datos</p>

				</div>
<?php

                mostrarFormulario ($nick, $nome);

			}

		}

	}

?>

</div>

	</div>

</section>

</body>

</html>

<?php include("inc/footer.php"); ?>
