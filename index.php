<?php
    session_start();

?> 

<DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>heartbeasties</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>

 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


    <!-- nome do site -->

    <?php
        $nome = "heartbeasties";
        require 'includes/sobre.php';
        require 'includes/produtos.php';
        require 'includes/contato.php';
        require 'includes/termos.php';

        echo "<h1>Bem-vindo ao $nome!</h1>";
    ?>

    <!-- menu de navegação -->

    <section class="menu">
        <nav>
            <ul>
                <li><a href="#">Início</a></li>
                <li><a href="#">Sobre Nós</a></li>
                <li><a href="#">Produtos</a></li>
                <li><a href="#">Contato</a></li>
                <li><a href="#">Termos de Contrato</a></li>
                
            </ul>
        </nav>

    <!-- Deus oq eu to fazendo -->

    <h2> A melhor Loja Geek que voce vai conhecer.</h2>


    <p>Descubra as figures dos seus sonhos por um preco que cabe no bolso.</p>
    
    <section class="hero">

    <div class="container">
        <!-- Icone da loja -->
         <!-- recriado a partir do codigo antigo -->

         <div class="icone">
            <div class="preview-icone">
                <!-- bootstrap pra icone -->
                 <i class="bi bi-person-circle"></i>
            </div>
        </div>
    </div>

<div class="hero-content">

<!-- pagina de conteudo, sujeito a modificacao -->

    <h2> HEARTBEASTIES </h2>

    <p> Suas compras no precinho de um sonho ! </p>

</div>

</section>


<!-- produtos (question mark??)-->

<section class="produtos">

<!-- pai afasta de mim esse calice (a ponte) -->
<!-- eu no sabo oq estou fazendo pfv piedade em minha alma -->

    <div class="container">

        <div class="section-title">
            <span>PRODUTOS<span>
        </div>
        
        <div class="product-grid">
            


</body>
</html>

