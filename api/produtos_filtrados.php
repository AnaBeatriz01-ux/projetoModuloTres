<?php
/**Chama a stored procedure sp_produtos_filtrados
 
 * Parâmetros (todos opcionais, via GET):
 *   categoria_id, preco_min, preco_max
 * Se não vier nenhum, a procedure devolve tudo,já trata isso
 * com "IS NULL OR ..." dentro do WHERE.
 */

header('Content-Type: application/json; charset=utf-8');
require '../includes/db.php';

$categoriaId = isset($_GET['categoria_id']) && $_GET['categoria_id'] !== ''
    ? (int) $_GET['categoria_id']
    : null;

$precoMin = isset($_GET['preco_min']) && $_GET['preco_min'] !== ''
    ? (float) $_GET['preco_min']
    : null;

$precoMax = isset($_GET['preco_max']) && $_GET['preco_max'] !== ''
    ? (float) $_GET['preco_max']
    : null;

try {
    $stmt = $pdo->prepare('CALL sp_produtos_filtrados(:categoria_id, :preco_min, :preco_max)');
    $stmt->bindValue(':categoria_id', $categoriaId, $categoriaId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $stmt->bindValue(':preco_min', $precoMin, $precoMin === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':preco_max', $precoMax, $precoMax === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->execute();

    $produtos = $stmt->fetchAll();
    $stmt->closeCursor(); // mesmo cuidado do relatorio.php — evita travar caso essa API cresça e passe a rodar outra query depois

    echo json_encode([
        'sucesso' => true,
        'produtos' => $produtos,
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'sucesso' => false,
        'erro' => 'Não foi possível filtrar os produtos.',
    ]);
}