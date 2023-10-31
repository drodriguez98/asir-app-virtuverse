<?php include("config.php"); ?>

<?php

    #   Conectarse a la base de datos.

	function conectarBD() {

		try {

			$conexion = new PDO("mysql:host=".HOST."; dbname=".DBNAME."; charset=utf8", USER, PASSWORD);
			$conexion -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch (PDOException $e) {

			echo "Error: Error al conectarse con la base de datos: ". $e -> getMessage();
			file_put_contents("PDOErrors.txt", "\r\n".date('j F, Y, g:i a ').$e -> getMessage(), FILE_APPEND);
			exit;

		}

		return $conexion;

	}


	#   Desconectarse de la base de datos.

	function desconectarBD($conexion) {

		$conexion = NULL;

	}


    #   Comprobar durante el registro que no existe un usuario con el email introducido.

	function seleccionar_nick ($nick) {

		$conexion = conectarBD();

		try {

			$sql = "SELECT * FROM usuarios WHERE nick = :nick";
			$stmt = $conexion -> prepare($sql);
			$stmt -> bindParam(':nick', $nick);
			$stmt -> execute();

		} catch (PDOException $e) {

			echo "Error: Error al conectarse con la base de datos: ". $e -> getMessage();
			file_put_contents("PDOErrors.txt", "\r\n".date('j F, Y, g:i a ').$e -> getMessage(), FILE_APPEND);
			exit;

		}

		$numfilas = $stmt -> rowCount();

		desconectarBD($conexion);

		return $numfilas;

	}


    #   Seleccionar todos los datos de un usuario con el nick.

	function seleccionar_usuario($nick) {

		$conexion = conectarBD();

		try {

			$sql = "SELECT * FROM usuarios WHERE nick = :nick";
			$stmt = $conexion -> prepare($sql);
			$stmt -> bindParam(':nick', $nick);
			$stmt -> execute();
			$row = $stmt -> fetchAll(PDO::FETCH_ASSOC);

		} catch (PDOException $e) {

			echo "Error: Error al conectarse con la base de datos: ". $e -> getMessage();
			file_put_contents("PDOErrors.txt", "\r\n".date('j F, Y, g:i a ').$e -> getMessage(), FILE_APPEND);
			exit;

		}

		desconectarBD($conexion);

		return $row;

	}


    function seleccionar_usuario2($nick) {

		$conexion = conectarBD();

		try {

			$sql = "SELECT * FROM usuarios WHERE nick = :nick";
			$stmt = $conexion -> prepare($sql);
			$stmt -> bindParam(':nick', $nick);
			$stmt -> execute();
			$row = $stmt -> fetch(PDO::FETCH_ASSOC);

		} catch (PDOException $e) {

			echo "Error: Error al conectarse con la base de datos: ". $e -> getMessage();
			file_put_contents("PDOErrors.txt", "\r\n".date('j F, Y, g:i a ').$e -> getMessage(), FILE_APPEND);
			exit;

		}

		desconectarBD($conexion);

		return $row;

	}


	# Registro.

	function registro ($nome, $nick, $password) {

		$conexion = conectarBD();
		$password = password_hash ($password, PASSWORD_BCRYPT);

		try {

			$sql = "INSERT INTO usuarios (nome, nick, password, rol, online) VALUES (:nome, :nick, :password, 'user', 1)";
			$stmt = $conexion -> prepare($sql);
			$stmt -> bindParam(':nome', $nome);
			$stmt -> bindParam(':nick',  $nick);
			$stmt -> bindParam(':password', $password);
			$stmt -> execute();

		} catch (PDOException $e) {

			echo "Error: Error al conectarse con la base de datos: ". $e -> getMessage();
			file_put_contents("PDOErrors.txt", "\r\n".date('j F, Y, g:i a ').$e -> getMessage(), FILE_APPEND);
			exit;

		}

		$numfilas = $stmt -> rowCount();

		desconectarBD ($conexion);

		return $numfilas;

	}


	#   Iniciar sesión.

	function login ($nick, $password) {

		$conexion = conectarBD();

		try {

			$sql = "SELECT * FROM usuarios WHERE nick = :nick AND online = 1";
			$stmt = $conexion -> prepare($sql);
			$stmt -> bindParam(':nick', $nick);
			$stmt -> execute();
			$row = $stmt -> fetch (PDO::FETCH_ASSOC);

		} catch (PDOException $e) {

			echo "Error: Error al conectarse con la base de datos: ". $e -> getMessage();
			file_put_contents("PDOErrors.txt", "\r\n".date('j F, Y, g:i a ').$e -> getMessage(), FILE_APPEND);
			exit;

		}

		return password_verify ($password, $row['password']);

	}


	# Editar datos de un usuario

	function editar_usuario ($idUsuario, $nick, $nome) {

		$conexion = conectarBD();

		try {

			$sql = "UPDATE usuarios SET nick = :nick, nome = :nome WHERE idUsuario = :idUsuario";
			$stmt = $conexion -> prepare($sql);
			$stmt -> bindParam(':nick', $nick);
			$stmt -> bindParam(':nome', $nome);
			$stmt -> bindParam(':idUsuario', $idUsuario);
			$stmt -> execute();

		} catch (PDOException $e) {

			echo "Error: Error al conectarse con la base de datos: ". $e -> getMessage();
			file_put_contents("PDOErrors.txt", "\r\n".date('j F, Y, g:i a ').$e -> getMessage(), FILE_APPEND);
			exit;

		}

		$numfilas = $stmt -> rowCount();

		desconectarBD($conexion);

		return $numfilas;

	}


	#   Cambiar la contraseña de los usuarios

	function cambiarPassword ($nick, $passwordNew) {

		$conexion = conectarBD();
		$passwordNew = password_hash ($passwordNew, PASSWORD_BCRYPT);

		try {

			$sql = "UPDATE usuarios SET password = :passwordNew WHERE nick = :nick";
			$stmt = $conexion -> prepare($sql);
			$stmt -> bindParam(':nick', $nick);
			$passwordNew1 = password_hash($passwordNew, PASSWORD_BCRYPT);
			$stmt -> bindParam(':passwordNew', $passwordNew);
			$stmt -> execute();

		} catch (PDOException $e) {

			echo "Error: Error al conectarse con la base de datos: ". $e -> getMessage();
			file_put_contents("PDOErrors.txt", "\r\n".date('j F, Y, g:i a ').$e -> getMessage(), FILE_APPEND);
			exit;

		}

		$numfilas = $stmt -> rowCount();

		desconectarBD($conexion);

		return $numfilas;

	}


        # Función para conectarse por SSH al servidor lxc.

        function conectarSSH() {

		$conexion = ssh2_connect('172.20.3.121');

		if (!$conexion) echo "ERROR CONEXION";

		else {

	                if (ssh2_auth_password($conexion, 'adminlxc', "abc123.")) {

        	                return $conexion;

                	    } else {

                        	return false;
		    	}
		}

        }


	# Función para ejecutar comandos por SSH en el servidor lxc.

	function executarComandoSSH($cmd,$out=false) {

		if (($conexion=conectarSSH())) {

			$stream=ssh2_exec($conexion,$cmd);

			if ($out) {

				stream_set_blocking($stream, true);
				$stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
				return stream_get_contents($stream_out);

			}

		}
	}


        // executarComandoSSH("scripts/crearContainer.sh $nome $nick $num1 $num2 $rede $template");
	// executarComandoSSH("scripts/crearContainer.sh pepito 172.20.11.21");
	// executarComandoSSH("ls -al",true);


	# Insertar una máquina a la base de datos si se ha creado correctamente

	function insertarMaquinaBBDD($nomeNovaMaquina, $descripcionMaquina, $idUsuario) {

		$ret = 0;

		$conexion = conectarBD();

		try {

			$sql = "INSERT INTO maquinas (nome, descripcion, definicion, tipo, estado, idUsuario, idServidor) VALUES (:nome, :descripcion, 'NULL', 'lxc', 0, :idUsuario, 1)";
			$stmt = $conexion -> prepare($sql);
			$stmt -> bindParam(':nome', $nomeNovaMaquina);
			$stmt -> bindParam(':descripcion',  $descripcionMaquina);
			$stmt -> bindParam(':idUsuario', $idUsuario);

			if ($stmt -> execute()) $ret=$conexion->lastInsertId();

		} catch (PDOException $e) {

			echo "Error: Error al conectarse con la base de datos: ". $e -> getMessage();
			file_put_contents("PDOErrors.txt", "\r\n".date('j F, Y, g:i a ').$e -> getMessage(), FILE_APPEND);
			exit;

		} finally {

			desconectarBD ($conexion);
		}

		return $ret;

	}


        # Insertar una máquina a la base de datos si se ha creado correctamente

        function insertarMaquinaBBDDAES($nomeNovaMaquina, $encryptedPassword, $descripcionMaquina, $idUsuario) {

                $ret = 0;

                $conexion = conectarBD();

                try {

                        $sql = "INSERT INTO maquinas (nome, descripcion, definicion, tipo, estado, idUsuario, idServidor, password) VALUES (:nome, :descripcion, 'NULL', 'lxc', 0, :idUsuario, 1, :encryptedPassword)";
                        $stmt = $conexion -> prepare($sql);
                        $stmt -> bindParam(':nome', $nomeNovaMaquina);
			$stmt -> bindParam(':encryptedPassword', $encryptedPassword);
                        $stmt -> bindParam(':descripcion',  $descripcionMaquina);
                        $stmt -> bindParam(':idUsuario', $idUsuario);

                        if ($stmt -> execute()) $ret=$conexion->lastInsertId();

                } catch (PDOException $e) {

                        echo "Error: Error al conectarse con la base de datos: ". $e -> getMessage();
                        file_put_contents("PDOErrors.txt", "\r\n".date('j F, Y, g:i a ').$e -> getMessage(), FILE_APPEND);
                        exit;

                } finally {

                        desconectarBD ($conexion);
                }

                return $ret;

        }



        # Insertar una nic a la base de datos si se ha creado correctamente

        function insertarNicBBDD($nomeNovaNic, $ipGenerada, $idNovaMaquina, $idNetwork) {

                $ret = 0;

                $conexion = conectarBD();

                try {

                        $sql = "INSERT INTO nics (nome, mac, ip, gateway, idMaquina, idNetwork) VALUES (:nome, 'NULL', :ip, '172.20.0.1', :idMaquina, :idNetwork)";
                        $stmt = $conexion -> prepare($sql);
                        $stmt -> bindParam(':nome', $nomeNovaNic);
                        $stmt -> bindParam(':ip',  $ipGenerada);
                        $stmt -> bindParam(':idMaquina', $idNovaMaquina);
                        $stmt -> bindParam(':idNetwork', $idNetwork);

                        if ($stmt -> execute()) $ret=$conexion->lastInsertId();

                } catch (PDOException $e) {

                        echo "Error: Error al conectarse con la base de datos: ". $e -> getMessage();
                        file_put_contents("PDOErrors.txt", "\r\n".date('j F, Y, g:i a ').$e -> getMessage(), FILE_APPEND);
                        exit;

                } finally {

                        desconectarBD ($conexion);
                }

                return $ret;

        }


        # Desactivar una máquina en la base de datos. Si un usuario quiere borrarla da error, así que se desactiva en la BBDD.

        function desactivarMaquinaBBDD($idMaquina) {

                $conexion = conectarBD();

                try {

                        $sql = "UPDATE maquinas SET activa = 0 WHERE idMaquina = :idMaquina";
                        $stmt = $conexion -> prepare($sql);
                        $stmt -> bindParam(':idMaquina', $idMaquina);
                        $stmt -> execute();

                } catch (PDOException $e) {

                        echo "Error: Error al conectarse con la base de datos: ". $e -> getMessage();
                        file_put_contents("PDOErrors.txt", "\r\n".date('j F, Y, g:i a ').$e -> getMessage(), FILE_APPEND);
                        exit;

                }

                $numfilas = $stmt -> rowCount();
                desconectarBD($conexion);
                return $numfilas;

        }


	# Cambiar el estado de una máquina a 1 (iniciada)

        function cambiarEstado0BBDD($idMaquina) {

                $conexion = conectarBD();

                try {

                        $sql = "UPDATE maquinas SET estado = 1 WHERE idMaquina = :idMaquina";
                        $stmt = $conexion -> prepare($sql);
                        $stmt -> bindParam(':idMaquina', $idMaquina);
                        $stmt -> execute();

                } catch (PDOException $e) {

                        echo "Error: Error al conectarse con la base de datos: ". $e -> getMessage();
                        file_put_contents("PDOErrors.txt", "\r\n".date('j F, Y, g:i a ').$e -> getMessage(), FILE_APPEND);
                        exit;

                }

                $numfilas = $stmt -> rowCount();
                desconectarBD($conexion);
                return $numfilas;

        }


        # Cambiar el estado de una máquina a 0 (detenida)

        function cambiarEstado1BBDD($idMaquina) {

                $conexion = conectarBD();

                try {

                        $sql = "UPDATE maquinas SET estado = 0 WHERE idMaquina = :idMaquina";
                        $stmt = $conexion -> prepare($sql);
                        $stmt -> bindParam(':idMaquina', $idMaquina);
                        $stmt -> execute();

                } catch (PDOException $e) {

                        echo "Error: Error al conectarse con la base de datos: ". $e -> getMessage();
                        file_put_contents("PDOErrors.txt", "\r\n".date('j F, Y, g:i a ').$e -> getMessage(), FILE_APPEND);
                        exit;

                }

                $numfilas = $stmt -> rowCount();
                desconectarBD($conexion);
                return $numfilas;

        }


	# Seleccionar todas las plantillas

	function seleccionarTemplates() {

		$conexion = conectarBD();

		try {
			$sql = "SELECT * FROM maquinas WHERE tipo = 'plantilla'";
			$stmt = $conexion -> query($sql);
			$rows = $stmt -> fetchAll(PDO::FETCH_ASSOC);

		} catch (PDOException $e) {

			echo "Error: Error al conectarse con la base de datos: ". $e -> getMessage();
			file_put_contents("PDOErrors.txt", "\r\n".date('j F, Y, g:i a ').$e -> getMessage(), FILE_APPEND);
			exit;

		}

		desconectarBD($conexion);

		return $rows;

	}


	# Seleccionar todas las redes

        function seleccionarRedes() {

                $conexion = conectarBD();

                try {
                        $sql = "SELECT * FROM networks";
                        $stmt = $conexion -> query($sql);
                        $rows = $stmt -> fetchAll(PDO::FETCH_ASSOC);

                } catch (PDOException $e) {

                        echo "Error: Error al conectarse con la base de datos: ". $e -> getMessage();
                        file_put_contents("PDOErrors.txt", "\r\n".date('j F, Y, g:i a ').$e -> getMessage(), FILE_APPEND);
                        exit;

                }

                desconectarBD($conexion);

                return $rows;

        }


	# Seleccionar datos dunha rede pasándolle o nome da mesma

	function seleccionarRede($rede) {

	        $conexion = conectarBD();

		try {

			$sql = "SELECT * FROM networks WHERE nome = :nome";
			$stmt = $conexion -> prepare($sql);
			$stmt -> bindParam(':nome', $rede);
			$stmt -> execute();
			$row = $stmt -> fetch(PDO::FETCH_ASSOC);

		} catch (PDOException $e) {

			echo "Error: Error al conectarse con la base de datos: ". $e -> getMessage();
			file_put_contents("PDOErrors.txt", "\r\n".date('j F, Y, g:i a ').$e -> getMessage(), FILE_APPEND);
			exit;

		}

		desconectarBD($conexion);

		return $row;

	}


	# Seleccionar datos dunha máquina pasándolle o id da mesma

        function seleccionarMaquina($idMaquina) {

                $conexion = conectarBD();

                try {

                        $sql = "SELECT * FROM maquinas WHERE idMaquina = :idMaquina";
                        $stmt = $conexion -> prepare($sql);
                        $stmt -> bindParam(':idMaquina', $idMaquina);
                        $stmt -> execute();
                        $row = $stmt -> fetch(PDO::FETCH_ASSOC);

                } catch (PDOException $e) {

                        echo "Error: Error al conectarse con la base de datos: ". $e -> getMessage();
                        file_put_contents("PDOErrors.txt", "\r\n".date('j F, Y, g:i a ').$e -> getMessage(), FILE_APPEND);
                        exit;

                }

                desconectarBD($conexion);

                return $row;

        }

	# Seleccionar todos os datos dunha máquina pasándolle o id da mesma

        function seleccionarMaquina2($idMaquina) {

                $conexion = conectarBD();

                try {

                        $sql = "SELECT a.nome AS nomeMaquina, a.descripcion, a.estado, b.nome AS nomeNic, b.ip, c.nome AS nomeNetwork FROM maquinas a, nics b, networks c WHERE a.idMaquina = :idMaquina AND a.idMaquina = b.idMaquina AND b.idNetwork = c.idNetwork";
                        $stmt = $conexion -> prepare($sql);
                        $stmt -> bindParam(':idMaquina', $idMaquina);
                        $stmt -> execute();
                        $row = $stmt -> fetchAll(PDO::FETCH_ASSOC);

                } catch (PDOException $e) {

                        echo "Error: Error al conectarse con la base de datos: ". $e -> getMessage();
                        file_put_contents("PDOErrors.txt", "\r\n".date('j F, Y, g:i a ').$e -> getMessage(), FILE_APPEND);
                        exit;

                }

                desconectarBD($conexion);

                return $row;

        }


	# Seleccionar todas las máquinas de un usuario

        function seleccionarMaquinasUsuario($idUsuario) {

                $conexion = conectarBD();

                try {

                        $sql = "SELECT * FROM maquinas where tipo = 'lxc' AND activa = 1 AND idUsuario = :idUsuario";
                        $stmt = $conexion -> prepare($sql);
                        $stmt -> bindParam(':idUsuario', $idUsuario);
                        $stmt -> execute();
			$rows = $stmt -> fetchAll(PDO::FETCH_ASSOC);

                } catch (PDOException $e) {

                        echo "Error: Error al conectarse con la base de datos: ". $e -> getMessage();
                        file_put_contents("PDOErrors.txt", "\r\n".date('j F, Y, g:i a ').$e -> getMessage(), FILE_APPEND);
                        exit;

                }

                desconectarBD ($conexion);

                return $rows;

        }


        # Seleccionar todas las máquinas de un usuario con su ip. PRUEBA PARA CONECTAR Y ABRIR TERMINAL EN INDEX.PRUEBA.PHP.

        function seleccionarMaquinasUsuario2($idUsuario) {

                $conexion = conectarBD();

                try {

                        $sql = "SELECT a.idMaquina, a.nome as nomeMaquina, a.estado, a.password, b.ip FROM maquinas a, nics b where a.tipo = 'lxc' AND a.activa = 1 AND a.idUsuario = :idUsuario AND a.idMaquina = b.idMaquina";
                        $stmt = $conexion -> prepare($sql);
                        $stmt -> bindParam(':idUsuario', $idUsuario);
                        $stmt -> execute();
                        $rows = $stmt -> fetchAll(PDO::FETCH_ASSOC);

                } catch (PDOException $e) {

                        echo "Error: Error al conectarse con la base de datos: ". $e -> getMessage();
                        file_put_contents("PDOErrors.txt", "\r\n".date('j F, Y, g:i a ').$e -> getMessage(), FILE_APPEND);
                        exit;

                }

                desconectarBD ($conexion);

                return $rows;

        }

	# Seleccionar ips ya utilizadas por otras máquinas

	function seleccionarIpsExistentes() {

                $conexion = conectarBD();

                try {
                        $sql = "SELECT ip FROM nics WHERE idMaquina NOT IN (SELECT idMaquina FROM maquinas WHERE activa = 0)";
                        $stmt = $conexion -> query($sql);
                        $rows = $stmt -> fetchAll(PDO::FETCH_ASSOC);

                } catch (PDOException $e) {

                        echo "Error: Error al conectarse con la base de datos: ". $e -> getMessage();
                        file_put_contents("PDOErrors.txt", "\r\n".date('j F, Y, g:i a ').$e -> getMessage(), FILE_APPEND);
                        exit;

                }

                desconectarBD($conexion);

                return $rows;

        }


	# Generar una ip disponible para una nueva máquina

	function generarIP() {

		$baseIP = '172.20.100.';
		$minIP = 2;
		$maxIP = 254;

		$ipsExistentes = seleccionarIPsExistentes();

		do {
			$numeroIP = rand($minIP, $maxIP);
			$ipGenerada = $baseIP . $numeroIP;

		} while (in_array($ipGenerada, $ipsExistentes));

		$ipsExistentes[] = $ipGenerada;

		return $ipGenerada;
	}


	# Obtener la ip de un contenedor para conectarse por SSH y utilizar la terminal

	function obtenerIP($idMaquina) {

                $conexion = conectarBD();

                try {

                        $sql = "SELECT b.ip FROM maquinas a, nics b WHERE a.idMaquina = :idMaquina and a.idMaquina = b.idMaquina";
                        $stmt = $conexion -> prepare($sql);
                        $stmt -> bindParam(':idMaquina', $idMaquina);
                        $stmt -> execute();
                        $ip = $stmt -> fetchColumn();

                } catch (PDOException $e) {

                        echo "Error: Error al conectarse con la base de datos: ". $e -> getMessage();
                        file_put_contents("PDOErrors.txt", "\r\n".date('j F, Y, g:i a ').$e -> getMessage(), FILE_APPEND);
                        exit;

                }

                desconectarBD($conexion);

                return $ip;

        }


	// Encriptar password con AES

	function encriptarPassword($password, $key) {

	    $cipher = "AES-256-CBC";
	    $ivLength = openssl_cipher_iv_length($cipher);
	    $iv = openssl_random_pseudo_bytes($ivLength);
	    $encrypted = openssl_encrypt($password, $cipher, $key, OPENSSL_RAW_DATA, $iv);
	    $encryptedPassword = base64_encode($iv . $encrypted);
	    return $encryptedPassword;

	}


	// Desencriptar password con AES

	function desencriptarPassword($encryptedPassword, $key) {

	    $cipher = "AES-256-CBC";
	    $ivLength = openssl_cipher_iv_length($cipher);
	    $data = base64_decode($encryptedPassword);
	    $iv = substr($data, 0, $ivLength);
	    $encrypted = substr($data, $ivLength);
	    $decryptedPassword = openssl_decrypt($encrypted, $cipher, $key, OPENSSL_RAW_DATA, $iv);
	    return $decryptedPassword;

	}


	// Generar salto

	function generateSalt($length = 16) {
    		$bytes = openssl_random_pseudo_bytes($length);
    		return base64_encode($bytes);
	}


	// Encriptar password en /etc/shadow

	function encriptarPasswordUnix($password) {
		$salt = generateSalt();
		return crypt($password, $salt);
	}
?>
