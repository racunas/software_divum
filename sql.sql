-- ESTA ES LA ESTRUCTURA DE LA BASE DE DATOS PARA MANEJAR LAS ORDENES DE LA CLÍNICA DIVUM

--
-- Estructura de tabla para la tabla `imagenes`
--

CREATE TABLE `imagenes` (
  `id_archivo` int(11) NOT NULL,
  `nombre` varchar(256) COLLATE utf8_spanish_ci NOT NULL,
  `id_ord` varchar(13) COLLATE utf8_spanish_ci DEFAULT NULL,
  `id_historial_ord` int(11) NOT NULL DEFAULT '1',
  `id_box` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Estructura de tabla para la tabla `box`
--

CREATE TABLE `box` (
  `id_box` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `tipo` varchar(10) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'ordinario',
  `dientes` varchar(1024) COLLATE utf8_spanish_ci NOT NULL DEFAULT '11',
  `id_prod` int(11) DEFAULT NULL,
  `id_prod_ort` int(11) DEFAULT NULL,
  `recepcion` int(11) NOT NULL,
  `entrega` int(11) NOT NULL,
  `paciente` varchar(120) COLLATE utf8_spanish_ci NOT NULL,
  `specs` varchar(500) COLLATE utf8_spanish_ci DEFAULT NULL,
  `colorimetria` int(11) DEFAULT NULL,
  `estado` int(11) NOT NULL,
  `fecha_rec` date NOT NULL,
  `hora` time NOT NULL,
  `porcentajePagar` float NOT NULL,
  `fecha_ent` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Estructura de tabla para la tabla `calificacion`
--

CREATE TABLE `calificacion` (
  `id_calif` int(11) NOT NULL,
  `id_trab` int(11) DEFAULT NULL,
  `id_orto` int(11) DEFAULT NULL,
  `opinion` varchar(250) NOT NULL,
  `precio` int(11) NOT NULL,
  `tiempo` int(11) NOT NULL,
  `calidad` int(11) NOT NULL,
  `id_clie` int(11) NOT NULL,
  `id_ord` varchar(13) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Estructura de tabla para la tabla `dentista`
--

CREATE TABLE `dentista` (
  `nomb` varchar(60) DEFAULT NULL,
  `tel` varchar(10) DEFAULT NULL,
  `id_clie` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `esp` varchar(40) DEFAULT NULL,
  `clinica` varchar(255) DEFAULT NULL,
  `direccion_predet` int(11) DEFAULT NULL,
  `img_perfil` varchar(500) NOT NULL DEFAULT 'dentista.png',
  `fecha_nac` date DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Estructura de tabla para la tabla `color`
--

CREATE TABLE `color` (
  `id_col` int(11) NOT NULL,
  `nomb` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `color`
--

INSERT INTO `color` (`id_col`, `nomb`) VALUES
(1, 'A1'),
(2, 'A2'),
(3, 'A3'),
(4, 'A4'),
(5, 'B1'),
(6, 'B2'),
(7, 'B3'),
(8, 'B4'),
(9, 'C1'),
(10, 'C2'),
(11, 'C3'),
(12, 'C4'),
(13, 'D1'),
(14, 'D2'),
(15, 'D3'),
(16, 'D4');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direccion`
--

CREATE TABLE `direccion` (
  `id_direc` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `calle` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `cp` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `id_rep` mediumint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


--
-- Estructura de tabla para la tabla `lista_precios_ortodoncia`
--

CREATE TABLE `lista_precios_ortodoncia` (
  `id_ort` int(11) NOT NULL,
  `id_lab` int(11) NOT NULL,
  `precio` decimal(10,0) NOT NULL,
  `id_ort_prod` int(11) NOT NULL,
  `dias_entrega` int(11) NOT NULL,
  `dias_terminado` int(11) NOT NULL,
  `porcentaje` float NOT NULL,
  `disponible` int(11) NOT NULL,
  `fecha_agregado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Estructura de tabla para la tabla `lista_precios_ortodoncia_urg`
--

CREATE TABLE `lista_precios_ortodoncia_urg` (
  `id_ort` int(11) NOT NULL,
  `precio` decimal(10,0) NOT NULL,
  `dias_terminado` int(11) NOT NULL,
  `disponible` int(11) NOT NULL,
  `fecha_agregado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Estructura de tabla para la tabla `lista_precios_protesis`
--

CREATE TABLE `lista_precios_protesis` (
  `id_lista_precios_protesis` int(11) NOT NULL,
  `id_pro` int(11) NOT NULL,
  `id_lab` int(11) NOT NULL,
  `precio` decimal(10,0) DEFAULT NULL,
  `dias_entrega` int(11) NOT NULL,
  `disponible` tinyint(1) NOT NULL DEFAULT '0',
  `dias_terminado` int(11) NOT NULL,
  `porciento_inicial` float NOT NULL,
  `fecha_agregado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Estructura de tabla para la tabla `lista_precios_protesis_urg`
--

CREATE TABLE `lista_precios_protesis_urg` (
  `id_lista_precios_protesis` int(11) NOT NULL,
  `precio` decimal(10,0) NOT NULL,
  `dias_entrega` int(11) NOT NULL,
  `disponible` tinyint(1) NOT NULL,
  `fecha_agregado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `id_est` int(11) NOT NULL,
  `nomb` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`id_est`, `nomb`) VALUES
(1, 'Terminado'),
(2, 'A prueba'),
(3, 'En proceso');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_orden_trabajo`
--

CREATE TABLE `historial_orden_trabajo` (
  `id_hist_ord` int(11) NOT NULL,
  `visto` int(1) NOT NULL DEFAULT '0',
  `id_ord` varchar(13) NOT NULL,
  `fecha_hist_ord` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pago` decimal(10,0) NOT NULL DEFAULT '0',
  `qr` varchar(255) DEFAULT NULL,
  `etapa` varchar(50) NOT NULL,
  `estado` int(11) NOT NULL,
  `estadoPago` int(11) NOT NULL,
  `confirmacionTecnico` int(11) NOT NULL DEFAULT '0',
  `fecha_rec` date NOT NULL,
  `fecha_ent` date NOT NULL,
  `hora_rec` time NOT NULL,
  `descr` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Estructura de tabla para la tabla `laboratorio`
--

CREATE TABLE `laboratorio` (
  `id_lab` int(11) NOT NULL,
  `nomb` varchar(60) DEFAULT NULL,
  `direc` varchar(100) DEFAULT NULL,
  `tel` varchar(10) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `id_rep` mediumint(8) UNSIGNED DEFAULT NULL,
  `nomb_art` varchar(70) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `img_art` varchar(255) NOT NULL DEFAULT 'imgRelleno.png',
  `img_prod` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL DEFAULT 'imgRellenoProducto.png',
  `img_orden` varchar(100) NOT NULL DEFAULT 'imgOrden.png',
  `pais` int(11) DEFAULT '146',
  `descr` varchar(130) DEFAULT NULL,
  `banco` varchar(60) NOT NULL DEFAULT 'X',
  `num_cuenta` varchar(30) NOT NULL DEFAULT 'X',
  `fecha_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `perfil_completo` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Estructura de tabla para la tabla `material`
--

CREATE TABLE `material` (
  `id_mat` int(11) NOT NULL,
  `nomb` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `material`
--

INSERT INTO `material` (`id_mat`, `nomb`) VALUES
(1, 'metal porcelana'),
(2, 'acrilico autocurable'),
(3, 'metalica total'),
(4, 'metal precioso porcelana'),
(5, 'metal acrilico'),
(6, 'cuello ceramico'),
(7, 'zirconio porcelana'),
(8, 'zirconio monolitica'),
(9, 'emax porcelana'),
(10, 'emax monolitica'),
(11, 'acrilico termocurado'),
(12, 'telio ivoclar'),
(13, 'ceromero'),
(14, 'captek'),
(15, 'pekkton'),
(16, 'suprinity'),
(17, 'unidad de encia rosa'),
(18, 'emax zirpress sobre zirconia'),
(19, 'metal precioso'),
(20, 'metal'),
(21, 'zirconio'),
(22, 'emax'),
(23, 'porcelana'),
(24, 'porcelana feldespatica'),
(25, 'acrilico'),
(26, 'teflon'),
(27, 'flexible'),
(28, 'metal flexible'),
(29, 'prxiii'),
(30, 'lucitone frs'),
(31, 'sr ivocap injection system'),
(32, 'disilicato'),
(33, 'oro'),
(34, 'titanio'),
(35, 'barra'),
(36, 'zirconia traslucida'),
(37, 'zirconia extratraslucida'),
(38, 'E max con Cad CAM'),
(39, 'grafeno'),
(40, 'ceramica prensada'),
(41, 'resina'),
(42, 'zirconia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensaje`
--

CREATE TABLE `mensaje` (
  `id_mensaje` int(11) NOT NULL,
  `id_ord` varchar(13) CHARACTER SET latin1 NOT NULL,
  `id_lab` int(11) DEFAULT NULL,
  `id_clie` int(11) DEFAULT NULL,
  `mensaje` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `leido` int(1) NOT NULL DEFAULT '0',
  `imagenes` varchar(2500) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Estructura de tabla para la tabla `notificacion`
--

CREATE TABLE `notificacion` (
  `id_notif` int(11) NOT NULL,
  `id_clie` int(11) DEFAULT NULL,
  `id_lab` int(11) DEFAULT NULL,
  `titulo` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `mensaje` varchar(300) COLLATE utf8_spanish_ci NOT NULL,
  `visto` int(11) NOT NULL DEFAULT '0',
  `url` varchar(500) COLLATE utf8_spanish_ci NOT NULL,
  `color` varchar(7) COLLATE utf8_spanish_ci NOT NULL DEFAULT '#9ac76d',
  `icono` varchar(50) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'fas fa-tooth',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tipo` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `auxiliar` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


--
-- Estructura de tabla para la tabla `orden_trabajo`
--

CREATE TABLE `orden_trabajo` (
  `id_ord` varchar(13) NOT NULL,
  `id_rel` int(11) NOT NULL,
  `tipo` varchar(10) NOT NULL DEFAULT 'ordinario',
  `dientes` varchar(1024) NOT NULL DEFAULT '11',
  `id_prod` int(11) DEFAULT NULL,
  `id_orto` int(11) DEFAULT NULL,
  `paciente` varchar(100) NOT NULL,
  `colorimetria` int(11) DEFAULT NULL,
  `dir_rec` int(11) NOT NULL,
  `dir_ent` int(11) NOT NULL,
  `fecha_orden` datetime NOT NULL,
  `entregado` int(11) NOT NULL DEFAULT '0',
  `cantidad` decimal(10,0) NOT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `fecha_ultima_orden` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Estructura de tabla para la tabla `ortodoncia_prod`
--

CREATE TABLE `ortodoncia_prod` (
  `id_ort_prod` int(11) NOT NULL,
  `nomb` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ortodoncia_prod`
--

INSERT INTO `ortodoncia_prod` (`id_ort_prod`, `nomb`) VALUES
(222, 'placas hawley con tornillo'),
(223, 'placas hawley con tornillo de abanico'),
(224, 'placas hawley con tornillo de bertoni'),
(225, 'encapsulados de mauricio'),
(226, 'placa sagital'),
(227, 'placa sagital con 2 tornillos y 2 ganchos'),
(228, 'placa sagital con 1 tornillos y 2 ganchos'),
(229, 'placa de nord'),
(230, 'placa de progenia con arco de eschler'),
(231, 'placa con tornillo triple'),
(232, 'placa schuartz con tornillo'),
(233, 'placa schuartz con 2 tornillos'),
(234, 'placa clark'),
(235, 'super spring aligner 3a3'),
(236, 'placa con spring aligner'),
(237, 'placa para descruzar mordida posterior'),
(238, 'placas hawley'),
(239, 'placa circunferencial'),
(240, 'resorte de alineacion con placa'),
(241, 'resorte de alineacion'),
(242, 'resorte de retencion'),
(243, 'retenedor dentsply essix'),
(244, 'placas con plano de mordida'),
(245, 'placas con plano inclinado'),
(246, 'placas con pista oclusal'),
(247, 'placas con trampa de lengua o dedo'),
(248, 'placas con trampa de lengua o dedo mas tornillo'),
(249, 'recuperador de espacios'),
(250, 'recuperador de espacios bilateral'),
(251, 'placa de disyuncion'),
(252, 'activador de andreasen'),
(253, 'bionator balters'),
(254, 'bionator california'),
(255, 'bionator 1'),
(256, 'bionator 2'),
(257, 'bionator 3'),
(258, 'corrector ortopedico dinamico klammt 1'),
(259, 'corrector ortopedico dinamico klammt 2'),
(260, 'corrector ortopedico dinamico klammt 3'),
(261, 'frankel'),
(262, 'chateau cuatro piezas'),
(263, 'blimer'),
(264, 'corrector ortopedico witzing'),
(265, 'alineador orthobite block'),
(266, 'klammt estandar 2'),
(267, 'klammt estandar 3'),
(268, 'twin block'),
(269, 'pistas planas'),
(270, 'simoes network'),
(271, 'arco lingual sin bandas'),
(272, 'arco lingual con bandas'),
(273, 'arco lingual por cajas sin bandas'),
(274, 'arco lingual por cajas con bandas'),
(275, 'arco platino sin bandas'),
(276, 'arco platino con bandas'),
(277, 'arco w de porter sin bandas'),
(278, 'arco w de porter con bandas'),
(279, 'arco w con extensiones sin bandas'),
(280, 'arco w con extensiones con bandas'),
(281, 'arco traspalatal sin bandas'),
(282, 'arco traspalatal con bandas'),
(283, 'quad helix con soldadura sin bandas'),
(284, 'quad helix con soldadura con bandas'),
(285, 'quad helix con caja sin bandas'),
(286, 'quad helix con caja con bandas'),
(287, 'bi helix con banda sin bandas'),
(288, 'bi helix con banda con bandas'),
(289, 'six helix sin bandas'),
(290, 'six helix con bandas'),
(291, 'quad helix con perla de tucat sin bandas'),
(292, 'quad helix con perla de tucat con bandas'),
(293, 'boton de nance sin bandas'),
(294, 'boton de nance con bandas'),
(295, 'lip bumper sin bandas'),
(296, 'lip bumper con bandas'),
(297, 'duo helix sin bandas'),
(298, 'duo helix con bandas'),
(299, 'pendulo sin bandas'),
(300, 'pendulo con bandas'),
(301, 'pendulo con tornillo sin bandas'),
(302, 'pendulo con tornillo con bandas'),
(303, 'pendex sin bandas'),
(304, 'pendex con bandas'),
(305, 'placas de hass sin bandas'),
(306, 'placas de hass con bandas'),
(307, 'trampa platina fija sin bandas'),
(308, 'trampa platina fija con bandas'),
(309, 'trampa platina loops sin bandas'),
(310, 'trampa platina loops con bandas'),
(311, 'trampa con picos sin bandas'),
(312, 'trampa con picos con bandas'),
(313, 'trampa con perla de tucat sin bandas'),
(314, 'trampa con perla de tucat con bandas'),
(315, 'trampa de dedo sin bandas'),
(316, 'trampa de dedo con bandas'),
(317, 'hyrax con 4 bandas sin bandas'),
(318, 'hyrax con 4 bandas con bandas'),
(319, 'hyrax con cementacion directa sin bandas'),
(320, 'hyrax con cementacion directa con bandas'),
(321, 'tornillo de disyuncion mc namara sin bandas'),
(322, 'tornillo de disyuncion mc namara con bandas'),
(323, 'tornillo de disyuncion sin bandas'),
(324, 'tornillo de disyuncion con bandas'),
(325, 'recuperador de espacios sin bandas'),
(326, 'recuperador de espacios con bandas'),
(327, 'retenedor de espacios fijo sin bandas'),
(328, 'retenedor de espacios fijo con bandas'),
(329, 'mantenedor de espacios unilaterales sin bandas'),
(330, 'mantenedor de espacios unilaterales con bandas'),
(331, 'mantenedor de espacios con ansa sin bandas'),
(332, 'mantenedor de espacios con ansa con bandas'),
(333, 'traccion para mascara facial de acrilico sin banda'),
(334, 'traccion para mascara facial de acrilico con banda'),
(335, 'traccion para mascara facial soldada sin bandas'),
(336, 'traccion para mascara facial soldada con bandas'),
(337, 'guarda oclusal rigido acrilico'),
(338, 'guarda oclusal rigido acetato'),
(339, 'guarda oclusal nocturno'),
(340, 'guarda oclusal rigido acetato con movimiento'),
(341, 'guarda reprogramador'),
(342, 'guarda ortodinamico'),
(343, 'acetato blando para blanqueamiento'),
(344, 'splith'),
(345, 'ganchos de bola o flecha'),
(346, 'resortes'),
(347, 'bandas sencillas');

-- --------------------------------------------------------


--
-- Estructura de tabla para la tabla `protesis`
--

CREATE TABLE `protesis` (
  `id_pro` int(11) NOT NULL,
  `id_mat` int(11) DEFAULT NULL,
  `id_trab` int(11) DEFAULT NULL,
  `nomb` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `protesis`
--

INSERT INTO `protesis` (`id_pro`, `id_mat`, `id_trab`, `nomb`) VALUES
(1, 1, 1, 'corona de metal porcelana'),
(2, 2, 1, 'corona de acrilico autocurable'),
(3, 3, 1, 'corona metalica total'),
(4, 4, 1, 'corona de metal precioso porcelana'),
(5, 5, 1, 'corona de metal acrilico'),
(6, 6, 1, 'corona collaer less'),
(7, 7, 1, 'corona de zirconio porcelana'),
(8, 8, 1, 'corona de zirconio monolitica'),
(9, 9, 1, 'corona de emax porcelana'),
(10, 10, 1, 'corona de emax monolitica'),
(11, 11, 1, 'corona de acrilico termocurado'),
(12, 12, 1, 'corona de telio ivoclar'),
(13, 13, 1, 'corona de ceromero'),
(14, 14, 1, 'corona de captek'),
(15, 15, 1, 'corona de pekkton'),
(16, 16, 1, 'corona sprinity'),
(17, 17, 1, 'corona con encia rosa'),
(18, 18, 1, 'corona de emax zirpress sobre zirconia'),
(21, 3, 3, 'incrustacion metalica total'),
(22, 19, 3, 'incrustacion de metal precioso'),
(23, 8, 3, 'incrustacion de zirconio monolitica'),
(24, 10, 3, 'incrustacion de emax monolitica'),
(25, 13, 3, 'incrustacion de ceromero'),
(26, 20, 4, 'endoposte metalico'),
(27, 21, 4, 'endoposte de zirconio'),
(28, 22, 4, 'endoposte de emax'),
(29, 22, 20, 'endocrown de emax'),
(30, 21, 20, 'endocrown de zirconio'),
(31, 24, 5, 'carilla de porcelana feldespatica'),
(32, 22, 5, 'carilla de emax'),
(33, 21, 5, 'carilla de zirconio'),
(34, 13, 5, 'carilla de ceromero'),
(35, 25, 16, 'prostodoncia total de acrilico'),
(36, 27, 16, 'prostodoncia total flexible'),
(37, 26, 16, 'prostodoncia total de teflon'),
(38, 31, 16, 'prostodoncia total de sr ivocap injection system'),
(39, 30, 16, 'prostodoncia total de lucitone frs'),
(40, 5, 19, 'protesis removible parcial bilateral de metal acrilico'),
(41, 28, 19, 'protesis removible parcial bilateral de metal flexible'),
(42, 29, 19, 'protesis removible parcial bilateral de prxiii'),
(43, 27, 19, 'protesis removible parcial bilateral flexible'),
(44, 5, 18, 'protesis removible parcial unilateral de metal acrilico'),
(45, 28, 18, 'protesis removible parcial unilateral de metal flexible'),
(46, 29, 18, 'protesis removible parcial unilateral de prxiii'),
(47, 27, 18, 'protesis removible parcial unilateral flexible'),
(48, 36, 21, 'Corona estratificada con ceramica de zirconia traslucida'),
(49, 36, 23, 'Corona monolitica solo glaseada de zirconia traslucida'),
(50, 36, 24, 'Corona monolitica maquillada de zirconia traslucida'),
(51, 36, 25, 'Corona monolitica maquillaje 3D de zirconia traslucida'),
(52, 36, 26, 'Incrustacion estratificada con ceramica de zirconia traslucida'),
(53, 36, 27, 'Incrustacion monolitica solo glaseada de zirconia traslucida'),
(54, 36, 28, 'Incrustacion monolitica maquillada de zirconia traslucida'),
(55, 36, 29, 'Incrustacion monolitica maquillaje 3D de zirconia traslucida'),
(56, 36, 30, 'Carilla estratificada con ceramica de zirconia traslucida'),
(57, 36, 31, 'Carilla monolitica solo glaseada de zirconia traslucida'),
(58, 36, 32, 'Carilla monolitica maquillada de zirconia traslucida'),
(59, 36, 33, 'Carilla monolitica maquillaje 3D de zirconia traslucida'),
(60, 37, 21, 'Corona estratificada con ceramica de zirconia extratraslucida'),
(61, 37, 23, 'Corona monolitica solo glaseada de zirconia extratraslucida'),
(62, 37, 24, 'Corona monolitica maquillada de zirconia extratraslucida'),
(63, 37, 25, 'Corona monolitica maquillaje 3D de zirconia extratraslucida'),
(64, 37, 26, 'Incrustacion estratificada con ceramica de zirconia extratraslucida'),
(65, 37, 27, 'Incrustacion monolitica solo glaseada de zirconia extratraslucida'),
(66, 37, 28, 'Incrustacion monolitica maquillada de zirconia extratraslucida'),
(67, 37, 29, 'Incrustacion monolitica maquillaje 3D de zirconia extratraslucida'),
(68, 37, 30, 'Carilla estratificada con ceramica de zirconia extratraslucida'),
(69, 37, 31, 'Carilla monolitica solo glaseada de zirconia extratraslucida'),
(70, 37, 32, 'Carilla monolitica maquillada de zirconia extratraslucida'),
(71, 37, 33, 'Carilla monolitica maquillaje 3D de zirconia extratraslucida'),
(72, 37, 34, 'Endoposte con muñon de zirconia extratraslucida'),
(73, 38, 35, 'Incrustacion monolitica de e max con cad cam'),
(74, 38, 36, 'Onlay monolitica de e max con cad cam'),
(75, 38, 37, 'Carilla monolitica de e max con cad cam'),
(76, 38, 38, 'Corona estratificada de e max con cad cam'),
(77, 38, 39, 'Incrustacion estratificada de e max con cad cam'),
(78, 38, 40, 'Carilla estratificada de e max con cad cam'),
(79, 39, 41, 'Corona estratificada con resina de grafeno'),
(80, 39, 42, 'Incrustación estratificada con resina de grafeno'),
(81, 39, 43, 'Carilla estratificada con resina de grafeno'),
(82, 39, 23, 'Corona monolitica solo glaseada de grafeno'),
(83, 39, 24, 'Corona monolitica maquillada de grafeno'),
(84, 39, 25, 'Corona monolitica maquillaje 3D de grafeno'),
(85, 39, 27, 'Incrustación monolitica solo glaseada de grafeno'),
(86, 39, 28, 'Incrustación monolitica maquillada de grafeno'),
(87, 39, 29, 'Incrustación monolitica maquillaje 3D de grafeno'),
(88, 39, 31, 'Carilla monolitica solo glaseada de grafeno'),
(89, 39, 32, 'Carilla monolitica maquillada de grafeno'),
(90, 39, 33, 'Carilla monolitica maquillaje 3D de grafeno'),
(91, 40, 3, 'Incrustación de cerámica prensada'),
(92, 40, 44, 'Onlay de ceramica prensada'),
(93, 40, 5, 'Carilla de ceramica prensada'),
(94, 40, 45, 'Corona total de ceramica prensada'),
(95, 25, 46, 'Protesis removible provisional de acrilico'),
(96, 25, 47, 'Corona provisional de acrilico'),
(97, 28, 46, 'Protesis removible provisional flexible'),
(98, 12, 47, 'Corona provisional de telio'),
(99, 25, 48, 'Protesis provisional fija de acrilico'),
(100, 1, 49, 'Corona de metal porcelana atornillada sobre implante'),
(101, 1, 50, 'Corona de metal porcelana cementada sobre implante'),
(102, 20, 51, 'Pilar de metal sobre implante'),
(103, 25, 55, 'Puente maryland de acrilico'),
(104, 21, 55, 'Puente maryland de zirconio'),
(105, 20, 55, 'Puente maryland de metal'),
(106, 32, 55, 'Puente maryland de disilicato'),
(107, 13, 55, 'Puente maryland de ceromero'),
(108, 1, 55, 'Puente maryland de metal porcelana'),
(109, 20, 52, 'Barras atornilladas sobre implante de metal'),
(110, 20, 53, 'Barras con supraestructura removibles sobre implante de metal'),
(111, 20, 54, 'Barras con supraestructura sobre implante de metal'),
(112, 41, 56, 'Chip de resina'),
(113, 22, 56, 'Chip de emax'),
(114, 23, 56, 'Chip de porcelana'),
(115, 21, 56, 'Chip de zirconio'),
(116, 42, 1, 'Corona monolitica de zirconia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `relacion`
--

CREATE TABLE `relacion` (
  `id_rel` int(11) NOT NULL,
  `id_clie` int(11) NOT NULL,
  `id_lab` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Estructura de tabla para la tabla `republica`
--

CREATE TABLE `republica` (
  `id_rep` int(11) NOT NULL,
  `nomb` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `republica`
--

INSERT INTO `republica` (`id_rep`, `nomb`) VALUES
(1, 'Aguascalientes'),
(2, 'Baja California'),
(3, 'Baja California Sur'),
(4, 'Campeche'),
(5, 'Coahuila de Zaragoza'),
(6, 'Colima'),
(7, 'Chiapas'),
(8, 'Chihuahua'),
(9, 'Distrito Federal'),
(10, 'Durango'),
(11, 'Guanajuato'),
(12, 'Guerrero'),
(13, 'Hidalgo'),
(14, 'Jalisco'),
(15, 'Estado de Mexico'),
(16, 'Michoacan'),
(17, 'Morelos'),
(18, 'Nayarit'),
(19, 'Nuevo Leon'),
(20, 'Oaxaca'),
(21, 'Puebla'),
(22, 'Queretaro'),
(23, 'Quintana Roo'),
(24, 'San Luis Potosi'),
(25, 'Sinaloa'),
(26, 'Sonora'),
(27, 'Tabasco'),
(28, 'Tamaulipas'),
(29, 'Tlaxcala'),
(30, 'Veracruz'),
(31, 'Yucatan'),
(32, 'Zacatecas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajo`
--

CREATE TABLE `trabajo` (
  `id_trab` int(11) NOT NULL,
  `nomb` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `trabajo`
--

INSERT INTO `trabajo` (`id_trab`, `nomb`) VALUES
(1, 'Corona'),
(2, 'Puente'),
(3, 'Incrustacion'),
(4, 'Endoposte'),
(5, 'Carilla'),
(6, 'Implante'),
(7, 'Provicional'),
(10, 'Prostodoncia'),
(11, 'Protesis'),
(12, 'Removible'),
(16, 'Prostodoncia total'),
(17, 'Protesis removible parcial'),
(18, 'Unilateral'),
(19, 'Bilateral'),
(20, 'Corona con endoposte'),
(21, 'Corona estratificada con ceramica'),
(23, 'Corona monolitica solo glaseada'),
(24, 'Corona monolitica maquillada'),
(25, 'Corona monolitica maquillaje 3D'),
(26, 'Incrustacion estratificada con ceramica'),
(27, 'Incrustacion monolitica solo glaseada'),
(28, 'Incrustacion monolitica maquillada'),
(29, 'Incrustacion monolitica maquillaje 3D'),
(30, 'Carilla estratificada con ceramica'),
(31, 'Carilla monolitica solo glaseada'),
(32, 'Carilla monolitica maquillada'),
(33, 'Carilla monolitica maquillaje 3D'),
(34, 'Endoposte con muñon'),
(35, 'Incrustacion monolitica'),
(36, 'Onlay monolitica'),
(37, 'Carilla monolitica'),
(38, 'Corona estratificada'),
(39, 'Incrustacion estratificada'),
(40, 'Carilla estratificada'),
(41, 'Corona estratificada con resina'),
(42, 'Incrustacion estratificada con resina'),
(43, 'Carilla estratificada con resina'),
(44, 'Onlay'),
(45, 'Corona total'),
(46, 'Protesis removible provisional'),
(47, 'Corona provisional'),
(48, 'Protesis provisional fija'),
(49, 'Corona atornillada sobre implante'),
(50, 'Corona cementada sobre implante'),
(51, 'Pilar sobre implante'),
(52, 'Barras atornilladas sobre implante'),
(53, 'Barras con supraestructura removibles sobre implante'),
(54, 'Barras con supraestructura sobre implante'),
(55, 'Puente Maryland'),
(56, 'Chip');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `correo` varchar(30) NOT NULL,
  `pass` varchar(64) NOT NULL,
  `id_clie` int(11) NOT NULL DEFAULT '0',
  `premium` tinyint(1) DEFAULT NULL,
  `activo` int(11) NOT NULL DEFAULT '0',
  `aleatorio` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Estructura de tabla para la tabla `usuarios_tecnicos`
--

CREATE TABLE `usuarios_tecnicos` (
  `correo` varchar(80) COLLATE utf8_spanish_ci NOT NULL,
  `pass` varchar(400) COLLATE utf8_spanish_ci NOT NULL,
  `id_lab` int(11) NOT NULL,
  `activo` int(11) NOT NULL DEFAULT '0',
  `aleatorio` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `imagenes`
--
ALTER TABLE `imagenes`
  ADD PRIMARY KEY (`id_archivo`);

--
-- Indices de la tabla `box`
--
ALTER TABLE `box`
  ADD PRIMARY KEY (`id_box`);

--
-- Indices de la tabla `calificacion`
--
ALTER TABLE `calificacion`
  ADD PRIMARY KEY (`id_calif`);

--
-- Indices de la tabla `dentista`
--
ALTER TABLE `dentista`
  ADD PRIMARY KEY (`id_clie`),
  ADD KEY `direccion predeterminada` (`direccion_predet`);

--
-- Indices de la tabla `color`
--
ALTER TABLE `color`
  ADD PRIMARY KEY (`id_col`,`nomb`);

--
-- Indices de la tabla `direccion`
--
ALTER TABLE `direccion`
  ADD PRIMARY KEY (`id_direc`,`id_usuario`,`calle`),
  ADD KEY `republica2` (`id_rep`);

--
-- Indices de la tabla `lista_precios_ortodoncia`
--
ALTER TABLE `lista_precios_ortodoncia`
  ADD PRIMARY KEY (`id_ort`,`id_lab`,`id_ort_prod`),
  ADD KEY `ortodoncia producto` (`id_ort_prod`),
  ADD KEY `laboratorio` (`id_lab`);

--
-- Indices de la tabla `lista_precios_ortodoncia_urg`
--
ALTER TABLE `lista_precios_ortodoncia_urg`
  ADD PRIMARY KEY (`id_ort`);

--
-- Indices de la tabla `lista_precios_protesis`
--
ALTER TABLE `lista_precios_protesis`
  ADD PRIMARY KEY (`id_lista_precios_protesis`) USING BTREE;

--
-- Indices de la tabla `lista_precios_protesis_urg`
--
ALTER TABLE `lista_precios_protesis_urg`
  ADD PRIMARY KEY (`id_lista_precios_protesis`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`id_est`,`nomb`);

--
-- Indices de la tabla `historial_orden_trabajo`
--
ALTER TABLE `historial_orden_trabajo`
  ADD PRIMARY KEY (`id_hist_ord`);

--
-- Indices de la tabla `laboratorio`
--
ALTER TABLE `laboratorio`
  ADD PRIMARY KEY (`id_lab`),
  ADD KEY `id_rep` (`id_rep`);

--
-- Indices de la tabla `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`id_mat`,`nomb`);

--
-- Indices de la tabla `mensaje`
--
ALTER TABLE `mensaje`
  ADD PRIMARY KEY (`id_mensaje`),
  ADD KEY `orden` (`id_ord`);

--
-- Indices de la tabla `notificacion`
--
ALTER TABLE `notificacion`
  ADD PRIMARY KEY (`id_notif`);

--
-- Indices de la tabla `orden_trabajo`
--
ALTER TABLE `orden_trabajo`
  ADD PRIMARY KEY (`id_ord`);

--
-- Indices de la tabla `ortodoncia_prod`
--
ALTER TABLE `ortodoncia_prod`
  ADD PRIMARY KEY (`id_ort_prod`);

--
-- Indices de la tabla `protesis`
--
ALTER TABLE `protesis`
  ADD PRIMARY KEY (`id_pro`),
  ADD KEY `id_mat` (`id_mat`),
  ADD KEY `id_trab` (`id_trab`);

--
-- Indices de la tabla `relacion`
--
ALTER TABLE `relacion`
  ADD PRIMARY KEY (`id_rel`);

--
-- Indices de la tabla `republica`
--
ALTER TABLE `republica`
  ADD PRIMARY KEY (`id_rep`);

--
-- Indices de la tabla `sepomex`
--
ALTER TABLE `sepomex`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `trabajo`
--
ALTER TABLE `trabajo`
  ADD PRIMARY KEY (`id_trab`,`nomb`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_clie`);

--
-- Indices de la tabla `usuarios_tecnicos`
--
ALTER TABLE `usuarios_tecnicos`
  ADD PRIMARY KEY (`id_lab`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `imagenes`
--
ALTER TABLE `imagenes`
  MODIFY `id_archivo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1347;

--
-- AUTO_INCREMENT de la tabla `box`
--
ALTER TABLE `box`
  MODIFY `id_box` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1483;

--
-- AUTO_INCREMENT de la tabla `calificacion`
--
ALTER TABLE `calificacion`
  MODIFY `id_calif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `dentista`
--
ALTER TABLE `dentista`
  MODIFY `id_clie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT de la tabla `color`
--
ALTER TABLE `color`
  MODIFY `id_col` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;


--
-- AUTO_INCREMENT de la tabla `direccion`
--
ALTER TABLE `direccion`
  MODIFY `id_direc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT de la tabla `lista_precios_ortodoncia`
--
ALTER TABLE `lista_precios_ortodoncia`
  MODIFY `id_ort` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=279;

--
-- AUTO_INCREMENT de la tabla `lista_precios_protesis`
--
ALTER TABLE `lista_precios_protesis`
  MODIFY `id_lista_precios_protesis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1420;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `id_est` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `historial_orden_trabajo`
--
ALTER TABLE `historial_orden_trabajo`
  MODIFY `id_hist_ord` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2818;

--
-- AUTO_INCREMENT de la tabla `laboratorio`
--
ALTER TABLE `laboratorio`
  MODIFY `id_lab` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;


--
-- AUTO_INCREMENT de la tabla `material`
--
ALTER TABLE `material`
  MODIFY `id_mat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `mensaje`
--
ALTER TABLE `mensaje`
  MODIFY `id_mensaje` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT de la tabla `notificacion`
--
ALTER TABLE `notificacion`
  MODIFY `id_notif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7535;

--
-- AUTO_INCREMENT de la tabla `ortodoncia_prod`
--
ALTER TABLE `ortodoncia_prod`
  MODIFY `id_ort_prod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=348;

--
-- AUTO_INCREMENT de la tabla `protesis`
--
ALTER TABLE `protesis`
  MODIFY `id_pro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT de la tabla `relacion`
--
ALTER TABLE `relacion`
  MODIFY `id_rel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `republica`
--
ALTER TABLE `republica`
  MODIFY `id_rep` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `trabajo`
--
ALTER TABLE `trabajo`
  MODIFY `id_trab` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;


--
-- AUTO_INCREMENT de la tabla `usuarios_tecnicos`
--
ALTER TABLE `usuarios_tecnicos`
  MODIFY `id_lab` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- Filtros para la tabla `dentista`
--
ALTER TABLE `dentista`
  ADD CONSTRAINT `direccion predeterminada` FOREIGN KEY (`direccion_predet`) REFERENCES `direccion` (`id_direc`);

--
-- Filtros para la tabla `direccion`
--
ALTER TABLE `direccion`
  ADD CONSTRAINT `republica2` FOREIGN KEY (`id_rep`) REFERENCES `sepomex` (`id`);

--
-- Filtros para la tabla `lista_precios_ortodoncia`
--
ALTER TABLE `lista_precios_ortodoncia`
  ADD CONSTRAINT `laboratorio` FOREIGN KEY (`id_lab`) REFERENCES `laboratorio` (`id_lab`),
  ADD CONSTRAINT `ortodoncia producto` FOREIGN KEY (`id_ort_prod`) REFERENCES `ortodoncia_prod` (`id_ort_prod`);

--
-- Filtros para la tabla `lista_precios_ortodoncia_urg`
--
ALTER TABLE `lista_precios_ortodoncia_urg`
  ADD CONSTRAINT `lista_precios_ortodoncia` FOREIGN KEY (`id_ort`) REFERENCES `lista_precios_ortodoncia` (`id_ort`) ON DELETE CASCADE;

--
-- Filtros para la tabla `lista_precios_protesis_urg`
--
ALTER TABLE `lista_precios_protesis_urg`
  ADD CONSTRAINT `lista_precios_protesis` FOREIGN KEY (`id_lista_precios_protesis`) REFERENCES `lista_precios_protesis` (`id_lista_precios_protesis`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `laboratorio`
--
ALTER TABLE `laboratorio`
  ADD CONSTRAINT `republica` FOREIGN KEY (`id_rep`) REFERENCES `sepomex` (`id`);

--
-- Filtros para la tabla `protesis`
--
ALTER TABLE `protesis`
  ADD CONSTRAINT `protesis_ibfk_1` FOREIGN KEY (`id_mat`) REFERENCES `material` (`id_mat`),
  ADD CONSTRAINT `protesis_ibfk_2` FOREIGN KEY (`id_trab`) REFERENCES `trabajo` (`id_trab`);

--
-- Filtros para la tabla `usuarios_tecnicos`
--
ALTER TABLE `usuarios_tecnicos`
  ADD CONSTRAINT `login` FOREIGN KEY (`id_lab`) REFERENCES `laboratorio` (`id_lab`) ON DELETE CASCADE ON UPDATE CASCADE;
