-- =============================================
-- PROYECTO: MOSES
-- DESCRIPCIÓN: Script unificado de Base de Datos
-- =============================================

CREATE DATABASE IF NOT EXISTS moses_db;
USE moses_db;

-- 1. TABLA DE USUARIOS
-- Maneja el acceso por 'usuario' y define el rol del personal.
CREATE TABLE Usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('A', 'O', 'R') NOT NULL -- A: Admin, O: Operador, R: Reparador
);

-- 2. TABLA DE VEHICULOS
-- Controla el estado de seguridad y la validación final del Administrador.
CREATE TABLE Vehiculos (
    id_vehiculo INT AUTO_INCREMENT PRIMARY KEY,
    placas VARCHAR(20) NOT NULL,
    aseguradora VARCHAR(100),
    vencimiento_seguro DATE,
    estatus_mecanico CHAR(1) DEFAULT 'O', -- 'O': OK, 'F': Falla
    estatus_llantas CHAR(1) DEFAULT 'O',   -- 'O': OK, 'F': Falla
    validacion_admin CHAR(1) DEFAULT 'P'  -- 'V': Validado, 'P': Pendiente
);

-- 3. TABLA DE LLANTAS
-- Registra los datos técnicos y de desgaste de cada neumático.
CREATE TABLE Llantas (
    id_llanta INT AUTO_INCREMENT PRIMARY KEY,
    id_vehiculo INT,
    clave_producto_servicio INT NOT NULL, 
    presion DECIMAL(5,2),
    remanente DECIMAL(5,2),
    FOREIGN KEY (id_vehiculo) REFERENCES Vehiculos(id_vehiculo) ON DELETE CASCADE
);

-- 4. TABLA DE BITACORA (AUDITORÍA)
-- Registra quién realiza cambios en el sistema para control del Administrador.
CREATE TABLE Bitacora_Eventos (
    id_evento INT AUTO_INCREMENT PRIMARY KEY,
    id_vehiculo INT,
    id_llanta INT NULL,
    id_usuario INT,
    descripcion TEXT,
    fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_vehiculo) REFERENCES Vehiculos(id_vehiculo),
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);

-- =============================================
-- INSERCIÓN DE DATOS DE PRUEBA
-- =============================================

-- Insertar Usuarios (Acceso por usuario)
INSERT INTO Usuarios (nombre, usuario, password, rol) VALUES 
('Azul Rodriguez', 'azul_admin', 'pass123', 'A'),
('Juan Perez', 'juan_op', 'pass456', 'O'),
('Pedro Gomez', 'pedro_rep', 'pass789', 'R');

-- Insertar Vehículos con distintos escenarios de validación
INSERT INTO Vehiculos (placas, aseguradora, vencimiento_seguro, estatus_mecanico, estatus_llantas, validacion_admin) VALUES 
('ABC-123', 'Qualitas', '2027-01-15', 'O', 'O', 'P'), -- Escenario: Listo para validación Admin
('XYZ-789', 'AXA Seguros', '2026-12-01', 'F', 'O', 'P'), -- Escenario: Requiere Reparador Mecánico
('LMN-456', 'GNP', '2026-05-20', 'O', 'F', 'P');        -- Escenario: Requiere Reparador de Llantas

-- Insertar Llantas (Usando claves de producto numéricas)
INSERT INTO Llantas (id_vehiculo, clave_producto_servicio, presion, remanente) VALUES 
(1, 100100, 110.5, 8.5), -- Camión 1: Estado Normal (Verde)
(2, 200200, 105.0, 4.2), -- Camión 2: Estado Alerta (Amarillo)
(3, 300300, 95.0, 2.1);   -- Camión 3: Estado Crítico (Rojo)

-- Insertar un evento inicial en la bitácora
INSERT INTO Bitacora_Eventos (id_vehiculo, id_usuario, descripcion) VALUES 
(2, 2, 'Inspección de rutina: se detectó falla mecánica (F) en motor por el Operador.');