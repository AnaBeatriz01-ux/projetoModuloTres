
CREATE DATABASE IF NOT EXISTS heartbeasties
    CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE heartbeasties;

-- ------------------------------------------------------------
-- Tabela: categorias  (CRUD 1)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(80) NOT NULL,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_categoria_nome (nome)
);

-- ------------------------------------------------------------
-- Tabela: produtos  (CRUD 2)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    descricao VARCHAR(255) DEFAULT '',
    preco DECIMAL(10,2) NOT NULL,
    categoria_id INT NOT NULL,
    imagem VARCHAR(255) DEFAULT 'imgs/figura1.jpg',
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uq_produto_nome (nome),

    CONSTRAINT fk_produto_categoria
        FOREIGN KEY (categoria_id) REFERENCES categorias(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

-- ------------------------------------------------------------
-- Tabela: clientes  (CRUD 3)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefone VARCHAR(20) DEFAULT '',
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,

    -- Impede dois clientes cadastrados com o mesmo e-mail
    UNIQUE KEY uq_cliente_email (email)
);

-- ------------------------------------------------------------
-- Tabela: log_exclusoes
-- Guarda um registro de tudo que foi apagado, com data/hora.
-- Alimentada automaticamente pelos triggers abaixo.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS log_exclusoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tabela VARCHAR(40) NOT NULL,
    registro_id INT NOT NULL,
    descricao VARCHAR(255),
    excluido_em DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- Triggers: toda vez que um produto ou cliente for
-- deletado, registra automaticamente no log_exclusoes.
-- ------------------------------------------------------------
DELIMITER $$

CREATE TRIGGER trg_produto_excluido
AFTER DELETE ON produtos
FOR EACH ROW
BEGIN
    INSERT INTO log_exclusoes (tabela, registro_id, descricao)
    VALUES ('produtos', OLD.id, OLD.nome);
END$$

CREATE TRIGGER trg_cliente_excluido
AFTER DELETE ON clientes
FOR EACH ROW
BEGIN
    INSERT INTO log_exclusoes (tabela, registro_id, descricao)
    VALUES ('clientes', OLD.id, CONCAT(OLD.nome, ' (', OLD.email, ')'));
END$$

DELIMITER ;

-- ------------------------------------------------------------
-- View: vw_produtos_completo
-- Junta produto + nome da categoria, pra não precisar
-- repetir esse JOIN em toda página que lista produtos.
-- ------------------------------------------------------------
CREATE OR REPLACE VIEW vw_produtos_completo AS
SELECT
    p.id,
    p.nome,
    p.descricao,
    p.preco,
    p.imagem,
    p.categoria_id,
    c.nome AS categoria_nome
FROM produtos p
INNER JOIN categorias c ON c.id = p.categoria_id;

-- ------------------------------------------------------------
-- Stored Procedure: sp_produtos_filtrados
-- Usada no relatório com filtros (categoria e faixa de preço).
-- Passar NULL num parâmetro = "não filtrar por esse campo".
-- ------------------------------------------------------------
DELIMITER $$

CREATE PROCEDURE sp_produtos_filtrados(
    IN p_categoria_id INT,
    IN p_preco_min DECIMAL(10,2),
    IN p_preco_max DECIMAL(10,2)
)
BEGIN
    SELECT *
    FROM vw_produtos_completo
    WHERE (p_categoria_id IS NULL OR categoria_id = p_categoria_id)
      AND (p_preco_min IS NULL OR preco >= p_preco_min)
      AND (p_preco_max IS NULL OR preco <= p_preco_max)
    ORDER BY nome;
END$$

DELIMITER ;

-- ------------------------------------------------------------
-- Dados iniciais 
-- ------------------------------------------------------------
INSERT INTO categorias (nome) VALUES
    ('Figures'), ('Pixel Art'), ('Acessórios')
ON DUPLICATE KEY UPDATE nome = nome;

INSERT INTO produtos (nome, descricao, preco, categoria_id, imagem) VALUES
    ('Figura 1', 'Descrição da Figura 1.', 299.99, 1, 'imgs/figura1.jpg'),
    ('Figura 2', 'Descrição da Figura 2.', 199.99, 1, 'imgs/figura1.jpg'),
    ('Figura 3', 'Descrição da Figura 3.', 1500.99, 1, 'imgs/figura1.jpg')
ON DUPLICATE KEY UPDATE nome = nome;
