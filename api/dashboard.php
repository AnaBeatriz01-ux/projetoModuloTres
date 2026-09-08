<?php

header('Content-Type: application/json; charset=utf-8');
require '../includes/db.php';

try {
    $sql = "SELECT id, nome, preco, estoque, vendas, imagem, categoria_nome, status_estoque
            FROM vw_dashboard_analitico";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $produtos = $stmt->fetchAll();

    http_response_code(200);

    // mando o array direto, sem embrulhar em nada tipo {sucesso: true}
    // pq o reduce() do TS precisa do array cru pra fazer a conta de
    // preco x vendas ele mesmo, não quero mandar já calculado daqui
    echo json_encode($produtos, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    // se o banco cair ou a view não existir mais por algum motivo,
    // devolve isso em vez de estourar um erro feio de PHP em HTML
    // (o que quebraria o fetch().json() lá do TS)
    http_response_code(500);
    echo json_encode(['error' => 'Não foi possível carregar os dados do dashboard.']);
}