<?php
    function isActive($page) {
        if (basename($_SERVER['PHP_SELF']) == $page) {
            return "activemenu";
        }
    }
    //nav contents:
    $labels = ['Reserva', 'Pratos', 'Pedido',"Clientes"];
    $mainFiles = ['reservas.php', 'pratos.php', 'pedidos.php', "clientes.php"];
    $listFiles = ['listaReservas.php', 'listaPratos.php', 'listaPedidos.php', "listaClientes.php"];


    if(isset($_SESSION['utilizador']) && $_SESSION['tipo'] == 1){
?>

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="src/img/logo.png" id="logo">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <?php
                foreach ($labels as $index => $label) {
                    $mainFile = $mainFiles[$index];
                    $listFile = $listFiles[$index];
                    ?>
                    <li class="nav-item dropdown <?= isActive($mainFile) ?> <?= isActive($listFile) ?>">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= $label ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item <?= isActive($mainFile) ?>" href="<?= $mainFile ?>">Adicionar</a></li>
                            <li><a class="dropdown-item <?= isActive($listFile) ?>" href="<?= $listFile ?>">Listar</a></li>
                        </ul>
                    </li>
                <?php } ?>
                <li class="nav-item <?= isActive('main.php') ?>">
                    <a class="nav-link" href="main.php" role="button" aria-expanded="false">Profile</a>
                </li>
            </ul>
        </div>

    </div>
</nav>

<?php 
} else {
?>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="src/img/logo.png">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <?php
                foreach ($labels as $index => $label) {
                    $mainFile = $mainFiles[$index];
                    $listFile = $listFiles[$index];
                    ?>
                    <li class="nav-item dropdown <?= isActive($mainFile) ?> <?= isActive($listFile) ?>">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= $label ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item <?= isActive($mainFile) ?>" href="<?= $mainFile ?>">Adicionar</a></li>
                            <li><a class="dropdown-item <?= isActive($listFile) ?>" href="<?= $listFile ?>">Listar</a></li>
                        </ul>
                    </li>
                <?php } ?>
                <li class="nav-item <?= isActive('main.php') ?>">
                    <a class="nav-link" href="main.php" role="button" aria-expanded="false">Profile</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<?php    
}
?>

<div style="display: none;">
    Current Page: <?= basename($_SERVER['PHP_SELF']) ?>
</div>





