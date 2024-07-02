# ************************************************************
# Sequel Pro SQL dump
# Versão 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.5.5-10.4.21-MariaDB)
# Base de Dados: Restaurante
# Tempo de Geração: 2024-07-02 07:19:49 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump da tabela clientes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `nif` int(11) NOT NULL,
  `nome` text NOT NULL DEFAULT '',
  `morada` text NOT NULL DEFAULT '',
  `telefone` int(11) DEFAULT NULL,
  `email` text DEFAULT NULL,
  PRIMARY KEY (`nif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump da tabela cozinha
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cozinha`;

CREATE TABLE `cozinha` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idPedido` int(11) NOT NULL DEFAULT 0,
  `idPrato` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `FK_cozinha_pedido` (`idPedido`),
  KEY `FK_cozinha_pratos` (`idPrato`),
  CONSTRAINT `FK_cozinha_pedido` FOREIGN KEY (`idPedido`) REFERENCES `pedido` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_cozinha_pratos` FOREIGN KEY (`idPrato`) REFERENCES `pratos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump da tabela estadopedido
# ------------------------------------------------------------

DROP TABLE IF EXISTS `estadopedido`;

CREATE TABLE `estadopedido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `estadopedido` WRITE;
/*!40000 ALTER TABLE `estadopedido` DISABLE KEYS */;

INSERT INTO `estadopedido` (`id`, `descricao`)
VALUES
	(1,'Em execução'),
	(2,'Servido'),
	(3,'Finalizado'),
	(4,'Iniciado');

/*!40000 ALTER TABLE `estadopedido` ENABLE KEYS */;
UNLOCK TABLES;


# Dump da tabela estadoreserva
# ------------------------------------------------------------

DROP TABLE IF EXISTS `estadoreserva`;

CREATE TABLE `estadoreserva` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `estadoreserva` WRITE;
/*!40000 ALTER TABLE `estadoreserva` DISABLE KEYS */;

INSERT INTO `estadoreserva` (`id`, `descricao`)
VALUES
	(1,'Cancelada'),
	(2,'Terminada'),
	(3,'Provisória');

/*!40000 ALTER TABLE `estadoreserva` ENABLE KEYS */;
UNLOCK TABLES;


# Dump da tabela mesas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mesas`;

CREATE TABLE `mesas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` text NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `mesas` WRITE;
/*!40000 ALTER TABLE `mesas` DISABLE KEYS */;

INSERT INTO `mesas` (`id`, `nome`)
VALUES
	(1,'Mesa1'),
	(2,'Mesa2'),
	(3,'Mesa3'),
	(4,'Mesa4'),
	(5,'Mesa5'),
	(6,'Mesa6'),
	(7,'Mesa7'),
	(8,'Mesa8'),
	(9,'Mesa9'),
	(10,'Mesa10');

/*!40000 ALTER TABLE `mesas` ENABLE KEYS */;
UNLOCK TABLES;


# Dump da tabela pedido
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pedido`;

CREATE TABLE `pedido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idMesa` int(11) NOT NULL DEFAULT 0,
  `idEstado` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `FK_pedido_mesas` (`idMesa`),
  KEY `FK_pedido_estadopedido` (`idEstado`),
  CONSTRAINT `FK_pedido_estadopedido` FOREIGN KEY (`idEstado`) REFERENCES `estadopedido` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_pedido_mesas` FOREIGN KEY (`idMesa`) REFERENCES `mesas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump da tabela pratos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pratos`;

CREATE TABLE `pratos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` text DEFAULT NULL,
  `preco` double DEFAULT NULL,
  `idTipo` int(11) DEFAULT NULL,
  `foto` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_pratos_tipoprato` (`idTipo`),
  CONSTRAINT `FK_pratos_tipoprato` FOREIGN KEY (`idTipo`) REFERENCES `tipoprato` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump da tabela reserva
# ------------------------------------------------------------

DROP TABLE IF EXISTS `reserva`;

CREATE TABLE `reserva` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump da tabela tipoprato
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tipoprato`;

CREATE TABLE `tipoprato` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` text NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `tipoprato` WRITE;
/*!40000 ALTER TABLE `tipoprato` DISABLE KEYS */;

INSERT INTO `tipoprato` (`id`, `descricao`)
VALUES
	(1,'sopa'),
	(2,'carne'),
	(3,'peixe'),
	(4,'vegetariano'),
	(5,'sobremesa'),
	(6,'entrada');

/*!40000 ALTER TABLE `tipoprato` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;