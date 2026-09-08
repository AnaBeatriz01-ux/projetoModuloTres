<?php
    session_start();
    require '_layout_topo.php';
?>

<h1 class="fw-bold mb-1"><i class="bi bi-speedometer2"></i> Painel de Controle</h1>
<p class="text-muted mb-4">Dados calculados em TypeScript a partir do que a API devolve.</p>

<div id="dashboard-erro" class="alert alert-danger d-none">
    Não foi possível carregar os dados do dashboard. Tente novamente em instantes.
</div>

<h2 class="fs-5 fw-bold mb-1"><i class="bi bi-star-fill text-warning"></i> Destaques</h2>
<p class="text-muted small mb-3" id="categoria-destaque-texto"></p>
<div class="row g-3 mb-4" id="destaques-container">
    <div class="col-12 text-muted small">Carregando destaques...</div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="card shadow-sm border-0 p-3 text-center">
            <div class="fs-3 fw-bold text-danger" id="card-total-produtos">-</div>
            <div class="text-muted small">produtos cadastrados</div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="card shadow-sm border-0 p-3 text-center">
            <div class="fs-3 fw-bold text-danger" id="card-faturamento">-</div>
            <div class="text-muted small">faturamento total</div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h2 class="fs-5 fw-bold mb-0">Todos os produtos</h2>
    <div style="min-width: 220px;">
        <select id="filtro-categoria" class="form-select form-select-sm">
            <option value="">Todas as categorias</option>
        </select>
    </div>
</div>

<div class="card shadow-sm border-0">
    <table class="table table-hover mb-0 align-middle">
        <thead class="table-light">
            <tr>
                <th>Produto</th>
                <th>Categoria</th>
                <th>Status do estoque</th>
                <th>Preço</th>
                <th>Vendas</th>
                <th>Faturamento</th>
            </tr>
        </thead>
        <!-- tbody vazio de propósito, quem preenche é o JS -->
        <tbody id="tabela-produtos-corpo">
            <tr><td colspan="6" class="text-center text-muted py-4">Carregando...</td></tr>
        </tbody>
    </table>
</div>

<!-- Modal com detalhes do produto — abre ao clicar numa linha da tabela -->
<div class="modal fade" id="modal-detalhes-produto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-produto-nome">Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <p class="mb-1"><strong>Categoria:</strong> <span id="modal-produto-categoria"></span></p>
                <p class="mb-1"><strong>Status do estoque:</strong> <span id="modal-produto-status"></span></p>
                <p class="mb-1"><strong>Preço:</strong> <span id="modal-produto-preco"></span></p>
                <p class="mb-1"><strong>Vendas:</strong> <span id="modal-produto-vendas"></span></p>
                <p class="mb-0"><strong>Faturamento:</strong> <span id="modal-produto-faturamento"></span></p>
            </div>
        </div>
    </div>
</div>

<!-- aponta pro js já compilado, não pro .ts (o navegador não entende
     typescript). lembrar de rodar "npx tsc" de novo depois de mexer
     no dashboard.ts -->
<script src="js/dashboard.js"></script>

<?php require '_layout_rodape.php'; ?>