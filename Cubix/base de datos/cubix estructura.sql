-- ============================================
-- CREACIÓN DE LA BASE DE DATOS
-- ============================================

DROP DATABASE IF EXISTS Cubix;
CREATE DATABASE Cubix;
USE Cubix;

-- ============================================
-- TABLA: Cubo
-- ============================================

CREATE TABLE Cubo (

    ID_Cubo         INT AUTO_INCREMENT PRIMARY KEY,
    Marca           VARCHAR(10),
    Modelo          VARCHAR(50),
    enlace_compra   VARCHAR(200)

);

-- ============================================
-- TABLA: Algoritmo
-- ============================================

CREATE TABLE Algoritmo (

    ID_Algoritmo    INT AUTO_INCREMENT PRIMARY KEY,
    Metodo          VARCHAR(50) NOT NULL,
    Paso            VARCHAR(50) NOT NULL,
    Nombre          VARCHAR(50)

);

-- ============================================
-- TABLA: Mezcla
-- ============================================

CREATE TABLE Mezcla (

    ID_Mezcla       INT AUTO_INCREMENT PRIMARY KEY,
    ID_Algoritmo    INT NOT NULL,
    Combinacion     varchar(100) NOT NULL

);

-- ============================================
-- TABLA: Usuario
-- ============================================

CREATE TABLE Usuario (

    ID_Usuario      INT AUTO_INCREMENT PRIMARY KEY,
    Nombre          VARCHAR(50) NOT NULL,
    Apellido        VARCHAR(50) NOT NULL,
    Correo          VARCHAR(100) NOT NULL UNIQUE,
    Contraseña      VARCHAR(50) NOT NULL,
    WCA_ID          VARCHAR(50)

);

-- ============================================
-- TABLA: Usuario_Cubo
-- ============================================

CREATE TABLE Usuario_Cubo (

    ID_Usuario      INT NOT NULL,
    ID_Cubo         INT NOT NULL
    
);

-- ============================================
-- TABLA: Usuario_Algoritmo
-- ============================================

CREATE TABLE Usuario_Algoritmo (

    ID_Usuario      INT NOT NULL,
    ID_Algoritmo    INT NOT NULL
    
);

-- ============================================
-- TABLA: Tiempo
-- ============================================

CREATE TABLE Tiempo (

    ID_Tiempo       INT AUTO_INCREMENT PRIMARY KEY,
    Tiempo          TIME(2) NOT NULL,
    Fecha           DATE NOT NULL,
    Mezcla          VARCHAR(100) NOT NULL,
    Mas_2           BOOLEAN NOT NULL,
    DNF             BOOLEAN NOT NULL,
    ID_Usuario      INT NOT NULL,
    ID_Cubo         INT NOT NULL
    
);

-- ============================================
-- TABLA: Amigo
-- ============================================

CREATE TABLE Amigo (

    ID_Usuario      INT NOT NULL,
    ID_Amigo        INT NOT NULL
    
);

-- ============================================
-- CLAVES
-- ============================================

Alter TABLE Amigo ADD FOREIGN KEY (ID_Usuario) REFERENCES usuario(ID_Usuario);
Alter TABLE Amigo ADD FOREIGN KEY (ID_Amigo) REFERENCES usuario(ID_Usuario);
Alter TABLE Amigo ADD PRIMARY KEY (ID_Usuario, ID_Amigo);


Alter TABLE Usuario_Algoritmo ADD FOREIGN KEY (ID_Usuario) REFERENCES Usuario(ID_Usuario);
Alter TABLE Usuario_Algoritmo ADD FOREIGN KEY (ID_Algoritmo) REFERENCES Algoritmo(ID_Algoritmo);


Alter TABLE Mezcla ADD FOREIGN KEY (ID_Algoritmo) REFERENCES Algoritmo(ID_Algoritmo);


Alter TABLE Usuario_Cubo ADD FOREIGN KEY (ID_Usuario) REFERENCES Usuario(ID_Usuario);
Alter TABLE Usuario_Cubo ADD FOREIGN KEY (ID_Cubo) REFERENCES Cubo(ID_Cubo);
Alter TABLE Usuario_Cubo ADD PRIMARY KEY (ID_Usuario, ID_Cubo);


Alter TABLE Tiempo ADD FOREIGN KEY (ID_Usuario) REFERENCES Usuario_Cubo(ID_Usuario);
Alter TABLE Tiempo ADD FOREIGN KEY (ID_Cubo) REFERENCES Usuario_Cubo(ID_Cubo);
