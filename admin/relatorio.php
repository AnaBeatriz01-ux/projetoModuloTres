<?php
require '../includes/db.php';

$pagina_admin = 'relatorio';

// Filtros vindos do formulário (GET, pra poder compartilhar o link)
$filtro_categoria = $_GET['categoria_id'] ?? '';
$filtro_preco_min = $_GET['preco_min'] ?? '';
$filtro_preco_max = $_GET['preco_max'] ?? '';

// A stored procedure espera NULL quando o filtro não for usado
$p_categoria = $filtro_categoria !== '' ? $filtro_categoria : null;
$p_min = $filtro_preco_min !== '' ? $filtro_preco_min : null;
$p_max = $filtro_preco_max !== '' ? $filtro_preco_max : null;

$stmt = $pdo->prepare("CALL sp_produtos_filtrados(:cat, :min, :max)");
$stmt->bindValue(':cat', $p_categoria, $p_categoria === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
$stmt->bindValue(':min', $p_min);
$stmt->bindValue(':max', $p_max);
$stmt->execute();
$resultado = $stmt->fetchAll();

// Total geral, pra mostrar no topo do relatório
$valorTotal = array_sum(array_column($resultado, 'preco'));

$categorias = $pdo->query("SELECT * FROM categorias ORDER BY nome")->fetchAll();

require '_layout_topo.php';
?>

<h1 class="fw-bold mb-4"><i class="bi bi-bar-chart-fill"></i> Relatório de Produtos</h1>

<div class="card shadow-sm border-0 p-3 mb-4">
    <form method="get" class="row g-3 align-items-end">
        <div class="col-12 col-md-4">
            <label class="form-label">Categoria</label>
            <select name="categoria_id" class="form-select">
                <option value="">Todas</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php echo $filtro_categoria == $cat['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['nome']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-6 col-md-3">
            <label class="form-label">Preço mínimo</label>
            <input type="number" step="0.01" name="preco_min" class="form-control" value="<?php echo htmlspecialchars($filtro_preco_min); ?>">
        </div>
        <div class="col-6 col-md-3">
            <label class="form-label">Preço máximo</label>
            <input type="number" step="0.01" name="preco_max" class="form-control" value="<?php echo htmlspecialchars($filtro_preco_max); ?>">
        </div>
        <div class="col-12 col-md-2">
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel-fill"></i> Filtrar</button>
        </div>
    </form>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card shadow-sm border-0 p-3 text-center">
            <div class="fs-3 fw-bold text-danger"><?php echo count($resultado); ?></div>
            <div class="text-muted small">produtos encontrados</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow-sm border-0 p-3 text-center">
            <div class="fs-3 fw-bold text-danger">R$ <?php echo number_format($valorTotal, 2, ',', '.'); ?></div>
            <div class="text-muted small">valor total somado</div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <table class="table table-hover mb-0 align-middle">
        <thead class="table-light">
            <tr><th>Nome</th><th>Categoria</th><th>Preço</th></tr>
        </thead>
        <tbody>
            <?php foreach ($resultado as $r): ?>
                <tr>
                    <td><?php echo htmlspecialchars($r['nome']); ?></td>
                    <td><span class="badge bg-secondary"><?php echo htmlspecialchars($r['categoria_nome']); ?></span></td>
                    <td>R$ <?php echo number_format($r['preco'], 2, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($resultado)): ?>
                <tr><td colspan="3" class="text-center text-muted py-4">Nenhum produto encontrado com esses filtros.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require '_layout_rodape.php'; ?>
