
USE heartbeasties;

ALTER TABLE produtos
    ADD COLUMN estoque INT NOT NULL DEFAULT 0 AFTER preco,
    ADD COLUMN vendas INT NOT NULL DEFAULT 0 AFTER estoque;

UPDATE produtos SET estoque = 9, vendas = 8 WHERE nome = 'Figura 1';
UPDATE produtos SET estoque = 14, vendas = 5 WHERE nome = 'Figura 2';
UPDATE produtos SET estoque = 2,  vendas = 1 WHERE nome = 'Figura 3';
