-- phpMyAdmin SQL Dump
-- version 5.2.1deb1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Xerado en: 07 de Xuño de 2023 ás 22:24
-- Versión do servidor: 10.11.3-MariaDB-1
-- Versión do PHP: 8.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `virtuverse`
--

-- --------------------------------------------------------

--
-- Estrutura da táboa `maquinas`
--

CREATE TABLE `maquinas` (
  `idMaquina` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `definicion` varchar(255) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `estado` tinyint(1) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `idServidor` int(11) NOT NULL,
  `activa` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A extraer os datos da táboa `maquinas`
--

INSERT INTO `maquinas` (`idMaquina`, `nome`, `descripcion`, `definicion`, `tipo`, `estado`, `idUsuario`, `idServidor`, `activa`) VALUES
(3, 'dnsTemplate', 'Plantilla para Servidor DNS con Bind', 'NULL', 'plantilla', 1, 11, 1, 1),
(4, 'mailTemplate', 'Plantilla para Servidor de Correo Electrónico', 'NULL', 'plantilla', 1, 11, 1, 1),
(5, 'webTemplate', 'Plantilla para Servidor Web con Apache', 'NULL', 'plantilla', 1, 11, 1, 1),
(6, 'mariadbTemplate', 'Plantilla para Servidor de BBDD MariaDB', 'NULL', 'plantilla', 1, 11, 1, 1),
(7, 'baseTemplate', 'Plantilla genérica', 'NULL', 'plantilla', 1, 11, 1, 1),
(18, 'mail.user1', 'Maquina para servidor de correo electronico do usuario nº 1', 'NULL', 'lxc', 0, 9, 1, 1),
(19, 'www.user1', 'Maquina para servidor web do usuario nº 1', 'NULL', 'lxc', 0, 9, 1, 0),
(20, 'bbdd.user1', 'Maquina para bases de datos do usuario nº 1', 'NULL', 'lxc', 0, 9, 1, 1),
(21, 'xeral.user1', 'Maquina de proposito xeral do usuario nº 1', 'NULL', 'lxc', 0, 9, 1, 0),
(22, 'ns.user1', 'Máquina para servidor de nomes do usuario 1', 'NULL', 'lxc', 0, 9, 1, 0),
(23, 'xeral.user2', 'Maquina de proposito xeral do usuario nº 2', 'NULL', 'lxc', 1, 8, 1, 0),
(24, 'bbdd.user2', 'Máquina para bases de datos do usuario nº 2', 'NULL', 'lxc', 0, 8, 1, 1),
(25, 'ns.user2', 'Máquina para servidor de nomes do usuario 2', 'NULL', 'lxc', 0, 8, 1, 0),
(26, 'www.user2', 'Maquina para servidor web do usuario nº 2', 'NULL', 'lxc', 0, 8, 1, 1),
(27, 'ns.user1', 'Máquina para servidor de nomes do usuario 1', 'NULL', 'lxc', 0, 9, 1, 0),
(28, 'mail.user3', 'Maquina para servidor de correo electronico do usuario nº 3', 'NULL', 'lxc', 0, 10, 1, 1),
(29, 'www.user3', 'Maquina para servidor web do usuario nº 3', 'NULL', 'lxc', 1, 10, 1, 1),
(30, 'xeral.prueba', 'Maquina de proposito xeral do usuario Proba', 'NULL', 'lxc', 0, 17, 1, 0),
(31, 'ns.user1', 'Máquina para servidor de nomes do usuario 1', 'NULL', 'lxc', 0, 9, 1, 1),
(32, 'xeral.user2', 'Maquina de proposito xeral do usuario 2', 'NULL', 'lxc', 0, 8, 1, 0),
(33, 'ns.user3', 'Máquina para servidor de nomes do usuario 3', 'NULL', 'lxc', 0, 10, 1, 1),
(34, 'xeral.user1', 'Maquina de proposito xeral do usuario 1', 'NULL', 'lxc', 1, 9, 1, 1),
(35, 'www.user1', 'Maquina para servidor web do usuario nº 1', 'NULL', 'lxc', 0, 9, 1, 0),
(36, 'mail.user2', 'Maquina para correo electronico do usuario 2', 'NULL', 'lxc', 0, 8, 1, 1),
(37, 'ns.user2', 'Máquina para servidor de nomes do usuario 2', 'NULL', 'lxc', 0, 8, 1, 1),
(38, 'xeral.user3', 'Maquina de proposito xeral do usuario 3', 'NULL', 'lxc', 1, 10, 1, 1);

-- --------------------------------------------------------

--
-- Estrutura da táboa `networks`
--

CREATE TABLE `networks` (
  `idNetwork` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `networkAddress` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A extraer os datos da táboa `networks`
--

INSERT INTO `networks` (`idNetwork`, `nome`, `networkAddress`) VALUES
(1, 'LAN', '172.20.0.0/16'),
(3, 'PRIVADA', '172.30.0.0/16'),
(4, 'XESTION', '172.40.0.0/16');

-- --------------------------------------------------------

--
-- Estrutura da táboa `nics`
--

CREATE TABLE `nics` (
  `idNic` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `mac` varchar(50) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `gateway` varchar(50) DEFAULT NULL,
  `idMaquina` int(11) NOT NULL,
  `idNetwork` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A extraer os datos da táboa `nics`
--

INSERT INTO `nics` (`idNic`, `nome`, `mac`, `ip`, `gateway`, `idMaquina`, `idNetwork`) VALUES
(1, 'NIC_18', 'NULL', '172.20.100.31', '172.20.0.1', 18, 1),
(2, 'NIC_19', 'NULL', '172.20.100.42', '172.20.0.1', 19, 1),
(3, 'NIC_20', 'NULL', '172.20.100.174', '172.20.0.1', 20, 1),
(4, 'NIC_21', 'NULL', '172.20.100.227', '172.20.0.1', 21, 1),
(5, 'NIC_22', 'NULL', '172.20.100.226', '172.20.0.1', 22, 1),
(6, 'NIC_23', 'NULL', '172.20.100.97', '172.20.0.1', 23, 1),
(7, 'NIC_24', 'NULL', '172.20.100.170', '172.20.0.1', 24, 1),
(8, 'NIC_25', 'NULL', '172.20.100.138', '172.20.0.1', 25, 1),
(9, 'NIC_26', 'NULL', '172.20.100.68', '172.20.0.1', 26, 1),
(10, 'NIC_27', 'NULL', '172.20.100.82', '172.20.0.1', 27, 1),
(11, 'NIC_28', 'NULL', '172.20.100.175', '172.20.0.1', 28, 1),
(12, 'NIC_29', 'NULL', '172.20.100.220', '172.20.0.1', 29, 1),
(13, 'NIC_30', 'NULL', '172.20.100.252', '172.20.0.1', 30, 1),
(14, 'NIC_31', 'NULL', '172.20.100.52', '172.20.0.1', 31, 1),
(15, 'NIC_32', 'NULL', '172.20.100.92', '172.20.0.1', 32, 1),
(16, 'NIC_33', 'NULL', '172.20.100.95', '172.20.0.1', 33, 1),
(17, 'NIC_34', 'NULL', '172.20.100.117', '172.20.0.1', 34, 1),
(18, 'NIC_35', 'NULL', '172.20.100.58', '172.20.0.1', 35, 1),
(19, 'NIC_36', 'NULL', '172.20.100.159', '172.20.0.1', 36, 1),
(20, 'NIC_37', 'NULL', '172.20.100.10', '172.20.0.1', 37, 1),
(21, 'NIC_38', 'NULL', '172.20.100.160', '172.20.0.1', 38, 1);

-- --------------------------------------------------------

--
-- Estrutura da táboa `servidores`
--

CREATE TABLE `servidores` (
  `idServidor` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `hostname` varchar(50) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `chavePublica` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A extraer os datos da táboa `servidores`
--

INSERT INTO `servidores` (`idServidor`, `nome`, `descripcion`, `hostname`, `ip`, `chavePublica`) VALUES
(1, 'lxc', 'Servidor 1', 'lxc', '172.20.3.121', NULL);

-- --------------------------------------------------------

--
-- Estrutura da táboa `usuarios`
--

CREATE TABLE `usuarios` (
  `idUsuario` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `nick` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` varchar(50) NOT NULL,
  `online` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A extraer os datos da táboa `usuarios`
--

INSERT INTO `usuarios` (`idUsuario`, `nome`, `nick`, `password`, `rol`, `online`) VALUES
(8, 'user2', 'user2', '$2y$10$NbefsL7arA5D9AYEnONXQ.bXjEGCmden9TbSBJnvMe2FwVr9qt2i6', 'user', 1),
(9, 'user1', 'user1', '$2y$10$F7n2yiyM.7QwlTXIRH4srO2dJg3DYWnJ4MWdPJ2li1wg3DJkT0UxK', 'user', 1),
(10, 'user3', 'user3', '$2y$10$n0Jn1fkHI24O73EMdLHIIOzVy/gFedqqXw1DRHJ2.s6hOywIDZ5Jq', 'user', 1),
(11, 'admin1', 'admin1', '$2y$10$aDHMrX7xkK1oChW.CJxaG.L8nDWj3i8e8a7OpvUQv0POk9oRkmppq', 'admin', 1),
(12, 'admin2', 'admin2', '$2y$10$A9jM1sVDs0jikhiXFb6pbufRaeu7O99SzjE4lG5xyUcRzvs8dwFbm', 'admin', 1),
(13, 'admin3', 'admin3', '$2y$10$E71zFWdw6DA63n7dS/MR1OXWtT3ghCmnbbhDRA.CIk0n3htrKj.6u', 'admin', 1),
(17, 'prueba', 'prueba', '$2y$10$UEmrEvH0Q/H2RsLngiXMb.U3bGb/N8VCKys1FXRdpn8KV.5sK.zK.', 'user', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `maquinas`
--
ALTER TABLE `maquinas`
  ADD PRIMARY KEY (`idMaquina`),
  ADD KEY `idUsuario` (`idUsuario`),
  ADD KEY `idServidor` (`idServidor`);

--
-- Indexes for table `networks`
--
ALTER TABLE `networks`
  ADD PRIMARY KEY (`idNetwork`);

--
-- Indexes for table `nics`
--
ALTER TABLE `nics`
  ADD PRIMARY KEY (`idNic`),
  ADD KEY `idMaquina` (`idMaquina`),
  ADD KEY `idNetwork` (`idNetwork`);

--
-- Indexes for table `servidores`
--
ALTER TABLE `servidores`
  ADD PRIMARY KEY (`idServidor`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idUsuario`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `maquinas`
--
ALTER TABLE `maquinas`
  MODIFY `idMaquina` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `networks`
--
ALTER TABLE `networks`
  MODIFY `idNetwork` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `nics`
--
ALTER TABLE `nics`
  MODIFY `idNic` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `servidores`
--
ALTER TABLE `servidores`
  MODIFY `idServidor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Restricións para os envorcados das táboas
--

--
-- Restricións para a táboa `maquinas`
--
ALTER TABLE `maquinas`
  ADD CONSTRAINT `maquinas_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`idUsuario`),
  ADD CONSTRAINT `maquinas_ibfk_2` FOREIGN KEY (`idServidor`) REFERENCES `servidores` (`idServidor`);

--
-- Restricións para a táboa `nics`
--
ALTER TABLE `nics`
  ADD CONSTRAINT `nics_ibfk_1` FOREIGN KEY (`idMaquina`) REFERENCES `maquinas` (`idMaquina`),
  ADD CONSTRAINT `nics_ibfk_2` FOREIGN KEY (`idNetwork`) REFERENCES `networks` (`idNetwork`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
