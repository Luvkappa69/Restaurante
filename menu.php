<div class="container mt-5">
    <div class="row col-2">
        <h2>Nome de Utilizador</h2>
        <h3><?php echo($_SESSION['utilizador']);?></h3>
        <img class="img-thumbnail img-size" src="<?php echo($_SESSION['foto']);?>">
    </div>
    <div class="row">
        <div class="col-1">

        </div>
        <div class="col-1">
            <button type="button" class="btn btn-info" onclick="logout()">logout</button>
        </div>
    </div>
</div>







