<?php
require '../includes/db.php';

$pagina_admin = 'produtos';
$mensagem = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salvar'])) {

    $id = $_POST['id'] ?? '';
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $preco = $_POST['preco'] ?? '';
    $categoria_id = $_POST['categoria_id'] ?? '';

    // --- Validações básicas ---
    if ($nome === '' || $preco === '' || $categoria_id === '') {
        $erro = "Preencha nome, preço e categoria.";
    } elseif (!is_numeric($preco) || $preco < 0) {
        $erro = "O preço precisa ser um número válido (ex: 199.90).";
    } else {

        // --- Checa duplicidade de nome antes de salvar ---
        $sqlChecagem = "SELECT id FROM produtos WHERE nome = :nome";
        if ($id !== '') $sqlChecagem .= " AND id != :id";
        $stmt = $pdo->prepare($sqlChecagem);
        $stmt->bindValue(':nome', $nome);
        if ($id !== '') $stmt->bindValue(':id', $id);
        $stmt->execute();

        if ($stmt->fetch()) {
            $erro = "Já existe um produto chamado \"$nome\".";
        } else {
            try {
                if ($id === '') {
                    $stmt = $pdo->prepare(
                        "INSERT INTO produtos (nome, descricao, preco, categoria_id)
                         VALUES (:nome, :descricao, :preco, :categoria_id)"
                    );
                    $stmt->execute([
                        'nome' => $nome, 'descricao' => $descricao,
                        'preco' => $preco, 'categoria_id' => $categoria_id,
                    ]);
                    $mensagem = "Produto cadastrado com sucesso!";
                } else {
                    $stmt = $pdo->prepare(
                        "UPDATE produtos SET nome = :nome, descricao = :descricao,
                         preco = :preco, categoria_id = :categoria_id WHERE id = :id"
                    );
                    $stmt->execute([
                        'nome' => $nome, 'descricao' => $descricao,
                        'preco' => $preco, 'categoria_id' => $categoria_id, 'id' => $id,
                    ]);
                    $mensagem = "Produto atualizado com sucesso!";
                }
            } catch (PDOException $e) {
                $erro = "Não foi possível salvar: verifique os dados e tente novamente.";
            }
        }
    }
}

if (isset($_GET['excluir'])) {
    $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = :id");
    $stmt->execute(['id' => $_GET['excluir']]);
    $mensagem = "Produto excluído.";
}

$produtoEditando = null;
if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = :id");
    $stmt->execute(['id' => $_GET['editar']]);
    $produtoEditando = $stmt->fetch();
}

// Lista de categorias pro <select>
$categorias = $pdo->query("SELECT * FROM categorias ORDER BY nome")->fetchAll();

// Lista de produtos já com o nome da categoria (usando a VIEW)
$produtos = $pdo->query("SELECT * FROM vw_produtos_completo ORDER BY nome")->fetchAll();

require '_layout_topo.php';
?>

<h1 class="fw-bold mb-4"><i class="bi bi-box-seam-fill"></i> Produtos</h1>

<?php if ($mensagem): ?><div class="alert alert-success"><?php echo htmlspecialchars($mensagem); ?></div><?php endif; ?>
<?php if ($erro): ?><div class="alert alert-danger"><?php echo htmlspecialchars($erro); ?></div><?php endif; ?>

<?php if (empty($categorias)): ?>
    <div class="alert alert-warning">
        Cadastre pelo menos uma <a href="categorias.php">categoria</a> antes de adicionar produtos.
    </div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0 p-3">
            <h5 class="fw-bold mb-3"><?php echo $produtoEditando ? 'Editar produto' : 'Novo produto'; ?></h5>
            <form method="post">
                <input type="hidden" name="id" value="<?php echo $produtoEditando['id'] ?? ''; ?>">

                <div class="mb-3">
                    <label class="form-label">Nome</label>
                    <input type="text" name="nome" class="form-control" required
                        value="<?php echo htmlspecialchars($produtoEditando['nome'] ?? ''); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Descrição</label>
                    <textarea name="descricao" class="form-control" rows="2"><?php echo htmlspecialchars($produtoEditando['descricao'] ?? ''); ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Preço (R$)</label>
                    <input type="number" step="0.01" min="0" name="preco" class="form-control" required
                        value="<?php echo $produtoEditando['preco'] ?? ''; ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Categoria</label>
                    <select name="categoria_id" class="form-select" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>"
                                <?php echo (isset($produtoEditando['categoria_id']) && $produtoEditando['categoria_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" name="salvar" class="btn btn-primary w-100">
                    <?php echo $produtoEditando ? 'Salvar alterações' : 'Cadastrar'; ?>
                </button>
                <?php if ($produtoEditando): ?>
                    <a href="produtos.php" class="btn btn-outline-secondary w-100 mt-2">Cancelar edição</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="col-12 col-lg-8">
        <div class="card shadow-sm border-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nome</th>
                        <th>Categoria</th>
                        <th>Preço</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos as $p): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($p['nome']); ?></td>
                            <td><span class="badge bg-secondary"><?php echo htmlspecialchars($p['categoria_nome']); ?></span></td>
                            <td>R$ <?php echo number_format($p['preco'], 2, ',', '.'); ?></td>
                            <td class="text-end">
                                <a href="produtos.php?editar=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-fill"></i></a>
                                <a href="produtos.php?excluir=<?php echo $p['id']; ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Excluir o produto \'<?php echo htmlspecialchars($p['nome']); ?>\'?');">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($produtos)): ?>
                        <tr><td colspan="4" class="text-center text-muted py-4">Nenhum produto cadastrado ainda.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require '_layout_rodape.php'; ?>
