<?php session_start(); ?>

<?php include ('inc/funciones.php'); ?>
<?php include ('inc/bbdd.php'); ?>

<?php

    $titulo1 = "Login";
    $titulo2 = "Iniciar sesión";

?>

<?php include("inc/header.php"); ?>

<section class="py-5">

	<div class="container px-4 px-lg-5 mt-5">

		<?php

			function mostrarFormulario ($nick, $password) { ?>

				<form method="get" class="form-center">

				<div class="mb-3 text-center" style="width: 300px; margin: 0 auto;">

					<label for="nick" class="form-label"><strong>Introduce tu nick</strong></label>
					<input type="text" class="form-control text-center" id="nick" name="nick" value="<?=$nick ?>" style="width: 100%; padding: 10px; font-size: 16px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;"/>

					</div>

					<div class="mb-3 text-center" style="width: 300px; margin: 0 auto;">

					<label for="password" class="form-label"><strong>Introduce tu contraseña</strong></label>
					<input type="password" class="form-control text-center" id="password" name="password" value="<?=$password ?>" style="width: 100%; padding: 10px; font-size: 16px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;"/>

					<br>

					<button type="submit" class="btn btn-primary" name="btn-enviar">Enviar</button>

					<a href="registro.php" class="btn btn-success">Quiero crear una cuenta</a>

				</form>


<?php

	}

	if (!isset($_REQUEST['btn-enviar'])) {

		$nick = "";
		$password = "";

		mostrarFormulario ($nick, $password);

	} else {

		$nick = recoge('nick');
		$password = recoge('password');

		$errores = "";

		if ($nick == "") {

			$errores.= "<li>Debes introducir un nick</li>";

		}

		if ($password == "") {

			$errores.= "<li>Debes introducir una contraseña</li>";

		}

		if ($errores != "") {

			echo "<div class='alert alert-danger' role='alert'>";

				echo "<ul>$errores</ul>";
				echo "</hr>";

			echo "</div>";

			mostrarFormulario ($nick, $password);

		}

		else {

			$login = login ($nick, $password);

			if ($login == 0) {

				echo "<h2 class='pagination justify-content-center'>Nick y/o password incorrectos</p>";

			} else {

				$_SESSION['user'] = $nick;

				header("Location: index.php");

			}

		}

	}

?>

	            </div>

            </section>

        </body>

</html>

<?php include("inc/footer.php"); ?>
