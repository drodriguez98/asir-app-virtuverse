<?php session_start(); ?>

<?php include ('inc/funciones.php'); ?>
<?php include ('inc/bbdd.php'); ?>

<?php

$titulo1 = "Cambiar contraseña";
$titulo2 = "Cambiar contraseña";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}

$nick = $_SESSION['user'];

?>

<?php include("inc/header.php"); ?>

<section class="py-5">

	<div class="container px-4 px-lg-5 mt-5">

        <?php function mostrarFormulario($nick, $passwordOld, $passwordNew) { ?>

            <form method="post">

                <br>

                <div class="mb-3 text-center" style="width: 300px; margin: 0 auto;">
                    <label for="nick" class="form-label">Nick</label>
                    <input type="text" class="form-control text-center" id="nick" name="nick" value="<?= htmlspecialchars($nick) ?>" readonly style="width: 100%; padding: 10px; font-size: 16px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;"/>
                </div>

                <br>

                <div class="mb-3 text-center" style="width: 300px; margin: 0 auto;">
                    <label for="passwordOld" class="form-label">Contraseña actual</label>
                    <input type="password" class="form-control text-center" id="passwordOld" name="passwordOld" value="<?= htmlspecialchars($passwordOld) ?>" style="width: 100%; padding: 10px; font-size: 16px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;"/>
                </div>

                <br>

                <div class="mb-3 text-center" style="width: 300px; margin: 0 auto;">
                    <label for="passwordNew" class="form-label">Contraseña nueva</label>
                    <input type="password" class="form-control text-center" id="passwordNew" name="passwordNew" value="<?= htmlspecialchars($passwordNew) ?>" style="width: 100%; padding: 10px; font-size: 16px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;"/>
                </div>

                <br><br>

                <div class="mb-3 text-center">
                    <button type="submit" class="btn btn-outline-dark" name="btn-enviar">Actualizar datos</button>
		</div>

	</form>

	<?php }

	if (!isset($_REQUEST['btn-enviar'])) {

        	$passwordOld = "";
        	$passwordNew = "";

        	mostrarFormulario ($nick, $passwordOld, $passwordNew);

	} else {

        	$errores = "";

        	$usuario = seleccionar_usuario2($nick);
        	$passwordOldBBDD = $usuario['password'];

                $passwordOld = recoge('passwordOld');
        	$passwordNew = recoge('passwordNew');

		$verificada = password_verify($passwordOld, $passwordOldBBDD);

        	if (!$verificada) {

            		$errores.= "<li>La contraseña actual no es correcta</li>";

        	}

        	if ($passwordOld == "") {

            		$errores.= "<li>Debes introducir tu contraseña actual</li>";

        	}

        	if ($passwordNew == "") {

            		$errores.= "<li>Debes introducir una contraseña nueva</li>";

        	}

		if ($errores != "") {

                        echo "<div class='alert alert-danger' role= 'alert'>";
                	echo "<ul>$errores</ul>";
                        echo "</hr>";
	                echo "</div>";

		mostrarFormulario ($nick, $passwordOld, $passwordNew);

		}

        	else {

            		$cambiada = cambiarPassword ($nick, $passwordNew);

                        if ($cambiada) {

?>

                                <div class="alert alert-success" role= "alert">

                    			<h2 class="pagination justify-content-center">Datos actualizados correctamente</h2>

                                </div>

<?php

                        } else {

?>

                                <div class="alert alert-danger" role= "alert">

                    			<h2 class="pagination justify-content-center">No hemos podido actualizar los datos</h2>

                                </div>
<?php

                                mostrarFormulario ($nick, $passwordOld, $passwordNew);


                        }

                }

        }

?>

</div>

</section>

<?php include("inc/footer.php"); ?>


















