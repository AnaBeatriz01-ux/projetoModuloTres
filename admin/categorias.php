<?php
require '../includes/db.php';

$pagina_admin = 'categorias';
$mensagem = '';
$erro = '';


// CREATE (cadastrar) ou UPDATE (editar

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salvar'])) {

    $id = $_POST['id'] ?? '';
    $nome = trim($_POST['nome'] ?? '');

    if ($nome === '') {
        $erro = "O nome da categoria não pode ficar vazio.";
    } else {

        // ---  verifica se já existe uma
        // categoria com esse nome antes de salvar ---
        $sqlChecagem = "SELECT id FROM categorias WHERE nome = :nome";
        // Se estamos editando, ignora a própria categoria na checagem
        if ($id !== '') {
            $sqlChecagem .= " AND id != :id";
        }
        $stmt = $pdo->prepare($sqlChecagem);
        $stmt->bindValue(':nome', $nome);
        if ($id !== '') {
            $stmt->bindValue(':id', $id);
        }
        $stmt->execute();
        $jaExiste = $stmt->fetch();

        if ($jaExiste) {
            $erro = "Já existe uma categoria chamada \"$nome\".";
        } else {
            try {
                if ($id === '') {
                    // CREATE
                    $stmt = $pdo->prepare("INSERT INTO categorias (nome) VALUES (:nome)");
                    $stmt->execute(['nome' => $nome]);
                    $mensagem = "Categoria cadastrada com sucesso!";
                } else {
                    // UPDATE
                    $stmt = $pdo->prepare("UPDATE categorias SET nome = :nome WHERE id = :id");
                    $stmt->execute(['nome' => $nome, 'id' => $id]);
                    $mensagem = "Categoria atualizada com sucesso!";
                }
            } catch (PDOException $e) {
                // Segunda camada de proteção: se por acaso passou da
                // checagem acima mas o banco tem UNIQUE KEY, cai aqui.
                $erro = "Não foi possível salvar: já existe uma categoria com esse nome.";
            }
        }
    }
}


// DELETE

if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    try {
        $stmt = $pdo->prepare("DELETE FROM categorias WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $mensagem = "Categoria excluída.";
    } catch (PDOException $e) {
        // Cai aqui se algum produto ainda usa essa categoria
        // (a FOREIGN KEY ... ON DELETE RESTRICT impede a exclusão)
        $erro = "Não é possível excluir: existem produtos cadastrados nessa categoria.";
    }
}


// Se veio "?editar=ID" na URL, carrega os dados pra preencher
// o formulário no modo edição

$categoriaEditando = null;
if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare("SELECT * FROM categorias WHERE id = :id");
    $stmt->execute(['id' => $_GET['editar']]);
    $categoriaEditando = $stmt->fetch();
}


// READ (listar tudo, mais recente primeiro)

$categorias = $pdo->query("SELECT * FROM categorias ORDER BY nome")->fetchAll();

require '_layout_topo.php';
?>

<h1 class="fw-bold mb-4"><i class="bi bi-tags-fill"></i> Categorias</h1>

<?php if ($mensagem): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($mensagem); ?></div>
<?php endif; ?>
<?php if ($erro): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($erro); ?></div>
<?php endif; ?>

<div class="row g-4">
    <!-- Formulário de cadastro/edição -->
    <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0 p-3">
            <h5 class="fw-bold mb-3">
                <?php echo $categoriaEditando ? 'Editar categoria' : 'Nova categoria'; ?>
            </h5>
            <form method="post">
                <input type="hidden" name="id" value="<?php echo $categoriaEditando['id'] ?? ''; ?>">
                <div class="mb-3">
                    <label class="form-label">Nome</label>
                    <input type="text" name="nome" class="form-control" required
                        value="<?php echo htmlspecialchars($categoriaEditando['nome'] ?? ''); ?>">
                </div>
                <button type="submit" name="salvar" class="btn btn-primary w-100">
                    <?php echo $categoriaEditando ? 'Salvar alterações' : 'Cadastrar'; ?>
                </button>
                <?php if ($categoriaEditando): ?>
                    <a href="categorias.php" class="btn btn-outline-secondary w-100 mt-2">Cancelar edição</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Listagem -->
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm border-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Criada em</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categorias as $c): ?>
                        <tr>
                            <td><?php echo $c['id']; ?></td>
                            <td><?php echo htmlspecialchars($c['nome']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($c['criado_em'])); ?></td>
                            <td class="text-end">
                                <a href="categorias.php?editar=<?php echo $c['id']; ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <a href="categorias.php?excluir=<?php echo $c['id']; ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Tem certeza que quer excluir a categoria \'<?php echo htmlspecialchars($c['nome']); ?>\'? Essa ação não pode ser desfeita.');">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($categorias)): ?>
                        <tr><td colspan="4" class="text-center text-muted py-4">Nenhuma categoria cadastrada ainda.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require '_layout_rodape.php'; ?>

