<?php

header('Content-Type: application/json; charset=utf-8');
require '../includes/db.php';

try {
    $sql = "SELECT p.id, p.nome, p.descricao, p.preco, p.estoque, p.vendas,
                   p.categoria_id, c.nome AS categoria_nome
            FROM produtos p
            INNER JOIN categorias c ON c.id = p.categoria_id
            ORDER BY p.nome";

    $produtos = $pdo->query($sql)->fetchAll();

    // Sempre devolve um formato previsível: { sucesso, produtos }
    echo json_encode([
        'sucesso' => true,
        'produtos' => $produtos,
    ]);

} catch (PDOException $e) {
    // Se o banco cair ou a query falhar, devolve um JSON de erro
    http_response_code(500);
    echo json_encode([
        'sucesso' => false,
        'erro' => 'Não foi possível carregar os produtos.',
    ]);
}