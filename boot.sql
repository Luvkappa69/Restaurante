-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.7.0.6850
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for restaurante
DROP DATABASE IF EXISTS `restaurante`;
CREATE DATABASE IF NOT EXISTS `restaurante` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `restaurante`;

-- Dumping structure for table restaurante.clientes
DROP TABLE IF EXISTS `clientes`;
CREATE TABLE IF NOT EXISTS `clientes` (
  `nif` int(11) NOT NULL,
  `nome` text NOT NULL DEFAULT '',
  `morada` text NOT NULL DEFAULT '',
  `telefone` int(11) DEFAULT NULL,
  `email` text DEFAULT NULL,
  PRIMARY KEY (`nif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table restaurante.clientes: ~2 rows (approximately)
DELETE FROM `clientes`;
INSERT INTO `clientes` (`nif`, `nome`, `morada`, `telefone`, `email`) VALUES
	(123456789, 'nome', 'm,orada', 966555444, 'diogo@email.com'),
	(222555881, 'Nome 345', 'llllsssshddhdhdh', 968451333, 'ddddd @email.com'),
	(222555888, 'Nome 2', 'moaradaaaaa', 955448754, 'email.@email.com');

-- Dumping structure for table restaurante.cozinha
DROP TABLE IF EXISTS `cozinha`;
CREATE TABLE IF NOT EXISTS `cozinha` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idPedido` int(11) NOT NULL DEFAULT 0,
  `idPrato` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `FK_cozinha_pedido` (`idPedido`),
  KEY `FK_cozinha_pratos` (`idPrato`),
  CONSTRAINT `FK_cozinha_pedido` FOREIGN KEY (`idPedido`) REFERENCES `pedido` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_cozinha_pratos` FOREIGN KEY (`idPrato`) REFERENCES `pratos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table restaurante.cozinha: ~3 rows (approximately)
DELETE FROM `cozinha`;
INSERT INTO `cozinha` (`id`, `idPedido`, `idPrato`) VALUES
	(21, 23, 5),
	(22, 24, 5),
	(23, 25, 2);

-- Dumping structure for table restaurante.estadopedido
DROP TABLE IF EXISTS `estadopedido`;
CREATE TABLE IF NOT EXISTS `estadopedido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table restaurante.estadopedido: ~4 rows (approximately)
DELETE FROM `estadopedido`;
INSERT INTO `estadopedido` (`id`, `descricao`) VALUES
	(1, 'Em execução'),
	(2, 'Servido'),
	(3, 'Finalizado'),
	(4, 'Iniciado');

-- Dumping structure for table restaurante.estadoreserva
DROP TABLE IF EXISTS `estadoreserva`;
CREATE TABLE IF NOT EXISTS `estadoreserva` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table restaurante.estadoreserva: ~3 rows (approximately)
DELETE FROM `estadoreserva`;
INSERT INTO `estadoreserva` (`id`, `descricao`) VALUES
	(1, 'Cancelada'),
	(2, 'Terminada'),
	(3, 'Provisória');

-- Dumping structure for table restaurante.mesas
DROP TABLE IF EXISTS `mesas`;
CREATE TABLE IF NOT EXISTS `mesas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` text NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table restaurante.mesas: ~10 rows (approximately)
DELETE FROM `mesas`;
INSERT INTO `mesas` (`id`, `nome`) VALUES
	(1, 'Mesa1'),
	(2, 'Mesa2'),
	(3, 'Mesa3'),
	(4, 'Mesa4'),
	(5, 'Mesa5'),
	(6, 'Mesa6'),
	(7, 'Mesa7'),
	(8, 'Mesa8'),
	(9, 'Mesa9'),
	(10, 'Mesa10');

-- Dumping structure for table restaurante.pedido
DROP TABLE IF EXISTS `pedido`;
CREATE TABLE IF NOT EXISTS `pedido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idMesa` int(11) NOT NULL DEFAULT 0,
  `idEstado` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `FK_pedido_mesas` (`idMesa`),
  KEY `FK_pedido_estadopedido` (`idEstado`),
  CONSTRAINT `FK_pedido_estadopedido` FOREIGN KEY (`idEstado`) REFERENCES `estadopedido` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_pedido_mesas` FOREIGN KEY (`idMesa`) REFERENCES `mesas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table restaurante.pedido: ~3 rows (approximately)
DELETE FROM `pedido`;
INSERT INTO `pedido` (`id`, `idMesa`, `idEstado`) VALUES
	(23, 2, 3),
	(24, 2, 3),
	(25, 2, 3);

-- Dumping structure for table restaurante.pratos
DROP TABLE IF EXISTS `pratos`;
CREATE TABLE IF NOT EXISTS `pratos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` text DEFAULT NULL,
  `preco` double DEFAULT NULL,
  `idTipo` int(11) DEFAULT NULL,
  `foto` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_pratos_tipoprato` (`idTipo`),
  CONSTRAINT `FK_pratos_tipoprato` FOREIGN KEY (`idTipo`) REFERENCES `tipoprato` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table restaurante.pratos: ~2 rows (approximately)
DELETE FROM `pratos`;
INSERT INTO `pratos` (`id`, `nome`, `preco`, `idTipo`, `foto`) VALUES
	(2, 'Pizza', 30, 6, 'src/imagens/c6586b2e09d0ed0ee6431a1bb280657f/_prato20240703001947.png'),
	(5, 'pato', 69, 2, 'src/imagens/259823af837e251e560ca1158a4e77c7/_prato20240703030257.png');

-- Dumping structure for table restaurante.reserva
DROP TABLE IF EXISTS `reserva`;
CREATE TABLE IF NOT EXISTS `reserva` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idCliente` int(11) NOT NULL DEFAULT 0,
  `idMesa` int(11) NOT NULL DEFAULT 0,
  `data` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_reserva_clientes` (`idCliente`),
  KEY `FK_reserva_mesas` (`idMesa`),
  KEY `estado` (`estado`),
  CONSTRAINT `FK_reserva_clientes` FOREIGN KEY (`idCliente`) REFERENCES `clientes` (`nif`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_reserva_mesas` FOREIGN KEY (`idMesa`) REFERENCES `mesas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `reserva_ibfk_1` FOREIGN KEY (`estado`) REFERENCES `estadoreserva` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table restaurante.reserva: ~3 rows (approximately)
DELETE FROM `reserva`;
INSERT INTO `reserva` (`id`, `idCliente`, `idMesa`, `data`, `hora`, `estado`) VALUES
	(6, 222555881, 4, '2024-07-08', '15:12:00', 3),
	(7, 222555888, 2, '2024-07-18', '21:12:00', 3);

-- Dumping structure for table restaurante.tipoprato
DROP TABLE IF EXISTS `tipoprato`;
CREATE TABLE IF NOT EXISTS `tipoprato` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` text NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table restaurante.tipoprato: ~6 rows (approximately)
DELETE FROM `tipoprato`;
INSERT INTO `tipoprato` (`id`, `descricao`) VALUES
	(1, 'sopa'),
	(2, 'carne'),
	(3, 'peixe'),
	(4, 'vegetariano'),
	(5, 'sobremesa'),
	(6, 'entrada');

-- Dumping structure for table restaurante.tipouser
DROP TABLE IF EXISTS `tipouser`;
CREATE TABLE IF NOT EXISTS `tipouser` (
  `id_user` int(11) NOT NULL,
  `descricao_user` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table restaurante.tipouser: ~2 rows (approximately)
DELETE FROM `tipouser`;
INSERT INTO `tipouser` (`id_user`, `descricao_user`) VALUES
	(1, 'ADMIN'),
	(2, 'USER');

-- Dumping structure for table restaurante.utilizador
DROP TABLE IF EXISTS `utilizador`;
CREATE TABLE IF NOT EXISTS `utilizador` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(50) DEFAULT NULL,
  `pw` varchar(100) DEFAULT NULL,
  `idtuser` int(11) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_utilizador_tipouser` (`idtuser`),
  CONSTRAINT `FK_utilizador_tipouser` FOREIGN KEY (`idtuser`) REFERENCES `tipouser` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table restaurante.utilizador: ~1 rows (approximately)
DELETE FROM `utilizador`;
INSERT INTO `utilizador` (`id`, `user`, `pw`, `idtuser`, `foto`) VALUES
	(2, 'admin', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', 1, 'src/img/user/user.webp');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
