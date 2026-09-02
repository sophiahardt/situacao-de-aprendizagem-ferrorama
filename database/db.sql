CREATE DATABASE IF NOT EXISTS db_ferrovia;
USE db_ferrovia;

CREATE TABLE cargo (
id_cargo INT AUTO_INCREMENT PRIMARY KEY,
nome_cargo VARCHAR(45) NOT NULL
);

CREATE TABLE trem (
id_trem INT AUTO_INCREMENT PRIMARY KEY,
nome_trem VARCHAR(100) NOT NULL, 
velocidade_maxima DECIMAL(5,2) NOT NULL,
capacidade INT NOT NULL, 
dt_trem_registro DATETIME DEFAULT CURRENT_TIMESTAMP 
);

CREATE TABLE rota (
id_rota INT AUTO_INCREMENT PRIMARY KEY,
nome_rota VARCHAR(45),
extensao DECIMAL(8,2),
tempo_estimado_minutos  INT,
dt_rota DATETIME DEFAULT CURRENT_TIMESTAMP 
);

CREATE TABLE usuario (
id_usuario INT AUTO_INCREMENT PRIMARY KEY,
id_cargo INT NOT NULL,
nome VARCHAR(45) NOT NULL,
email VARCHAR(45) NOT NULL UNIQUE,
telefone VARCHAR(45),
senha_hash VARCHAR(255) NOT NULL,
FOREIGN KEY (id_cargo) REFERENCES cargo(id_cargo)
);

CREATE TABLE log_acesso (
id_log INT AUTO_INCREMENT PRIMARY KEY,
id_usuario INT NOT NULL,
acao VARCHAR(45) NOT NULL,
dt_acesso DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

CREATE TABLE sensor (
id_sensor INT AUTO_INCREMENT PRIMARY KEY,
nome_sensor VARCHAR(45) NOT NULL,
tipo_sensor ENUM(
'Velocidade',
'Temperatura',
'Presença'
),
localizacao VARCHAR(45),
id_trem INT NULL,
id_rota INT NULL,
FOREIGN KEY (id_trem) REFERENCES trem(id_trem),
FOREIGN KEY (id_rota) REFERENCES rota(id_rota)
); 