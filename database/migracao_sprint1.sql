
USE heartbeasties;


CREATE OR REPLACE VIEW vw_dashboard_analitico AS

    WITH produtos_consolidados AS (
        SELECT
            p.id,
            p.nome,
            p.preco,
            p.estoque,
            p.vendas,
            c.nome AS categoria_nome
        FROM produtos p
        INNER JOIN categorias c ON c.id = p.categoria_id
    )

    SELECT id, nome, preco, estoque, vendas, categoria_nome
    FROM produtos_consolidados
    ORDER BY nome;


DELIMITER $$

CREATE TRIGGER trg_produtos_valores_positivos
    BEFORE UPDATE ON produtos
    FOR EACH ROW
BEGIN

    IF NEW.preco < 0 THEN
        SET NEW.preco = ABS(NEW.preco);
    END IF;

    IF NEW.estoque < 0 THEN
        SET NEW.estoque = ABS(NEW.estoque);
    END IF;

    IF NEW.vendas < 0 THEN
        SET NEW.vendas = ABS(NEW.vendas);
    END IF;

END$$

DELIMITER ;
