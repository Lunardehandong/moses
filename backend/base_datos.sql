-- =============================================
-- PROYECTO: MOSES - VERSIÓN FINAL DEFINITIVA
-- =============================================

CREATE DATABASE IF NOT EXISTS moses;
USE moses;

-- 1. TABLA: Usuarios
-- Roles: A (Admin), O (Operador), R (Reparador)
-- Estatus: A (Activo), I (Inactivo)
CREATE TABLE Usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('A', 'O', 'R') NOT NULL,
    estatus CHAR(1) DEFAULT 'A' 
);

-- 2. TABLA: Operadores
-- Estatus: A (Activo), I (Inactivo)
CREATE TABLE Operadores (
    id_operador INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    licencia VARCHAR(50) NOT NULL,
    telefono VARCHAR(20),
    estatus CHAR(1) DEFAULT 'A'
);

-- 3. TABLA: Vehiculos
-- Estatus Mecánico/Llantas: O (OK), F (Falla)
-- Validación Admin: P (Pendiente), V (Validado)
-- Estatus VH: A (Activo), T (Taller), B (Baja)
CREATE TABLE Vehiculos (
    id_vehiculo INT AUTO_INCREMENT PRIMARY KEY,
    placas VARCHAR(20) UNIQUE NOT NULL,
    numero_unidad VARCHAR(50),
    modelo VARCHAR(100),
    anio INT,
    estatus_mecanico CHAR(1) DEFAULT 'O', 
    estatus_llantas CHAR(1) DEFAULT 'O',   
    validacion_admin CHAR(1) DEFAULT 'P',  
    estatus_vh CHAR(1) DEFAULT 'A'         
);

-- 4. TABLA: Llantas
-- Estado Físico: N (Nueva), U (Usada), R (Reutilizada), D (Dañada)
-- Nivel Estado (Semáforo): V (Verde), A (Amarillo), R (Rojo)
CREATE TABLE Llantas (
    id_llanta INT AUTO_INCREMENT PRIMARY KEY,
    id_vehiculo INT,
    clave_producto_servicio INT NOT NULL,
    kilometraje DECIMAL(10,2) DEFAULT 0,
    estado_fisico CHAR(1) DEFAULT 'N', 
    nivel_estado CHAR(1) DEFAULT 'V',  
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_vehiculo) REFERENCES Vehiculos(id_vehiculo) ON DELETE SET NULL
);

-- 5. TABLA: Bitacora
CREATE TABLE Bitacora (
    id_movimiento INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    accion TEXT NOT NULL,
    modulo VARCHAR(50),
    fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);

-- =============================================
-- INSERCIÓN DE DATOS DE PRUEBA
-- =============================================

-- Usuarios (1 de cada rol)
INSERT INTO Usuarios (nombre, usuario, password, rol, estatus) VALUES 
('Azul Rodriguez', 'azul_admin', 'admin123', 'A', 'A'),
('Marcos Lopez', 'marcos_op', 'op456', 'O', 'A'),
('Ricardo Perez', 'ricardo_rep', 'rep789', 'R', 'I'); -- Ejemplo de usuario Inactivo

-- Operadores
INSERT INTO Operadores (nombre, licencia, telefono, estatus) VALUES 
('Carlos Sanchez', 'L-554433', '3310001122', 'A'),
('Roberto Diaz', 'L-221100', '3322334455', 'A'),
('Mario Ruiz', 'L-998877', '3399887766', 'I'); -- Ejemplo de operador Inactivo

-- Vehiculos (3 estados operativos distintos)
INSERT INTO Vehiculos (placas, numero_unidad, modelo, anio, estatus_mecanico, estatus_llantas, estatus_vh) VALUES 
('JAL-1010', 'ECO-01', 'Freightliner', 2022, 'O', 'O', 'A'),
('MEX-2020', 'ECO-02', 'Kenworth', 2021, 'F', 'O', 'T'),
('GTO-3030', 'ECO-03', 'International', 2023, 'O', 'F', 'A');

-- Llantas (Usando N de Nueva y R de Reutilizada)
INSERT INTO Llantas (id_vehiculo, clave_producto_servicio, kilometraje, estado_fisico, nivel_estado) VALUES 
(1, 90001, 15000.50, 'N', 'V'),
(2, 90002, 45000.00, 'U', 'A'),
(3, 90003, 80000.25, 'R', 'R');

-- Bitacora
INSERT INTO Bitacora (id_usuario, accion, modulo) VALUES 
(1, 'Configuración inicial del sistema MOSES', 'Sistema'),
(2, 'Cambio de estatus a Falla (F) en unidad ECO-02', 'Vehiculos'),
(3, 'Actualización de kilometraje en llanta 90003', 'Llantas');

SELECT * FROM USUARIOS;

select* from llantas;

select * from vehiculos;

INSERT INTO Usuarios (nombre, usuario, password, rol, estatus) VALUES 
('testuser', 'testuser', '12345', 'A', 'A');

INSERT INTO Usuarios (nombre, usuario, password, rol, estatus) VALUES 
('testuser', 'user1', '12345', 'O', 'A');
INSERT INTO Usuarios (nombre, usuario, password, rol, estatus) VALUES 
('testuser', 'user2', '12345', 'R', 'A');

select * from usuarios;