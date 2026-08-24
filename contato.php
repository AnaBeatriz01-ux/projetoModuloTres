<?php
    session_start();

    require_once 'includes/header.php';
    require_once 'includes/nav.php';
?>

<div class="container my-5 container-narrow-sm">

    <div class="section-title text-center mb-4">
        <h1 class="fw-bold">FALE COM A GENTE</h1>
        <p class="fw-semibold fs-5 text-warning">Dúvidas, sugestões ou só quer dar um oi 💌</p>
    </div>

    <form action="#" method="post" class="card shadow-sm border-0 p-4">
        <div class="mb-3">
            <label for="nome" class="form-label fw-semibold">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">E-mail</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="mensagem" class="form-label fw-semibold">Mensagem</label>
            <textarea class="form-control" id="mensagem" name="mensagem" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-send-fill me-1"></i> Enviar
        </button>
    </form>

</div>

<?php
    require_once 'includes/footer.php';
?>