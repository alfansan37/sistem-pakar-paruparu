-- Buat database
CREATE DATABASE IF NOT EXISTS db_pakar_paru;
USE db_pakar_paru;

-- Tabel users
CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel penyakit
CREATE TABLE penyakit (
    id_penyakit INT AUTO_INCREMENT PRIMARY KEY,
    nama_penyakit VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    solusi TEXT
);

-- Tabel gejala
CREATE TABLE gejala (
    id_gejala INT AUTO_INCREMENT PRIMARY KEY,
    kode_gejala VARCHAR(10) NOT NULL,
    nama_gejala VARCHAR(200) NOT NULL
);

-- Tabel basis_pengetahuan
CREATE TABLE basis_pengetahuan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_penyakit INT,
    id_gejala INT,
    FOREIGN KEY (id_penyakit) REFERENCES penyakit(id_penyakit),
    FOREIGN KEY (id_gejala) REFERENCES gejala(id_gejala)
);

-- Tabel hasil_diagnosa
CREATE TABLE hasil_diagnosa (
    id_hasil INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT,
    id_penyakit INT,
    gejala_terpilih TEXT,
    tanggal DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user),
    FOREIGN KEY (id_penyakit) REFERENCES penyakit(id_penyakit)
);

-- Insert data admin default
INSERT INTO users (nama, email, password, role) VALUES 
('Administrator', 'admin@system.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert data penyakit
INSERT INTO penyakit (nama_penyakit, deskripsi, solusi) VALUES 
('Tuberkulosis (TBC)', 'Penyakit menular yang disebabkan oleh bakteri Mycobacterium tuberculosis yang menyerang paru-paru', 'Pengobatan dengan antibiotik jangka panjang, istirahat cukup, nutrisi baik, dan kontrol rutin'),
('Bronkitis', 'Peradangan pada saluran bronkial yang membawa udara ke paru-paru', 'Istirahat, banyak minum air, obat batuk, dan menghindari iritan paru-paru'),
('Pneumonia', 'Infeksi yang menyebabkan peradangan pada kantung udara di paru-paru', 'Antibiotik, istirahat, obat pereda nyeri, dan perawatan suportif'),
('Asma', 'Penyakit kronis yang menyebabkan peradangan dan penyempitan saluran pernapasan', 'Menghindari pemicu, menggunakan inhaler, dan obat kontrol jangka panjang'),
('PPOK (Penyakit Paru Obstruktif Kronis)', 'Kelompok penyakit paru yang menghalangi aliran udara dan membuat sulit bernapas', 'Berhenti merokok, terapi oksigen, obat bronkodilator, dan rehabilitasi paru');

-- Insert data gejala
INSERT INTO gejala (kode_gejala, nama_gejala) VALUES 
('G01', 'Batuk terus-menerus lebih dari 2 minggu'),
('G02', 'Batuk berdahak'),
('G03', 'Batuk berdarah'),
('G04', 'Sesak napas'),
('G05', 'Nyeri dada'),
('G06', 'Demam'),
('G07', 'Berkeringat di malam hari'),
('G08', 'Penurunan berat badan'),
('G09', 'Kehilangan nafsu makan'),
('G10', 'Mudah lelah'),
('G11', 'Mengi (napas berbunyi)'),
('G12', 'Demam tinggi'),
('G13', 'Menggigil'),
('G14', 'Sakit kepala'),
('G15', 'Nyeri otot');

-- Insert basis pengetahuan
INSERT INTO basis_pengetahuan (id_penyakit, id_gejala) VALUES 
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6), (1, 7), (1, 8), (1, 9), (1, 10),
(2, 1), (2, 2), (2, 4), (2, 5), (2, 6), (2, 10), (2, 11),
(3, 2), (3, 4), (3, 5), (3, 6), (3, 12), (3, 13), (3, 14), (3, 15),
(4, 4), (4, 5), (4, 10), (4, 11),
(5, 1), (5, 2), (5, 4), (5, 5), (5, 10), (5, 11);