-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Waktu pembuatan: 25 Bulan Mei 2026 pada 23.32
-- Versi server: 11.8.6-MariaDB-log
-- Versi PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u458429422_Nakama_HMJTI`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `anggota`
--

CREATE TABLE `anggota` (
  `id_anggota` int(11) NOT NULL,
  `kode_pos` varchar(5) NOT NULL,
  `nim` varchar(20) NOT NULL,
  `nama_lengkap` varchar(150) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `angkatan` varchar(10) DEFAULT NULL,
  `jurusan` varchar(100) DEFAULT NULL,
  `program_studi` varchar(100) DEFAULT NULL,
  `status_keanggotaan` varchar(50) NOT NULL DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `anggota`
--

INSERT INTO `anggota` (`id_anggota`, `kode_pos`, `nim`, `nama_lengkap`, `email`, `no_telp`, `angkatan`, `jurusan`, `program_studi`, `status_keanggotaan`) VALUES
(1, '68121', 'E41251558', 'Irzal Amru Alqahtani', 'E41251558@student.polije.ac.id', '', '2025', '', '', 'Aktif'),
(2, '68121', 'E41251215', 'Fajar Muharram', 'E41251215@student.polije.ac.id', '081234000002', '2025', 'Teknologi Informasi', 'D4 Teknik Informatika', 'Aktif'),
(3, '68123', 'E41251276', 'Devis Sapta Pratama', 'E41251276@student.polije.ac.id', '081234000003', '2025', 'Teknologi Informasi', 'D4 Teknik Informatika', 'Aktif'),
(4, '68124', 'E41251254', 'Gabriel Putra S.H', 'E41251254@student.polije.ac.id', '081234000004', '2025', 'Teknologi Informasi', 'D4 Teknik Informatika', 'Aktif'),
(5, '68125', 'E41251323', 'Aditya Bambang Kurniawan', 'E41251323@student.polije.ac.id', '081234000005', '2025', 'Teknologi Informasi', 'D4 Teknik Informatika', 'Aktif'),
(6, '68121', 'E41351310', 'Muhammad Nanda Krisna Murti', 'E41251310@student.polije.ac.id', '081234000006', '2025', 'Teknologi Informasi', 'D4 Teknik Informatika', 'Aktif'),
(8, '68124', 'E41251206', 'Asmaun', 'fajarmuharram1901@gmail.com', '', '2025', '', '', 'Aktif'),
(12, '68121', 'E41251513', 'Vici Gremy Aldiano Susatyo', 'e41251513@student.polije.ac.id', '', '2025', '', '', 'Aktif'),
(19, '68124', 'E41251557', 'data seseorang baru', 'irzalamru83@gmail.com', '', '2025', '', '', 'Aktif'),
(25, '68121', 'E41237891', 'Nama Lengkap', 'fajar.muharram87@sma.belajar.id', '', '2025', '', '', 'Aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `anggota_periode`
--

CREATE TABLE `anggota_periode` (
  `id_anggota_periode` int(11) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `id_periode` int(11) NOT NULL,
  `id_jabatan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `anggota_periode`
--

INSERT INTO `anggota_periode` (`id_anggota_periode`, `id_anggota`, `id_periode`, `id_jabatan`) VALUES
(1, 1, 2, 1),
(2, 2, 2, 2),
(3, 3, 2, 1),
(4, 4, 2, 1),
(5, 5, 2, 1),
(6, 6, 2, 2),
(7, 8, 2, 1),
(11, 12, 2, 8),
(18, 19, 2, 3),
(23, 25, 2, 11);

-- --------------------------------------------------------

--
-- Struktur dari tabel `bukti_kegiatan`
--

CREATE TABLE `bukti_kegiatan` (
  `id_bukti` int(11) NOT NULL,
  `id_kegiatan` int(11) NOT NULL,
  `file_bukti` varchar(255) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `diunggah_pada` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `bukti_kegiatan`
--

INSERT INTO `bukti_kegiatan` (`id_bukti`, `id_kegiatan`, `file_bukti`, `keterangan`, `diunggah_pada`) VALUES
(26, 23, 'uploads/kegiatan/img_6a059c52d29b2.jpg', NULL, '2026-05-14 09:56:34'),
(28, 25, 'uploads/kegiatan/img_6a059cf861e3c.jpg', NULL, '2026-05-14 09:59:20'),
(29, 23, 'uploads/kegiatan/img_6a059de5bf697.jpg', NULL, '2026-05-14 10:03:17'),
(30, 23, 'uploads/kegiatan/img_6a059de5bf89e.jpg', NULL, '2026-05-14 10:03:17'),
(31, 23, 'uploads/kegiatan/img_6a059de5bfa11.jpg', NULL, '2026-05-14 10:03:17'),
(32, 23, 'uploads/kegiatan/img_6a059de5bfb4e.jpg', NULL, '2026-05-14 10:03:17'),
(33, 23, 'uploads/kegiatan/img_6a059de5bfc84.jpg', NULL, '2026-05-14 10:03:17'),
(34, 23, 'uploads/kegiatan/img_6a059de5bfd92.jpg', NULL, '2026-05-14 10:03:17'),
(35, 23, 'uploads/kegiatan/img_6a059de5bfea4.jpg', NULL, '2026-05-14 10:03:17'),
(36, 23, 'uploads/kegiatan/img_6a059de5bffc3.jpg', NULL, '2026-05-14 10:03:17'),
(41, 32, 'uploads/kegiatan/img_6a0cfa256e733.jpeg', NULL, '2026-05-20 00:02:45'),
(42, 25, 'uploads/kegiatan/img_6a0d3c43e9ef5.jpg', NULL, '2026-05-20 04:44:51');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_peminjaman`
--

CREATE TABLE `detail_peminjaman` (
  `id_detail` int(11) NOT NULL,
  `id_peminjaman` int(11) NOT NULL,
  `id_ruangan` int(11) NOT NULL,
  `waktu_mulai` datetime NOT NULL,
  `waktu_selesai` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `detail_peminjaman`
--

INSERT INTO `detail_peminjaman` (`id_detail`, `id_peminjaman`, `id_ruangan`, `waktu_mulai`, `waktu_selesai`) VALUES
(11, 14, 19, '2026-05-14 18:11:00', '2026-05-14 20:11:00'),
(12, 15, 19, '2026-06-05 18:45:00', '2026-06-06 18:45:00'),
(16, 20, 22, '2026-05-20 13:00:00', '2026-05-20 16:00:00'),
(17, 21, 19, '2026-05-20 18:25:00', '2026-05-21 17:25:00'),
(18, 23, 24, '2026-05-22 23:10:00', '2026-05-25 14:10:00');

--
-- Trigger `detail_peminjaman`
--
DELIMITER $$
CREATE TRIGGER `trg_cek_konflik_jadwal` BEFORE INSERT ON `detail_peminjaman` FOR EACH ROW BEGIN
  DECLARE konflik INT DEFAULT 0;
  SELECT COUNT(*) INTO konflik
  FROM detail_peminjaman dp
    JOIN peminjaman_ruangan pr ON dp.id_peminjaman = pr.id_peminjaman
  WHERE dp.id_ruangan    = NEW.id_ruangan
    AND pr.status       NOT IN ('Ditolak','Selesai')
    AND dp.waktu_mulai   < NEW.waktu_selesai
    AND dp.waktu_selesai > NEW.waktu_mulai;
  IF konflik > 0 THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Konflik jadwal: ruangan sudah dipesan pada waktu tersebut';
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_cek_konflik_jadwal_update` BEFORE UPDATE ON `detail_peminjaman` FOR EACH ROW BEGIN
  DECLARE konflik INT DEFAULT 0;
  SELECT COUNT(*) INTO konflik
  FROM detail_peminjaman dp
    JOIN peminjaman_ruangan pr ON dp.id_peminjaman = pr.id_peminjaman
  WHERE dp.id_ruangan    = NEW.id_ruangan
    AND dp.id_detail    != NEW.id_detail
    AND pr.status       NOT IN ('Ditolak','Selesai')
    AND dp.waktu_mulai   < NEW.waktu_selesai
    AND dp.waktu_selesai > NEW.waktu_mulai;
  IF konflik > 0 THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Konflik jadwal: ruangan sudah dipesan pada waktu tersebut';
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `divisi`
--

CREATE TABLE `divisi` (
  `id_divisi` int(11) NOT NULL,
  `nama_divisi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `divisi`
--

INSERT INTO `divisi` (`id_divisi`, `nama_divisi`) VALUES
(1, 'BPH'),
(2, 'Administrasi'),
(3, 'Keilmuan'),
(4, 'Perhubungan'),
(5, 'Kominfo'),
(6, 'KWU');

-- --------------------------------------------------------

--
-- Struktur dari tabel `draft_surat`
--

CREATE TABLE `draft_surat` (
  `id_draft` int(11) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `file` varchar(255) DEFAULT NULL,
  `deskripsi_kegiatan` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `draft_surat`
--

INSERT INTO `draft_surat` (`id_draft`, `id_anggota`, `file`, `deskripsi_kegiatan`, `created_at`) VALUES
(7, 1, 'uploads/surat/surat_6a030e2818a15_Artikel+Jurnal+Penelitian+Pendidikan (2).pdf', '234', '2026-05-12 11:25:28'),
(11, 6, 'uploads/surat/surat_6a075a94eb57c_20260504_210350_284b8da8_Workshop Pengembangan Proyek Perangkat Lunak_Minggu 12.pdf', 'ada rapat', '2026-05-15 17:40:36'),
(12, 6, 'uploads/surat/surat_6a075e813d383_20260504_210350_284b8da8_Workshop Pengembangan Proyek Perangkat Lunak_Minggu 12.pdf', 'rapat', '2026-05-15 17:57:21');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jabatan`
--

CREATE TABLE `jabatan` (
  `id_jabatan` int(11) NOT NULL,
  `nama_jabatan` varchar(100) NOT NULL,
  `id_divisi` int(11) DEFAULT NULL,
  `id_role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jabatan`
--

INSERT INTO `jabatan` (`id_jabatan`, `nama_jabatan`, `id_divisi`, `id_role`) VALUES
(1, 'Ketua', NULL, 1),
(2, 'Sekretaris', NULL, 2),
(3, 'Bendahara', NULL, 3),
(4, 'Ketua Divisi BPH', 1, 4),
(5, 'Ketua Divisi Administrasi', 2, 4),
(6, 'Ketua Divisi Keilmuan', 3, 4),
(7, 'Ketua Divisi Perhubungan', 4, 4),
(8, 'Ketua Divisi Kominfo', 5, 4),
(9, 'Ketua Divisi KWU', 6, 4),
(10, 'Anggota Divisi BPH', 1, 4),
(11, 'Anggota Divisi Administrasi', 2, 4),
(12, 'Anggota Divisi Keilmuan', 3, 4),
(13, 'Anggota Divisi Perhubungan', 4, 4),
(14, 'Anggota Divisi Kominfo', 5, 4),
(15, 'Anggota Divisi KWU', 6, 4),
(16, 'Alumni', NULL, 5),
(17, 'Wakil Ketua', NULL, 1),
(18, 'Bendahara II', NULL, 3),
(19, 'Anggota BPH', 1, 4),
(20, 'Anggota Administrasi', 2, 4),
(21, 'Anggota Keilmuan', 3, 4),
(22, 'Anggota Perhubungan', 4, 4),
(23, 'Anggota Kominfo', 5, 4),
(24, 'Anggota KWU', 6, 4),
(26, 'Anggota', NULL, 4),
(27, 'Alumni', NULL, 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `jenis` enum('Pemasukan Iuran','Pemasukan DIPA','Pemasukan Sponsorship','Pengeluaran') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `jenis`) VALUES
(1, 'Iuran Anggota', 'Pemasukan Iuran'),
(2, 'Dana Kampus/DIPA', 'Pemasukan DIPA'),
(3, 'Sponsorship', 'Pemasukan Sponsorship'),
(4, 'Konsumsi', 'Pengeluaran'),
(5, 'Perlengkapan', 'Pengeluaran'),
(6, 'Transportasi', 'Pengeluaran'),
(7, 'Honorarium', 'Pengeluaran');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kegiatan`
--

CREATE TABLE `kegiatan` (
  `id_kegiatan` int(11) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `penanggung_jawab` varchar(100) DEFAULT NULL,
  `tempat` varchar(200) DEFAULT NULL,
  `waktu_mulai` datetime DEFAULT NULL,
  `waktu_selesai` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kegiatan`
--

INSERT INTO `kegiatan` (`id_kegiatan`, `id_anggota`, `judul`, `deskripsi`, `penanggung_jawab`, `tempat`, `waktu_mulai`, `waktu_selesai`, `created_at`, `updated_at`) VALUES
(23, 8, 'kegiatan 1', 'pengajian besaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaar', 'uztad', 'mesjid dekat rumah', '2026-05-14 16:57:00', '2026-05-14 19:56:00', '2026-05-14 09:56:34', '2026-05-14 13:58:00'),
(25, 6, 'Seminar Teknologi AI', 'wipssssssssssss seswaqswaw', 'ron', 'kegiatan 3', '2026-05-16 16:58:00', '2026-05-16 17:58:00', '2026-05-14 09:59:20', '2026-05-20 04:20:21'),
(32, 1, 'persiapan untuk presentasi 566557', 'presentasi kelompok nakama', 'satyo', 'Ruang kelas 3.9', '2026-05-20 13:00:00', '2026-05-20 16:00:00', '2026-05-20 00:02:05', '2026-05-20 04:20:08'),
(33, 5, 'coba yah semoga bisa, Bismillah', 'jadi gini le, oaiawioawamodwjkn', 'Gabriel', 'kost griya', '2026-05-21 22:37:00', '2026-05-22 22:37:00', '2026-05-20 15:38:00', '2026-05-20 15:38:00'),
(34, 5, 'coba lagi yang lains emoga bisa lagi, Bismillah', 'adalah pokonya hehehehehehehe', 'Gabriel', 'kost tidar', '2026-05-29 22:48:00', '2026-05-30 14:48:00', '2026-05-20 15:48:47', '2026-05-20 15:48:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kode_pos`
--

CREATE TABLE `kode_pos` (
  `kode_pos` varchar(5) NOT NULL,
  `provinsi` varchar(100) NOT NULL,
  `kota_kabupaten` varchar(100) NOT NULL,
  `kecamatan` varchar(100) NOT NULL,
  `kelurahan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kode_pos`
--

INSERT INTO `kode_pos` (`kode_pos`, `provinsi`, `kota_kabupaten`, `kecamatan`, `kelurahan`) VALUES
('68121', 'Jawa Timur', 'Kabupaten Jember', 'Sumbersari', 'Sumbersari'),
('68122', 'Jawa Timur', 'Kabupaten Jember', 'Sumbersari', 'Kranjingan'),
('68123', 'Jawa Timur', 'Kabupaten Jember', 'Sumbersari', 'Wirolegi'),
('68124', 'Jawa Timur', 'Kabupaten Jember', 'Kaliwates', 'Kaliwates'),
('68125', 'Jawa Timur', 'Kabupaten Jember', 'Patrang', 'Patrang');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id_log` int(11) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `aksi` enum('CREATE','UPDATE','DELETE') NOT NULL,
  `tabel_target` varchar(50) NOT NULL,
  `id_target` int(11) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id_log`, `id_anggota`, `aksi`, `tabel_target`, `id_target`, `keterangan`, `created_at`) VALUES
(1, 1, 'CREATE', 'kegiatan', 1, 'Menambah kegiatan Seminar Teknologi AI 2026', '2026-05-06 08:44:00'),
(2, 1, 'CREATE', 'kegiatan', 2, 'Menambah kegiatan Rapat Koordinasi Bulanan', '2026-05-06 08:44:00'),
(3, 2, 'CREATE', 'draft_surat', 1, 'Membuat draft surat Seminar AI', '2026-05-06 08:44:00'),
(4, 2, 'CREATE', 'pengajuan_surat', 1, 'Mengajukan surat Seminar AI', '2026-05-06 08:44:00'),
(5, 1, 'UPDATE', 'pengajuan_surat', 1, 'Mengubah status menjadi Diproses', '2026-05-06 08:44:00'),
(6, 1, 'UPDATE', 'pengajuan_surat', 1, 'Mengubah status menjadi Selesai', '2026-05-06 08:44:00'),
(7, 4, 'CREATE', 'pemasukan', 1, 'Mencatat pemasukan iuran September 2026', '2026-05-06 08:44:00'),
(8, 4, 'CREATE', 'pengeluaran', 1, 'Mencatat pengeluaran konsumsi Seminar AI', '2026-05-06 08:44:00'),
(9, 4, 'UPDATE', 'pengeluaran', 3, 'Mengubah status pengeluaran transport menjadi Ditolak', '2026-05-06 08:44:00'),
(10, 1, 'CREATE', 'kegiatan', 3, 'Menambah kegiatan LKTI 2026', '2026-05-06 08:44:00'),
(11, 3, 'CREATE', 'peminjaman_ruangan', 1, 'Mengajukan peminjaman ruang Rapat BPH', '2026-05-06 08:44:00'),
(12, 1, 'UPDATE', 'peminjaman_ruangan', 1, 'Menyetujui peminjaman Rapat BPH', '2026-05-06 08:44:00'),
(13, 1, 'UPDATE', 'peminjaman_ruangan', 4, 'Menolak peminjaman Rapat Keuangan', '2026-05-06 08:44:00'),
(14, 6, 'CREATE', 'pemasukan', 5, 'Mencatat dana DIPA untuk LKTI 2026', '2026-05-06 08:44:00'),
(15, 6, 'DELETE', 'pengeluaran', 3, 'Menghapus pengeluaran transport yang ditolak', '2026-05-06 08:44:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id_notifikasi` int(11) NOT NULL,
  `id_kegiatan` int(11) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `pesan` text DEFAULT NULL,
  `dibaca` tinyint(4) NOT NULL DEFAULT 0,
  `dibaca_pada` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `tipe_notif` varchar(20) DEFAULT NULL,
  `ditampilkan` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `notifikasi`
--

INSERT INTO `notifikasi` (`id_notifikasi`, `id_kegiatan`, `id_anggota`, `judul`, `pesan`, `dibaca`, `dibaca_pada`, `created_at`, `tipe_notif`, `ditampilkan`) VALUES
(654, 23, 1, 'Kegiatan Baru: kegiatan 1', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 14 May 2026, 16:57 di mesjid.', 1, '2026-05-14 10:09:54', '2026-05-14 09:56:34', 'manual', 1),
(655, 23, 6, 'Kegiatan Baru: kegiatan 1', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 14 May 2026, 16:57 di mesjid.', 0, NULL, '2026-05-14 09:56:34', 'manual', 1),
(656, 23, 12, 'Kegiatan Baru: kegiatan 1', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 14 May 2026, 16:57 di mesjid.', 0, NULL, '2026-05-14 09:56:34', 'manual', 0),
(657, 23, 2, 'Kegiatan Baru: kegiatan 1', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 14 May 2026, 16:57 di mesjid.', 1, '2026-05-17 00:13:48', '2026-05-14 09:56:34', 'manual', 1),
(658, 23, 3, 'Kegiatan Baru: kegiatan 1', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 14 May 2026, 16:57 di mesjid.', 1, '2026-05-20 01:37:10', '2026-05-14 09:56:34', 'manual', 1),
(659, 23, 4, 'Kegiatan Baru: kegiatan 1', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 14 May 2026, 16:57 di mesjid.', 0, NULL, '2026-05-14 09:56:34', 'manual', 1),
(660, 23, 8, 'Kegiatan Baru: kegiatan 1', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 14 May 2026, 16:57 di mesjid.', 1, '2026-05-20 01:38:10', '2026-05-14 09:56:34', 'manual', 1),
(663, 23, 5, 'Kegiatan Baru: kegiatan 1', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 14 May 2026, 16:57 di mesjid.', 0, NULL, '2026-05-14 09:56:34', 'manual', 1),
(674, 23, 1, 'Kegiatan Dimulai: kegiatan 1', 'Kegiatan \"kegiatan 1\" sedang berlangsung sekarang!', 1, '2026-05-14 10:09:54', '2026-05-14 09:58:00', 'mulai', 1),
(675, 23, 6, 'Kegiatan Dimulai: kegiatan 1', 'Kegiatan \"kegiatan 1\" sedang berlangsung sekarang!', 0, NULL, '2026-05-14 09:58:00', 'mulai', 1),
(676, 23, 12, 'Kegiatan Dimulai: kegiatan 1', 'Kegiatan \"kegiatan 1\" sedang berlangsung sekarang!', 0, NULL, '2026-05-14 09:58:00', 'mulai', 0),
(677, 23, 2, 'Kegiatan Dimulai: kegiatan 1', 'Kegiatan \"kegiatan 1\" sedang berlangsung sekarang!', 1, '2026-05-17 00:13:48', '2026-05-14 09:58:00', 'mulai', 1),
(678, 23, 3, 'Kegiatan Dimulai: kegiatan 1', 'Kegiatan \"kegiatan 1\" sedang berlangsung sekarang!', 1, '2026-05-20 01:37:10', '2026-05-14 09:58:00', 'mulai', 1),
(679, 23, 4, 'Kegiatan Dimulai: kegiatan 1', 'Kegiatan \"kegiatan 1\" sedang berlangsung sekarang!', 1, '2026-05-21 02:47:39', '2026-05-14 09:58:00', 'mulai', 1),
(680, 23, 8, 'Kegiatan Dimulai: kegiatan 1', 'Kegiatan \"kegiatan 1\" sedang berlangsung sekarang!', 1, '2026-05-20 01:38:10', '2026-05-14 09:58:00', 'mulai', 1),
(683, 23, 5, 'Kegiatan Dimulai: kegiatan 1', 'Kegiatan \"kegiatan 1\" sedang berlangsung sekarang!', 0, NULL, '2026-05-14 09:58:00', 'mulai', 1),
(734, 23, 1, '90897656454w', '3w4e65790-', 1, '2026-05-14 13:58:32', '2026-05-14 13:58:23', 'manual', 1),
(735, 23, 6, '90897656454w', '3w4e65790-', 0, NULL, '2026-05-14 13:58:23', 'manual', 1),
(736, 23, 12, '90897656454w', '3w4e65790-', 0, NULL, '2026-05-14 13:58:23', 'manual', 0),
(737, 23, 2, '90897656454w', '3w4e65790-', 1, '2026-05-17 00:13:44', '2026-05-14 13:58:23', 'manual', 1),
(738, 23, 3, '90897656454w', '3w4e65790-', 1, '2026-05-20 01:37:10', '2026-05-14 13:58:23', 'manual', 1),
(739, 23, 4, '90897656454w', '3w4e65790-', 0, NULL, '2026-05-14 13:58:23', 'manual', 1),
(740, 23, 8, '90897656454w', '3w4e65790-', 1, '2026-05-20 01:38:10', '2026-05-14 13:58:23', 'manual', 1),
(743, 23, 5, '90897656454w', '3w4e65790-', 1, '2026-05-16 16:41:40', '2026-05-14 13:58:23', 'manual', 1),
(913, 32, 1, 'Kegiatan Diperbarui: persiapan untuk presentasi 566557', 'Info kegiatan telah diperbarui. Pelaksanaan: 20 May 2026, 13:00 di Ruang kelas 3.9.', 0, NULL, '2026-05-20 04:20:08', 'manual', 1),
(914, 32, 2, 'Kegiatan Diperbarui: persiapan untuk presentasi 566557', 'Info kegiatan telah diperbarui. Pelaksanaan: 20 May 2026, 13:00 di Ruang kelas 3.9.', 1, '2026-05-20 04:39:06', '2026-05-20 04:20:08', 'manual', 1),
(915, 32, 6, 'Kegiatan Diperbarui: persiapan untuk presentasi 566557', 'Info kegiatan telah diperbarui. Pelaksanaan: 20 May 2026, 13:00 di Ruang kelas 3.9.', 0, NULL, '2026-05-20 04:20:08', 'manual', 1),
(916, 32, 12, 'Kegiatan Diperbarui: persiapan untuk presentasi 566557', 'Info kegiatan telah diperbarui. Pelaksanaan: 20 May 2026, 13:00 di Ruang kelas 3.9.', 0, NULL, '2026-05-20 04:20:08', 'manual', 0),
(917, 32, 3, 'Kegiatan Diperbarui: persiapan untuk presentasi 566557', 'Info kegiatan telah diperbarui. Pelaksanaan: 20 May 2026, 13:00 di Ruang kelas 3.9.', 1, '2026-05-20 09:16:36', '2026-05-20 04:20:08', 'manual', 1),
(918, 32, 4, 'Kegiatan Diperbarui: persiapan untuk presentasi 566557', 'Info kegiatan telah diperbarui. Pelaksanaan: 20 May 2026, 13:00 di Ruang kelas 3.9.', 0, NULL, '2026-05-20 04:20:08', 'manual', 1),
(919, 32, 8, 'Kegiatan Diperbarui: persiapan untuk presentasi 566557', 'Info kegiatan telah diperbarui. Pelaksanaan: 20 May 2026, 13:00 di Ruang kelas 3.9.', 1, '2026-05-20 05:26:34', '2026-05-20 04:20:08', 'manual', 1),
(920, 32, 19, 'Kegiatan Diperbarui: persiapan untuk presentasi 566557', 'Info kegiatan telah diperbarui. Pelaksanaan: 20 May 2026, 13:00 di Ruang kelas 3.9.', 1, '2026-05-20 07:12:12', '2026-05-20 04:20:08', 'manual', 1),
(921, 32, 5, 'Kegiatan Diperbarui: persiapan untuk presentasi 566557', 'Info kegiatan telah diperbarui. Pelaksanaan: 20 May 2026, 13:00 di Ruang kelas 3.9.', 0, NULL, '2026-05-20 04:20:08', 'manual', 1),
(922, 25, 1, 'Kegiatan Diperbarui: Seminar Teknologi AI', 'Info kegiatan telah diperbarui. Pelaksanaan: 16 May 2026, 16:58 di kegiatan 3.', 0, NULL, '2026-05-20 04:20:21', 'manual', 1),
(923, 25, 2, 'Kegiatan Diperbarui: Seminar Teknologi AI', 'Info kegiatan telah diperbarui. Pelaksanaan: 16 May 2026, 16:58 di kegiatan 3.', 0, NULL, '2026-05-20 04:20:21', 'manual', 1),
(924, 25, 6, 'Kegiatan Diperbarui: Seminar Teknologi AI', 'Info kegiatan telah diperbarui. Pelaksanaan: 16 May 2026, 16:58 di kegiatan 3.', 0, NULL, '2026-05-20 04:20:21', 'manual', 1),
(925, 25, 12, 'Kegiatan Diperbarui: Seminar Teknologi AI', 'Info kegiatan telah diperbarui. Pelaksanaan: 16 May 2026, 16:58 di kegiatan 3.', 0, NULL, '2026-05-20 04:20:21', 'manual', 0),
(926, 25, 3, 'Kegiatan Diperbarui: Seminar Teknologi AI', 'Info kegiatan telah diperbarui. Pelaksanaan: 16 May 2026, 16:58 di kegiatan 3.', 1, '2026-05-20 09:16:36', '2026-05-20 04:20:21', 'manual', 1),
(927, 25, 4, 'Kegiatan Diperbarui: Seminar Teknologi AI', 'Info kegiatan telah diperbarui. Pelaksanaan: 16 May 2026, 16:58 di kegiatan 3.', 0, NULL, '2026-05-20 04:20:21', 'manual', 1),
(928, 25, 8, 'Kegiatan Diperbarui: Seminar Teknologi AI', 'Info kegiatan telah diperbarui. Pelaksanaan: 16 May 2026, 16:58 di kegiatan 3.', 1, '2026-05-20 05:26:34', '2026-05-20 04:20:21', 'manual', 1),
(929, 25, 19, 'Kegiatan Diperbarui: Seminar Teknologi AI', 'Info kegiatan telah diperbarui. Pelaksanaan: 16 May 2026, 16:58 di kegiatan 3.', 1, '2026-05-20 07:12:11', '2026-05-20 04:20:21', 'manual', 1),
(930, 25, 5, 'Kegiatan Diperbarui: Seminar Teknologi AI', 'Info kegiatan telah diperbarui. Pelaksanaan: 16 May 2026, 16:58 di kegiatan 3.', 0, NULL, '2026-05-20 04:20:21', 'manual', 1),
(931, 25, 1, 'Kegiatan Dimulai: Seminar Teknologi AI', 'Kegiatan \"Seminar Teknologi AI\" sedang berlangsung sekarang!', 0, NULL, '2026-05-20 04:20:21', 'mulai', 1),
(932, 25, 2, 'Kegiatan Dimulai: Seminar Teknologi AI', 'Kegiatan \"Seminar Teknologi AI\" sedang berlangsung sekarang!', 1, '2026-05-20 04:39:04', '2026-05-20 04:20:21', 'mulai', 1),
(933, 25, 6, 'Kegiatan Dimulai: Seminar Teknologi AI', 'Kegiatan \"Seminar Teknologi AI\" sedang berlangsung sekarang!', 0, NULL, '2026-05-20 04:20:21', 'mulai', 1),
(934, 25, 12, 'Kegiatan Dimulai: Seminar Teknologi AI', 'Kegiatan \"Seminar Teknologi AI\" sedang berlangsung sekarang!', 0, NULL, '2026-05-20 04:20:21', 'mulai', 0),
(935, 25, 3, 'Kegiatan Dimulai: Seminar Teknologi AI', 'Kegiatan \"Seminar Teknologi AI\" sedang berlangsung sekarang!', 1, '2026-05-20 09:16:36', '2026-05-20 04:20:21', 'mulai', 1),
(936, 25, 4, 'Kegiatan Dimulai: Seminar Teknologi AI', 'Kegiatan \"Seminar Teknologi AI\" sedang berlangsung sekarang!', 0, NULL, '2026-05-20 04:20:21', 'mulai', 1),
(937, 25, 8, 'Kegiatan Dimulai: Seminar Teknologi AI', 'Kegiatan \"Seminar Teknologi AI\" sedang berlangsung sekarang!', 1, '2026-05-20 05:26:34', '2026-05-20 04:20:21', 'mulai', 1),
(938, 25, 19, 'Kegiatan Dimulai: Seminar Teknologi AI', 'Kegiatan \"Seminar Teknologi AI\" sedang berlangsung sekarang!', 1, '2026-05-20 07:12:07', '2026-05-20 04:20:21', 'mulai', 1),
(939, 25, 5, 'Kegiatan Dimulai: Seminar Teknologi AI', 'Kegiatan \"Seminar Teknologi AI\" sedang berlangsung sekarang!', 0, NULL, '2026-05-20 04:20:21', 'mulai', 1),
(940, 32, 1, 'Kegiatan Dimulai: persiapan untuk presentasi 566557', 'Kegiatan \"persiapan untuk presentasi 566557\" sedang berlangsung sekarang!', 0, NULL, '2026-05-20 06:11:53', 'mulai', 1),
(941, 32, 2, 'Kegiatan Dimulai: persiapan untuk presentasi 566557', 'Kegiatan \"persiapan untuk presentasi 566557\" sedang berlangsung sekarang!', 0, NULL, '2026-05-20 06:11:53', 'mulai', 1),
(942, 32, 6, 'Kegiatan Dimulai: persiapan untuk presentasi 566557', 'Kegiatan \"persiapan untuk presentasi 566557\" sedang berlangsung sekarang!', 0, NULL, '2026-05-20 06:11:53', 'mulai', 1),
(943, 32, 12, 'Kegiatan Dimulai: persiapan untuk presentasi 566557', 'Kegiatan \"persiapan untuk presentasi 566557\" sedang berlangsung sekarang!', 0, NULL, '2026-05-20 06:11:53', 'mulai', 0),
(944, 32, 3, 'Kegiatan Dimulai: persiapan untuk presentasi 566557', 'Kegiatan \"persiapan untuk presentasi 566557\" sedang berlangsung sekarang!', 1, '2026-05-20 09:16:34', '2026-05-20 06:11:53', 'mulai', 1),
(945, 32, 4, 'Kegiatan Dimulai: persiapan untuk presentasi 566557', 'Kegiatan \"persiapan untuk presentasi 566557\" sedang berlangsung sekarang!', 0, NULL, '2026-05-20 06:11:53', 'mulai', 1),
(946, 32, 8, 'Kegiatan Dimulai: persiapan untuk presentasi 566557', 'Kegiatan \"persiapan untuk presentasi 566557\" sedang berlangsung sekarang!', 0, NULL, '2026-05-20 06:11:53', 'mulai', 1),
(947, 32, 19, 'Kegiatan Dimulai: persiapan untuk presentasi 566557', 'Kegiatan \"persiapan untuk presentasi 566557\" sedang berlangsung sekarang!', 1, '2026-05-20 07:12:09', '2026-05-20 06:11:53', 'mulai', 1),
(948, 32, 5, 'Kegiatan Dimulai: persiapan untuk presentasi 566557', 'Kegiatan \"persiapan untuk presentasi 566557\" sedang berlangsung sekarang!', 0, NULL, '2026-05-20 06:11:53', 'mulai', 1),
(949, 33, 1, 'Kegiatan Baru: coba yah semoga bisa, Bismillah', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 21 May 2026, 22:37 di kost griya.', 0, NULL, '2026-05-20 15:38:00', 'manual', 1),
(950, 33, 2, 'Kegiatan Baru: coba yah semoga bisa, Bismillah', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 21 May 2026, 22:37 di kost griya.', 0, NULL, '2026-05-20 15:38:00', 'manual', 0),
(951, 33, 6, 'Kegiatan Baru: coba yah semoga bisa, Bismillah', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 21 May 2026, 22:37 di kost griya.', 0, NULL, '2026-05-20 15:38:00', 'manual', 0),
(952, 33, 12, 'Kegiatan Baru: coba yah semoga bisa, Bismillah', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 21 May 2026, 22:37 di kost griya.', 0, NULL, '2026-05-20 15:38:00', 'manual', 0),
(953, 33, 25, 'Kegiatan Baru: coba yah semoga bisa, Bismillah', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 21 May 2026, 22:37 di kost griya.', 0, NULL, '2026-05-20 15:38:00', 'manual', 0),
(954, 33, 3, 'Kegiatan Baru: coba yah semoga bisa, Bismillah', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 21 May 2026, 22:37 di kost griya.', 0, NULL, '2026-05-20 15:38:00', 'manual', 1),
(955, 33, 4, 'Kegiatan Baru: coba yah semoga bisa, Bismillah', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 21 May 2026, 22:37 di kost griya.', 0, NULL, '2026-05-20 15:38:00', 'manual', 1),
(956, 33, 8, 'Kegiatan Baru: coba yah semoga bisa, Bismillah', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 21 May 2026, 22:37 di kost griya.', 0, NULL, '2026-05-20 15:38:00', 'manual', 1),
(957, 33, 19, 'Kegiatan Baru: coba yah semoga bisa, Bismillah', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 21 May 2026, 22:37 di kost griya.', 0, NULL, '2026-05-20 15:38:00', 'manual', 0),
(958, 33, 5, 'Kegiatan Baru: coba yah semoga bisa, Bismillah', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 21 May 2026, 22:37 di kost griya.', 0, NULL, '2026-05-20 15:38:00', 'manual', 0),
(959, 33, 1, 'Pengingat 24 jam lagi: coba yah semoga bisa, Bismillah', 'Kegiatan \"coba yah semoga bisa, Bismillah\" akan dimulai dalam 24 jam lagi!', 0, NULL, '2026-05-20 15:38:00', 'h-24', 1),
(960, 33, 2, 'Pengingat 24 jam lagi: coba yah semoga bisa, Bismillah', 'Kegiatan \"coba yah semoga bisa, Bismillah\" akan dimulai dalam 24 jam lagi!', 0, NULL, '2026-05-20 15:38:00', 'h-24', 0),
(961, 33, 6, 'Pengingat 24 jam lagi: coba yah semoga bisa, Bismillah', 'Kegiatan \"coba yah semoga bisa, Bismillah\" akan dimulai dalam 24 jam lagi!', 0, NULL, '2026-05-20 15:38:00', 'h-24', 0),
(962, 33, 12, 'Pengingat 24 jam lagi: coba yah semoga bisa, Bismillah', 'Kegiatan \"coba yah semoga bisa, Bismillah\" akan dimulai dalam 24 jam lagi!', 0, NULL, '2026-05-20 15:38:00', 'h-24', 0),
(963, 33, 25, 'Pengingat 24 jam lagi: coba yah semoga bisa, Bismillah', 'Kegiatan \"coba yah semoga bisa, Bismillah\" akan dimulai dalam 24 jam lagi!', 0, NULL, '2026-05-20 15:38:00', 'h-24', 0),
(964, 33, 3, 'Pengingat 24 jam lagi: coba yah semoga bisa, Bismillah', 'Kegiatan \"coba yah semoga bisa, Bismillah\" akan dimulai dalam 24 jam lagi!', 0, NULL, '2026-05-20 15:38:00', 'h-24', 0),
(965, 33, 4, 'Pengingat 24 jam lagi: coba yah semoga bisa, Bismillah', 'Kegiatan \"coba yah semoga bisa, Bismillah\" akan dimulai dalam 24 jam lagi!', 0, NULL, '2026-05-20 15:38:00', 'h-24', 1),
(966, 33, 8, 'Pengingat 24 jam lagi: coba yah semoga bisa, Bismillah', 'Kegiatan \"coba yah semoga bisa, Bismillah\" akan dimulai dalam 24 jam lagi!', 0, NULL, '2026-05-20 15:38:00', 'h-24', 1),
(967, 33, 19, 'Pengingat 24 jam lagi: coba yah semoga bisa, Bismillah', 'Kegiatan \"coba yah semoga bisa, Bismillah\" akan dimulai dalam 24 jam lagi!', 0, NULL, '2026-05-20 15:38:00', 'h-24', 0),
(968, 33, 5, 'Pengingat 24 jam lagi: coba yah semoga bisa, Bismillah', 'Kegiatan \"coba yah semoga bisa, Bismillah\" akan dimulai dalam 24 jam lagi!', 0, NULL, '2026-05-20 15:38:00', 'h-24', 0),
(969, 34, 1, 'Kegiatan Baru: coba lagi yang lains emoga bisa lagi, Bismillah', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 29 May 2026, 22:48 di kost tidar.', 0, NULL, '2026-05-20 15:48:47', 'manual', 1),
(970, 34, 2, 'Kegiatan Baru: coba lagi yang lains emoga bisa lagi, Bismillah', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 29 May 2026, 22:48 di kost tidar.', 0, NULL, '2026-05-20 15:48:47', 'manual', 0),
(971, 34, 6, 'Kegiatan Baru: coba lagi yang lains emoga bisa lagi, Bismillah', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 29 May 2026, 22:48 di kost tidar.', 0, NULL, '2026-05-20 15:48:47', 'manual', 0),
(972, 34, 12, 'Kegiatan Baru: coba lagi yang lains emoga bisa lagi, Bismillah', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 29 May 2026, 22:48 di kost tidar.', 0, NULL, '2026-05-20 15:48:47', 'manual', 0),
(973, 34, 25, 'Kegiatan Baru: coba lagi yang lains emoga bisa lagi, Bismillah', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 29 May 2026, 22:48 di kost tidar.', 0, NULL, '2026-05-20 15:48:47', 'manual', 0),
(974, 34, 3, 'Kegiatan Baru: coba lagi yang lains emoga bisa lagi, Bismillah', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 29 May 2026, 22:48 di kost tidar.', 0, NULL, '2026-05-20 15:48:47', 'manual', 1),
(975, 34, 4, 'Kegiatan Baru: coba lagi yang lains emoga bisa lagi, Bismillah', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 29 May 2026, 22:48 di kost tidar.', 0, NULL, '2026-05-20 15:48:47', 'manual', 1),
(976, 34, 8, 'Kegiatan Baru: coba lagi yang lains emoga bisa lagi, Bismillah', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 29 May 2026, 22:48 di kost tidar.', 0, NULL, '2026-05-20 15:48:47', 'manual', 1),
(977, 34, 19, 'Kegiatan Baru: coba lagi yang lains emoga bisa lagi, Bismillah', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 29 May 2026, 22:48 di kost tidar.', 0, NULL, '2026-05-20 15:48:47', 'manual', 0),
(978, 34, 5, 'Kegiatan Baru: coba lagi yang lains emoga bisa lagi, Bismillah', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 29 May 2026, 22:48 di kost tidar.', 0, NULL, '2026-05-20 15:48:47', 'manual', 0),
(979, 33, 1, 'Kegiatan Dimulai: coba yah semoga bisa, Bismillah', 'Kegiatan \"coba yah semoga bisa, Bismillah\" sedang berlangsung sekarang!', 0, NULL, '2026-05-22 01:56:40', 'mulai', 1),
(980, 33, 2, 'Kegiatan Dimulai: coba yah semoga bisa, Bismillah', 'Kegiatan \"coba yah semoga bisa, Bismillah\" sedang berlangsung sekarang!', 0, NULL, '2026-05-22 01:56:40', 'mulai', 0),
(981, 33, 6, 'Kegiatan Dimulai: coba yah semoga bisa, Bismillah', 'Kegiatan \"coba yah semoga bisa, Bismillah\" sedang berlangsung sekarang!', 0, NULL, '2026-05-22 01:56:40', 'mulai', 0),
(982, 33, 12, 'Kegiatan Dimulai: coba yah semoga bisa, Bismillah', 'Kegiatan \"coba yah semoga bisa, Bismillah\" sedang berlangsung sekarang!', 0, NULL, '2026-05-22 01:56:40', 'mulai', 0),
(983, 33, 25, 'Kegiatan Dimulai: coba yah semoga bisa, Bismillah', 'Kegiatan \"coba yah semoga bisa, Bismillah\" sedang berlangsung sekarang!', 0, NULL, '2026-05-22 01:56:40', 'mulai', 0),
(984, 33, 3, 'Kegiatan Dimulai: coba yah semoga bisa, Bismillah', 'Kegiatan \"coba yah semoga bisa, Bismillah\" sedang berlangsung sekarang!', 0, NULL, '2026-05-22 01:56:40', 'mulai', 1),
(985, 33, 4, 'Kegiatan Dimulai: coba yah semoga bisa, Bismillah', 'Kegiatan \"coba yah semoga bisa, Bismillah\" sedang berlangsung sekarang!', 0, NULL, '2026-05-22 01:56:40', 'mulai', 1),
(986, 33, 8, 'Kegiatan Dimulai: coba yah semoga bisa, Bismillah', 'Kegiatan \"coba yah semoga bisa, Bismillah\" sedang berlangsung sekarang!', 1, '2026-05-22 01:56:43', '2026-05-22 01:56:40', 'mulai', 1),
(987, 33, 19, 'Kegiatan Dimulai: coba yah semoga bisa, Bismillah', 'Kegiatan \"coba yah semoga bisa, Bismillah\" sedang berlangsung sekarang!', 0, NULL, '2026-05-22 01:56:40', 'mulai', 0),
(988, 33, 5, 'Kegiatan Dimulai: coba yah semoga bisa, Bismillah', 'Kegiatan \"coba yah semoga bisa, Bismillah\" sedang berlangsung sekarang!', 0, NULL, '2026-05-22 01:56:40', 'mulai', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password`
--

CREATE TABLE `password` (
  `id_password` int(11) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `password`
--

INSERT INTO `password` (`id_password`, `id_anggota`, `username`, `password`) VALUES
(1, 1, 'irzal.ketua', '$2y$10$c/E4di3XU6183fL4N/svKuEA4dLMlglzQx9ZQrWAI7VbaBj7f15hS'),
(2, 2, 'fajar.sekret', '$2y$10$c/E4di3XU6183fL4N/svKuEA4dLMlglzQx9ZQrWAI7VbaBj7f15hS'),
(3, 3, 'devis.sekret', '$2y$10$c/E4di3XU6183fL4N/svKuEA4dLMlglzQx9ZQrWAI7VbaBj7f15hS'),
(4, 4, 'gabriel.bendah', '$2y$10$c/E4di3XU6183fL4N/svKuEA4dLMlglzQx9ZQrWAI7VbaBj7f15hS'),
(5, 5, 'aditya.wakil', '$2y$10$c/E4di3XU6183fL4N/svKuEA4dLMlglzQx9ZQrWAI7VbaBj7f15hS'),
(6, 6, 'nanda.bendah', '$2y$10$c/E4di3XU6183fL4N/svKuEA4dLMlglzQx9ZQrWAI7VbaBj7f15hS'),
(7, 8, 'fajarmuharram1901', '$2y$10$smG7qAeRRcmm0J6EobxCkOAW6t8EO1pw0Wpd2d2bZ/GMnHYS7ghKu'),
(11, 12, 'e41251513', '$2y$10$2lOWZO3bndDmFOv/Eox2ZOndgTWQ6Ony8OtYsLGi0OI2WyvjnzTD6'),
(18, 19, 'irzalamru83', '$2y$10$KlqcyhZMXSb1Fnh0Er9AcOqr7MKyQ171zaw.fF/bO7Rrf4m0hGnVu'),
(23, 25, 'fajar.muharram87', '$2y$10$BWtRmVLXTuKqiDhz8BKe4eIX5o7DNzrVV1JlxsGL983lYHazwYz2e');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pemasukan`
--

CREATE TABLE `pemasukan` (
  `id_pemasukan` int(11) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `id_kegiatan` int(11) DEFAULT NULL,
  `sumber_dana` varchar(100) DEFAULT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `tanggal` date NOT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pemasukan`
--

INSERT INTO `pemasukan` (`id_pemasukan`, `id_anggota`, `id_kategori`, `id_kegiatan`, `sumber_dana`, `jumlah`, `tanggal`, `bukti_pembayaran`, `status`) VALUES
(32, 8, 1, NULL, 'uang kas', 10000.00, '2026-05-20', 'uploads/keuangan/1779255076_121213.jpeg', 'Berhasil'),
(35, 1, 1, NULL, 'uang kas', 200000.00, '2026-05-20', 'uploads/keuangan/1779255587_20260306_144932_deee44ad_MINGGU 7- WORKSHOP SISTEM INFORMASI-BKPM.pdf', 'Berhasil'),
(36, 1, 1, NULL, 'uang kas', 10000.00, '2026-05-20', 'uploads/keuangan/1779256005_070c617ff50c7d67ff58b6d46f61f3ef.jpg', 'Menunggu'),
(37, 1, 1, NULL, 'uang kas', 20000.00, '2026-05-20', 'uploads/keuangan/1779256875_11063-Article Text-4891-1-10-20210831.pdf', 'Menunggu'),
(39, 1, 1, NULL, 'uang kas', 100000.00, '2026-05-20', 'uploads/keuangan/1779258254_Minggu 9_D_E41251558_Irzal Amru Alqahtani\'.pdf', 'Berhasil'),
(40, 1, 1, NULL, 'uang kas', 30000.00, '2026-05-20', 'uploads/keuangan/1779258775_20260306_144932_deee44ad_MINGGU 7- WORKSHOP SISTEM INFORMASI-BKPM.pdf', 'Menunggu'),
(41, 1, 1, NULL, 'uang kas', 10000.00, '2026-05-20', 'uploads/keuangan/1779259553_30425ebb613aa7bc4656cfe703468686.jpg', 'Menunggu'),
(43, 1, 1, NULL, 'uang kas', 10000.00, '2026-05-20', 'uploads/keuangan/1779260220_20260307_211617_ea465896_Pertemuan 6 OOP_compressed.pdf', 'Berhasil'),
(48, 1, 3, NULL, 'uang kas', 1000000.00, '2026-05-20', 'uploads/keuangan/1779267098_20260309_083006_d0b971a1_Studi Kasus Peminjaman Ruang dan Aset fix (1).pdf', 'Berhasil'),
(49, 1, 1, NULL, 'uang kas', 30000.00, '2026-05-20', 'uploads/keuangan/1779269274_12642-64192-1-PB.pdf', 'Berhasil'),
(50, 19, 3, NULL, 'bcss', 1000000.00, '2026-05-20', 'uploads/keuangan/1779272478_VOL.2+JUNI+2024+HAL+66-74.pdf', 'Berhasil');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjam`
--

CREATE TABLE `peminjam` (
  `id_peminjam` int(11) NOT NULL,
  `kode_peminjam` varchar(10) NOT NULL,
  `id_anggota` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `peminjam`
--

INSERT INTO `peminjam` (`id_peminjam`, `kode_peminjam`, `id_anggota`) VALUES
(1, 'PJM-001', 3),
(2, 'PJM-002', 5),
(3, 'PJM-003', 2),
(4, 'PJM-004', 6),
(5, 'PJM-005', 4),
(6, 'PM-5592', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman_ruangan`
--

CREATE TABLE `peminjaman_ruangan` (
  `id_peminjaman` int(11) NOT NULL,
  `kode_peminjaman` varchar(10) NOT NULL,
  `id_peminjam` int(11) NOT NULL,
  `tujuan_peminjaman` varchar(100) DEFAULT NULL,
  `surat_peminjaman` varchar(255) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Menunggu',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `peminjaman_ruangan`
--

INSERT INTO `peminjaman_ruangan` (`id_peminjaman`, `kode_peminjaman`, `id_peminjam`, `tujuan_peminjaman`, `surat_peminjaman`, `status`, `created_at`, `updated_at`) VALUES
(14, 'PR-1417121', 6, 'rapat', 'uploads/surat_peminjaman/sp_6a059ffb623a7_AFS Academic History 01 2018 (1).pdf', 'Ditolak', '2026-05-14 10:12:11', '2026-05-15 18:39:46'),
(15, 'PR-1418461', 1, 'kkk', 'uploads/surat_peminjaman/sp_6a05b60503474_D_E41251276_DevisSaptaPratama.pdf', 'Menunggu', '2026-05-14 11:46:13', '2026-05-20 06:46:42'),
(20, 'PR-2007040', 6, 'presentasi proyek', 'uploads/surat_peminjaman/sp_6a0cfa7740bed_BAB I - Wahyu.pdf', 'Disetujui', '2026-05-20 00:04:07', '2026-05-20 00:04:24'),
(21, 'PR-2017264', 3, 'rapat', 'uploads/surat_peminjaman/sp_6a0d8c60cf5e3_D_E41251215_Fajar Muharram.docx', 'Ditolak', '2026-05-20 10:26:40', '2026-05-22 08:19:30'),
(23, 'PR-2023112', 5, 'buat acara lomba hmj', 'uploads/surat_peminjaman/sp_6a0ddd2bf0ecf_D_E41251254_GABRIEL PUTRA SYAHBANI HIDAYAHTULLAH-BD13.pdf', 'Menunggu', '2026-05-20 16:11:23', '2026-05-20 16:11:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan_surat`
--

CREATE TABLE `pengajuan_surat` (
  `id_pengajuan` int(11) NOT NULL,
  `id_surat` int(11) NOT NULL,
  `tanggal_unggah` date NOT NULL,
  `tanggal_update` date DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Menunggu',
  `alasan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pengajuan_surat`
--

INSERT INTO `pengajuan_surat` (`id_pengajuan`, `id_surat`, `tanggal_unggah`, `tanggal_update`, `status`, `alasan`) VALUES
(7, 7, '2026-05-12', '2026-05-13', 'Selesai', NULL),
(11, 11, '2026-05-16', '2026-05-21', 'Diproses', NULL),
(12, 12, '2026-05-16', NULL, 'Menunggu', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengeluaran`
--

CREATE TABLE `pengeluaran` (
  `id_pengeluaran` int(11) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `id_kegiatan` int(11) DEFAULT NULL,
  `penerima` varchar(100) DEFAULT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `tanggal` date NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pengeluaran`
--

INSERT INTO `pengeluaran` (`id_pengeluaran`, `id_anggota`, `id_kategori`, `id_kegiatan`, `penerima`, `jumlah`, `tanggal`, `status`) VALUES
(30, 1, 6, NULL, 'jufri sukir', 100000.00, '2026-05-20', 'Disetujui'),
(33, 1, 5, NULL, 'jufri sukir', 80000.00, '2026-05-20', 'Disetujui'),
(36, 8, 5, NULL, 'toko perlengkapan', 80000.00, '2026-05-20', 'Disetujui'),
(37, 1, 4, NULL, 'jefri umar', 70000.00, '2026-05-20', 'Disetujui'),
(38, 1, 5, NULL, 'terakhir', 20000.00, '2026-05-20', 'Disetujui');

-- --------------------------------------------------------

--
-- Struktur dari tabel `periode`
--

CREATE TABLE `periode` (
  `id_periode` int(11) NOT NULL,
  `tahun_mulai` year(4) NOT NULL,
  `tahun_selesai` year(4) NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `periode`
--

INSERT INTO `periode` (`id_periode`, `tahun_mulai`, `tahun_selesai`, `keterangan`) VALUES
(1, '2024', '2025', 'Periode kepengurusan 2024/2025'),
(2, '2025', '2026', 'Periode kepengurusan 2025/2026');

-- --------------------------------------------------------

--
-- Struktur dari tabel `role`
--

CREATE TABLE `role` (
  `id_role` int(11) NOT NULL,
  `role_level` varchar(20) NOT NULL,
  `keterangan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `role`
--

INSERT INTO `role` (`id_role`, `role_level`, `keterangan`) VALUES
(1, 'ketua', 'Ketua & Wakil Ketua organisasi'),
(2, 'sekretaris', 'Sekretaris organisasi'),
(3, 'bendahara', 'Bendahara organisasi'),
(4, 'anggota', 'Anggota / pengurus divisi'),
(5, 'alumni', 'Anggota alumni');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ruangan`
--

CREATE TABLE `ruangan` (
  `id_ruangan` int(11) NOT NULL,
  `kode_ruangan` varchar(10) NOT NULL,
  `nama_ruangan` varchar(50) NOT NULL,
  `kapasitas` int(11) DEFAULT 0,
  `kursi` int(11) DEFAULT 0,
  `meja` int(11) DEFAULT 0,
  `ac` tinyint(4) DEFAULT 0,
  `papan_tulis` tinyint(4) DEFAULT 0,
  `proyektor` tinyint(4) DEFAULT 0,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `ruangan`
--

INSERT INTO `ruangan` (`id_ruangan`, `kode_ruangan`, `nama_ruangan`, `kapasitas`, `kursi`, `meja`, `ac`, `papan_tulis`, `proyektor`, `foto`) VALUES
(19, 'RNG-65598B', 'Aula luar', 40, 40, 40, 1, 1, 1, 'uploads/ruangan/ruangan_6a059ee6557d8.jpg'),
(22, 'RNG-B87599', '3.12', 32, 32, 32, 1, 1, 1, 'uploads/ruangan/ruangan_6a0d1b34b8d88.jpeg'),
(23, 'RNG-1239EE', '3.3', 80, 80, 80, 1, 1, 1, 'uploads/ruangan/ruangan_6a0d1e8de6561.jpg'),
(24, 'RNG-4E9FD4', '3.11', 42, 42, 42, 1, 1, 1, 'uploads/ruangan/ruangan_6a0c63c4e8be0.jpg'),
(25, 'RNG-BE3497', '3.5', 20, 20, 20, 1, 1, 0, 'uploads/ruangan/ruangan_6a0d1b3be3271.jpeg'),
(26, 'RNG-CB34C2', '3.4', 80, 80, 80, 1, 1, 1, 'uploads/ruangan/ruangan_6a0d5a7237746.jpeg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sesi_login`
--

CREATE TABLE `sesi_login` (
  `id_sesi` int(11) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `login_at` datetime NOT NULL DEFAULT current_timestamp(),
  `expires_at` datetime NOT NULL,
  `logout_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sesi_login`
--

INSERT INTO `sesi_login` (`id_sesi`, `id_anggota`, `token`, `ip_address`, `user_agent`, `login_at`, `expires_at`, `logout_at`) VALUES
(1, 1, 'tok_irzal_001abc', '192.168.1.10', 'Mozilla/5.0 (Windows NT 10.0)', '2026-10-01 08:00:00', '2026-10-01 20:00:00', '2026-10-01 15:30:00'),
(3, 3, 'tok_devis_003ghi', '192.168.1.12', 'Mozilla/5.0 (Windows NT 10.0)', '2026-10-01 09:00:00', '2026-10-01 21:00:00', '2026-05-20 03:09:32'),
(4, 1, 'tok_irzal_004jkl', '192.168.1.10', 'Mozilla/5.0 (iPhone)', '2026-11-01 07:45:00', '2026-11-01 19:45:00', '2026-11-01 12:00:00'),
(5, 4, 'tok_gabriel_005mno', '192.168.1.13', 'Mozilla/5.0 (Windows NT 10.0)', '2026-11-10 08:30:00', '2026-11-10 20:30:00', '2026-05-16 01:54:42'),
(6, 4, '5c102b8bcfab13fbbd2b2c6ebf508d5e8e950bc85ed79e389bc38652e4595c6d', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-12 01:32:50', '2026-06-11 08:32:50', '2026-05-12 01:35:37'),
(7, 5, 'e56c6a22b6ecc035d1f06bd73fd61d2263d53701493969890ad88654096e06e1', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-05-12 01:35:46', '2026-06-13 15:55:52', '2026-05-15 02:22:00'),
(8, 4, '80793802da14e96b73e9c802408c9ec9d5677a7eca5b5046328347c29d120add', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-12 01:36:11', '2026-06-11 08:36:11', '2026-05-16 01:54:42'),
(9, 8, '132b6ac7ca010429f45822596db796083a9dbf1e4f376169b6f94142c99fd3d9', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 01:37:35', '2026-06-11 08:37:35', '2026-05-13 02:45:43'),
(12, 1, '43608b6cddeff4bbb1541aeb562e932faf159ddb47e7705d6e75ba39575b147e', '182.5.233.131', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-05-12 01:57:12', '2026-06-11 08:57:12', '2026-05-13 02:53:32'),
(14, 6, 'cc46b2b37707709be0c6aff53d7e1154e4624720442c2a9f16da7b4424e16f78', '103.160.182.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-05-12 11:00:28', '2026-06-11 18:00:28', '2026-05-13 00:12:08'),
(15, 3, 'a94762fe75515eef7649905b8e57a5e359e0cba51c25749c0f1bb75002083d92', '2404:8000:1014:670:6189:5809:d360:6898', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-12 16:02:56', '2026-06-11 23:02:56', '2026-05-20 03:09:32'),
(16, 3, '67a04c74eb303841a670a6b0911eaac87bf452a0264bafff39f18ef091b82f81', '38.81.163.37', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 16:17:55', '2026-06-11 23:17:55', '2026-05-12 16:21:04'),
(17, 3, '5f0940a771c86ca7d377e3a3ba35c2b3d736f091bd63811567848ead6f4040dd', '38.81.163.37', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 16:42:01', '2026-06-13 18:45:21', '2026-05-20 01:23:31'),
(19, 2, '1566a9d2ff682aac9540f30e372bbcd483b9684157c6377a637087003335a9e8', '2001:448a:5130:591d:15dc:3593:2c9b:17bd', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 18:02:41', '2026-06-12 01:02:41', '2026-05-12 18:02:56'),
(20, 2, '400102b445ceaa73c021820d51760a565620c934e3f1003d058cb05621b32618', '2001:448a:5130:591d:15dc:3593:2c9b:17bd', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 18:12:39', '2026-06-12 01:12:39', '2026-05-12 18:12:59'),
(21, 2, '8ca3952f569ab690dc5d2f2814f3017f1fd314d10ab8c4471e9813274c0ed746', '2001:448a:5130:591d:15dc:3593:2c9b:17bd', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 18:17:57', '2026-06-12 01:17:57', '2026-05-12 18:18:26'),
(22, 2, '733a58da978ebcc0d02732414f54db5a1a4570b3c4b636e4790ef9fc9dea7831', '2001:448a:5130:591d:15dc:3593:2c9b:17bd', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 18:18:57', '2026-06-12 01:18:57', '2026-05-12 18:54:53'),
(23, 2, '4749cb57345eb82f394fae977b3ab69e2373efee67b0b13681ed39e662095885', '2001:448a:5130:591d:4019:9a5e:fd26:9637', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 18:55:21', '2026-06-12 01:55:21', '2026-05-12 18:58:10'),
(24, 2, 'c15837bbd404163982fc829f415d14035a8b15f039dc909dc11a0d8dd15b1ea2', '2001:448a:5130:591d:4019:9a5e:fd26:9637', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 18:58:35', '2026-06-12 01:58:35', '2026-05-13 03:08:16'),
(27, 6, '7e519c3809c9a39b8b54997e46a22f151519bc5335df1b8dce954eae695ce3ce', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-05-13 00:12:52', '2026-06-12 07:12:52', '2026-05-13 00:17:08'),
(28, 6, '841591936ccc4610dd38d11232f7d18a54805a4bee900a1e765b2e297e561a15', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-05-13 00:44:52', '2026-06-12 07:44:52', '2026-05-13 00:45:08'),
(29, 6, '2874a00cb50024f598264c18b5435a7bf9cb7129516838ee1517f3d0b6de804e', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-05-13 00:46:02', '2026-06-12 07:46:02', '2026-05-13 00:48:08'),
(30, 6, 'd002cfc55f1b3f02304314e1e3bc93726284d6c9a02d795bcc041d300471e54e', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-05-13 00:48:31', '2026-06-12 07:48:31', '2026-05-13 00:50:45'),
(31, 6, '70d0ee22bb4d4ba044457fa7a2d2a6e86518d84da6efb5b90f234f9357c23570', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-05-13 00:52:38', '2026-06-14 23:52:17', '2026-05-15 16:54:10'),
(32, 8, '748075c003023b924d7c762f0808b74cfd9e2cd6096772ad81d52144907ed129', '2400:9800:702:c047:1e85:9457:100c:c89b', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-13 01:16:50', '2026-06-13 17:08:43', NULL),
(33, 12, '1dd19a6a6f7c1ac0342bbb4e11a155d5b107f750e67321bd1878ef5bc006258c', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-13 01:23:38', '2026-06-12 08:23:38', '2026-05-15 18:38:28'),
(34, 2, 'b9cbf7a2454208682301f31ddf33db9424fe20f5e4a1ada58865f27c8f323fb9', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 02:54:09', '2026-06-12 09:54:09', '2026-05-13 03:08:16'),
(35, 1, '1902e5fc880d7cc3b687f459639580b9f73ce6a3180447fd61e4075d2ed7d8e3', '2404:c0:3075:cb4:90e1:7cf7:437f:62df', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-05-13 02:56:07', '2026-06-13 13:04:20', NULL),
(36, 2, '2cb008abad4c6004458acf213c088801b6f4d313d2ffd671950964f61688dc64', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 03:08:59', '2026-06-12 11:29:38', '2026-05-17 00:12:44'),
(37, 2, 'ba522a5bfd2598918110ab577795b5fd1d15dd06f3ae7b60d367c739ea6c4274', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 03:10:29', '2026-06-12 10:10:29', '2026-05-17 00:12:44'),
(38, 8, '8e6ee08ea768a1f24bd1eb80e096c4c7c267d5b6f9a600a32a21dcb6e5ea723e', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 04:27:54', '2026-06-12 11:27:54', '2026-05-20 08:01:15'),
(39, 1, 'c2bbd594ab6483f7cd833c52f63d2d43395a0cd54d58ccd4a66d97f181aeed1b', '2001:448a:5130:38a7:f04d:da70:a82e:8b09', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-14 08:17:46', '2026-06-13 15:17:46', NULL),
(42, 3, 'ead129b0e9de5eac7fe86f46ee53aafdb80d79588540914ed5d48c84b488a141', '38.81.163.37', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-15 13:25:17', '2026-06-14 20:25:17', '2026-05-20 03:09:32'),
(43, 6, 'fb593161d202c78709324dd7a3541b8dde6dc4b4ed3ef9c9b1fa9916f9d1afb6', '103.160.182.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-05-15 17:01:15', '2026-06-15 00:01:15', '2026-05-15 17:22:53'),
(45, 6, '88586dc696fee30913a028ffd6d56da0f34219ed591fd972fbef3c3bc1ba208d', '103.160.182.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-05-15 17:23:30', '2026-06-15 00:23:30', '2026-05-15 17:32:31'),
(46, 6, 'ca7c288a455bdf949934007cb5bbd8c544c3e720911ca62924d40af3b0fcc24c', '103.160.182.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-05-15 17:37:13', '2026-06-15 00:37:13', '2026-05-15 17:48:31'),
(47, 6, 'dbb15cb9869914c559a5c54b7ca3119d8eda9aef9eef24be3dc632524a84e629', '103.160.182.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-05-15 17:53:19', '2026-06-15 00:53:19', '2026-05-15 17:55:39'),
(48, 6, '6541e2815b53f3f3bd3944b2234fa1b84cd52d286fde116665019c39ab279a2f', '103.160.182.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-05-15 17:56:24', '2026-06-15 00:56:24', '2026-05-15 18:00:11'),
(49, 6, 'c99375946af0b727d80ebed25538445cbd4e1971d899203fefbb0cd073a66251', '103.160.182.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-05-15 18:02:09', '2026-06-15 01:02:09', '2026-05-15 18:03:20'),
(50, 6, '2c2d10b38023ef8d9a934573a5928565bdfd9a9375bc447573b9b71faed78804', '103.160.182.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-05-16 08:22:21', '2026-06-15 15:22:21', '2026-05-16 08:51:15'),
(51, 5, '6c4d93082723c386df0145b792b7584e334626cce58ef2994e43b5671db4d6cd', '2404:c0:357c:6e48:ac0c:2dff:fe40:16d8', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Mobile Safari/537.36', '2026-05-16 16:41:29', '2026-06-15 23:41:29', NULL),
(52, 2, '7edb6f1c60d0903232d227538b4dc18561463b8e9fe0c92b084ede8ffb970c3a', '2001:448a:5130:44ff:2064:4470:e913:3504', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 00:13:30', '2026-06-16 07:13:30', '2026-05-17 03:33:09'),
(53, 2, 'bca551fbbf6b415da5d0201898b2bb82f852fd26adb704bd95662c8b663b1ea1', '2001:448a:5130:44ff:2064:4470:e913:3504', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 05:06:08', '2026-06-16 12:06:08', '2026-05-20 04:39:33'),
(54, 6, 'e9b511f0d622d15300d72a9ad463adaedf44f1d3e6d2a58bd421d95eb8b401ce', '103.160.182.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-05-17 06:25:38', '2026-06-16 13:25:38', '2026-05-17 06:42:43'),
(55, 6, '579c93e7a335a8e686adb0856343b8f190ebf72fab8e47e7e3ce3385c91c0496', '103.160.182.180', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 09:24:11', '2026-06-16 16:24:11', '2026-05-17 09:24:48'),
(56, 6, '38bbc028a1041637b9c1e40c87b51f76e11946bedd22522d6d52de53f2bd9474', '103.160.182.180', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 09:25:42', '2026-06-16 16:25:42', '2026-05-17 09:26:30'),
(58, 6, '60e5cf387dd7b0afd007ef61f33d98bb0f8ad62272c68f519a70cb73f6c6c113', '103.160.182.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-05-19 04:23:17', '2026-06-18 11:23:17', '2026-05-20 03:08:58'),
(59, 5, '2e4358502bffaecde8672bdc7748329389df48b809d46bece29eb1d127458f8d', '2404:c0:3569:3eeb:4db3:eab6:5486:d2c4', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-05-19 10:03:39', '2026-06-18 17:03:39', NULL),
(60, 6, '1db3e655ee666fff2e94ed9da6771fb6d024109cd028d42a22ff462125497778', '103.160.182.180', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-19 11:56:01', '2026-06-18 18:56:01', '2026-05-20 03:11:03'),
(61, 3, '468e47a0c0f89bb47c6e8cde1969c0548c6be4e30ececc518892bd0afe36f5b4', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-20 01:28:32', '2026-06-19 08:28:32', '2026-05-20 03:09:32'),
(62, 3, 'a11d13e602138232897bdf2ef73c17789f4903489c5f0485cdc2d016e7d76ff0', '182.5.247.42', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-20 02:41:02', '2026-06-19 09:41:02', '2026-05-20 03:09:32'),
(63, 3, '62496ff7317b5c4559b6107f30db738d0f5c6c9a0295c5617c08b525ed62fd1f', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-20 03:09:56', '2026-06-19 10:09:56', '2026-05-20 03:13:39'),
(64, 19, 'bdd7a5e0f2e7a6961a6c1d4b81fc0d06fd5181a8836fb66ba53124cb70f6ed0c', '103.144.221.180', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-20 03:11:02', '2026-06-19 10:11:02', '2026-05-20 03:46:50'),
(65, 3, '8fa53492a82db4fe75c233ca5f819520964f3bc589d2493afce49bdfbe916aac', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-20 03:14:35', '2026-06-19 10:14:35', '2026-05-20 07:58:07'),
(66, 6, '951e6c2c757d2ea7579dee27f6a43397b1d0faf9163b8390124b60628c775dd3', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-05-20 03:33:49', '2026-06-19 10:33:49', '2026-05-20 03:34:45'),
(67, 6, 'f82a25ea6384b8865604d4fc098398b685be1e3ad5ca2764206c753fe7ed8014', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-05-20 03:35:24', '2026-06-19 10:35:24', '2026-05-20 06:50:13'),
(68, 4, '6904b17cec845d545030d12adb182f2615dc5ae1b27af457c03dc8e02e8db2d0', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-20 03:56:00', '2026-06-19 10:56:00', NULL),
(69, 8, 'd1b765fa59fa31f5079a8c5e2788271bef4e355fa40d078850fbc754ef6eaf54', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-20 04:36:58', '2026-06-19 11:36:58', '2026-05-20 04:37:51'),
(70, 2, '634e0a6a75ba481493861a4b93796d679b28a2dfc256aec1be028852951b5c25', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-20 04:38:53', '2026-06-19 11:38:53', '2026-05-20 04:39:33'),
(71, 2, '6d3112083b4e52065c771193380f2e75488ed183109c3596fe6df41c97b91caa', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-20 04:40:09', '2026-06-19 11:40:09', NULL),
(72, 19, '9bc9db347672fd4fc4bdbea2f40fe3085ee7808396750e8640caf796d9c607b9', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-20 07:11:59', '2026-06-19 14:11:59', NULL),
(73, 25, 'd05ed5517dd9605cd8aec4d4b498e97795c7a8cfb2ad1f9bf1b2e2a436d3518b', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-20 07:17:18', '2026-06-19 14:17:18', NULL),
(74, 3, 'f8976b9861961633d3a899485ea507d9cdf93724f59841fda3fa9568e35bfd8d', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-20 08:06:30', '2026-06-19 15:06:30', NULL),
(75, 6, '2394fdf464273bc4da8b163f3afb531fadc4d9cf545ff69b1a80ce390a92b66c', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-05-20 08:38:11', '2026-06-19 15:38:11', '2026-05-20 08:54:57'),
(76, 8, 'd7e040ab16d29e53d46b31bc57f5f50044dad9cb561bf34b16e7a983391642cd', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-20 08:50:07', '2026-06-19 15:50:07', '2026-05-20 09:25:33'),
(77, 6, '0a73bb605aec4e5836a4651691a768c1aed2b1450684e9c1d72273fe7cbd4d21', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-05-20 08:57:46', '2026-06-19 15:57:46', NULL),
(78, 8, '03cf452a7b2adbcf3ef4c367db3a3ea45b590de40efc1da97cf5baa33eca236c', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-20 09:25:49', '2026-06-19 16:25:49', '2026-05-20 09:29:11'),
(79, 8, '8d42005173c04ea260233a526776ffd007b9d77900ae8cdccdc5ee7de718d84d', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-20 10:34:25', '2026-06-19 17:34:25', NULL),
(80, 4, '06a9f7b73e6615bdbc5fda192d2a580d045153820c73645a2f7acef9089234d2', '125.166.117.161', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-21 02:04:56', '2026-06-20 09:04:56', '2026-05-21 09:26:28'),
(81, 4, '47559b75c5ff1140854f0fd88aa241afdb1b8fbbfc51775bc81a7036577b5193', '125.166.117.161', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-22 08:18:53', '2026-06-21 15:18:53', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `status_history`
--

CREATE TABLE `status_history` (
  `id_history` int(11) NOT NULL,
  `id_pengajuan` int(11) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `status_lama` varchar(20) DEFAULT NULL,
  `status_baru` varchar(20) NOT NULL,
  `tanggal_perubahan` datetime NOT NULL DEFAULT current_timestamp(),
  `catatan` text DEFAULT NULL,
  `alasan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `status_history`
--

INSERT INTO `status_history` (`id_history`, `id_pengajuan`, `id_anggota`, `status_lama`, `status_baru`, `tanggal_perubahan`, `catatan`, `alasan`) VALUES
(8, 7, 5, 'Menunggu', 'Menunggu', '2026-05-13 01:09:52', NULL, NULL),
(11, 7, 8, 'Menunggu', 'Selesai', '2026-05-13 04:28:37', NULL, NULL),
(15, 11, 8, 'Menunggu', 'Diproses', '2026-05-15 18:40:53', NULL, NULL),
(16, 11, 8, 'Diproses', 'Selesai', '2026-05-15 18:41:34', NULL, NULL),
(17, 11, 8, 'Selesai', 'Diproses', '2026-05-15 18:41:44', NULL, NULL),
(18, 11, 6, 'Diproses', 'Selesai', '2026-05-16 08:31:45', NULL, NULL),
(19, 11, 6, 'Selesai', 'Diproses', '2026-05-17 06:39:24', NULL, NULL),
(20, 11, 4, 'Diproses', 'Ditolak', '2026-05-21 09:18:22', NULL, NULL),
(21, 11, 4, 'Ditolak', 'Diproses', '2026-05-21 09:24:37', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `status_history_keuangan`
--

CREATE TABLE `status_history_keuangan` (
  `id_history` int(11) NOT NULL,
  `tipe` enum('pemasukan','pengeluaran') NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `status_lama` varchar(20) DEFAULT NULL,
  `status_baru` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `status_history_keuangan`
--

INSERT INTO `status_history_keuangan` (`id_history`, `tipe`, `id_transaksi`, `id_anggota`, `status_lama`, `status_baru`, `created_at`, `catatan`) VALUES
(1, 'pemasukan', 1, 1, 'Menunggu', 'Disetujui', '2026-05-06 08:44:00', 'Iuran September sudah diverifikasi'),
(2, 'pemasukan', 2, 1, 'Menunggu', 'Disetujui', '2026-05-06 08:44:00', 'Dana DIPA seminar sudah masuk'),
(3, 'pemasukan', 4, 1, 'Menunggu', 'Disetujui', '2026-05-06 08:44:00', 'Iuran Oktober sudah diverifikasi'),
(4, 'pemasukan', 5, 1, 'Menunggu', 'Disetujui', '2026-05-06 08:44:00', 'Dana DIPA LKTI sudah masuk'),
(5, 'pemasukan', 7, 1, 'Menunggu', 'Disetujui', '2026-05-06 08:44:00', 'Dana DIPA Musyawarah sudah masuk'),
(6, 'pengeluaran', 1, 1, 'Menunggu', 'Disetujui', '2026-05-06 08:44:00', 'Kwitansi catering diterima'),
(7, 'pengeluaran', 2, 1, 'Menunggu', 'Disetujui', '2026-05-06 08:44:00', 'Struk ATK diterima'),
(8, 'pengeluaran', 3, 1, 'Menunggu', 'Ditolak', '2026-05-06 08:44:00', 'Tidak ada bukti pembayaran transport'),
(9, 'pengeluaran', 4, 1, 'Menunggu', 'Disetujui', '2026-05-06 08:44:00', 'Kwitansi catering LKTI diterima'),
(10, 'pengeluaran', 5, 1, 'Menunggu', 'Disetujui', '2026-05-06 08:44:00', 'Struk ATK LKTI diterima'),
(11, 'pengeluaran', 6, 1, 'Menunggu', 'Disetujui', '2026-05-06 08:44:00', 'Honorarium instruktur UI/UX dibayar');

-- --------------------------------------------------------

--
-- Struktur dari tabel `status_history_ruangan`
--

CREATE TABLE `status_history_ruangan` (
  `id_history` int(11) NOT NULL,
  `id_peminjaman` int(11) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `status_lama` varchar(20) DEFAULT NULL,
  `status_baru` varchar(20) NOT NULL,
  `tanggal_perubahan` datetime NOT NULL DEFAULT current_timestamp(),
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `surat`
--

CREATE TABLE `surat` (
  `id_surat` int(11) NOT NULL,
  `id_draft` int(11) NOT NULL,
  `nomor_surat` varchar(50) DEFAULT NULL COMMENT 'Format: 001/HMJ-TI/III/2026',
  `file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `surat`
--

INSERT INTO `surat` (`id_surat`, `id_draft`, `nomor_surat`, `file`) VALUES
(7, 7, '001/HMJTI/2/2027', 'uploads/surat/surat_6a030e2818a15_Artikel+Jurnal+Penelitian+Pendidikan (2).pdf'),
(11, 11, 'pinjam', 'uploads/surat/surat_6a075a94eb57c_20260504_210350_284b8da8_Workshop Pengembangan Proyek Perangkat Lunak_Minggu 12.pdf'),
(12, 12, '022/HMJ-TI/V/2026', 'uploads/surat/surat_6a075e813d383_20260504_210350_284b8da8_Workshop Pengembangan Proyek Perangkat Lunak_Minggu 12.pdf');

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_anggota_lengkap`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_anggota_lengkap` (
`id_anggota` int(11)
,`nim` varchar(20)
,`nama_lengkap` varchar(150)
,`email` varchar(100)
,`no_telp` varchar(20)
,`angkatan` varchar(10)
,`jurusan` varchar(100)
,`program_studi` varchar(100)
,`status_keanggotaan` varchar(50)
,`nama_jabatan` varchar(100)
,`nama_divisi` varchar(100)
,`role_level` varchar(20)
,`kelurahan` varchar(100)
,`kecamatan` varchar(100)
,`kota_kabupaten` varchar(100)
,`provinsi` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_anggota_per_periode`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_anggota_per_periode` (
`tahun_mulai` year(4)
,`tahun_selesai` year(4)
,`nim` varchar(20)
,`nama_lengkap` varchar(150)
,`nama_jabatan` varchar(100)
,`nama_divisi` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_jadwal_peminjaman`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_jadwal_peminjaman` (
`id_detail` int(11)
,`kode_ruangan` varchar(10)
,`nama_ruangan` varchar(50)
,`nama_peminjam` varchar(150)
,`nim` varchar(20)
,`tujuan_peminjaman` varchar(100)
,`status` varchar(20)
,`waktu_mulai` datetime
,`waktu_selesai` datetime
);

-- --------------------------------------------------------

--
-- Struktur dari tabel `v_laporan_keuangan`
--

CREATE ALGORITHM=UNDEFINED DEFINER=`u458429422_Nakama_HMJTI`@`127.0.0.1` SQL SECURITY DEFINER VIEW `v_laporan_keuangan`  AS SELECT 'pemasukan' AS `tipe`, `pm`.`id_pemasukan` AS `id_transaksi`, `pm`.`kode_pemasukan` AS `kode`, `pm`.`tanggal` AS `tanggal`, `pm`.`jumlah` AS `jumlah`, `pm`.`status` AS `status`, `k`.`nama_kategori` AS `nama_kategori`, `kg`.`judul` AS `judul_kegiatan`, `a`.`nama_lengkap` AS `dicatat_oleh` FROM (((`pemasukan` `pm` join `kategori` `k` on(`pm`.`id_kategori` = `k`.`id_kategori`)) join `anggota` `a` on(`pm`.`id_anggota` = `a`.`id_anggota`)) left join `kegiatan` `kg` on(`pm`.`id_kegiatan` = `kg`.`id_kegiatan`))union all select 'pengeluaran' AS `tipe`,`pk`.`id_pengeluaran` AS `id_transaksi`,`pk`.`kode_pengeluaran` AS `kode`,`pk`.`tanggal` AS `tanggal`,`pk`.`jumlah` AS `jumlah`,`pk`.`status` AS `status`,`k`.`nama_kategori` AS `nama_kategori`,`kg`.`judul` AS `judul_kegiatan`,`a`.`nama_lengkap` AS `dicatat_oleh` from (((`pengeluaran` `pk` join `kategori` `k` on(`pk`.`id_kategori` = `k`.`id_kategori`)) join `anggota` `a` on(`pk`.`id_anggota` = `a`.`id_anggota`)) left join `kegiatan` `kg` on(`pk`.`id_kegiatan` = `kg`.`id_kegiatan`)) order by `tanggal` desc ;
-- Kesalahan membaca data untuk tabel u458429422_Nakama_HMJTI.v_laporan_keuangan: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'FROM `u458429422_Nakama_HMJTI`.`v_laporan_keuangan`' at line 1

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_login`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_login` (
`id_password` int(11)
,`id_anggota` int(11)
,`username` varchar(50)
,`password` varchar(255)
,`nama_lengkap` varchar(150)
,`email` varchar(100)
,`nama_jabatan` varchar(100)
,`nama_divisi` varchar(100)
,`role_derived` varchar(20)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_pengajuan_surat_lengkap`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_pengajuan_surat_lengkap` (
`id_pengajuan` int(11)
,`tanggal_unggah` date
,`tanggal_update` date
,`status` varchar(20)
,`file_surat` varchar(255)
,`file_draft` varchar(255)
,`deskripsi_kegiatan` text
,`pembuat` varchar(150)
,`nim` varchar(20)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_saldo_kas`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_saldo_kas` (
`total_pemasukan` decimal(37,2)
,`total_pengeluaran` decimal(37,2)
,`saldo` decimal(38,2)
);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`id_anggota`),
  ADD UNIQUE KEY `nim` (`nim`),
  ADD KEY `fk_anggota_kode_pos` (`kode_pos`);

--
-- Indeks untuk tabel `anggota_periode`
--
ALTER TABLE `anggota_periode`
  ADD PRIMARY KEY (`id_anggota_periode`),
  ADD UNIQUE KEY `uq_anggota_periode` (`id_anggota`,`id_periode`),
  ADD KEY `fk_ap_periode` (`id_periode`),
  ADD KEY `fk_ap_jabatan` (`id_jabatan`);

--
-- Indeks untuk tabel `bukti_kegiatan`
--
ALTER TABLE `bukti_kegiatan`
  ADD PRIMARY KEY (`id_bukti`),
  ADD KEY `fk_bukti_kegiatan` (`id_kegiatan`);

--
-- Indeks untuk tabel `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `fk_detail_peminjaman` (`id_peminjaman`),
  ADD KEY `fk_detail_ruangan` (`id_ruangan`);

--
-- Indeks untuk tabel `divisi`
--
ALTER TABLE `divisi`
  ADD PRIMARY KEY (`id_divisi`);

--
-- Indeks untuk tabel `draft_surat`
--
ALTER TABLE `draft_surat`
  ADD PRIMARY KEY (`id_draft`),
  ADD KEY `fk_draft_anggota` (`id_anggota`);

--
-- Indeks untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id_jabatan`),
  ADD KEY `fk_jabatan_divisi` (`id_divisi`),
  ADD KEY `fk_jabatan_role` (`id_role`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `kegiatan`
--
ALTER TABLE `kegiatan`
  ADD PRIMARY KEY (`id_kegiatan`),
  ADD KEY `fk_kegiatan_anggota` (`id_anggota`);

--
-- Indeks untuk tabel `kode_pos`
--
ALTER TABLE `kode_pos`
  ADD PRIMARY KEY (`kode_pos`);

--
-- Indeks untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `fk_log_anggota` (`id_anggota`);

--
-- Indeks untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id_notifikasi`),
  ADD KEY `fk_notif_kegiatan` (`id_kegiatan`),
  ADD KEY `fk_notif_anggota` (`id_anggota`);

--
-- Indeks untuk tabel `password`
--
ALTER TABLE `password`
  ADD PRIMARY KEY (`id_password`),
  ADD UNIQUE KEY `id_anggota` (`id_anggota`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `pemasukan`
--
ALTER TABLE `pemasukan`
  ADD PRIMARY KEY (`id_pemasukan`),
  ADD KEY `fk_pemasukan_anggota` (`id_anggota`),
  ADD KEY `fk_pemasukan_kategori` (`id_kategori`),
  ADD KEY `fk_pemasukan_kegiatan` (`id_kegiatan`);

--
-- Indeks untuk tabel `peminjam`
--
ALTER TABLE `peminjam`
  ADD PRIMARY KEY (`id_peminjam`),
  ADD UNIQUE KEY `kode_peminjam` (`kode_peminjam`),
  ADD KEY `fk_peminjam_anggota` (`id_anggota`);

--
-- Indeks untuk tabel `peminjaman_ruangan`
--
ALTER TABLE `peminjaman_ruangan`
  ADD PRIMARY KEY (`id_peminjaman`),
  ADD UNIQUE KEY `kode_peminjaman` (`kode_peminjaman`),
  ADD KEY `fk_peminjaman_peminjam` (`id_peminjam`);

--
-- Indeks untuk tabel `pengajuan_surat`
--
ALTER TABLE `pengajuan_surat`
  ADD PRIMARY KEY (`id_pengajuan`),
  ADD KEY `fk_pengajuan_surat` (`id_surat`);

--
-- Indeks untuk tabel `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD PRIMARY KEY (`id_pengeluaran`),
  ADD KEY `fk_pengeluaran_anggota` (`id_anggota`),
  ADD KEY `fk_pengeluaran_kategori` (`id_kategori`),
  ADD KEY `fk_pengeluaran_kegiatan` (`id_kegiatan`);

--
-- Indeks untuk tabel `periode`
--
ALTER TABLE `periode`
  ADD PRIMARY KEY (`id_periode`);

--
-- Indeks untuk tabel `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`),
  ADD UNIQUE KEY `uq_role_level` (`role_level`);

--
-- Indeks untuk tabel `ruangan`
--
ALTER TABLE `ruangan`
  ADD PRIMARY KEY (`id_ruangan`),
  ADD UNIQUE KEY `kode_ruangan` (`kode_ruangan`);

--
-- Indeks untuk tabel `sesi_login`
--
ALTER TABLE `sesi_login`
  ADD PRIMARY KEY (`id_sesi`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `fk_sesi_anggota` (`id_anggota`);

--
-- Indeks untuk tabel `status_history`
--
ALTER TABLE `status_history`
  ADD PRIMARY KEY (`id_history`),
  ADD KEY `fk_history_pengajuan` (`id_pengajuan`),
  ADD KEY `fk_history_anggota` (`id_anggota`);

--
-- Indeks untuk tabel `status_history_keuangan`
--
ALTER TABLE `status_history_keuangan`
  ADD PRIMARY KEY (`id_history`),
  ADD KEY `fk_shk_anggota` (`id_anggota`);

--
-- Indeks untuk tabel `status_history_ruangan`
--
ALTER TABLE `status_history_ruangan`
  ADD PRIMARY KEY (`id_history`),
  ADD KEY `fk_shr_peminjaman` (`id_peminjaman`),
  ADD KEY `fk_shr_anggota` (`id_anggota`);

--
-- Indeks untuk tabel `surat`
--
ALTER TABLE `surat`
  ADD PRIMARY KEY (`id_surat`),
  ADD UNIQUE KEY `nomor_surat` (`nomor_surat`),
  ADD KEY `fk_surat_draft` (`id_draft`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `anggota`
--
ALTER TABLE `anggota`
  MODIFY `id_anggota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `anggota_periode`
--
ALTER TABLE `anggota_periode`
  MODIFY `id_anggota_periode` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `bukti_kegiatan`
--
ALTER TABLE `bukti_kegiatan`
  MODIFY `id_bukti` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT untuk tabel `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `divisi`
--
ALTER TABLE `divisi`
  MODIFY `id_divisi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `draft_surat`
--
ALTER TABLE `draft_surat`
  MODIFY `id_draft` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `id_jabatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `kegiatan`
--
ALTER TABLE `kegiatan`
  MODIFY `id_kegiatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id_notifikasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=989;

--
-- AUTO_INCREMENT untuk tabel `password`
--
ALTER TABLE `password`
  MODIFY `id_password` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `pemasukan`
--
ALTER TABLE `pemasukan`
  MODIFY `id_pemasukan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT untuk tabel `peminjam`
--
ALTER TABLE `peminjam`
  MODIFY `id_peminjam` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `peminjaman_ruangan`
--
ALTER TABLE `peminjaman_ruangan`
  MODIFY `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `pengajuan_surat`
--
ALTER TABLE `pengajuan_surat`
  MODIFY `id_pengajuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `id_pengeluaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT untuk tabel `periode`
--
ALTER TABLE `periode`
  MODIFY `id_periode` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `ruangan`
--
ALTER TABLE `ruangan`
  MODIFY `id_ruangan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `sesi_login`
--
ALTER TABLE `sesi_login`
  MODIFY `id_sesi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT untuk tabel `status_history`
--
ALTER TABLE `status_history`
  MODIFY `id_history` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `status_history_keuangan`
--
ALTER TABLE `status_history_keuangan`
  MODIFY `id_history` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `status_history_ruangan`
--
ALTER TABLE `status_history_ruangan`
  MODIFY `id_history` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `surat`
--
ALTER TABLE `surat`
  MODIFY `id_surat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_anggota_lengkap`
--
DROP TABLE IF EXISTS `v_anggota_lengkap`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u458429422_Nakama_HMJTI`@`127.0.0.1` SQL SECURITY DEFINER VIEW `v_anggota_lengkap`  AS SELECT `a`.`id_anggota` AS `id_anggota`, `a`.`nim` AS `nim`, `a`.`nama_lengkap` AS `nama_lengkap`, `a`.`email` AS `email`, `a`.`no_telp` AS `no_telp`, `a`.`angkatan` AS `angkatan`, `a`.`jurusan` AS `jurusan`, `a`.`program_studi` AS `program_studi`, `a`.`status_keanggotaan` AS `status_keanggotaan`, `j`.`nama_jabatan` AS `nama_jabatan`, `d`.`nama_divisi` AS `nama_divisi`, `r`.`role_level` AS `role_level`, `k`.`kelurahan` AS `kelurahan`, `k`.`kecamatan` AS `kecamatan`, `k`.`kota_kabupaten` AS `kota_kabupaten`, `k`.`provinsi` AS `provinsi` FROM ((((((`anggota` `a` join `anggota_periode` `ap` on(`a`.`id_anggota` = `ap`.`id_anggota`)) join `periode` `p` on(`ap`.`id_periode` = `p`.`id_periode`)) join `jabatan` `j` on(`ap`.`id_jabatan` = `j`.`id_jabatan`)) join `role` `r` on(`j`.`id_role` = `r`.`id_role`)) left join `divisi` `d` on(`j`.`id_divisi` = `d`.`id_divisi`)) join `kode_pos` `k` on(`a`.`kode_pos` = `k`.`kode_pos`)) WHERE `p`.`tahun_selesai` = (select max(`periode`.`tahun_selesai`) from `periode`) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_anggota_per_periode`
--
DROP TABLE IF EXISTS `v_anggota_per_periode`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u458429422_Nakama_HMJTI`@`127.0.0.1` SQL SECURITY DEFINER VIEW `v_anggota_per_periode`  AS SELECT `p`.`tahun_mulai` AS `tahun_mulai`, `p`.`tahun_selesai` AS `tahun_selesai`, `a`.`nim` AS `nim`, `a`.`nama_lengkap` AS `nama_lengkap`, `j`.`nama_jabatan` AS `nama_jabatan`, `d`.`nama_divisi` AS `nama_divisi` FROM ((((`anggota_periode` `ap` join `anggota` `a` on(`ap`.`id_anggota` = `a`.`id_anggota`)) join `periode` `p` on(`ap`.`id_periode` = `p`.`id_periode`)) join `jabatan` `j` on(`ap`.`id_jabatan` = `j`.`id_jabatan`)) left join `divisi` `d` on(`j`.`id_divisi` = `d`.`id_divisi`)) ORDER BY `p`.`tahun_mulai` ASC, `j`.`id_jabatan` ASC ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_jadwal_peminjaman`
--
DROP TABLE IF EXISTS `v_jadwal_peminjaman`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u458429422_Nakama_HMJTI`@`127.0.0.1` SQL SECURITY DEFINER VIEW `v_jadwal_peminjaman`  AS SELECT `dp`.`id_detail` AS `id_detail`, `r`.`kode_ruangan` AS `kode_ruangan`, `r`.`nama_ruangan` AS `nama_ruangan`, `a`.`nama_lengkap` AS `nama_peminjam`, `a`.`nim` AS `nim`, `pr`.`tujuan_peminjaman` AS `tujuan_peminjaman`, `pr`.`status` AS `status`, `dp`.`waktu_mulai` AS `waktu_mulai`, `dp`.`waktu_selesai` AS `waktu_selesai` FROM ((((`detail_peminjaman` `dp` join `ruangan` `r` on(`dp`.`id_ruangan` = `r`.`id_ruangan`)) join `peminjaman_ruangan` `pr` on(`dp`.`id_peminjaman` = `pr`.`id_peminjaman`)) join `peminjam` `pj` on(`pr`.`id_peminjam` = `pj`.`id_peminjam`)) join `anggota` `a` on(`pj`.`id_anggota` = `a`.`id_anggota`)) ORDER BY `dp`.`waktu_mulai` ASC ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_login`
--
DROP TABLE IF EXISTS `v_login`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u458429422_Nakama_HMJTI`@`127.0.0.1` SQL SECURITY DEFINER VIEW `v_login`  AS SELECT `pw`.`id_password` AS `id_password`, `pw`.`id_anggota` AS `id_anggota`, `pw`.`username` AS `username`, `pw`.`password` AS `password`, `a`.`nama_lengkap` AS `nama_lengkap`, `a`.`email` AS `email`, `j`.`nama_jabatan` AS `nama_jabatan`, `d`.`nama_divisi` AS `nama_divisi`, `r`.`role_level` AS `role_derived` FROM ((((((`password` `pw` join `anggota` `a` on(`pw`.`id_anggota` = `a`.`id_anggota`)) join `anggota_periode` `ap` on(`a`.`id_anggota` = `ap`.`id_anggota`)) join `periode` `p` on(`ap`.`id_periode` = `p`.`id_periode`)) join `jabatan` `j` on(`ap`.`id_jabatan` = `j`.`id_jabatan`)) join `role` `r` on(`j`.`id_role` = `r`.`id_role`)) left join `divisi` `d` on(`j`.`id_divisi` = `d`.`id_divisi`)) WHERE `p`.`tahun_selesai` = (select max(`periode`.`tahun_selesai`) from `periode`) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_pengajuan_surat_lengkap`
--
DROP TABLE IF EXISTS `v_pengajuan_surat_lengkap`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u458429422_Nakama_HMJTI`@`127.0.0.1` SQL SECURITY DEFINER VIEW `v_pengajuan_surat_lengkap`  AS SELECT `ps`.`id_pengajuan` AS `id_pengajuan`, `ps`.`tanggal_unggah` AS `tanggal_unggah`, `ps`.`tanggal_update` AS `tanggal_update`, `ps`.`status` AS `status`, `s`.`file` AS `file_surat`, `ds`.`file` AS `file_draft`, `ds`.`deskripsi_kegiatan` AS `deskripsi_kegiatan`, `a`.`nama_lengkap` AS `pembuat`, `a`.`nim` AS `nim` FROM (((`pengajuan_surat` `ps` join `surat` `s` on(`ps`.`id_surat` = `s`.`id_surat`)) join `draft_surat` `ds` on(`s`.`id_draft` = `ds`.`id_draft`)) join `anggota` `a` on(`ds`.`id_anggota` = `a`.`id_anggota`)) ORDER BY `ps`.`tanggal_unggah` DESC ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_saldo_kas`
--
DROP TABLE IF EXISTS `v_saldo_kas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u458429422_Nakama_HMJTI`@`127.0.0.1` SQL SECURITY DEFINER VIEW `v_saldo_kas`  AS SELECT (select coalesce(sum(`pemasukan`.`jumlah`),0) from `pemasukan` where `pemasukan`.`status` in ('Berhasil','Disetujui')) AS `total_pemasukan`, (select coalesce(sum(`pengeluaran`.`jumlah`),0) from `pengeluaran` where `pengeluaran`.`status` in ('Berhasil','Disetujui')) AS `total_pengeluaran`, (select coalesce(sum(`pemasukan`.`jumlah`),0) from `pemasukan` where `pemasukan`.`status` in ('Berhasil','Disetujui')) - (select coalesce(sum(`pengeluaran`.`jumlah`),0) from `pengeluaran` where `pengeluaran`.`status` in ('Berhasil','Disetujui')) AS `saldo` ;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `anggota`
--
ALTER TABLE `anggota`
  ADD CONSTRAINT `fk_anggota_kode_pos` FOREIGN KEY (`kode_pos`) REFERENCES `kode_pos` (`kode_pos`);

--
-- Ketidakleluasaan untuk tabel `anggota_periode`
--
ALTER TABLE `anggota_periode`
  ADD CONSTRAINT `fk_ap_anggota` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`),
  ADD CONSTRAINT `fk_ap_jabatan` FOREIGN KEY (`id_jabatan`) REFERENCES `jabatan` (`id_jabatan`),
  ADD CONSTRAINT `fk_ap_periode` FOREIGN KEY (`id_periode`) REFERENCES `periode` (`id_periode`);

--
-- Ketidakleluasaan untuk tabel `bukti_kegiatan`
--
ALTER TABLE `bukti_kegiatan`
  ADD CONSTRAINT `fk_bukti_kegiatan` FOREIGN KEY (`id_kegiatan`) REFERENCES `kegiatan` (`id_kegiatan`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  ADD CONSTRAINT `fk_detail_peminjaman` FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman_ruangan` (`id_peminjaman`),
  ADD CONSTRAINT `fk_detail_ruangan` FOREIGN KEY (`id_ruangan`) REFERENCES `ruangan` (`id_ruangan`);

--
-- Ketidakleluasaan untuk tabel `draft_surat`
--
ALTER TABLE `draft_surat`
  ADD CONSTRAINT `fk_draft_anggota` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`);

--
-- Ketidakleluasaan untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  ADD CONSTRAINT `fk_jabatan_divisi` FOREIGN KEY (`id_divisi`) REFERENCES `divisi` (`id_divisi`),
  ADD CONSTRAINT `fk_jabatan_role` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`);

--
-- Ketidakleluasaan untuk tabel `kegiatan`
--
ALTER TABLE `kegiatan`
  ADD CONSTRAINT `fk_kegiatan_anggota` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`);

--
-- Ketidakleluasaan untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `fk_log_anggota` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`);

--
-- Ketidakleluasaan untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `fk_notif_anggota` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_notif_kegiatan` FOREIGN KEY (`id_kegiatan`) REFERENCES `kegiatan` (`id_kegiatan`);

--
-- Ketidakleluasaan untuk tabel `password`
--
ALTER TABLE `password`
  ADD CONSTRAINT `fk_password_anggota` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`);

--
-- Ketidakleluasaan untuk tabel `pemasukan`
--
ALTER TABLE `pemasukan`
  ADD CONSTRAINT `fk_pemasukan_anggota` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`),
  ADD CONSTRAINT `fk_pemasukan_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`),
  ADD CONSTRAINT `fk_pemasukan_kegiatan` FOREIGN KEY (`id_kegiatan`) REFERENCES `kegiatan` (`id_kegiatan`);

--
-- Ketidakleluasaan untuk tabel `peminjam`
--
ALTER TABLE `peminjam`
  ADD CONSTRAINT `fk_peminjam_anggota` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`);

--
-- Ketidakleluasaan untuk tabel `peminjaman_ruangan`
--
ALTER TABLE `peminjaman_ruangan`
  ADD CONSTRAINT `fk_peminjaman_peminjam` FOREIGN KEY (`id_peminjam`) REFERENCES `peminjam` (`id_peminjam`);

--
-- Ketidakleluasaan untuk tabel `pengajuan_surat`
--
ALTER TABLE `pengajuan_surat`
  ADD CONSTRAINT `fk_pengajuan_surat` FOREIGN KEY (`id_surat`) REFERENCES `surat` (`id_surat`);

--
-- Ketidakleluasaan untuk tabel `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD CONSTRAINT `fk_pengeluaran_anggota` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`),
  ADD CONSTRAINT `fk_pengeluaran_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`),
  ADD CONSTRAINT `fk_pengeluaran_kegiatan` FOREIGN KEY (`id_kegiatan`) REFERENCES `kegiatan` (`id_kegiatan`);

--
-- Ketidakleluasaan untuk tabel `sesi_login`
--
ALTER TABLE `sesi_login`
  ADD CONSTRAINT `fk_sesi_anggota` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`);

--
-- Ketidakleluasaan untuk tabel `status_history`
--
ALTER TABLE `status_history`
  ADD CONSTRAINT `fk_history_anggota` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`),
  ADD CONSTRAINT `fk_history_pengajuan` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan_surat` (`id_pengajuan`);

--
-- Ketidakleluasaan untuk tabel `status_history_keuangan`
--
ALTER TABLE `status_history_keuangan`
  ADD CONSTRAINT `fk_shk_anggota` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`);

--
-- Ketidakleluasaan untuk tabel `status_history_ruangan`
--
ALTER TABLE `status_history_ruangan`
  ADD CONSTRAINT `fk_shr_anggota` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`),
  ADD CONSTRAINT `fk_shr_peminjaman` FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman_ruangan` (`id_peminjaman`);

--
-- Ketidakleluasaan untuk tabel `surat`
--
ALTER TABLE `surat`
  ADD CONSTRAINT `fk_surat_draft` FOREIGN KEY (`id_draft`) REFERENCES `draft_surat` (`id_draft`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
