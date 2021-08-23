-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-07-2021 a las 01:59:30
-- Versión del servidor: 10.4.13-MariaDB
-- Versión de PHP: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `happyfeet`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=CURRENT_USER PROCEDURE `actualizar_precio_producto` (`n_cantidad` INT, `n_precio` DECIMAL(11,0), `codigo` INT)  BEGIN
	DECLARE nueva_existencia int;
    DECLARE nuevo_total decimal(11,0);
    DECLARE nuevo_precio decimal(11,0);
    
    DECLARE cant_actual int;
    DECLARE pre_actual decimal(11,0);
    
    DECLARE actual_existencia int;
    DECLARE actual_precio decimal(11,0);
    
    SELECT precio, existencia INTO actual_precio, actual_existencia FROM productos WHERE codigo_prod = codigo;
    SET nueva_existencia = actual_existencia + n_cantidad;
    SET nuevo_total = (actual_existencia * actual_precio) + (n_cantidad * n_precio);
    SET nuevo_precio = nuevo_total / nueva_existencia;
    
    UPDATE productos SET existencia = nueva_existencia, precio = nuevo_precio WHERE codigo_prod = codigo;
    SELECT nueva_existencia, nuevo_precio;
END$$

CREATE DEFINER=CURRENT_USER PROCEDURE `add_detalle_tem` (`codigo` INT, `cantidad` INT, `token_user` VARCHAR(50))  BEGIN 
	DECLARE precio_actual decimal(11,0);
    SELECT precio INTO precio_actual FROM productos WHERE codigo_prod = codigo;
    
    INSERT INTO prod_has_salida(token_user, producto_cod, cantidad, precio_venta) VALUES(token_user, codigo, cantidad, precio_actual);
    
    SELECT tmp.correlativo, tmp.producto_cod, p.descripcion, tmp.cantidad, tmp.precio_venta FROM prod_has_salida tmp 
    INNER JOIN productos p
    ON tmp.producto_cod = p.codigo_prod
    WHERE tmp.token_user = token_user;
END$$

CREATE DEFINER=CURRENT_USER PROCEDURE `anular_factura` (`no_factura` INT)  BEGIN
    	DECLARE existe_factura int;
        DECLARE registros int;
        DECLARE a int;
        
        DECLARE cod_producto int;
        DECLARE cant_producto int;
        DECLARE existencia_actual int;
        DECLARE nueva_existencia int;
        
        SET existe_factura = (SELECT COUNT(*) FROM salida WHERE id_salida = no_factura and estatus = 1);
        IF existe_factura > 0 THEN
        	CREATE TEMPORARY TABLE tbl_tmp(
                id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                cod_prod BIGINT,
                cant_prod INT
                );
            SET a = 1;
            SET registros = (SELECT COUNT(*) FROM detalle_salida WHERE id_salida = no_factura);
            IF registros > 0 THEN
            	INSERT INTO tbl_tmp(cod_prod,cant_prod) SELECT id_producto, cantidad FROM detalle_salida WHERE id_salida = no_factura;
                WHILE a <= registros DO
                	SELECT cod_prod, cant_prod INTO cod_producto, cant_producto FROM tbl_tmp WHERE id = a;
                    SELECT existencia INTO existencia_actual FROM productos WHERE codigo_prod = cod_producto;
                    SET nueva_existencia = existencia_actual + cant_producto;
                    UPDATE productos SET existencia = nueva_existencia WHERE codigo_prod = cod_producto;
                    SET a=a+1;
                END WHILE;
                
                UPDATE salida SET estatus = 2 WHERE id_salida = no_factura;
                DROP TABLE tbl_tmp;
                SELECT * FROM salida WHERE id_salida = no_factura;
            END IF;
        ELSE 
        	SELECT 0 salida;
    	END IF;
END$$

CREATE DEFINER=CURRENT_USER PROCEDURE `del_detalle_temp` (`id_detalle` INT, `token` VARCHAR(50))  BEGIN
    DELETE FROM prod_has_salida WHERE correlativo = id_detalle;
    
    SELECT tmp.correlativo, tmp.producto_cod,p.descripcion, tmp.cantidad, tmp.precio_venta FROM 	prod_has_salida tmp INNER JOIN productos p 
    ON tmp.producto_cod = p.codigo_prod
    WHERE tmp.token_user = token;
END$$

CREATE DEFINER=CURRENT_USER PROCEDURE `procesar_venta` (`cod_usuario` INT, `token` VARCHAR(50))  BEGIN 
	DECLARE factura int;
    DECLARE registros int;
    DECLARE total DECIMAL(11,0);
    DECLARE nueva_existencia int;
    DECLARE existencia_actual int;
    DECLARE tmp_cod_producto int;
    DECLARE tmp_cant_producto int;
    DECLARE a int;	
    SET a = 1;
    
    CREATE TEMPORARY TABLE tbl_tmp_tokenuser(
        id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        cod_prod BIGINT,
        cant_prod int
    );
        
    SET registros = (SELECT COUNT(*) FROM prod_has_salida WHERE token_user = token);
    IF registros > 0 THEN
    	INSERT INTO tbl_tmp_tokenuser(cod_prod, cant_prod) SELECT producto_cod, cantidad FROM prod_has_salida WHERE token_user = token;
        
        INSERT INTO salida(usuario) VALUES(cod_usuario);
        SET factura = LAST_INSERT_ID();
        
        INSERT INTO detalle_salida(id_salida,id_producto,cantidad,precio_venta) SELECT (factura) as id_salida, producto_cod, cantidad, 				precio_venta FROM prod_has_salida WHERE token_user = token;
        
        WHILE a <= registros DO
        	SELECT cod_prod, cant_prod INTO tmp_cod_producto, tmp_cant_producto FROM tbl_tmp_tokenuser WHERE id = a;
            SELECT existencia INTO existencia_actual FROM productos WHERE codigo_prod = tmp_cod_producto;
            
            SET nueva_existencia = existencia_actual - tmp_cant_producto;
            UPDATE productos SET existencia = nueva_existencia WHERE codigo_prod = tmp_cod_producto;
            
            SET a = a+1;
        END WHILE;
        
        SET total = (SELECT SUM(cantidad * precio_venta) FROM prod_has_salida WHERE token_user = token);
        UPDATE salida SET total_salida = total WHERE id_salida = factura;
        
        DELETE FROM prod_has_salida WHERE token_user = token;
        TRUNCATE TABLE tbl_tmp_tokenuser;
        SELECT * FROM salida WHERE id_salida = factura;
        
    ELSE
    	SELECT 0;
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `categoria_id` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`categoria_id`, `descripcion`, `usuario_id`, `estatus`) VALUES
(1, 'Calzado deportivo', 1, 0),
(2, 'Calzado formal', 1, 1),
(3, 'Calzado Infantil', 1, 1),
(4, 'Calzado casual', 1, 1),
(5, 'Calzado semi formal', 1, 1),
(6, 'Sandalia', 1, 1),
(7, 'Zapato de tacón', 1, 0),
(8, 'Zueco', 1, 0),
(9, 'Babucha', 1, 1),
(10, 'Botas', 1, 1),
(11, 'Botin', 1, 1),
(12, 'Mocasín', 1, 1),
(13, 'Bailarina', 1, 1),
(14, 'Tacón ', 1, 1),
(15, 'Playa', 1, 1),
(16, 'Dance', 9, 1),
(22, 'Deportivo', 1, 1),
(23, 'Guayos', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_salida`
--

CREATE TABLE `detalle_salida` (
  `correlativo` int(11) NOT NULL,
  `id_salida` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_venta` decimal(11,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `detalle_salida`
--

INSERT INTO `detalle_salida` (`correlativo`, `id_salida`, `id_producto`, `cantidad`, `precio_venta`) VALUES
(1, 5, 15, 1, '200000'),
(2, 5, 8, 1, '300000'),
(3, 5, 6, 1, '600000'),
(4, 6, 16, 3, '3000000'),
(5, 6, 20, 2, '300000'),
(6, 6, 19, 1, '500000'),
(7, 7, 15, 1, '200000'),
(8, 7, 16, 1, '3000000'),
(9, 7, 17, 1, '250000'),
(10, 8, 20, 1, '300000'),
(11, 8, 14, 1, '180000'),
(12, 8, 6, 1, '600000'),
(13, 9, 7, 1, '400000'),
(14, 10, 9, 4, '193333'),
(15, 10, 22, 5, '335000'),
(16, 10, 25, 5, '2500000'),
(17, 11, 10, 3, '146667'),
(18, 11, 11, 3, '200000'),
(19, 11, 13, 3, '300000'),
(20, 12, 10, 1, '146667'),
(21, 12, 12, 1, '270000'),
(22, 12, 13, 1, '300000'),
(23, 13, 8, 9, '300000'),
(24, 14, 21, 10, '700000'),
(25, 15, 10, 5, '146667'),
(26, 15, 8, 2, '300000'),
(27, 16, 16, 1, '3000000'),
(28, 16, 19, 1, '500000'),
(29, 16, 20, 1, '300000'),
(30, 17, 16, 1, '3000000'),
(31, 17, 15, 1, '200000'),
(33, 18, 19, 5, '500000'),
(34, 19, 16, 1, '3000000'),
(35, 19, 22, 1, '335000'),
(36, 19, 25, 1, '2500000'),
(37, 20, 28, 10, '800000'),
(38, 20, 29, 1, '200000'),
(39, 20, 26, 5, '150000'),
(40, 21, 28, 5, '650000'),
(41, 22, 23, 5, '468000'),
(42, 22, 12, 1, '270000');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrada`
--

CREATE TABLE `entrada` (
  `id_entrada` int(11) NOT NULL,
  `cod_producto` int(11) DEFAULT NULL,
  `fecha_entrada` datetime NOT NULL DEFAULT current_timestamp(),
  `cantidad` decimal(11,0) NOT NULL,
  `precio_entrada` decimal(11,0) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `entrada`
--

INSERT INTO `entrada` (`id_entrada`, `cod_producto`, `fecha_entrada`, `cantidad`, `precio_entrada`, `usuario_id`) VALUES
(2, 4, '2021-06-20 20:17:48', '150', '110000', 1),
(4, 6, '2021-06-21 01:59:02', '30', '300000', 17),
(5, 7, '2021-06-21 02:04:02', '100', '400000', 1),
(6, 8, '2021-06-21 02:11:07', '50', '300000', 21),
(7, 9, '2021-06-21 13:49:14', '40', '200000', 13),
(8, 10, '2021-06-21 14:07:58', '100', '150000', 16),
(9, 11, '2021-06-21 14:08:28', '100', '200000', 1),
(10, 12, '2021-06-21 14:09:06', '50', '270000', 1),
(11, 13, '2021-06-21 14:10:30', '50', '300000', 1),
(12, 14, '2021-06-21 14:12:34', '50', '180000', 1),
(17, 12, '2021-06-26 18:05:47', '50', '270000', 1),
(18, 10, '2021-06-26 18:07:50', '50', '140000', 1),
(19, 9, '2021-06-26 18:20:15', '40', '180000', 1),
(20, 15, '2021-06-26 21:35:55', '40', '200000', 1),
(21, 16, '2021-06-26 22:39:03', '20', '3000000', 1),
(22, 17, '2021-06-26 22:40:34', '30', '250000', 1),
(23, 18, '2021-06-26 22:41:02', '30', '270000', 1),
(24, 19, '2021-06-26 22:41:57', '40', '500000', 1),
(25, 20, '2021-06-26 22:42:35', '50', '300000', 1),
(26, 21, '2021-06-26 22:44:44', '20', '900000', 1),
(27, 22, '2021-06-26 22:45:21', '30', '400000', 1),
(28, 23, '2021-06-26 22:46:49', '30', '600000', 1),
(29, 24, '2021-06-26 22:48:08', '30', '1800000', 1),
(30, 25, '2021-06-26 22:48:54', '40', '2500000', 1),
(31, 26, '2021-06-26 23:01:25', '50', '150000', 1),
(32, 27, '2021-06-26 23:02:57', '30', '400000', 1),
(33, 28, '2021-06-27 02:05:01', '20', '800000', 1),
(34, 28, '2021-06-27 02:05:40', '20', '800000', 1),
(35, 29, '2021-06-27 02:07:02', '50', '200000', 1),
(36, 21, '2021-06-27 02:12:56', '20', '500000', 9),
(37, 22, '2021-06-28 16:41:29', '30', '270000', 1),
(41, 23, '2021-06-29 09:43:42', '20', '270000', 1),
(43, 33, '2021-07-02 18:20:17', '30', '400000', 1),
(44, 16, '2021-07-02 18:27:26', '30', '3000000', 1),
(45, 34, '2021-07-12 15:32:56', '40', '500000', 1),
(46, 35, '2021-07-12 15:36:50', '40', '400000', 1),
(47, 18, '2021-07-12 15:38:02', '10', '250000', 1),
(48, 21, '2021-07-12 23:27:38', '5', '650000', 1),
(49, 36, '2021-07-14 23:33:04', '30', '1500000', 1),
(50, 36, '2021-07-14 23:34:39', '10', '1500000', 1),
(51, 37, '2021-07-15 22:15:59', '50', '400000', 1),
(52, 37, '2021-07-15 23:01:00', '10', '450000', 1),
(53, 28, '2021-07-15 23:40:52', '30', '500000', 1),
(54, 6, '2021-07-17 22:53:59', '20', '600000', 1),
(55, 38, '2021-07-17 23:04:23', '53', '3235', 1),
(56, 24, '2021-07-17 23:05:28', '10', '600000', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `codigo_prod` int(11) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `proveedor` int(11) NOT NULL,
  `precio` decimal(11,0) NOT NULL,
  `existencia` int(11) NOT NULL,
  `date_add` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1,
  `foto` text NOT NULL,
  `category` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`codigo_prod`, `descripcion`, `proveedor`, `precio`, `existencia`, `date_add`, `usuario_id`, `estatus`, `foto`, `category`) VALUES
(4, 'Botas berlin', 5, '116', 250, '2021-06-20 20:17:48', 1, 0, 'img_producto.png', NULL),
(6, 'Sneaker B24', 9, '600000', 48, '2021-06-21 01:59:02', 1, 1, 'img_0fee11496e0b75ffdd9757cab2d8f7de.jpg', 4),
(7, 'Lego Sport', 4, '400000', 99, '2021-06-21 02:04:02', 1, 1, 'img_c55270cc36d09cfb6721cb356d080caf.jpg', 1),
(8, 'Niter Jogger ', 13, '300000', 40, '2021-06-21 02:11:07', 1, 1, 'img_112ffd870a7a9728f721e13af541385c.jpg', 1),
(9, 'Static', 3, '193333', 116, '2021-06-21 13:49:14', 1, 1, 'img_f88e21444e5b9d88fc39539c977975af.jpg', 1),
(10, 'Xray Lite', 3, '146667', 150, '2021-06-21 14:07:58', 1, 1, 'img_cb1f22d815b130a521997f1e742d84a2.jpg', 1),
(11, 'Xray Black', 3, '200000', 100, '2021-06-21 14:08:28', 1, 1, 'img_d2a032f026b9d2e3de161063b92676d3.jpg', 1),
(12, 'Enzo Sport', 7, '270000', 100, '2021-06-21 14:09:06', 1, 1, 'img_7c5a61c8c3c9be3ddf5d6ce7786a71d9.jpg', 1),
(13, 'Square F', 7, '300000', 50, '2021-06-21 14:10:30', 1, 1, 'img_6d9029edc4885d67a2d92ef314e6385f.jpg', 1),
(14, 'Enzo Sport X', 7, '180000', 59, '2021-06-21 14:12:34', 1, 1, 'img_f9de9195ff5b5931c140dbaa6b2e4b7a.jpg', 1),
(15, 'Tenis moda Lacoste', 12, '200000', 37, '2021-06-26 21:35:55', 1, 1, 'img_producto.png', 4),
(16, 'Gucci estampado', 11, '3000000', 44, '2021-06-26 22:39:03', 1, 1, 'img_e52207dbfcf1161d49bdffc1aa640bcc.jpg', 6),
(17, 'NITIfy', 12, '250000', 29, '2021-06-26 22:40:34', 1, 1, 'img_85f254c5fcaa1527b884f6021c40b872.jpg', 4),
(18, 'NITIfy negro', 12, '265000', 40, '2021-06-26 22:41:02', 1, 1, 'img_5aefcad25941a90b32f3dab1142d948a.jpg', 4),
(19, 'PhouTe', 9, '500000', 34, '2021-06-26 22:41:57', 1, 1, 'img_a877ebe1c9d2a3b8cfe0204a05b41bd9.jpg', 9),
(20, 'ThEres', 4, '300000', 47, '2021-06-26 22:42:35', 1, 1, 'img_f752b6e8d15d22be00dff75e5fd7be9f.jpg', 1),
(21, 'Laidri', 13, '692857', 45, '2021-06-26 22:44:44', 1, 1, 'img_a89491bca36b510a950ddefd8ebbf690.jpg', 4),
(22, 'YaLmeA', 13, '335000', 54, '2021-06-26 22:45:21', 1, 1, 'img_def12985427ab29f232a1f406934c22a.jpg', 4),
(23, 'Eneact', 13, '468000', 50, '2021-06-26 22:46:49', 1, 1, 'img_4693e14d872db11ca6cddfba62e65daa.jpg', 14),
(24, 'FUSkYo', 11, '1500000', 40, '2021-06-26 22:48:08', 1, 1, 'img_cf0775450e325c0776089a16274eeeb9.jpg', 10),
(25, 'IrApHi', 11, '2500000', 34, '2021-06-26 22:48:54', 1, 1, 'img_c96595baee20619e34f81f3fc69df378.jpg', 2),
(26, 'NITIfy blanco', 12, '150000', 45, '2021-06-26 23:01:25', 1, 1, 'img_eb13be53edc5835ee3dc1038046f3c46.jpg', 4),
(27, 'WrowPO', 3, '400000', 30, '2021-06-26 23:02:57', 1, 1, 'img_23609d3c8dea149ad102cbada13da9c7.jpg', 6),
(28, 'NDJS', 13, '650000', 60, '2021-06-27 02:05:01', 1, 1, 'img_ba2ac6b76d7d58da2e8a786b38743e72.jpg', 15),
(29, 'BHUDO', 14, '200000', 49, '2021-06-27 02:07:02', 1, 1, 'img_27df3e539f5c4cede5e036c5656c20d3.jpg', 10),
(33, 'Long Hh', 3, '400000', 30, '2021-07-02 18:20:17', 1, 0, 'img_456f2560a06213009f192c16719767a2.jpg', 9),
(34, 'Tofda', 3, '500000', 40, '2021-07-12 15:32:56', 1, 1, 'img_c650f24011a22a6c449157138e2dcfa3.jpg', 4),
(35, 'Peut', 13, '400000', 40, '2021-07-12 15:36:50', 1, 1, 'img_a46d4f6982cec0c9f93fda5ddb797a04.jpg', 4),
(36, 'Gucci pardo', 11, '1500000', 40, '2021-07-14 23:33:04', 1, 1, 'img_097addf8907d362c0cb32245f99d0a26.jpg', 9),
(37, 'Wlak Rel', 16, '408333', 60, '2021-07-15 22:15:59', 1, 1, 'img_28745670eb42aecad7d7264ffdbe441e.jpg', 4),
(38, 'fdjsk', 4, '3235', 53, '2021-07-17 23:04:23', 1, 1, 'img_f724bbf26f4b0a91d388290c91774f92.jpg', 9);

--
-- Disparadores `productos`
--
DELIMITER $$
CREATE TRIGGER `entradas_A_I` AFTER INSERT ON `productos` FOR EACH ROW BEGIN
		INSERT INTO entrada(cod_producto, cantidad, precio_entrada, usuario_id)
        VALUES(new.codigo_prod, new.existencia, new.precio, new.usuario_id);
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prod_has_entrada`
--

CREATE TABLE `prod_has_entrada` (
  `producto_cod` int(11) NOT NULL,
  `entrada_cod` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prod_has_salida`
--

CREATE TABLE `prod_has_salida` (
  `correlativo` int(11) NOT NULL,
  `token_user` varchar(50) NOT NULL,
  `producto_cod` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_venta` decimal(11,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `nit_proveedor` int(11) NOT NULL,
  `proveedor` varchar(100) DEFAULT NULL,
  `contacto` varchar(100) NOT NULL,
  `telefono` int(11) NOT NULL,
  `direccion` text NOT NULL,
  `date_add` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`nit_proveedor`, `proveedor`, `contacto`, `telefono`, `direccion`, `date_add`, `usuario_id`, `estatus`) VALUES
(3, 'NIKE', 'Claudia Martinez', 734930, 'Avenida las americas', '2021-06-20 15:13:30', 2, 1),
(4, 'ADIDAS', 'Andres Blanco ', 653246, 'Av. Ciudad de Cali No. 6C-09', '2021-06-20 15:22:09', 1, 1),
(5, 'BOSI', 'Camilo Rodriguez', 458796, 'Calle 109a N° 17-10 Sede Norte', '2021-06-20 15:22:44', 9, 1),
(6, 'RECREO', 'Esteban Quesada', 6532148, 'Carrera 1 Este No. 17 01', '2021-06-20 15:23:33', 21, 0),
(7, 'PUMA', 'Gabriel Orozco', 65789, 'Av.19 #152-48 Esquina', '2021-06-20 15:24:18', 16, 1),
(8, 'CONVERSE', 'Laura Diaz Mejia ', 321457, 'Calle 13 #3-17 Localidad Candelaria', '2021-06-20 15:25:47', 21, 1),
(9, 'DIOR', 'Julian Romero', 987531, 'Cl.93b # 11a- 84 Local 306', '2021-06-20 15:26:24', 19, 1),
(10, 'PRADA', 'Maria Camila', 654238, 'Calle 109a N° 17-10 Sede Norte', '2021-06-20 15:26:52', 18, 1),
(11, 'GUCCI', 'Natalia Andrea', 654328, 'Av.19 #152-48 Esquina', '2021-06-20 15:27:16', 13, 1),
(12, 'LACOSTE', 'Mariana Lopez', 548753, 'Calle 13 #3-17 Localidad Candelaria', '2021-06-20 16:12:55', 1, 1),
(13, 'CHANEL', 'Martin Castro Escorcia', 654238, 'Av. Ciudad de Cali No. 6C-09', '2021-06-26 22:43:38', 1, 1),
(14, 'PEILES', 'Mariana Castro', 234524, 'Cr.21 # 88-12', '2021-06-27 02:06:30', 1, 1),
(15, 'ZARA', 'Luz Marina Castro', 649205, 'calle 452-463c', '2021-07-02 18:18:39', 1, 1),
(16, 'REEBOK', 'Carlos Andrés Gómez', 653248, 'Calle 109a N° 17-10 Sede Norte', '2021-07-15 22:08:33', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `rol` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `rol`) VALUES
(1, 'Administrador'),
(2, 'Empleado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salida`
--

CREATE TABLE `salida` (
  `id_salida` int(11) NOT NULL,
  `fecha_salida` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario` int(11) NOT NULL,
  `total_salida` int(11) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `salida`
--

INSERT INTO `salida` (`id_salida`, `fecha_salida`, `usuario`, `total_salida`, `estatus`) VALUES
(5, '2021-07-10 02:48:05', 1, 1100000, 1),
(6, '2021-07-12 14:23:51', 1, 10100000, 1),
(7, '2021-07-12 17:21:52', 1, 3450000, 1),
(8, '2021-07-12 17:25:59', 1, 1080000, 1),
(9, '2021-07-12 17:32:19', 1, 400000, 2),
(10, '2021-07-12 20:19:04', 1, 14948332, 1),
(11, '2021-07-12 21:59:59', 1, 1940001, 2),
(12, '2021-07-12 22:46:26', 1, 716667, 2),
(13, '2021-07-12 23:26:10', 1, 2700000, 1),
(14, '2021-07-12 23:27:01', 1, 7000000, 2),
(15, '2021-07-12 23:56:40', 1, 1333335, 2),
(16, '2021-07-15 17:00:40', 1, 3800000, 2),
(17, '2021-07-15 17:32:00', 1, 3200000, 1),
(18, '2021-07-15 17:33:04', 1, 2500000, 1),
(19, '2021-07-15 17:41:28', 1, 5835000, 1),
(20, '2021-07-15 23:39:50', 1, 8950000, 1),
(21, '2021-07-17 22:55:05', 1, 3250000, 2),
(22, '2021-07-17 23:06:14', 1, 2610000, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `codigo` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `correo` varchar(50) DEFAULT NULL,
  `usuario` varchar(50) DEFAULT NULL,
  `contraseña` varchar(50) DEFAULT NULL,
  `rol` int(11) DEFAULT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`codigo`, `nombre`, `correo`, `usuario`, `contraseña`, `rol`, `estatus`) VALUES
(1, 'Leider Isaac', 'isaacbolivar234@gmail.com', 'ShakyLove', '202cb962ac59075b964b07152d234b70', 1, 1),
(2, 'Leslie Nathalia', 'nath123@gmail.com', 'De-ShakyLove', '202cb962ac59075b964b07152d234b70', 1, 1),
(9, 'Harold Mercado', 'harold43@gmail.com', 'Hamervit', '827ccb0eea8a706c4c34a16891f84e7b', 2, 1),
(13, 'Cristian David', 'cris234@hotmail.com', 'Lazdekill', '9450476b384b32d8ad8b758e76c98a69', 2, 1),
(16, 'Juan Jose ', 'jose6743@gmail.com', 'jose123', '6f04f0d75f6870858bae14ac0b6d9f73', 2, 1),
(17, 'Miguel Eduardo', 'miguel73@gmail.com', 'miguel123', '202cb962ac59075b964b07152d234b70', 2, 1),
(18, 'Elian Araque', 'elian755@gmail.com', 'Elian123', '202cb962ac59075b964b07152d234b70', 2, 1),
(19, 'Kevin Roncancio', 'kevin4566@gamil.com', 'Kevin123', '202cb962ac59075b964b07152d234b70', 2, 0),
(20, 'Neider Bolivar', 'neybm453@gmail.com', 'Neybm', '81dc9bdb52d04dc20036dbd8313ed055', 2, 1),
(21, 'Lelyam Amelia', 'lemempo43@gamil.com', 'Lelyam234', '81dc9bdb52d04dc20036dbd8313ed055', 2, 1),
(22, 'Camila Martinez', 'cami473@gmail.com', 'camila17', '827ccb0eea8a706c4c34a16891f84e7b', 1, 0),
(25, 'Karen Dahiana', 'holacomoestas@hotmail.com', 'Karen35', '827ccb0eea8a706c4c34a16891f84e7b', 2, 1),
(26, 'Luz Marina Castro', 'luscastro234@hotmail.com', 'LuzCastro35', '843c08482be95f765afd8abd2e3e0195', 1, 0),
(27, 'Orlando', 'orlando345@gmail.com', 'orlando35', '827ccb0eea8a706c4c34a16891f84e7b', 2, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`categoria_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `detalle_salida`
--
ALTER TABLE `detalle_salida`
  ADD PRIMARY KEY (`correlativo`),
  ADD KEY `id_salida` (`id_salida`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `entrada`
--
ALTER TABLE `entrada`
  ADD PRIMARY KEY (`id_entrada`),
  ADD KEY `cod_producto` (`cod_producto`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`codigo_prod`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `proveedor` (`proveedor`),
  ADD KEY `category` (`category`);

--
-- Indices de la tabla `prod_has_entrada`
--
ALTER TABLE `prod_has_entrada`
  ADD KEY `producto_cod` (`producto_cod`),
  ADD KEY `entrada_cod` (`entrada_cod`);

--
-- Indices de la tabla `prod_has_salida`
--
ALTER TABLE `prod_has_salida`
  ADD PRIMARY KEY (`correlativo`),
  ADD KEY `producto_cod` (`producto_cod`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`nit_proveedor`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `salida`
--
ALTER TABLE `salida`
  ADD PRIMARY KEY (`id_salida`),
  ADD KEY `usuario` (`usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `rol` (`rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `categoria_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `detalle_salida`
--
ALTER TABLE `detalle_salida`
  MODIFY `correlativo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `entrada`
--
ALTER TABLE `entrada`
  MODIFY `id_entrada` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `codigo_prod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `prod_has_salida`
--
ALTER TABLE `prod_has_salida`
  MODIFY `correlativo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `nit_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `salida`
--
ALTER TABLE `salida`
  MODIFY `id_salida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD CONSTRAINT `categorias_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_salida`
--
ALTER TABLE `detalle_salida`
  ADD CONSTRAINT `detalle_salida_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`codigo_prod`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_salida_ibfk_2` FOREIGN KEY (`id_salida`) REFERENCES `salida` (`id_salida`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `entrada`
--
ALTER TABLE `entrada`
  ADD CONSTRAINT `entrada_ibfk_1` FOREIGN KEY (`cod_producto`) REFERENCES `productos` (`codigo_prod`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `entrada_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`proveedor`) REFERENCES `proveedor` (`nit_proveedor`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `productos_ibfk_4` FOREIGN KEY (`category`) REFERENCES `categorias` (`categoria_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `prod_has_salida`
--
ALTER TABLE `prod_has_salida`
  ADD CONSTRAINT `prod_has_salida_ibfk_1` FOREIGN KEY (`producto_cod`) REFERENCES `productos` (`codigo_prod`);

--
-- Filtros para la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD CONSTRAINT `proveedor_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `salida`
--
ALTER TABLE `salida`
  ADD CONSTRAINT `salida_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol`) REFERENCES `rol` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
