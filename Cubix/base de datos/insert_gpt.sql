USE Cubix;

-- ============================================
-- CUBO
-- ============================================

INSERT INTO Cubo (Marca, Modelo, enlace_compra) VALUES
('GAN', 'GAN 12 Maglev', 'https://www.gancube.com/gan12'),
('MoYu', 'RS3M 2021', 'https://www.moyustore.com/rs3m-2021'),
('QiYi', 'MS 3x3', 'https://www.qiyitoys.com/ms-3x3'),
('YJ', 'MGC Elite', 'https://www.yjtoys.com/mgc-elite'),
('GAN', 'GAN 13 Maglev', 'https://www.gancube.com/gan13'),
('MoYu', 'Super RS3M V2', 'https://www.moyustore.com/super-rs3m-v2');


-- ============================================
-- ALGORITMO
-- ============================================

INSERT INTO Algoritmo (Metodo, Paso, Nombre) VALUES
('CFOP', 'OLL', 'Sune'),
('CFOP', 'OLL', 'Antisune'),
('CFOP', 'PLL', 'T-Perm'),
('CFOP', 'PLL', 'J-Perm'),
('CFOP', 'OLL', 'Niklas'),
('CFOP', 'F2L', 'F2L Basico'),
('Roux', 'CMLL', 'Sune'),
('Roux', 'CMLL', 'T-Perm'),
('Roux', 'LSE', 'M Slice'),
('ZZ', 'EOLine', 'EOLine Basico'),
('ZZ', 'ZBLL', 'ZBLL Sune'),
('CFOP', 'PLL', 'Y-Perm');


-- ============================================
-- MEZCLA
-- ============================================

INSERT INTO Mezcla (ID_Algoritmo, Combinacion) VALUES
(1, 'R U R'' U R U2 R'''),
(2, 'R U2 R'' U'' R U'' R'''),
(3, 'R U R'' U'' R'' F R2 U'' R'' U'' R U R'' F'''),
(4, 'R U R'' F'' R U R'' U'' R'' F R2 U'' R'''),
(5, 'R U'' L'' U R'' U'' L'),
(6, 'U R U'' R'''),
(7, 'R U R'' U R U2 R'''),
(8, 'R U R'' U'' R'' F R F'''),
(9, 'M2 U M2 U2 M2 U M2'),
(10, 'F R U R'' U'' F'''),
(11, 'R U R'' U R U2 R'''),
(12, 'R U R'' U'' R'' F R2 U'' R'' U'' R U R'' F''');


-- ============================================
-- USUARIO
-- ============================================

INSERT INTO Usuario 
(Nombre, Apellido, Correo, Contraseña, WCA_ID) VALUES
('Carlos', 'Garcia', 'carlos.garcia@gmail.com', '123456', '2019GARC01'),
('Lucia', 'Martinez', 'lucia.martinez@gmail.com', 'lucia123', '2020MART02'),
('Miguel', 'Lopez', 'miguel.lopez@gmail.com', 'miguel123', '2018LOPE03'),
('Sofia', 'Sanchez', 'sofia.sanchez@gmail.com', 'sofia123', '2021SANC04'),
('Daniel', 'Fernandez', 'daniel.fernandez@gmail.com', 'daniel123', '2017FERN05'),
('Ana', 'Rodriguez', 'ana.rodriguez@gmail.com', 'ana123', '2022RODR06'),
('Pablo', 'Gomez', 'pablo.gomez@gmail.com', 'pablo123', '2023GOME07'),
('Laura', 'Diaz', 'laura.diaz@gmail.com', 'laura123', NULL);


-- ============================================
-- USUARIO_CUBO
-- ============================================

INSERT INTO Usuario_Cubo (ID_Usuario, ID_Cubo) VALUES
(1, 1),
(1, 2),
(2, 2),
(2, 3),
(3, 1),
(3, 4),
(4, 3),
(4, 5),
(5, 1),
(5, 5),
(6, 2),
(6, 6),
(7, 4),
(7, 6),
(8, 3);


-- ============================================
-- USUARIO_ALGORITMO
-- ============================================

INSERT INTO Usuario_Algoritmo (ID_Usuario, ID_Algoritmo) VALUES
(1, 1),
(1, 3),
(1, 4),
(1, 6),

(2, 1),
(2, 2),
(2, 5),

(3, 3),
(3, 4),
(3, 7),

(4, 1),
(4, 5),
(4, 12),

(5, 3),
(5, 6),
(5, 9),

(6, 2),
(6, 4),
(6, 8),

(7, 1),
(7, 3),
(7, 11),

(8, 5),
(8, 10);


-- ============================================
-- TIEMPO
-- ============================================

INSERT INTO Tiempo 
(Tiempo, Fecha, Mezcla, Mas_2, DNF, ID_Usuario, ID_Cubo) VALUES

('00:00:12.15', '2026-10-01',
 'R U R'' U'' F R U R'' U'' F''',
 FALSE, FALSE, 1, 1),

('00:00:10.09', '2026-10-02',
 'F R U R'' U'' F'' U2',
 FALSE, FALSE, 1, 1),

('00:00:09.97', '2026-10-03',
 'R2 U R2 F2 U2 R2 U2 F2',
 FALSE, FALSE, 1, 2),

('00:00:14.32', '2026-10-03',
 'U R2 F B R B2 R U2 L B2 R U'' D'' R2 F R'' L B2 U2 F2',
 FALSE, FALSE, 2, 2),

('00:00:11.12', '2026-10-04',
 'R U2 F'' R'' U'' L U R U'' L''',
 FALSE, FALSE, 2, 3),

('00:00:08.01', '2026-10-05',
 'L2 B2 U R'' U L2 B2 U'' R U''',
 FALSE, FALSE, 3, 1),

('00:00:13.96', '2026-10-05',
 'F R2 D2 B R'' U2 F2 L B2 U R2',
 FALSE, FALSE, 3, 4),

('00:00:15.87', '2026-10-06',
 'R U R'' U R U2 R'' F R U R'' U'' F''',
 FALSE, FALSE, 4, 3),

('00:00:18.12', '2026-10-07',
 'U2 R2 F2 D2 L2 B2 U R2 F2',
 FALSE, FALSE, 4, 5),

('00:00:12.43', '2026-10-08',
 'R'' F R F'' U2 R U R''',
 FALSE, FALSE, 5, 1),

('00:00:16.21', '2026-10-08',
 'F2 U L2 R2 F2 U2 B2 R2',
 FALSE, FALSE, 5, 5),

('00:00:09.23', '2026-10-09',
 'R U2 R2 F R F'' U2 R'' F R''',
 FALSE, FALSE, 6, 2),

('00:00:07.32', '2026-10-10',
 'U R U'' L'' U R'' U'' L',
 FALSE, FALSE, 6, 6),

('00:00:20.31', '2026-10-10',
 'B2 U2 R2 F2 L2 D2 B2',
 TRUE, FALSE, 7, 4),

('00:00:17.45', '2026-10-11',
 'R2 U2 F2 R2 B2 L2 U'' R2',
 FALSE, FALSE, 7, 6),

('00:00:11.57', '2026-10-12',
 'R U R'' U'' R'' F R F''',
 FALSE, FALSE, 8, 3),

('00:00:25.25', '2026-10-12',
 'F R U R'' U'' F'' R U2 R''',
 FALSE, TRUE, 8, 3);


-- ============================================
-- AMIGO
-- ============================================

INSERT INTO Amigo (ID_Usuario, ID_Amigo) VALUES
(1, 2),
(1, 3),
(2, 1),
(2, 4),
(3, 1),
(3, 5),
(4, 2),
(4, 6),
(5, 3),
(5, 7),
(6, 4),
(6, 8),
(7, 5),
(7, 8),
(8, 6);