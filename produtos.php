<?php
    session_start();

    require_once 'includes/header.php';
    require_once 'includes/nav.php';

    // Lista de produtos (depois isso vem do banco de dados)
    $produtos = [
        ["nome" => "Figura 1", "descricao" => "Descrição da Figura 1.", "preco" => 299.99, "img" => "imgs/figura1.jpg"],
        ["nome" => "Figura 2", "descricao" => "Descrição da Figura 2.", "preco" => 199.99, "img" => "imgs/figura1.jpg"],
        ["nome" => "Figura 3", "descricao" => "Descrição da Figura 3.", "preco" => 1500.99, "img" => "imgs/figura1.jpg"],
    ];
?>

<div class="container my-5 hb-pattern">

    <div class="section-title text-center mb-4">
        <h1 class="fw-bold">NOSSOS PRODUTOS</h1>
        <p class="fw-semibold fs-5 text-warning">Confira toda a nossa coleção!</p>
    </div>

    <div class="row g-4">
        <?php foreach ($produtos as $i => $produto): ?>
            <div class="col-12 col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <?php if ($i === 0): ?>
                        <span class="hb-tag hb-tag-accent">Mais vendido</span>
                    <?php endif; ?>
                    <div class="hb-photo-frame">
                        <img src="<?php echo $produto['img']; ?>" alt="<?php echo $produto['nome']; ?>">
                        <span class="hb-seal hb-seal-sm"><i class="bi bi-heart-fill"></i></span>
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold"><?php echo $produto['nome']; ?></h5>
                        <p class="card-text text-muted"><?php echo $produto['descricao']; ?></p>
                        <p class="card-text fw-bold text-danger fs-5">
                            R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                        </p>
                        <a href="#" class="btn btn-primary w-100">Comprar</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<section class="promo-strip">
    <div class="container">
        <p>💛 Feito com carinho pra colecionadores — frete grátis nas compras acima de R$ 200</p>
    </div>
</section>

<?php
    require_once 'includes/footer.php';
?>