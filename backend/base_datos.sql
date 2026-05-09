-- =========================================================
-- PROYECTO: MOSES - VERSIÓN FINAL DEFINITIVA
-- =========================================================
-- DESCRIPCIÓN:
-- Sistema de administración para control de:
--  * Usuarios
--  * Operadores
--  * Vehículos
--  * Llantas
--  * Bitácora de movimientos
--
-- El sistema permite administrar unidades vehiculares,
-- controlar el estado mecánico y de llantas, registrar
-- operadores y almacenar acciones realizadas dentro del sistema.
-- =========================================================



-- =========================================================
-- CREACIÓN DE BASE DE DATOS
-- =========================================================

-- Crea la base de datos únicamente si no existe
CREATE DATABASE IF NOT EXISTS moses;

-- Selecciona la base de datos para trabajar
USE moses;



-- =========================================================
-- TABLA: Usuarios
-- =========================================================
-- FUNCIÓN:
-- Almacena los usuarios que pueden ingresar al sistema.
--
-- ROLES:
-- A = Administrador
-- O = Operador
-- R = Reparador
--
-- ESTATUS:
-- A = Activo
-- I = Inactivo
-- =========================================================

CREATE TABLE Usuarios (

    -- Identificador único del usuario
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,

    -- Nombre completo del usuario
    nombre VARCHAR(100) NOT NULL,

    -- Nombre de usuario para inicio de sesión
    usuario VARCHAR(50) UNIQUE NOT NULL,

    -- Contraseña del usuario
    password VARCHAR(255) NOT NULL,

    -- Rol asignado al usuario
    rol ENUM('A', 'O', 'R') NOT NULL,

    -- Estatus actual del usuario
    estatus CHAR(1) DEFAULT 'A'
);



-- =========================================================
-- TABLA: Operadores
-- =========================================================
-- FUNCIÓN:
-- Guarda la información de los operadores de vehículos.
--
-- ESTATUS:
-- A = Activo
-- I = Inactivo
-- =========================================================

CREATE TABLE Operadores (

    -- Identificador único del operador
    id_operador INT AUTO_INCREMENT PRIMARY KEY,

    -- Nombre completo del operador
    nombre VARCHAR(100) NOT NULL,

    -- Número de licencia del operador
    licencia VARCHAR(50) NOT NULL,

    -- Número telefónico del operador
    telefono VARCHAR(20),

    -- Estatus del operador
    estatus CHAR(1) DEFAULT 'A'
);



-- =========================================================
-- TABLA: Vehiculos
-- =========================================================
-- FUNCIÓN:
-- Almacena la información general de las unidades vehiculares.
--
-- ESTATUS MECÁNICO:
-- O = OK
-- F = Falla
--
-- ESTATUS LLANTAS:
-- O = OK
-- F = Falla
--
-- VALIDACIÓN ADMIN:
-- P = Pendiente
-- V = Validado
--
-- ESTATUS DEL VEHÍCULO:
-- A = Activo
-- T = Taller
-- B = Baja
-- =========================================================

CREATE TABLE Vehiculos (

    -- Identificador único del vehículo
    id_vehiculo INT AUTO_INCREMENT PRIMARY KEY,

    -- Número de placas
    placas VARCHAR(20) UNIQUE NOT NULL,

    -- Número interno de la unidad
    numero_unidad VARCHAR(50),

    -- Modelo del vehículo
    modelo VARCHAR(100),

    -- Año del vehículo
    anio INT,

    -- Estado mecánico de la unidad
    estatus_mecanico CHAR(1) DEFAULT 'O',

    -- Estado de las llantas
    estatus_llantas CHAR(1) DEFAULT 'O',

    -- Validación realizada por administrador
    validacion_admin CHAR(1) DEFAULT 'P',

    -- Estado operativo del vehículo
    estatus_vh CHAR(1) DEFAULT 'A'
);



-- =========================================================
-- TABLA: Llantas
-- =========================================================
-- FUNCIÓN:
-- Almacena información sobre las llantas instaladas
-- en los vehículos.
--
-- ESTADO FÍSICO:
-- N = Nueva
-- U = Usada
-- R = Reutilizada
-- D = Dañada
--
-- NIVEL DE ESTADO:
-- V = Verde
-- A = Amarillo
-- R = Rojo
-- =========================================================

CREATE TABLE Llantas (

    -- Identificador único de la llanta
    id_llanta INT AUTO_INCREMENT PRIMARY KEY,

    -- Relación con el vehículo
    id_vehiculo INT,

    -- Clave del producto o servicio
    clave_producto_servicio INT NOT NULL,

    -- Kilometraje recorrido por la llanta
    kilometraje DECIMAL(10,2) DEFAULT 0,

    -- Estado físico de la llanta
    estado_fisico CHAR(1) DEFAULT 'N',

    -- Nivel visual tipo semáforo
    nivel_estado CHAR(1) DEFAULT 'V',

    -- Fecha de registro automática
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    -- Llave foránea hacia la tabla Vehiculos
    FOREIGN KEY (id_vehiculo)
    REFERENCES Vehiculos(id_vehiculo)
    ON DELETE SET NULL
);



-- =========================================================
-- TABLA: Bitacora
-- =========================================================
-- FUNCIÓN:
-- Guarda el historial de movimientos y acciones
-- realizadas dentro del sistema.
-- =========================================================

CREATE TABLE Bitacora (

    -- Identificador único del movimiento
    id_movimiento INT AUTO_INCREMENT PRIMARY KEY,

    -- Usuario que realizó la acción
    id_usuario INT,

    -- Descripción de la acción realizada
    accion TEXT NOT NULL,

    -- Módulo donde ocurrió la acción
    modulo VARCHAR(50),

    -- Fecha y hora automática del movimiento
    fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    -- Llave foránea hacia Usuarios
    FOREIGN KEY (id_usuario)
    REFERENCES Usuarios(id_usuario)
);



-- =========================================================
-- INSERCIÓN DE DATOS DE PRUEBA
-- =========================================================
-- Estos registros permiten probar el funcionamiento
-- inicial del sistema.
-- =========================================================



-- =========================================================
-- DATOS DE PRUEBA: Usuarios
-- =========================================================
-- Se agrega un usuario por cada rol disponible.
-- =========================================================

INSERT INTO Usuarios
(nombre, usuario, password, rol, estatus)
VALUES

('Azul Rodriguez', 'azul_admin', 'admin123', 'A', 'A'),

('Marcos Lopez', 'marcos_op', 'op456', 'O', 'A'),

-- Usuario reparador inactivo
('Ricardo Perez', 'ricardo_rep', 'rep789', 'R', 'I');



-- =========================================================
-- DATOS DE PRUEBA: Operadores
-- =========================================================

INSERT INTO Operadores
(nombre, licencia, telefono, estatus)
VALUES

('Carlos Sanchez', 'L-554433', '3310001122', 'A'),

('Roberto Diaz', 'L-221100', '3322334455', 'A'),

-- Operador inactivo
('Mario Ruiz', 'L-998877', '3399887766', 'I');



-- =========================================================
-- DATOS DE PRUEBA: Vehículos
-- =========================================================
-- Se insertan vehículos con diferentes estados.
-- =========================================================

INSERT INTO Vehiculos
(
    placas,
    numero_unidad,
    modelo,
    anio,
    estatus_mecanico,
    estatus_llantas,
    estatus_vh
)
VALUES

('JAL-1010', 'ECO-01', 'Freightliner', 2022, 'O', 'O', 'A'),

('MEX-2020', 'ECO-02', 'Kenworth', 2021, 'F', 'O', 'T'),

('GTO-3030', 'ECO-03', 'International', 2023, 'O', 'F', 'A');



-- =========================================================
-- DATOS DE PRUEBA: Llantas
-- =========================================================

INSERT INTO Llantas
(
    id_vehiculo,
    clave_producto_servicio,
    kilometraje,
    estado_fisico,
    nivel_estado
)
VALUES

(1, 90001, 15000.50, 'N', 'V'),

(2, 90002, 45000.00, 'U', 'A'),

(3, 90003, 80000.25, 'R', 'R');



-- =========================================================
-- DATOS DE PRUEBA: Bitácora
-- =========================================================

INSERT INTO Bitacora
(id_usuario, accion, modulo)
VALUES

(1, 'Configuración inicial del sistema MOSES', 'Sistema'),

(2, 'Cambio de estatus a Falla (F) en unidad ECO-02', 'Vehiculos'),

(3, 'Actualización de kilometraje en llanta 90003', 'Llantas');



-- =========================================================
-- CONSULTAS DE VERIFICACIÓN
-- =========================================================
-- Permiten visualizar los registros almacenados
-- en las diferentes tablas del sistema.
-- =========================================================

SELECT * FROM Usuarios;

SELECT * FROM Llantas;

SELECT * FROM Vehiculos;

SELECT * FROM Operadores;

SELECT * FROM Bitacora;



-- =========================================================
-- REGISTROS EXTRA DE PRUEBA
-- =========================================================
-- Usuarios creados para pruebas adicionales
-- de autenticación y manejo de roles.
-- =========================================================

INSERT INTO Usuarios
(nombre, usuario, password, rol, estatus)
VALUES

('testuser', 'testuser', '12345', 'A', 'A');



INSERT INTO Usuarios
(nombre, usuario, password, rol, estatus)
VALUES

('testuser', 'user1', '12345', 'O', 'A');



INSERT INTO Usuarios
(nombre, usuario, password, rol, estatus)
VALUES

('testuser', 'user2', '12345', 'R', 'A');



-- =========================================================
-- CONSULTA FINAL DE VERIFICACIÓN
-- =========================================================

SELECT * FROM Usuarios;