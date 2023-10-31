<?php session_start(); ?>

<?php include ('inc/bbdd.php'); ?>
<?php include ('inc/funciones.php'); ?>

<?php

    $titulo1 = 'Nueva máquina';
    $titulo2 = 'Introduce los datos de la nueva máquina';

    if (!isset ($_SESSION['user'])) {
        header ("Location: login.php");
    }

    $nick = $_SESSION['user'];
    $usuario = seleccionar_usuario2 ($nick);
    $idUsuario = $usuario['idUsuario'];

?>

<?php include ('inc/header.php'); ?>

<section class="py-5">

	<div class="container px-4 px-lg-5 mt-5">

		<?php

			function mostrarFormulario ($nome, $password, $descripcionMaquina, $template, $rede) { ?>

				<form method="get" class="form-center">

				<div class="mb-3 text-center" style="width: 300px; margin: 0 auto;">

					<label for="nome" class="form-label">Hostname</label>
					<input type="text" class="form-control text-center" id="nome" name="nome" value="<?=$nome ?>" style="width: 100%; padding: 10px; font-size: 16px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;"/>

				</div>

				<!-- AÑADIR COLUMNA DE PASSWORD A CADA MAQUINA -->

                                <div class="mb-3 text-center" style="width: 300px; margin: 0 auto;">

                                        <label for="password" class="form-label">Contraseña</label>
                                        <input type="password" class="form-control text-center" id="password" name="password" value="<?=$password ?>" style="width: 100%; padding: 10px; font-size: 16px; border: >

				</div>

				<!-- Select para las plantillas -->

				<div class="mb-3 text-center">

                                        <label for="template" class="form-label">Plantilla</label>

					<br>

                                        <select class="form-select form-control text-center" name="template" id ="template" style="width: 300px; margin: 0 auto;">

					<?php

						$plantillas = seleccionarTemplates();

						foreach ($plantillas as $plantilla) {

							$nome = $plantilla['nome'];
							$descripcionPlantilla = $plantilla['descripcion'];

					?>

							<option value="<?php echo $nome;?>" style="text-align: center;"><?php echo $descripcionPlantilla;?></option>

					<?php	} ?>

					</select>

				</div>

				<!-- Select para las redes. Por ahora se muestra a mano porque sólo está habilitada la red LAN -->

				<div class="mb-3 text-center">

					<label for="rede" class="form-label">Rede</label>

					<br>

					<select class="form-select form-control text-center" name="rede" id ="rede" style="width: 300px; margin: 0 auto;">

						<option value="LAN" style="text-align: center;">LAN</option>

					</select>

				</div>

				<div class="mb-3 text-center" style="width: 300px; margin: 0 auto;">

					<label for="descripcionMaquina" class="form-label">Descripción</label>
					<input type="text" class="form-control text-center" id="descripcionMaquina" name="descripcionMaquina" value="<?=$descripcionMaquina ?>" style="width: 100%; height: 100px; padding: 10px; font-size: 16px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;"/>

				</div>

				<div class="mb-3 text-center">

					<button type="submit" class="btn btn-primary" name="btn-enviar">Enviar</button>

				</div>

				</form>

<?php

	}

	if (!isset($_REQUEST['btn-enviar'])) {

		$nome = "";
		$password = "";
		$descripcionMaquina = "";
		$template = "";
		$rede = "";

		mostrarFormulario ($nome, $password, $descripcionMaquina, $template, $rede);

	} else {

		$nome = recoge('nome');
		$password = recoge('password');
		$descripcionMaquina = recoge('descripcionMaquina');
		$template = recoge('template');
		$rede = recoge('rede');
		$errores = "";

		if ($nome == "") {

			$errores.= "<li>Debes introducir un nome</li>";

		}

                if ($password == "") {

                        $errores.= "<li>Debes introducir unha contrasinal</li>";

                }

		if ($descripcionMaquina == "") {

			$errores.= "<li>Debes introducir unha descripción</li>";

		}

                if ($template == "") {

                        $errores.= "<li>Debes escoller unha plantilla</li>";

                }

                if ($rede == "") {

                        $errores.= "<li>Debes escoller unha rede</li>";

                }

		if ($errores != "") {

			echo "<div class='alert alert-danger' role='alert'>";

				echo "<ul>$errores</ul>";
				echo "</hr>";

			echo "</div>";

			mostrarFormulario ($nome, $password, $descripcionMaquina, $template, $rede);

		}

		else {

			$ipGenerada = generarIp();
			$encryptedPassUnix=encriptarPasswordUnix($password);

			// AQUI SE ENVIA SIN ENCRIPTAR PARA QUE SE ENCRIPTE EN LA PROPIA MAQUINA LXC (ARCHIVO /ETC/SHADOW).

			$outputCMD = executarComandoSSH("sudo scripts/crearContainer.sh $nome $nick $encryptedPassUnix $ipGenerada $template $rede", true);

			if (trim($outputCMD) != "OK") {

				echo "<h2 class='pagination justify-content-center'>No hemos podido crear la máquina: <br> $outputCMD</p>";

			} else {

				echo "<h2 class='pagination justify-content-center'>La máquina se ha creado correctamente</p>";

				$nomeNovaMaquina = $nome . "." . $nick;

				// AQUI SE ENVIA ENCRIPTADA A LA BASE DE DATOS CON AES.
				// CADA VEZ QUE SE HAGA UNA CONEXION DESDE INDEX HAY QUE DESENCRIPTARLA PREVIAMENTE CON LA KEY.
				// LA KEY ES UNA CONSTANTE YA QUE ES LA MISMA PARA TODAS LAS MAQUINAS.

				$key = KEY;
				$encryptedPassword = encriptarPassword($password, $key);

				// CREAR ESTA FUNCION.

                		$idNovaMaquina = insertarMaquinaBBDDAES($nomeNovaMaquina, $encryptedPassword, $descripcionMaquina, $idUsuario);

                		if ($idNovaMaquina == 0) {

                			echo "<h2 class='pagination justify-content-center'>No hemos podido insertar la máquina en la base de datos</p>";

                		} else {

                        		echo "<h2 class='pagination justify-content-center'>La máquina se ha insertado correctamente en la base de datos</p>";

					$datosRede = seleccionarRede($rede);

					$idNetwork = $datosRede['idNetwork'];

					$nomeNovaNic = "NIC_". $idNovaMaquina;

					$idNovaNic = insertarNicBBDD($nomeNovaNic, $ipGenerada, $idNovaMaquina, $idNetwork);

					if ($idNovaNic == 0) {

						echo "<h2 class='pagination justify-content-center'>No hemos podido insertar la tarjeta de red en la base de datos</p>";

					} else {

						echo "<h2 class='pagination justify-content-center'>La tarjeta de red se ha insertado correctamente</p>";

					}

				}

                	}


		}

	}

?>

	            </div>

            </section>

        </body>

</html>

<?php include("inc/footer.php"); ?>
