<?php

//Se inicializa una sesion para almacenar los productos del carrito
session_start();
//variable para recibir lo ingresado a carrito
$mensaje = '';
//condicional para recibir el valor en el boton agregar y desincriptar los valores
if(isset($_POST['btnAccion'])){
    switch($_POST['btnAccion']){
        case 'Agregar':
            if(is_numeric(openssl_decrypt($_POST['id'],COD,KEY))){
                $id = openssl_decrypt($_POST['id'],COD,KEY);
                $mensaje .= 'OK ID correcto' . $id . '<br />';
            }else{
                $mensaje .= 'Upss... ID incorrecto';

                break;
            }

            if(is_string(openssl_decrypt($_POST['nombre'],COD,KEY))){
                $nombre = openssl_decrypt($_POST['nombre'],COD,KEY);
                $mensaje .= 'OK NOMBRE' . $nombre . '<br />';
            }else{
                $mensaje .= 'Upss... algo pasa con el nombre';

                break;
            }

            if(is_numeric(openssl_decrypt($_POST['precio'],COD,KEY))){
                $precio = openssl_decrypt($_POST['precio'],COD,KEY);
                $mensaje .= 'OK PRECIO' . $precio . '<br />';
            }else{
                $mensaje .= 'Upss... algo pasa con el precio';

                break;
            }

            if(is_numeric(openssl_decrypt($_POST['cantidad'],COD,KEY))){
                $cantidad = openssl_decrypt($_POST['cantidad'],COD,KEY);
                $mensaje .= 'OK CANTIDAD' . $cantidad . '<br />';
            }else{
                $mensaje .= 'Upss... algo pasa con la cantidad';

                break;
            }
            //Condicional para la variable session de almacenamiento del carrito
            if(!isset($_SESSION['carrito'])){

                $producto = array(
                    'id' => $id,
                    'nombre' => $nombre,
                    'precio' => $precio,
                    'cantidad' => $cantidad
                );
                $_SESSION['carrito'][0] = $producto;
                $mensaje = 'Producto agregado al carrito';
            }else{
                //Variable que almacena todos los id por medio de un array
                $idProductos = array_column($_SESSION['carrito'],'id');
                //Validar que el user no ingrese mas de una vez un producto
                if(in_array($id, $idProductos)){
                    echo '<script>alert("El producto ya ha sido seleccionado...");</script>';
                    $mensaje = '';

                //si el user desea agregar mas productos
                }else{
                    $numeroProductos = count($_SESSION['carrito']);

                    $producto = array(
                        'id' => $id,
                        'nombre' => $nombre,
                        'precio' => $precio,
                        'cantidad' => $cantidad
                    );
                    $_SESSION['carrito'][$numeroProductos] = $producto;
                    $mensaje = 'Producto agregado al carrito';
                }
            }
           

        break;
        //Boton creado en mostrar carrito para eliminar productos
        case 'Eliminar':
            if(is_numeric(openssl_decrypt($_POST['id'],COD,KEY))){
                $id = openssl_decrypt($_POST['id'],COD,KEY);
               //Realizar un barrido en busca del producto a eliminar en carrito
                foreach($_SESSION['carrito'] as $indice => $producto){
                    //Se valida si se encuenta y se elimina por medio de la fn unset
                    if($producto['id'] == $id){
                        unset($_SESSION['carrito'][$indice]);
                        echo '<script>alert("Producto eliminado...");</script>';
                    }
                }

            }else{
                $mensaje .= 'Upss... ID incorrecto';

                break;
            }

        break;
    }
}

?>