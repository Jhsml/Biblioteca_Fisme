-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-07-2025 a las 05:33:45
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
-- Base de datos: `untrm`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autores`
--

CREATE TABLE `autores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `nacionalidad` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `autores`
--

INSERT INTO `autores` (`id`, `nombre`, `apellido`, `fecha_nacimiento`, `nacionalidad`) VALUES
(1, 'Ramez', 'Elmasri', NULL, NULL),
(2, 'Joshua', 'Bloch', NULL, NULL),
(3, 'Stuart', 'Russell', NULL, NULL),
(4, 'Peter', 'Norvig', NULL, NULL),
(5, 'Kenneth', 'Rosen', NULL, NULL),
(6, 'Erich', 'Gamma', NULL, NULL),
(7, 'Richard', 'Helm', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `editoriales`
--

CREATE TABLE `editoriales` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `editoriales`
--

INSERT INTO `editoriales` (`id`, `nombre`, `direccion`, `telefono`) VALUES
(1, 'McGraw-Hill', 'Av. Principal 123', '012345678'),
(2, 'Pearson', 'Calle Secundaria 456', '098765432'),
(3, 'Springer', 'Jr. Tecnología 789', '011122233');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ejemplares`
--

CREATE TABLE `ejemplares` (
  `id` int(11) NOT NULL,
  `libro_id` int(11) NOT NULL,
  `escuela_id` int(11) NOT NULL,
  `estado` enum('disponible','prestado','dañado','perdido') DEFAULT 'disponible',
  `ubicacion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ejemplares`
--

INSERT INTO `ejemplares` (`id`, `libro_id`, `escuela_id`, `estado`, `ubicacion`) VALUES
(9, 1, 2, 'disponible', 'Estante A1 - Biblioteca Central'),
(10, 1, 2, 'disponible', 'Estante A1 - Biblioteca Central'),
(11, 2, 2, 'disponible', 'Estante B3 - Biblioteca Central'),
(12, 2, 2, 'disponible', 'Estante B3 - Biblioteca Central'),
(13, 3, 2, 'disponible', 'Estante C5 - Biblioteca Central'),
(14, 4, 2, 'disponible', 'Estante D2 - Biblioteca Central'),
(15, 4, 2, 'disponible', 'Estante D2 - Biblioteca Central'),
(16, 5, 2, 'disponible', 'Estante E4 - Biblioteca Central'),
(17, 1, 2, 'disponible', 'Estante A1 - Biblioteca Central'),
(18, 1, 2, 'disponible', 'Estante A1 - Biblioteca Central'),
(19, 2, 2, 'disponible', 'Estante B3 - Biblioteca Central'),
(20, 2, 2, 'disponible', 'Estante B3 - Biblioteca Central'),
(21, 3, 2, 'disponible', 'Estante C5 - Biblioteca Central'),
(22, 4, 2, 'disponible', 'Estante D2 - Biblioteca Central'),
(23, 4, 2, 'disponible', 'Estante D2 - Biblioteca Central'),
(24, 5, 2, 'disponible', 'Estante E4 - Biblioteca Central');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `escuelas`
--

CREATE TABLE `escuelas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `facultad_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `escuelas`
--

INSERT INTO `escuelas` (`id`, `nombre`, `facultad_id`) VALUES
(2, 'Ingeniería de Sistemas', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `faculties`
--

CREATE TABLE `faculties` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `faculties`
--

INSERT INTO `faculties` (`id`, `nombre`) VALUES
(1, 'Ingeniería');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `generos`
--

CREATE TABLE `generos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `generos`
--

INSERT INTO `generos` (`id`, `nombre`) VALUES
(1, 'Ingeniería de Sistemas'),
(2, 'Programación'),
(3, 'Matemáticas'),
(4, 'Base de Datos'),
(5, 'Inteligencia Artificial');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros`
--

CREATE TABLE `libros` (
  `id` int(11) NOT NULL,
  `isbn` varchar(13) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `publicacion_anio` year(4) DEFAULT NULL,
  `editorial_id` int(11) NOT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `libros`
--

INSERT INTO `libros` (`id`, `isbn`, `titulo`, `descripcion`, `publicacion_anio`, `editorial_id`, `fecha_registro`) VALUES
(1, '978-007802215', 'Sistemas de Bases de Datos', 'Fundamentos de diseño e implementación de bases de datos', '2019', 1, '2025-07-01 13:51:49'),
(2, '978-013468599', 'Effective Java', 'Mejores prácticas para el programador Java', '2018', 2, '2025-07-01 13:51:49'),
(3, '978-303042388', 'Artificial Intelligence: A Modern Approach', 'Guía completa sobre IA y machine learning', '2020', 3, '2025-07-01 13:51:49'),
(4, '978-013461328', 'Discrete Mathematics and Its Applications', 'Matemáticas discretas para ciencias de la computación', '2018', 1, '2025-07-01 13:51:49'),
(5, '978-020163361', 'Design Patterns', 'Elementos de software orientado a objetos reutilizable', '1994', 2, '2025-07-01 13:51:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros_autores`
--

CREATE TABLE `libros_autores` (
  `libro_id` int(11) NOT NULL,
  `autor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `libros_autores`
--

INSERT INTO `libros_autores` (`libro_id`, `autor_id`) VALUES
(1, 1),
(2, 2),
(3, 3),
(3, 4),
(4, 5),
(5, 6),
(5, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros_generos`
--

CREATE TABLE `libros_generos` (
  `libro_id` int(11) NOT NULL,
  `genero_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `libros_generos`
--

INSERT INTO `libros_generos` (`libro_id`, `genero_id`) VALUES
(1, 4),
(2, 2),
(3, 1),
(3, 5),
(4, 1),
(4, 3),
(5, 1),
(5, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `multas`
--

CREATE TABLE `multas` (
  `id` int(11) NOT NULL,
  `prestamo_id` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','pagada') DEFAULT 'pendiente',
  `fecha_emision` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE `prestamos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `ejemplar_id` int(11) NOT NULL,
  `fecha_prestamo` date NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `fecha_devolucion` date DEFAULT NULL,
  `estado` varchar(20) DEFAULT 'activo' CHECK (`estado` in ('activo','devuelto','perdido','reservado')),
  `observaciones` text DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`) VALUES
(1, 'Usuario General'),
(2, 'Bibliotecario'),
(3, 'Administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `table_cliente`
--

CREATE TABLE `table_cliente` (
  `IDCLIENTE` int(11) NOT NULL,
  `NOMBRE_CLIENTE` varchar(45) NOT NULL,
  `APELLIDO_CLIENTE` varchar(45) NOT NULL,
  `CEDULA_CLIENTE` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `table_facturas`
--

CREATE TABLE `table_facturas` (
  `NO_FACTURAS` int(11) NOT NULL,
  `CLIENTE` int(11) NOT NULL,
  `FECHA` date NOT NULL,
  `VENDEDOR` int(11) NOT NULL,
  `TOTALS` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `table_productos`
--

CREATE TABLE `table_productos` (
  `IDPRODUCTOS` varchar(45) NOT NULL,
  `NOMBREPRODUCTOS` varchar(45) NOT NULL,
  `PRECIOSPRODUCTOS` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `table_vendedor`
--

CREATE TABLE `table_vendedor` (
  `IDVENDEDOR` int(11) NOT NULL,
  `NOMBREVENDEDOR` varchar(55) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `table_vendedor`
--

INSERT INTO `table_vendedor` (`IDVENDEDOR`, `NOMBREVENDEDOR`) VALUES
(1, 'Pedro Perico de los Palotes');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `table_ventas`
--

CREATE TABLE `table_ventas` (
  `IDVENTAS` int(11) NOT NULL,
  `NO_FACTURAS` int(11) NOT NULL,
  `PRODUCTOS` varchar(45) NOT NULL,
  `CANTIDAD` int(11) NOT NULL,
  `IMPORTE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `tipo_documento` varchar(20) NOT NULL,
  `num_documento` varchar(20) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `escuela_id` int(11) NOT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_completo`, `correo`, `contraseña`, `tipo_documento`, `num_documento`, `rol_id`, `escuela_id`, `fecha_registro`) VALUES
(3, 'Juan Pérez', 'juan.bibliotecario@untrm.edu.pe', '12345', 'DNI', '45678912', 2, 2, '2025-07-01 13:50:33'),
(5, 'María López', 'maria.admin@untrum.edu.pe', 'admin123', 'DNI', '12345678', 3, 2, '2025-07-01 13:51:21'),
(6, 'donal', 'd@gmail.com', '$2y$10$T4UK1Kdoe/jhEgUKxJTBGOVP029AgqsjEXfS77Wl9AEQr/YWZ8g/.', 'DNI', '12345677', 1, 2, '2025-07-08 17:55:37'),
(7, 'admin', 'j@gmail.com', '$2y$10$OOk4pg1JRjGz7fBLZl2D9eX.2kUzjfbM/vomoSQHRLeM9l.VgwF5q', 'DNI', '87654321', 2, 2, '2025-07-08 18:06:24'),
(8, 'jefe', 'adm@gmail.com', '$2y$10$Ut2OLJJdWYueC.HuuXAlb.mnNtc401r9Ucj8sybOm/GvvRm.AXtr2', 'DNI', '80080031', 3, 2, '2025-07-08 19:34:07');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `autores`
--
ALTER TABLE `autores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `editoriales`
--
ALTER TABLE `editoriales`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ejemplares`
--
ALTER TABLE `ejemplares`
  ADD PRIMARY KEY (`id`),
  ADD KEY `libro_id` (`libro_id`),
  ADD KEY `escuela_id` (`escuela_id`);

--
-- Indices de la tabla `escuelas`
--
ALTER TABLE `escuelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `facultad_id` (`facultad_id`);

--
-- Indices de la tabla `faculties`
--
ALTER TABLE `faculties`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `generos`
--
ALTER TABLE `generos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `libros`
--
ALTER TABLE `libros`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `isbn` (`isbn`),
  ADD KEY `editorial_id` (`editorial_id`);

--
-- Indices de la tabla `libros_autores`
--
ALTER TABLE `libros_autores`
  ADD PRIMARY KEY (`libro_id`,`autor_id`),
  ADD KEY `autor_id` (`autor_id`);

--
-- Indices de la tabla `libros_generos`
--
ALTER TABLE `libros_generos`
  ADD PRIMARY KEY (`libro_id`,`genero_id`),
  ADD KEY `genero_id` (`genero_id`);

--
-- Indices de la tabla `multas`
--
ALTER TABLE `multas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prestamo_id` (`prestamo_id`);

--
-- Indices de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `ejemplar_id` (`ejemplar_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `table_cliente`
--
ALTER TABLE `table_cliente`
  ADD PRIMARY KEY (`IDCLIENTE`);

--
-- Indices de la tabla `table_facturas`
--
ALTER TABLE `table_facturas`
  ADD PRIMARY KEY (`NO_FACTURAS`);

--
-- Indices de la tabla `table_productos`
--
ALTER TABLE `table_productos`
  ADD PRIMARY KEY (`IDPRODUCTOS`);

--
-- Indices de la tabla `table_vendedor`
--
ALTER TABLE `table_vendedor`
  ADD PRIMARY KEY (`IDVENDEDOR`);

--
-- Indices de la tabla `table_ventas`
--
ALTER TABLE `table_ventas`
  ADD PRIMARY KEY (`IDVENTAS`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD UNIQUE KEY `num_documento` (`num_documento`),
  ADD KEY `rol_id` (`rol_id`),
  ADD KEY `escuela_id` (`escuela_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `autores`
--
ALTER TABLE `autores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `editoriales`
--
ALTER TABLE `editoriales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ejemplares`
--
ALTER TABLE `ejemplares`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `escuelas`
--
ALTER TABLE `escuelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `faculties`
--
ALTER TABLE `faculties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `generos`
--
ALTER TABLE `generos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `libros`
--
ALTER TABLE `libros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `multas`
--
ALTER TABLE `multas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `table_cliente`
--
ALTER TABLE `table_cliente`
  MODIFY `IDCLIENTE` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `table_facturas`
--
ALTER TABLE `table_facturas`
  MODIFY `NO_FACTURAS` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `table_vendedor`
--
ALTER TABLE `table_vendedor`
  MODIFY `IDVENDEDOR` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `table_ventas`
--
ALTER TABLE `table_ventas`
  MODIFY `IDVENTAS` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ejemplares`
--
ALTER TABLE `ejemplares`
  ADD CONSTRAINT `ejemplares_ibfk_1` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`id`),
  ADD CONSTRAINT `ejemplares_ibfk_2` FOREIGN KEY (`escuela_id`) REFERENCES `escuelas` (`id`);

--
-- Filtros para la tabla `escuelas`
--
ALTER TABLE `escuelas`
  ADD CONSTRAINT `escuelas_ibfk_1` FOREIGN KEY (`facultad_id`) REFERENCES `faculties` (`id`);

--
-- Filtros para la tabla `libros`
--
ALTER TABLE `libros`
  ADD CONSTRAINT `libros_ibfk_1` FOREIGN KEY (`editorial_id`) REFERENCES `editoriales` (`id`);

--
-- Filtros para la tabla `libros_autores`
--
ALTER TABLE `libros_autores`
  ADD CONSTRAINT `libros_autores_ibfk_1` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`id`),
  ADD CONSTRAINT `libros_autores_ibfk_2` FOREIGN KEY (`autor_id`) REFERENCES `autores` (`id`);

--
-- Filtros para la tabla `libros_generos`
--
ALTER TABLE `libros_generos`
  ADD CONSTRAINT `libros_generos_ibfk_1` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`id`),
  ADD CONSTRAINT `libros_generos_ibfk_2` FOREIGN KEY (`genero_id`) REFERENCES `generos` (`id`);

--
-- Filtros para la tabla `multas`
--
ALTER TABLE `multas`
  ADD CONSTRAINT `multas_ibfk_1` FOREIGN KEY (`prestamo_id`) REFERENCES `prestamos` (`id`);

--
-- Filtros para la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD CONSTRAINT `prestamos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `prestamos_ibfk_2` FOREIGN KEY (`ejemplar_id`) REFERENCES `ejemplares` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`escuela_id`) REFERENCES `escuelas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
