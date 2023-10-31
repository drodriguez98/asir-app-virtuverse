<?php session_start(); ?>

<?php include ('inc/funciones.php'); ?>
<?php include ('inc/bbdd.php'); ?>

<?php

    $titulo1 = "Registro";
    $titulo2 = "Página de registro";

?>

<?php include("inc/header.php"); ?>

<div class="container px-4 px-lg-5 mt-5">

    <?php function mostrarFormulario ($nome, $nick, $password) { ?>

        <form method="get">

            <div class="mb-3 text-center" style="width: 300px; margin: 0 auto;">

            <label for="nome" class="form-label"><strong>Nome</strong></label>
            <input type="text" class="form-control text-center" id="nome" name="nome" value="<?=$nome ?>" style="width: 100%; padding: 10px; font-size: 16px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;"/>

            </div>

            <div class="mb-3 text-center" style="width: 300px; margin: 0 auto;">

                <label for="nick" class="form-label"><strong>Nick</strong></label>
                <input type="nick" class="form-control text-center" id="nick" name="nick" value="<?=$nick ?>" style="width: 100%; padding: 10px; font-size: 16px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">

            </div>

            <div class="mb-3 text-center" style="width: 300px; margin: 0 auto;">

            <label for="password" class="form-label"><strong>Contraseña</strong></label>
            <input type="password" class="form-control text-center" id="password" name="password" value="<?=$password ?>" style="width: 100%; padding: 10px; font-size: 16px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">

            </div>

            <ul class="mb-3 pagination justify-content-center">

                <button type="submit" class="btn btn-primary" name="btn-enviar">Enviar</button>

            </ul>

        </form>

    <?php } ?>


<!--	Recogida de datos	-->

<?php

    if (!isset($_REQUEST['btn-enviar'])) {

		$nome = "";
		$nick = "";
        $password = "";

        mostrarFormulario ($nome, $nick, $password);

	} else {

		$nome = recoge('nome');
		$nick = recoge('nick');
        	$password = recoge('password');

		$errores = "";

		if ($nome == "") {

			$errores.= "<li>Debes introducir un nome</li>";

		}

        if ($nick == "") {

			$errores.= "<li>Debes introducir un nick</li>";

		}

        if ($password == "") {

			$errores.= "<li>Debes introducir una contraseña</li>";

		}

        else {

			$repetido = seleccionar_nick ($nick);

			if ($repetido) {

				$errores.= "<li>Ya existe un usuario con ese email</li>";
			}
		}

		if ($errores != "") {

			echo "<div class='alert alert-danger' role='alert'>";

				echo "<ul>$errores</ul>";
				echo "</hr>";

			echo "</div>";

			mostrarFormulario ($nome, $nick, $password);

        }

		else {

			$registro = registro ($nome, $nick, $password);

			if ($registro == 0) {

				echo "<p>No se ha podido realizar la operación</p>";

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
