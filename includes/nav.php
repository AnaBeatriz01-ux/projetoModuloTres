<?php
    // Descobre em qual página estamo
    $pagina_atual = basename($_SERVER['PHP_SELF']);

    function nav_ativo($pagina, $atual) {
        return $pagina === $atual ? 'active fw-bold' : '';
    }
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm sticky-top border-bottom border-danger-subtle">
    <div class="container">

        <a class="navbar-brand fw-bold text-danger fs-4" href="index.php">
            <i class="bi bi-heart-fill me-1"></i> Heartbeasties
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#menuNavbar" aria-controls="menuNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>


        <div class="collapse navbar-collapse" id="menuNavbar">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 fw-semibold text-center">
                <li class="nav-item">
                    <a class="nav-link text-secondary <?php echo nav_ativo('index.php', $pagina_atual); ?>" href="index.php">🌸 Início</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-secondary <?php echo nav_ativo('produtos.php', $pagina_atual); ?>" href="produtos.php">🛍️ Produtos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-secondary <?php echo nav_ativo('sobre.php', $pagina_atual); ?>" href="sobre.php">🦊 Sobre Nós</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-secondary <?php echo nav_ativo('contato.php', $pagina_atual); ?>" href="contato.php">💌 Contato</a>
                </li>
            </ul>

            <div class="d-flex justify-content-center mt-2 mt-lg-0">
                <a href="produtos.php" class="btn btn-outline-danger rounded-pill px-3 fw-bold shadow-sm">
                    <i class="bi bi-bag-heart-fill me-1"></i> Ver Mimos
                </a>
            </div>
        </div>

    </div>
</nav>
