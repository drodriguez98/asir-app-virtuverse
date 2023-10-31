<?php session_start(); ?>

<?php include ('inc/bbdd.php'); ?>
<?php include ('inc/funciones.php'); ?>

<?php

	$titulo1 = 'Home';
	$titulo2 = 'Mis máquinas';

	if (!isset($_SESSION['user'])) {
		header("Location: login.php");
	}

	$nick = $_SESSION['user'];
	$usuario = seleccionar_usuario2 ($nick);
	$idUsuario = $usuario['idUsuario'];

	include('inc/header.php');

?>

<section class="py-5">

        <link rel="stylesheet" href="js/terminalSSH/lib/xterm/xterm.css" />
        <link rel="stylesheet" href="js/terminalSSH/terminalWeb.css" />

        <script src="js/terminalSSH/lib/xterm/xterm.js"></script>
        <script src="js/terminalSSH/lib/xterm/addons/attach/attach.js"></script>
        <script src="js/terminalSSH/lib/xterm/addons/fit/fit.js"></script>
        <script src="js/terminalSSH/terminalWeb.js"></script>

        <div class="container" style=" width:90vw; margin-left: 100px; margin-bottom: 100px">
        <div style="margin-bottom:20;"> <a href="crearMaquina.php" class="btn btn-dark">Crear nueva máquina</a></div>

        <div class="row">

        	<?php
		$key = KEY;
       	    	$maquinas = seleccionarMaquinasUsuario2($idUsuario);
       	    	foreach ($maquinas as $maquina) {
	    		$idMaquina = $maquina['idMaquina'];
                	$nome = $maquina['nomeMaquina'];
               	    	$estado = $maquina['estado'];
			$ip = $maquina['ip'];
			// DESENCRIPTA LA PASSWORD DE LA BBDD CON LA CONSTANTE KEY
			$encryptedPassword = $maquina['password'];
			$password = desencriptarPassword($encryptedPassword,$key);
		?>

		<div class="card col-md-3">
                	<img class="card-img-top" src="img/4.jpg" alt="..." />
                	<div class="card-body p-4">
                    	<div class="text-center"> <h5 class="fw-bolder"><?=$nome;?></h5></div>
                </div>

                <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">

	                <div class="row justify-content-center">

			<?php if ($estado == 0) { ?>

		                <div class="col text-center"><a class="btn btn-light" href="iniciarMaquina.php?idMaquina=<?=$idMaquina?>""><img src="img/play.png" width="25" height="25"></a></div>
				<div class="col text-center"><a class="btn btn-light" href="editarMaquina.php?idMaquina=<?=$idMaquina?>""><img src="img/ver.png" width="25" height="25"></a></div>

			<?php } else { ?>

				<div class="col text-center"><a class="btn btn-light" href="detenerMaquina.php?idMaquina=<?=$idMaquina?>""><img src="img/pause.png" width="25" height="25"></a></div>

				<!-- AQUI HACE LA CONEXION CON LA PASSWORD DESENCRIPTADA -->

				<button class="sshterminal col text-center" type="button" onclick="setServerAndConnect('<?=$ip?>',22,'<?=$nick?>','<?=$password?>')">Conectar</button>

			<?php } ?>

                        	<div class="col text-center"><a class="btn btn-light" href="borrarMaquina.php?idMaquina=<?=$idMaquina?>""><img src="img/delete.png" width="25" height="25"></a></div>

			</div>

                </div>

	</div> <!-- card -->

            <?php } ?>

        <div class="col-sm"><div class="terminalbox" style="margin-right: 100px; margin-top: 75px;"><div id="terminal"></div></div></div>

	<script>
		function setServerAndConnect(server,port,nick,password) {
			setServer(server,port,nick,password);
			setTimeout(ConnectServer,500);
		}
function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

	        setSSHWebSocket("172.20.3.122",8080);
		showTerminal("terminal");
        </script>

</div>

</div>

</section>

<?php

	include('inc/footer.php');

?>


