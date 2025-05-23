-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-05-2025 a las 20:35:12
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `runningweb_v2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrera`
--

CREATE TABLE `carrera` (
  `id_carrera` int(11) NOT NULL,
  `distancia` decimal(6,2) NOT NULL,
  `id_evento` int(11) DEFAULT NULL,
  `tipo_carrera_id` int(11) DEFAULT NULL,
  `id_categoria` int(11) NOT NULL,
  `id_ruta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrera`
--

INSERT INTO `carrera` (`id_carrera`, `distancia`, `id_evento`, `tipo_carrera_id`, `id_categoria`, `id_ruta`) VALUES
(1, 5.00, 3, 1, 1, 1),
(2, 10.00, 1, 2, 2, 2),
(3, 42.20, 2, 2, 4, 3),
(4, 5.00, 4, 5, 1, 4),
(5, 3.00, 5, 1, 6, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nombre`, `descripcion`) VALUES
(1, '5K', 'Carrera de cinco kilómetros para principiantes y aficionados'),
(2, '10K', 'Carrera de diez kilómetros para corredores intermedios'),
(3, '21K', 'Media maratón para corredores experimentados'),
(4, '42K', 'Maratón completa para atletas de alto rendimiento'),
(5, 'Trail Running', 'Carreras por senderos de montaña y naturaleza'),
(6, 'Carrera Infantil', 'Categoría para niños con distancias cortas'),
(7, 'Carrera Inclusiva', 'Categoría adaptada para personas con discapacidad');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciudad`
--

CREATE TABLE `ciudad` (
  `id_ciudad` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ciudad`
--

INSERT INTO `ciudad` (`id_ciudad`, `nombre`) VALUES
(1, 'Bogotá'),
(2, 'Medellín'),
(3, 'Cali'),
(4, 'Barranquilla'),
(5, 'Cartagena'),
(6, 'Bucaramanga'),
(7, 'Santa Marta'),
(8, 'Manizales'),
(9, 'Pereira'),
(10, 'Cúcuta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_usuario`
--

CREATE TABLE `compra_usuario` (
  `id_compra` int(11) NOT NULL,
  `id_ubicacion_envio` int(11) DEFAULT NULL,
  `fecha_compra` date NOT NULL,
  `fecha_entrega_real` date DEFAULT NULL,
  `fecha_envio` date DEFAULT NULL,
  `fecha_entrega_estimada` date DEFAULT NULL,
  `precio_compra` decimal(10,2) DEFAULT NULL,
  `comprador_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `corredor`
--

CREATE TABLE `corredor` (
  `id_corredor` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `ciudad_id` int(11) DEFAULT NULL,
  `equipo_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `corredor`
--

INSERT INTO `corredor` (`id_corredor`, `usuario_id`, `ciudad_id`, `equipo_id`) VALUES
(1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipo`
--

CREATE TABLE `equipo` (
  `id_equipo` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evento`
--

CREATE TABLE `evento` (
  `id_evento` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `descripcion` text DEFAULT NULL,
  `id_patrocinador` int(11) DEFAULT NULL,
  `ubicacion_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `evento`
--

INSERT INTO `evento` (`id_evento`, `nombre`, `tipo`, `fecha`, `hora`, `descripcion`, `id_patrocinador`, `ubicacion_id`) VALUES
(1, 'Carrera Bogotá 10K', 'Competitiva', '2025-08-10', '07:00:00', 'Carrera de 10 kilómetros en el corazón de Bogotá', 1, 1),
(2, 'Maratón Medellín 42K', 'Competitiva', '2025-09-15', '06:30:00', 'Maratón completa con recorrido por la ciudad', 2, 2),
(3, 'Carrera Solidaria Cali 5K', 'Solidaria', '2025-07-20', '08:00:00', 'Carrera benéfica para recaudar fondos para salud infantil', 3, 3),
(4, 'Night Run Barranquilla', 'Nocturna', '2025-10-05', '19:00:00', 'Carrera nocturna con luces y música', 4, 4),
(5, 'Carrera Patrimonial Cartagena', 'Recreativa', '2025-06-18', '07:30:00', 'Carrera turística por el centro histórico', 5, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fotos_producto`
--

CREATE TABLE `fotos_producto` (
  `id_foto_producto` int(11) NOT NULL,
  `url_foto` varchar(255) DEFAULT NULL,
  `id_producto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lista_productos_comprados`
--

CREATE TABLE `lista_productos_comprados` (
  `id_lista_compra` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `id_compra` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participacion_evento`
--

CREATE TABLE `participacion_evento` (
  `usuario_id` int(11) NOT NULL,
  `evento_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `participacion_evento`
--

INSERT INTO `participacion_evento` (`usuario_id`, `evento_id`) VALUES
(1, 1),
(1, 3),
(1, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `patrocinador`
--

CREATE TABLE `patrocinador` (
  `id_patrocinador` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `ciudad_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `patrocinador`
--

INSERT INTO `patrocinador` (`id_patrocinador`, `nombre`, `ciudad_id`) VALUES
(1, 'Adidas', 1),
(2, 'Gatorade', 2),
(3, 'Red Cross', 3),
(4, 'Claro', 4),
(5, 'MinTurismo', 5),
(6, 'Adidas', 1),
(7, 'Gatorade', 2),
(8, 'Red Cross', 3),
(9, 'Claro', 4),
(10, 'MinTurismo', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `fecha_publicacion` date DEFAULT NULL,
  `vendedor_id` int(11) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `stock` int(11) NOT NULL,
  `imagenUrl` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id_producto`, `nombre`, `descripcion`, `precio`, `fecha_publicacion`, `vendedor_id`, `categoria`, `stock`, `imagenUrl`) VALUES
(1, 'Zapatos Para correr a 100xh', 'Con estos zapatos puedes correr mucho', 300000.00, '2025-05-23', 4, 'zapatillas', 12, 'https://placehold.co/80x80?text=Barra+Energética');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rutas`
--

CREATE TABLE `rutas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `puntos` text NOT NULL,
  `distancia` decimal(10,2) NOT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rutas`
--

INSERT INTO `rutas` (`id`, `usuario_id`, `nombre`, `puntos`, `distancia`, `fecha_creacion`) VALUES
(1, 1, 'Siruma', '[[11.216926747221347,-74.21404552469541],[11.212822400605228,-74.21669554720212],[11.212927640990223,-74.21772551546384],[11.212741036607065,-74.21800768380308],[11.212338294340677,-74.2180795670538],[11.212212005616589,-74.2189056874304],[11.211064880512504,-74.21993565569211],[11.202787159254564,-74.22527217884637]]', 2.09, '2025-05-23 07:54:23'),
(2, 1, 'Siruma', '[[11.251962205529145,-74.20687866368098],[11.248763313475425,-74.21220016636654]]', 0.68, '2025-05-23 08:30:52'),
(3, 1, 'Ruta', '[[11.250279240047464,-74.21237719068814],[11.252194360399718,-74.2104781867056],[11.252715952263857,-74.20932000880386]]', 0.44, '2025-05-23 09:35:08'),
(4, 1, 'dddd', '[[11.250279240047464,-74.21237719068814],[11.252194360399718,-74.2104781867056],[11.252715952263857,-74.20932000880386]]', 0.44, '2025-05-23 09:50:11'),
(5, 1, 'dddd', '[[11.250279240047464,-74.21237719068814],[11.252194360399718,-74.2104781867056],[11.252715952263857,-74.20932000880386]]', 0.44, '2025-05-23 09:51:57'),
(6, 1, 'Ruta siruma', '[[11.250279240047464,-74.21237719068814],[11.252194360399718,-74.2104781867056],[11.252715952263857,-74.20932000880386]]', 0.44, '2025-05-23 09:53:06'),
(7, 1, 'Farit', '[[11.250279240047464,-74.21237719068814],[11.252194360399718,-74.2104781867056],[11.252715952263857,-74.20932000880386]]', 0.44, '2025-05-23 09:53:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_carrera`
--

CREATE TABLE `tipo_carrera` (
  `id_tipo_carrera` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_carrera`
--

INSERT INTO `tipo_carrera` (`id_tipo_carrera`, `nombre`, `descripcion`) VALUES
(1, 'Recreativa', 'Carreras con enfoque en la participación y el disfrute, sin carácter competitivo'),
(2, 'Competitiva', 'Carreras donde los participantes compiten por posiciones y premios'),
(3, 'Virtual', 'Carreras que se realizan de forma remota usando apps o GPS'),
(4, 'Solidaria', 'Carreras organizadas con fines benéficos o sociales'),
(5, 'Nocturna', 'Carreras que se desarrollan en horario nocturno'),
(6, 'Por relevos', 'Carreras en equipo donde los miembros corren por turnos'),
(7, 'Con obstáculos', 'Carreras que incluyen desafíos físicos como muros, barro o cuerdas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicacion`
--

CREATE TABLE `ubicacion` (
  `id_ubicacion` int(11) NOT NULL,
  `direccion` text NOT NULL,
  `descripcion` text DEFAULT NULL,
  `coordenadas` varchar(100) DEFAULT NULL,
  `id_ciudad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ubicacion`
--

INSERT INTO `ubicacion` (`id_ubicacion`, `direccion`, `descripcion`, `coordenadas`, `id_ciudad`) VALUES
(1, 'Parque Simón Bolívar', 'Lugar de salida y llegada de la carrera', '4.6584,-74.0935', 1),
(2, 'Estadio Atanasio Girardot', 'Circuito alrededor del estadio', '6.2569,-75.5905', 2),
(3, 'Boulevard del Río', 'Ruta plana a orillas del río Cali', '3.4500,-76.5320', 3),
(4, 'Malecon del Río', 'Zona turística ideal para eventos deportivos', '10.9838,-74.8019', 4),
(5, 'Castillo de San Felipe', 'Inicio de la carrera patrimonial', '10.4236,-75.5482', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `rol` varchar(20) NOT NULL DEFAULT 'corredor',
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) DEFAULT NULL,
  `correo` varchar(100) NOT NULL,
  `contrasena` varchar(100) NOT NULL,
  `sexo` varchar(10) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `fecha_registro` date DEFAULT NULL,
  `token_recuperacion` varchar(255) DEFAULT NULL,
  `token_expiracion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `rol`, `nombre`, `apellido`, `correo`, `contrasena`, `sexo`, `fecha_nacimiento`, `fecha_registro`, `token_recuperacion`, `token_expiracion`) VALUES
(1, 'corredor', 'Farit', 'Teran', 'faritteranacosta@gmail.com', '12345', 'masculino', '2004-01-04', '2025-05-23', NULL, NULL),
(4, 'vendedor', 'meyling', 'mon', 'meylingMon@gmail.com', '12345', 'femenino', '2007-05-15', '2025-05-23', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vendedor`
--

CREATE TABLE `vendedor` (
  `id_vendedor` int(11) NOT NULL,
  `nombre_tienda` varchar(100) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vendedor`
--

INSERT INTO `vendedor` (`id_vendedor`, `nombre_tienda`, `usuario_id`) VALUES
(1, 'FRE', 4);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrera`
--
ALTER TABLE `carrera`
  ADD PRIMARY KEY (`id_carrera`),
  ADD KEY `id_evento` (`id_evento`),
  ADD KEY `tipo_carrera_id` (`tipo_carrera_id`),
  ADD KEY `id_categoria` (`id_categoria`),
  ADD KEY `id_ruta` (`id_ruta`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `ciudad`
--
ALTER TABLE `ciudad`
  ADD PRIMARY KEY (`id_ciudad`);

--
-- Indices de la tabla `compra_usuario`
--
ALTER TABLE `compra_usuario`
  ADD PRIMARY KEY (`id_compra`),
  ADD KEY `id_ubicacion_envio` (`id_ubicacion_envio`),
  ADD KEY `comprador_id` (`comprador_id`);

--
-- Indices de la tabla `corredor`
--
ALTER TABLE `corredor`
  ADD PRIMARY KEY (`id_corredor`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `ciudad_id` (`ciudad_id`),
  ADD KEY `equipo_id` (`equipo_id`);

--
-- Indices de la tabla `equipo`
--
ALTER TABLE `equipo`
  ADD PRIMARY KEY (`id_equipo`);

--
-- Indices de la tabla `evento`
--
ALTER TABLE `evento`
  ADD PRIMARY KEY (`id_evento`),
  ADD KEY `id_patrocinador` (`id_patrocinador`),
  ADD KEY `ubicacion_id` (`ubicacion_id`);

--
-- Indices de la tabla `fotos_producto`
--
ALTER TABLE `fotos_producto`
  ADD PRIMARY KEY (`id_foto_producto`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `lista_productos_comprados`
--
ALTER TABLE `lista_productos_comprados`
  ADD PRIMARY KEY (`id_lista_compra`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `id_compra` (`id_compra`);

--
-- Indices de la tabla `participacion_evento`
--
ALTER TABLE `participacion_evento`
  ADD PRIMARY KEY (`usuario_id`,`evento_id`),
  ADD KEY `evento_id` (`evento_id`);

--
-- Indices de la tabla `patrocinador`
--
ALTER TABLE `patrocinador`
  ADD PRIMARY KEY (`id_patrocinador`),
  ADD KEY `ciudad_id` (`ciudad_id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `vendedor_id` (`vendedor_id`);

--
-- Indices de la tabla `rutas`
--
ALTER TABLE `rutas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `tipo_carrera`
--
ALTER TABLE `tipo_carrera`
  ADD PRIMARY KEY (`id_tipo_carrera`);

--
-- Indices de la tabla `ubicacion`
--
ALTER TABLE `ubicacion`
  ADD PRIMARY KEY (`id_ubicacion`),
  ADD KEY `id_ciudad` (`id_ciudad`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `vendedor`
--
ALTER TABLE `vendedor`
  ADD PRIMARY KEY (`id_vendedor`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrera`
--
ALTER TABLE `carrera`
  MODIFY `id_carrera` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `ciudad`
--
ALTER TABLE `ciudad`
  MODIFY `id_ciudad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `compra_usuario`
--
ALTER TABLE `compra_usuario`
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `corredor`
--
ALTER TABLE `corredor`
  MODIFY `id_corredor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `equipo`
--
ALTER TABLE `equipo`
  MODIFY `id_equipo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `evento`
--
ALTER TABLE `evento`
  MODIFY `id_evento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `fotos_producto`
--
ALTER TABLE `fotos_producto`
  MODIFY `id_foto_producto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lista_productos_comprados`
--
ALTER TABLE `lista_productos_comprados`
  MODIFY `id_lista_compra` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `patrocinador`
--
ALTER TABLE `patrocinador`
  MODIFY `id_patrocinador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `rutas`
--
ALTER TABLE `rutas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tipo_carrera`
--
ALTER TABLE `tipo_carrera`
  MODIFY `id_tipo_carrera` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `ubicacion`
--
ALTER TABLE `ubicacion`
  MODIFY `id_ubicacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `vendedor`
--
ALTER TABLE `vendedor`
  MODIFY `id_vendedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrera`
--
ALTER TABLE `carrera`
  ADD CONSTRAINT `carrera_ibfk_1` FOREIGN KEY (`id_evento`) REFERENCES `evento` (`id_evento`),
  ADD CONSTRAINT `carrera_ibfk_2` FOREIGN KEY (`tipo_carrera_id`) REFERENCES `tipo_carrera` (`id_tipo_carrera`),
  ADD CONSTRAINT `carrera_ibfk_3` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`),
  ADD CONSTRAINT `carrera_ibfk_4` FOREIGN KEY (`id_ruta`) REFERENCES `rutas` (`id`);

--
-- Filtros para la tabla `compra_usuario`
--
ALTER TABLE `compra_usuario`
  ADD CONSTRAINT `compra_usuario_ibfk_1` FOREIGN KEY (`id_ubicacion_envio`) REFERENCES `ubicacion` (`id_ubicacion`),
  ADD CONSTRAINT `compra_usuario_ibfk_2` FOREIGN KEY (`comprador_id`) REFERENCES `corredor` (`id_corredor`);

--
-- Filtros para la tabla `corredor`
--
ALTER TABLE `corredor`
  ADD CONSTRAINT `corredor_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `corredor_ibfk_2` FOREIGN KEY (`ciudad_id`) REFERENCES `ciudad` (`id_ciudad`),
  ADD CONSTRAINT `corredor_ibfk_3` FOREIGN KEY (`equipo_id`) REFERENCES `equipo` (`id_equipo`);

--
-- Filtros para la tabla `evento`
--
ALTER TABLE `evento`
  ADD CONSTRAINT `evento_ibfk_1` FOREIGN KEY (`id_patrocinador`) REFERENCES `patrocinador` (`id_patrocinador`),
  ADD CONSTRAINT `evento_ibfk_2` FOREIGN KEY (`ubicacion_id`) REFERENCES `ubicacion` (`id_ubicacion`);

--
-- Filtros para la tabla `fotos_producto`
--
ALTER TABLE `fotos_producto`
  ADD CONSTRAINT `fotos_producto_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`);

--
-- Filtros para la tabla `lista_productos_comprados`
--
ALTER TABLE `lista_productos_comprados`
  ADD CONSTRAINT `lista_productos_comprados_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id_producto`),
  ADD CONSTRAINT `lista_productos_comprados_ibfk_2` FOREIGN KEY (`id_compra`) REFERENCES `compra_usuario` (`id_compra`);

--
-- Filtros para la tabla `participacion_evento`
--
ALTER TABLE `participacion_evento`
  ADD CONSTRAINT `participacion_evento_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `participacion_evento_ibfk_2` FOREIGN KEY (`evento_id`) REFERENCES `evento` (`id_evento`);

--
-- Filtros para la tabla `patrocinador`
--
ALTER TABLE `patrocinador`
  ADD CONSTRAINT `patrocinador_ibfk_1` FOREIGN KEY (`ciudad_id`) REFERENCES `ciudad` (`id_ciudad`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`vendedor_id`) REFERENCES `vendedor` (`usuario_id`);

--
-- Filtros para la tabla `rutas`
--
ALTER TABLE `rutas`
  ADD CONSTRAINT `rutas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `ubicacion`
--
ALTER TABLE `ubicacion`
  ADD CONSTRAINT `ubicacion_ibfk_1` FOREIGN KEY (`id_ciudad`) REFERENCES `ciudad` (`id_ciudad`);

--
-- Filtros para la tabla `vendedor`
--
ALTER TABLE `vendedor`
  ADD CONSTRAINT `vendedor_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
