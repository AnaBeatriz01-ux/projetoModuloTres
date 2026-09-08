<?php
require '../includes/db.php';

$pagina_admin = 'clientes';
$mensagem = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salvar'])) {

    $id = $_POST['id'] ?? '';
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');

    if ($nome === '' || $email === '') {
        $erro = "Nome e e-mail são obrigatórios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Digite um e-mail válido.";
    } else {

        //  Checa se já existe cliente com esse e-mail 
        $sqlChecagem = "SELECT id FROM clientes WHERE email = :email";
        if ($id !== '') $sqlChecagem .= " AND id != :id";
        $stmt = $pdo->prepare($sqlChecagem);
        $stmt->bindValue(':email', $email);
        if ($id !== '') $stmt->bindValue(':id', $id);
        $stmt->execute();

        if ($stmt->fetch()) {
            $erro = "Já existe um cliente cadastrado com o e-mail \"$email\".";
        } else {
            try {
                if ($id === '') {
                    $stmt = $pdo->prepare(
                        "INSERT INTO clientes (nome, email, telefone) VALUES (:nome, :email, :telefone)"
                    );
                    $stmt->execute(['nome' => $nome, 'email' => $email, 'telefone' => $telefone]);
                    $mensagem = "Cliente cadastrado com sucesso!";
                } else {
                    $stmt = $pdo->prepare(
                        "UPDATE clientes SET nome = :nome, email = :email, telefone = :telefone WHERE id = :id"
                    );
                    $stmt->execute(['nome' => $nome, 'email' => $email, 'telefone' => $telefone, 'id' => $id]);
                    $mensagem = "Cliente atualizado com sucesso!";
                }
            } catch (PDOException $e) {
                $erro = "Não foi possível salvar: e-mail já cadastrado.";
            }
        }
    }
}

if (isset($_GET['excluir'])) {
    $stmt = $pdo->prepare("DELETE FROM clientes WHERE id = :id");
    $stmt->execute(['id' => $_GET['excluir']]);
    $mensagem = "Cliente excluído.";
}

$clienteEditando = null;
if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = :id");
    $stmt->execute(['id' => $_GET['editar']]);
    $clienteEditando = $stmt->fetch();
}

$clientes = $pdo->query("SELECT * FROM clientes ORDER BY nome")->fetchAll();

require '_layout_topo.php';
?>

<h1 class="fw-bold mb-4"><i class="bi bi-people-fill"></i> Clientes</h1>

<?php if ($mensagem): ?><div class="alert alert-success"><?php echo htmlspecialchars($mensagem); ?></div><?php endif; ?>
<?php if ($erro): ?><div class="alert alert-danger"><?php echo htmlspecialchars($erro); ?></div><?php endif; ?>

<div class="row g-4">
    <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0 p-3">
            <h5 class="fw-bold mb-3"><?php echo $clienteEditando ? 'Editar cliente' : 'Novo cliente'; ?></h5>
            <form method="post">
                <input type="hidden" name="id" value="<?php echo $clienteEditando['id'] ?? ''; ?>">
                <div class="mb-3">
                    <label class="form-label">Nome</label>
                    <input type="text" name="nome" class="form-control" required
                        value="<?php echo htmlspecialchars($clienteEditando['nome'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">E-mail</label>
                    <input type="email" name="email" class="form-control" required
                        value="<?php echo htmlspecialchars($clienteEditando['email'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Telefone</label>
                    <input type="text" name="telefone" class="form-control"
                        value="<?php echo htmlspecialchars($clienteEditando['telefone'] ?? ''); ?>">
                </div>
                <button type="submit" name="salvar" class="btn btn-primary w-100">
                    <?php echo $clienteEditando ? 'Salvar alterações' : 'Cadastrar'; ?>
                </button>
                <?php if ($clienteEditando): ?>
                    <a href="clientes.php" class="btn btn-outline-secondary w-100 mt-2">Cancelar edição</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="col-12 col-lg-8">
        <div class="card shadow-sm border-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr><th>Nome</th><th>E-mail</th><th>Telefone</th><th class="text-end">Ações</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $c): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($c['nome']); ?></td>
                            <td><?php echo htmlspecialchars($c['email']); ?></td>
                            <td><?php echo htmlspecialchars($c['telefone'] ?: '—'); ?></td>
                            <td class="text-end">
                                <a href="clientes.php?editar=<?php echo $c['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-fill"></i></a>
                                <a href="clientes.php?excluir=<?php echo $c['id']; ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Excluir o cliente \'<?php echo htmlspecialchars($c['nome']); ?>\'?');">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($clientes)): ?>
                        <tr><td colspan="4" class="text-center text-muted py-4">Nenhum cliente cadastrado ainda.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require '_layout_rodape.php'; ?>