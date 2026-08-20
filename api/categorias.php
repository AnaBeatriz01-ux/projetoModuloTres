<?php
header('Content-Type: application/json; charset=utf-8');
require '../includes/db.php';

try {
    $categorias = $pdo->query("SELECT id, nome FROM categorias ORDER BY nome")->fetchAll();

    echo json_encode([
        'sucesso' => true,
        'categorias' => $categorias,
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'sucesso' => false,
        'erro' => 'Não foi possível carregar as categorias.',
    ]);
}