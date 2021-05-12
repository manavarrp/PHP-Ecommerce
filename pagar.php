<!-- Se conecta con los demas archivos  -->
<?php

include 'global/config.php';
include 'global/conexion.php';
include 'carrito.php';
include 'templates/header.php';

?>

<?php
// Validar que datos se enviaron via POST
if($_POST){
    $total = 0;
    $email = $_POST['email'];
    $SID = session_id();
    //Recorre todos los productos que trae session
    foreach($_SESSION['carrito'] as $indice => $producto){
        $total = $total + ($producto['precio'] * $producto['cantidad']);
    }
    //Consulta para guardar los datos a la tabla ventas de la BD
    $query = $pdo->prepare("INSERT INTO ventas
                        (id, clave_transaccion, paypal_datos, fecha, correo, total, `status`)
                        VALUES (NULL, :clave_transaccion, '', NOW(), :correo, :total, 'pendiente');");
                        //Se asignan los datos de manera segura
                        $query->bindParam(':clave_transaccion', $SID);
                        $query->bindParam(':correo', $email);
                        $query->bindParam(':total', $total);
                        $query->execute();
                        $idVenta = $pdo->lastInsertId();
                        //Ciclo para ir insertando los productos almacenados en el carrito
                        foreach($_SESSION['carrito'] as $indice => $producto){
                            //Consulta para guardar los datos a la tabla detalle de ventas de la BD
                            $query = $pdo->prepare("INSERT INTO detalle_venta
                                                (id, id_venta, id_producto, precio_unitario, cantidad)
                                                VALUES (NULL, :id_venta, :id_producto, :precio_unitario, :cantidad);");

                                                $query->bindParam(':id_venta', $idVenta);
                                                $query->bindParam(':id_producto', $producto['id']);
                                                $query->bindParam(':precio_unitario', $producto['precio']);
                                                $query->bindParam(':cantidad', $producto['cantidad']);
                                                $query->execute();
                        }                        
                        
    
}

?>
<!-- Se vincula a paypal -->  
<script src="https://www.paypalobjects.com/api/checkout.js"></script>

<div class="jumbotron text-center">
    <h1 class="display-4">¡Paso Final!</h1>
    <hr class="my-4">
    <p class="lead">Estas a punto de pagar con paypal la cantidad de:
        <h4>$<?php echo number_format($total, 2); ?></h4>
    </p>
    <p>
        <strong>(Para aclaraciones: manavarrp@gmail.com)</strong>
    </p>
    <p>
        <div id="paypal-button-container"></div>
    </p>
</div>

<script>
    // renderiza a paypal para el pago
    paypal.Button.render({

        env: 'sandbox', // sandbox | production

        style: {
            label: 'checkout',
            size: 'responsive',
            shape: 'pill',
            color: 'gold'
        },

        client: {
            sandbox: 'AV1MLL2fnOGBPmjjixRTzkcFSJTznj52QVXnaruGEzCL_KubZbrevQkLN28zxCCccdEPiueUuXt5qc3u',
            production: ''
        },

        // Configura la transaccion
        payment: function(data, actions) {
            return actions.payment.create({
                payment: {
                    transactions: [
                        {
                            amount: { total: '<?php echo $total; ?>', currency: 'USD' },
                            description:"Compra de productos a manavarrp:$<?php echo number_format($total, 2); ?>",
                            custom:"<?php echo $SID; ?>#<?php echo openssl_encrypt($idVenta,COD,KEY); ?>"
                        }
                    ]
                }
            });
        },

        // Finalizar el pago
        onAuthorize: function(data, actions) {
            return actions.payment.execute().then(function() {
               
                // Tu compra fue exitosa
                console.log(data);
               
            });
        }


    }, '#paypal-button-container');
</script>


<?php

include 'templates/footer.php';

?>