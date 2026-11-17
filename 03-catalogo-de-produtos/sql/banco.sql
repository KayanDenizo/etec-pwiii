CREATE DATABASE IF NOT EXISTS loja_etim;
USE loja_etim;

CREATE TABLE IF NOT EXISTS produtos(
    id_produto int AUTO_INCREMENT PRIMARY KEY,
    nome_produto varchar(100),
    descricao text,
    valor decimal(10,2)
);

CREATE TABLE IF NOT EXISTS imagens(
    id_imagem int AUTO_INCREMENT PRIMARY KEY,
    nome_imagem varchar(100),
    fk_id_produto int,
    FOREIGN KEY (fk_id_produto) REFERENCES produtos(id_produto)
);

CREATE TABLE IF NOT EXISTS usuarios(
    id_usuario int AUTO_INCREMENT PRIMARY KEY,
    nome varchar(100) NOT NULL,
    email varchar(150) NOT NULL UNIQUE,
    senha varchar(255) NOT NULL
);

-- Usuário inicial para o primeiro login
-- e-mail: admin@etim.com | senha: admin123
-- (a senha fica gravada como hash gerado pelo password_hash do PHP)
INSERT IGNORE INTO usuarios (nome, email, senha) VALUES
('Administrador', 'admin@etim.com', '$2y$10$U0VWcCoaMoVNgIoDg1RqlOTRQSEk2sf6JCG4lZ4BF5VxCANAv4tMO');
