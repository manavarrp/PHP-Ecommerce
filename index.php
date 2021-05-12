<!-- Se conecta con los demas archivos  -->
<?php

include 'global/config.php';
include 'global/conexion.php';
include 'carrito.php';
include 'templates/header.php';

?>
 <!-- Validar si tenemos productos al carrito -->
<?php if($mensaje != ""){ ?>
<div class="alert alert-success">
    <?php echo $mensaje; ?>
    <a class="badge badge-success" href="mostrar-carrito.php">Ver carrito</a>
</div>
<?php } ?>

<!-- Producto -->
<div class="row">
<!-- Accedemos a la BD tienda desde el home -->
<?php
    $sentencia = $pdo->prepare("SELECT * FROM productos");
    $sentencia->execute();
    //variable para tomar los datos de la BD
    $listaProductos = $sentencia->fetchAll(PDO::FETCH_ASSOC);
    
?>
 <!-- Ciclo para tomar cada uno de los datos de la BD -->
<?php
    foreach($listaProductos as $producto){
?>
        <div class="col-3">
            <img 
                src="archivos/<?php echo $producto['imagen']; ?>" 
                alt="<?php echo $producto['nombre']; ?>"
                title="<?php echo $producto['nombre']; ?>"
                class="card-img-top"
                height="370px"
            >
            <div class="card-body">
                <span><?php echo $producto['nombre']; ?></span>
                <h5 class="card-title">$<?php echo $producto['precio']; ?></h5>
                <p class="card-text"><?php echo $producto['descripcion']; ?></p>
                 
                 <!-- Agregar info al carrito de compras -->  
                <form action="" method="post">
                    <input type="hidden" name="id" id="id" value="<?php echo openssl_encrypt($producto['id'],COD,KEY); ?>">
                    <input type="hidden" name="nombre" id="nombre" value="<?php echo openssl_encrypt($producto['nombre'],COD,KEY); ?>">
                    <input type="hidden" name="precio" id="precio" value="<?php echo openssl_encrypt($producto['precio'],COD,KEY); ?>">
                    <input type="hidden" name="cantidad" id="cantidad" value="<?php echo openssl_encrypt(1,COD,KEY); ?>">
                    <button
                        class="btn btn-primary"
                        name="btnAccion"
                        value="Agregar"
                        type="submit"
                    >
                    Agregar al carrito
                    </button>
                </form>
            </div>
        </div>
<?php
    }
?>
</div>
<?php

include 'templates/footer.php';

?>