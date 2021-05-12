<!-- Se conecta con los demas archivos  -->
<?php

include 'global/config.php';
include 'carrito.php';
include 'templates/header.php';

?>
<!-- Productos agregados al carrito -->
<br>
<h3>Lista del carrito</h3>
<!-- Valida si la variable session trae productos de carrito-->
<?php if(!empty($_SESSION['carrito'])){ ?>
<table class="table table-light">
    <tbody>
    <!-- Cabecera de la tabla-->
        <tr>
            <th width="40%">Descripción</th>
            <th width="15%">Cantidad</th>
            <th width="20%">Precio</th>
            <th width="20%">Total</th>
            <th width="5%">Acciones</th>
        </tr>
        <?php $total = 0; ?>
        <!--Se hace un barrido para traer los elementos que trae la variable session-->
        <?php foreach($_SESSION['carrito'] as $indice => $producto){ ?>
        <!-- Lista de los productos-->
        <tr>
         <!--Se llaman desde el array creado en carrito con la variable session-->
            <td width="40%"><?php echo $producto['nombre']; ?></td>
            <td width="15%"><?php echo $producto['cantidad']; ?></td>
            <td width="20%">$<?php echo $producto['precio']; ?></td>
            <td width="20%">$<?php echo number_format($producto['precio'] * $producto['cantidad'], 2); ?></td>
            <td width="5%">
            <!-- Se reutiliza la misma validacion del boton creado en carrito-->
                <form action="" method="post">
                    <input type="hidden" name="id" value="<?php echo openssl_encrypt($producto['id'],COD,KEY); ?>">
                    <button 
                    class="btn btn-danger" 
                    type="submit"
                    name="btnAccion"
                    value="Eliminar"
                    >
                    Eliminar
                    </button>
                </form>
            </td>
        </tr>
        <?php $total = $total + ($producto['precio'] * $producto['cantidad']); ?>
        <?php } ?>
        <!-- Total de lo agregado en el carrito-->
        <tr>
            <td colspan="3" class="text-right"><h3>Total</h3></td>
            <td class="text-right"><h3>$<?php echo number_format($total, 2); ?></h3></td>
            <td></td>
        </tr>
         <!-- Capturar correo del cliente-->
        <tr>
            <td colspan="5">
                <form action="pagar.php" method="post">
                    <div class="alert alert-success">
                        <div class="form-group">
                            <label for="my-input">Correo electrónico</label>
                            <input 
                            type="email"
                            name="email"
                            id="email"
                            class="form-control"
                            placeholder="Ingresa tú correo electrónico"
                            required
                            >
                        </div>
                    </div>
                    <button
                    class="btn btn-primary btn-lg btn-block"
                    type="submit"
                    name="btnAccion"
                    value="proceder"
                    >
                    Proceder a pagar >>
                    </button>
                </form>
            </td>
        </tr>
    </tbody>
</table>
<?php }else{ ?>
<div class="alert alert-success">
    No hay productos en el carrito...
</div>
<?php } ?>
<?php

include 'templates/footer.php';

?>