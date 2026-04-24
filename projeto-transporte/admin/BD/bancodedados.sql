-- Criar o banco de dados
CREATE DATABASE IF NOT EXISTS login_transporte;
USE login_transporte;

-- Criar a tabela de usuários
CREATE TABLE IF NOT EXISTS users (
    id_usuario INT NOT NULL AUTO_INCREMENT,
    login VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(120) NOT NULL,
    PRIMARY KEY (id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Garante que estamos usando o banco correto
USE login_transporte;

--Nova Tabela unitária para o usuário ADMIN
CREATE TABLE IF NOT EXISTS admin (
    id_admin INT NOT NULL AUTO_INCREMENT,
    login VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(120) NOT NULL,
    PRIMARY KEY (id_admin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO admin (login, senha) VALUES ('admin.stge20@seduc.ce.gov.br', MD5('eeep-adm-stge-seduc')); --eeep-adm-stge-seduc é a senha

CREATE TABLE IF NOT EXISTS ponto (
    id_ponto INT NOT NULL AUTO_INCREMENT,
    nome_ponto VARCHAR(100) NOT NULL,
    latitude DECIMAL(10, 8) NOT NULL,
    longitude DECIMAL(11, 8) NOT NULL,
    PRIMARY KEY (id_ponto) -- Corrigido para o ID da tabela
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS aluno (
    id_aluno INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    serie INT NOT NULL,
    endereco VARCHAR(100) NOT NULL,
    curso VARCHAR(100),
    id_ponto INT, -- Criada a coluna para armazenar a chave
    PRIMARY KEY (id_aluno), -- Corrigido para o ID da tabela
    CONSTRAINT fk_aluno_ponto FOREIGN KEY (id_ponto) REFERENCES ponto(id_ponto)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
