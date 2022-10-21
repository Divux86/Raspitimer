--crear la base de datos
CREATE DATABASE modo2;
--usar la base de datos
USE modo2;

--crear la tabla currace
CREATE TABLE `currace` (
	`nombre` INT(11) NOT NULL);

--crear la tabla players
CREATE TABLE `players` (
	`id` INT(11) NOT NULL ,
	`name` VARCHAR(255) UNIQUE NOT NULL,
	PRIMARY KEY (`id`)
);

--crear la tabla races
CREATE TABLE `races` (
	`id_race` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL,
	`vueltas` INT(11) NOT NULL,
	PRIMARY KEY (`id_race`)
);

--crear la tabla resultados
CREATE TABLE `resultados` (
	`id_carrera` INT(11) NOT NULL,
	`id_jugador` INT(11) NOT NULL,
	`lap` INT(11) NOT NULL,
	`time` DECIMAL(8,3) NOT NULL,
	PRIMARY KEY (`id_carrera`,`id_jugador`,`lap`)
);