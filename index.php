<?php
    session_start();

    require_once 'includes/header.php'; 
    require_once 'includes/nav.php'; 


?> 

 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


    <!-- nome do site -->

    <?php
        $nome = "Heartbeasties";

        echo "<h1 class='display-4 fw-bold text-center'>Bem-vindo ao $nome!</h1>";
    ?>

    <!-- Deus oq eu to fazendo -->
    
    <section class="hero bg-dark text-white text-center py-5 rounded-3 shadow-sm my-4">

    <div class="container">
        <!-- Icone da loja -->
         <!-- recriado a partir do codigo antigo -->

         <div class="icone mb-3">
            <div class="preview-icone">
                <!-- bootstrap pra icone -->
                 <i class="bi bi-person-circle display-1 text-danger"></i>
            </div>
        </div>
    </div>

<div class="hero-content">

<!-- pagina de conteudo, sujeito a modificacao -->

    <h2 class="display-4 fw-bold mb-2"> HEARTBEASTIES </h2>

    <p class="fw-semi-bold fs-4 text-warning"> Suas compras no precinho de um sonho ! </p>

<a href="produtos.php" class="btn btn-primary">
    Explorar Figures
</a>

</div>

</section>


<!-- produtos (question mark??)-->

<section class="produtos hb-pattern">

<!-- pai afasta de mim esse calice (a ponte) -->
<!-- eu no sabo oq estou fazendo pfv piedade da minha alma -->

    <div class="container my-5">

    
    <div class="section-title text-center mb-4">
        <span class="fw-bold fs-3 text-danger">PRODUTOS</span>
        <p class="fw-semibold fs-4 text-warning">Confira nossos produtos !!</p>
    </div>
    
   
    <div class="row g-4">

        <!-- Card 1 -->
        <div class="col-12 col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <span class="hb-tag hb-tag-accent">Mais vendido</span>
                <div class="hb-photo-frame">
                    <img src="imgs/figura1.jpg" alt="Figura 1">
                    <span class="hb-seal hb-seal-sm"><i class="bi bi-heart-fill"></i></span>
                </div>
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold">Figura 1</h5>
                    <p class="card-text text-muted">Descrição da Figura 1.</p>
                    <p class="card-text fw-bold text-danger fs-5">R$ 299,99</p>
                    <a href="#" class="btn btn-primary w-100">Comprar</a>
                </div>
            </div>
        </div> <!-- Fecha o Card 1 -->

        <!-- Card 2 -->
        <div class="col-12 col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="hb-photo-frame">
                    <img src="imgs/figura1.jpg" alt="Figura 2">
                    <span class="hb-seal hb-seal-sm"><i class="bi bi-heart-fill"></i></span>
                </div>
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold">Figura 2</h5>
                    <p class="card-text text-muted">Descrição da Figura 2.</p>
                    <p class="card-text fw-bold text-danger fs-5">R$ 199,99</p>
                    <a href="#" class="btn btn-primary w-100">Comprar</a>
                </div>
            </div>
        </div> <!-- fecha o Card 2 -->

        <!-- Card 3 -->
        <div class="col-12 col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <span class="hb-tag">Novo</span>
                <div class="hb-photo-frame">
                    <img src="imgs/figura1.jpg" alt="Figura 3">
                    <span class="hb-seal hb-seal-sm"><i class="bi bi-heart-fill"></i></span>
                </div>
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold">Figura 3</h5>
                    <p class="card-text text-muted">Descrição da Figura 3.</p>
                    <p class="card-text fw-bold text-danger fs-5">R$ 1.500,99</p>
                    <a href="#" class="btn btn-primary w-100">Comprar</a>
                </div>
            </div>
        </div>

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