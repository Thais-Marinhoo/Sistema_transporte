-- Criar o banco de dados
CREATE DATABASE IF NOT EXISTS login_transporte;
USE login_transporte;


-- Criar a tabela de usuários
CREATE TABLE IF NOT EXISTS users (
    id_usuario INT NOT NULL AUTO_INCREMENT,
    codigo_recuperacao VARCHAR(6),
    codigo_expira DATETIME,
    login VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(120) NOT NULL,
    PRIMARY KEY (id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Garante que estamos usando o banco correto
USE login_transporte;


-- 2. Tabela: ponto
CREATE TABLE ponto (
    id_ponto INT AUTO_INCREMENT PRIMARY KEY,
    numero_ponto INT NOT NULL UNIQUE,
    nome_ponto VARCHAR(255) NOT NULL,
    endereco VARCHAR(255) NOT NULL,
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8)
);


-- 3. Tabela: aluno
CREATE TABLE aluno (
    id_aluno INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    endereco VARCHAR(255),
    serie INT NOT NULL,
    curso VARCHAR(50) NOT NULL,
    latitude DECIMAL (10, 8),
    longitude DECIMAL (11,8),
    id_ponto INT NOT NULL,
    CONSTRAINT fk_aluno_ponto FOREIGN KEY (id_ponto) REFERENCES ponto(id_ponto),
    CONSTRAINT chk_serie CHECK (serie IN (1, 2, 3)),
    CONSTRAINT chk_curso CHECK (curso IN ('Informatica', 'DS', 'Enfermagem', 'Administracao'))
);


-- 4. Tabela: rota
CREATE TABLE rota (
    id_rota INT AUTO_INCREMENT PRIMARY KEY,
    nome_rota VARCHAR(255) NOT NULL,
    motorista_m VARCHAR(255),
    motorista_t VARCHAR(255),
    status VARCHAR(50),
    status_tarde VARCHAR(50),
);


-- 5. Tabela: rota_ponto (Tabela intermediária N:M)
CREATE TABLE rota_ponto (
    id_rota INT NOT NULL,
    id_ponto INT NOT NULL,
    ordem INT NOT NULL,
    PRIMARY KEY (id_rota, id_ponto),
    CONSTRAINT fk_rota_ponto_rota FOREIGN KEY (id_rota) REFERENCES rota(id_rota) ON DELETE CASCADE,
    CONSTRAINT fk_rota_ponto_ponto FOREIGN KEY (id_ponto) REFERENCES ponto(id_ponto) ON DELETE CASCADE
);


-- 6. Tabela: relatorio_gerado
CREATE TABLE relatorio_gerado (
    id_relatorio INT AUTO_INCREMENT PRIMARY KEY,
    data_geracao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    nome_documento VARCHAR(255) NOT NULL
);

INSERT INTO `users`(`login`, `senha`) VALUES ('rotacerta321@gmail.com', SHA2('123', 256))