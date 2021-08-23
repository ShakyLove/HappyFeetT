<?php
    session_start();
    include "../bd/conn.php";

    if(!empty($_POST)){

        //Extraer datos del producto
        if($_POST['action'] == 'infoProducto'){

            $producto_id = $_POST['producto'];
            $query = mysqli_query($conn, "SELECT codigo_prod, descripcion, precio, existencia FROM productos WHERE codigo_prod = $producto_id AND estatus = 1");
            $result = mysqli_num_rows($query);

            if($result > 0){

                $row = mysqli_fetch_assoc($query);
                echo  json_encode($row, JSON_UNESCAPED_UNICODE);
                exit;
            }else{
                echo 'error';
            }
            exit;
        }
        
        //Agregar producto al detalle temp
        if($_POST['action'] == 'addProductoDetalle'){
            
            if(empty($_POST['producto']) || empty($_POST['cantidad'])){

                echo 'error';
            }else{
                
                $codproducto = $_POST['producto'];
                $cantidad = $_POST['cantidad'];
                $token = md5($_SESSION['codigo']);

                $query_detalle_temp = mysqli_query($conn, "CALL add_detalle_tem($codproducto, $cantidad, '$token')");
                $result = mysqli_num_rows($query_detalle_temp);

                $detalleTabla = '';
                $sub_total = 0;
                $iva = 0;
                $arrayData = array();
                $total = 0;

                if($result > 0){

                    $iva = 19;
                    while($row = mysqli_fetch_assoc($query_detalle_temp)){

                        $precioTotal = round($row['cantidad'] * $row['precio_venta'], 0);
                        $sub_total = round($sub_total + $precioTotal, 0);
                        $total = round($total + $precioTotal, 0);

                        $detalleTabla .= '<tr>
                                            <td>'.$row['producto_cod'].'</td>
                                            <td colspan="2">'.$row['descripcion'].'</td>
                                            <td class="textcenter">'.$row['cantidad'].'</td>
                                            <td class="textright">'.$row['precio_venta'].'</td>
                                            <td class="textright">'.$precioTotal.'</td>
                                            <td>
                                                <a class="link_delete" href="#" onclick="event.preventDefault(); del_product_detalle('.$row['correlativo'].');">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                        ';
                    }
                    $impuesto = round($sub_total * ($iva / 100), 0);
                    $tl_sniva = round($sub_total - $impuesto, 0);
                    $total = round($tl_sniva + $impuesto, 0);

                    $detalleTotales = ' <tr>
                                            <td colspan="5" class="textright">SUBTOTAL Q.</td>
                                            <td class="textright">'.$tl_sniva.'</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="textright">IVA('.$iva.'%)</td>
                                            <td class="textright">'.$impuesto.'</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="textright">TOTAL Q.</td>
                                            <td class="textright">'.$total.'</td>
                                        </tr>
                    ';
                    $arrayData['detalle'] = $detalleTabla;
                    $arrayData['totales'] = $detalleTotales;

                    echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
                }else{
                    echo 'error';
                }
            }
            exit;

        }

        //Extrae datos del detalle temp
        if($_POST['action'] == 'searchForDetalle'){
            
            if(empty($_POST['user'])){

                echo 'error';
            }else{
                
                $token = md5($_SESSION['codigo']);

                $query_temp = mysqli_query($conn, "SELECT tmp.correlativo, tmp.token_user, tmp.cantidad, tmp.precio_venta, p.codigo_prod, p.descripcion 
                                                            FROM prod_has_salida tmp
                                                            INNER JOIN productos p 
                                                            ON tmp.producto_cod = p.codigo_prod
                                                            WHERE token_user = '$token'");

                $result = mysqli_num_rows($query_temp);

                $detalleTabla = '';
                $sub_total = 0;
                $iva = 0;
                $arrayData = array();
                $total = 0;

                if($result > 0){

                    $iva = 19;
                    while($row = mysqli_fetch_assoc($query_temp)){

                        $precioTotal = round($row['cantidad'] * $row['precio_venta'], 0);
                        $sub_total = round($sub_total + $precioTotal, 0);
                        $total = round($total + $precioTotal, 0);

                        $detalleTabla .= '<tr>
                                            <td>'.$row['codigo_prod'].'</td>
                                            <td colspan="2">'.$row['descripcion'].'</td>
                                            <td class="textcenter">'.$row['cantidad'].'</td>
                                            <td class="textright">'.$row['precio_venta'].'</td>
                                            <td class="textright">'.$precioTotal.'</td>
                                            <td>
                                                <a class="link_delete" href="#" onclick="event.preventDefault(); del_product_detalle('.$row['correlativo'].');">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                        ';
                    }
                    $impuesto = round($sub_total * ($iva / 100), 0);
                    $tl_sniva = round($sub_total - $impuesto, 0);
                    $total = round($tl_sniva + $impuesto, 0);

                    $detalleTotales = ' <tr>
                                            <td colspan="5" class="textright">SUBTOTAL Q.</td>
                                            <td class="textright">'.$tl_sniva.'</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="textright">IVA('.$iva.'%)</td>
                                            <td class="textright">'.$impuesto.'</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="textright">TOTAL Q.</td>
                                            <td class="textright">'.$total.'</td>
                                        </tr>
                    ';
                    $arrayData['detalle'] = $detalleTabla;
                    $arrayData['totales'] = $detalleTotales;

                    echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
                }else{
                    echo 'error';
                }
            }
            exit;

        }

        //Eliminar datos del detalle temp
        if($_POST['action'] == 'delProductoDetalle'){

            if(empty($_POST['id_detalle'])){

                echo 'error';
            }else{
                
                $id_detalle = $_POST['id_detalle'];
                $token = md5($_SESSION['codigo']);

                $query_del_detalle = mysqli_query($conn, "CALL del_detalle_temp($id_detalle, '$token')");
                $result = mysqli_num_rows($query_del_detalle);

                $detalleTabla = '';
                $sub_total = 0;
                $iva = 0;
                $arrayData = array();
                $total = 0;

                if($result > 0){

                    $iva = 19;
                    while($row = mysqli_fetch_assoc($query_del_detalle)){

                        $precioTotal = round($row['cantidad'] * $row['precio_venta'], 0);
                        $sub_total = round($sub_total + $precioTotal, 0);
                        $total = round($total + $precioTotal, 0);

                        $detalleTabla .= '<tr>
                                            <td>'.$row['producto_cod'].'</td>
                                            <td colspan="2">'.$row['descripcion'].'</td>
                                            <td class="textcenter">'.$row['cantidad'].'</td>
                                            <td class="textright">'.$row['precio_venta'].'</td>
                                            <td class="textright">'.$precioTotal.'</td>
                                            <td>
                                                <a class="link_delete" href="#" onclick="event.preventDefault(); del_product_detalle('.$row['correlativo'].');">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                        ';
                    }
                    $impuesto = round($sub_total * ($iva / 100), 0);
                    $tl_sniva = round($sub_total - $impuesto, 0);
                    $total = round($tl_sniva + $impuesto, 0);

                    $detalleTotales = ' <tr>
                                            <td colspan="5" class="textright">SUBTOTAL Q.</td>
                                            <td class="textright">'.$tl_sniva.'</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="textright">IVA('.$iva.'%)</td>
                                            <td class="textright">'.$impuesto.'</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="textright">TOTAL Q.</td>
                                            <td class="textright">'.$total.'</td>
                                        </tr>
                    ';
                    $arrayData['detalle'] = $detalleTabla;
                    $arrayData['totales'] = $detalleTotales;

                    echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
                }else{
                    echo 'error';
                }
            }
            exit;

        }

        //Anular venta 
        if($_POST['action'] == 'anularVenta'){

            $token = md5($_SESSION['codigo']);

            $query_del = mysqli_query($conn, "DELETE FROM prod_has_salida WHERE token_user = '$token'");

            if($query_del){
                echo 'ok';
            }else{
                echo 'error';
            }
            exit;
        }

        //Procesar venta 
        if($_POST['action'] == 'procesarVenta'){

            $token = md5($_SESSION['codigo']);
            $usuario = $_SESSION['codigo'];

            $query_procesar = mysqli_query($conn, "SELECT * FROM prod_has_salida WHERE token_user = '$token'");
            $result = mysqli_num_rows($query_procesar);

            if($result > 0){

                $query_procesar_call = mysqli_query($conn, "CALL procesar_venta($usuario, '$token')");
                $result_detalle = mysqli_num_rows($query_procesar_call);

                if($result_detalle > 0){

                    $row = mysqli_fetch_assoc($query_procesar_call);
                    echo json_encode($row, JSON_UNESCAPED_UNICODE);
                }else{
                    echo 'error';
                }
            }else{
                echo 'error';
            }
            exit;
        }

    }else{
        echo 'no existe post';
    }
?>