CREATE DATABASE IF NOT EXISTS db_ferrovia;
USE db_ferrovia;

CREATE TABLE cargo (
id_cargo INT AUTO_INCREMENT PRIMARY KEY,
nome_cargo VARCHAR(45) NOT NULL
);

CREATE TABLE rota (
id_rota INT AUTO_INCREMENT PRIMARY KEY,
nome_rota VARCHAR(45) NOT NULL,
descricao TEXT NOT NULL,
extensao DECIMAL(8,2) NOT NULL,
tempo_estimado_minutos INT NOT NULL,
data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE relatorio (
id_relatorio INT AUTO_INCREMENT PRIMARY KEY,
nome_relatorio VARCHAR(100) NOT NULL,
tipo_relatorio VARCHAR(45) NOT NULL,
data_relatorio DATE NOT NULL
);

CREATE TABLE usuario (
id_usuario INT AUTO_INCREMENT PRIMARY KEY,
id_cargo INT NOT NULL,
nome VARCHAR(45) NOT NULL,
email VARCHAR(45) NOT NULL UNIQUE,
telefone VARCHAR(45),
cep VARCHAR(9) NULL,
logradouro VARCHAR(150) NULL,
bairro VARCHAR(100) NULL,
cidade VARCHAR(100) NULL,
uf CHAR(2) NULL,
senha_hash VARCHAR(255) NOT NULL,
FOREIGN KEY (id_cargo) REFERENCES cargo(id_cargo)
);

CREATE TABLE trem (
id_trem INT AUTO_INCREMENT PRIMARY KEY,
nome_trem VARCHAR(100) NOT NULL,
velocidade_maxima DECIMAL(5,2) NOT NULL,
tipo_trem VARCHAR(45) NOT NULL,
id_usuario INT,
id_rota INT NOT NULL,
FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
FOREIGN KEY (id_rota) REFERENCES rota(id_rota)
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


CREATE TABLE leitura_sensor (
id_leitura INT AUTO_INCREMENT PRIMARY KEY,
id_sensor INT NOT NULL,
valor_leitura DECIMAL(10,2),
unidade_medida VARCHAR(45),
dt_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (id_sensor) REFERENCES sensor(id_sensor)
);

INSERT INTO cargo (nome_cargo) VALUES 
('Administrador'),
('Funcionário');
