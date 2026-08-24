<?php
// isso aqui é só pra destacar "Dashboard" certo no menu lateral
$pagina_admin = 'dashboard';
require '_layout_topo.php';
?>

<h1 class="fw-bold mb-1"><i class="bi bi-speedometer2"></i> Painel de Controle</h1>
<p class="text-muted mb-4">
    Faturamento calculado em TypeScript (preço × vendas, via reduce),
    a partir do array que a API manda.
</p>

<!-- esse alerta começa escondido (d-none), só aparece se o fetch falhar -->

<div id="dashboard-erro" class="alert alert-danger d-none">
    <i class="bi bi-exclamation-triangle-fill"></i>
    Não foi possível carregar os dados agora. Confere se o Apache e o
    MySQL tão rodando no XAMPP e tenta de novo.
</div>

<!-- os cards ficam "Carregando" até o JS trocar pelo valor real -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="card shadow-sm border-0 p-3 text-center">
            <div class="fs-3 fw-bold text-danger" id="card-total-produtos">Carregando...</div>
            <div class="text-muted small">produtos cadastrados</div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="card shadow-sm border-0 p-3 text-center">
            <div class="fs-3 fw-bold text-danger" id="card-faturamento">Carregando...</div>
            <div class="text-muted small">faturamento total (preço × vendas)</div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <table class="table table-hover mb-0 align-middle">
        <thead class="table-light">
            <tr>
                <th>Produto</th>
                <th>Categoria</th>
                <th>Preço</th>
                <th>Vendas</th>
                <th>Faturamento</th>
            </tr>
        </thead>
        <!-- tbody vazio de propósito, quem preenche é o JS -->
        <tbody id="tabela-produtos-corpo">
            <tr><td colspan="5" class="text-center text-muted py-4">Carregando...</td></tr>
        </tbody>
    </table>
</div>

<!-- aponta pro js já compilado, não pro .ts (o navegador não entende
     typescript). lembrar de rodar "npx tsc" de novo depois de mexer
     no dashboard.ts -->
<script src="js/dashboard.js"></script>

<?php require '_layout_rodape.php'; ?>
