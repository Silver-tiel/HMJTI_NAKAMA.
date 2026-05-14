-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Waktu pembuatan: 14 Bulan Mei 2026 pada 06.05
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
(2, '68122', 'E41251215', 'sitoso', 'E41251215@student.polije.ac.id', '081234000002', '2025', 'Teknologi Informasi', 'D4 Teknik Informatika', 'Aktif'),
(3, '68123', 'E41251276', 'Devis Sapta Pratama', 'E41251276@student.polije.ac.id', '081234000003', '2025', 'Teknologi Informasi', 'D4 Teknik Informatika', 'Aktif'),
(4, '68124', 'E41251254', 'Gabriel Putra S.H', 'E41251254@student.polije.ac.id', '081234000004', '2025', 'Teknologi Informasi', 'D4 Teknik Informatika', 'Aktif'),
(5, '68125', 'E41251323', 'Aditya Bambang Kurniawan', 'E41251323@student.polije.ac.id', '081234000005', '2025', 'Teknologi Informasi', 'D4 Teknik Informatika', 'Aktif'),
(6, '68121', 'E41351310', 'Muhammad Nanda Krisna Murti', 'E41251310@student.polije.ac.id', '081234000006', '2025', 'Teknologi Informasi', 'D4 Teknik Informatika', 'Aktif'),
(8, '68124', 'E41251206', 'Asmaun', 'fajarmuharram1901@gmail.com', NULL, '2025', NULL, NULL, 'Aktif'),
(9, '68124', 'e41251240', 'ewf', 'irzalamru83@gmail.com', '', '2025', '', '', 'Aktif'),
(12, '68121', 'E41251513', 'Vici Gremy Aldiano Susatyo', 'e41251513@student.polije.ac.id', '', '2025', '', '', 'Aktif'),
(14, '68124', 'e41251220', 'udin', 'amruirzal@gmail.com', '', '2025', '', '', 'Aktif');

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
(2, 2, 2, 26),
(3, 3, 2, 2),
(4, 4, 2, 3),
(5, 5, 2, 17),
(6, 6, 2, 1),
(7, 8, 2, 1),
(8, 9, 2, 3),
(11, 12, 2, 8),
(13, 14, 2, 1);

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
(8, 9, 'uploads/kegiatan/img_6a028db8705b5.png', NULL, '2026-05-12 02:17:28'),
(9, 10, 'uploads/kegiatan/img_6a028df109e61.jpg', NULL, '2026-05-12 02:18:25'),
(10, 11, 'uploads/kegiatan/img_6a029256b59eb.png', NULL, '2026-05-12 02:37:10'),
(11, 12, 'uploads/kegiatan/img_6a0295abd36d7.jpg', NULL, '2026-05-12 02:51:23'),
(12, 13, 'uploads/kegiatan/img_6a0295d884561.png', NULL, '2026-05-12 02:52:08'),
(13, 14, 'uploads/kegiatan/img_6a0354ec14550.jpg', NULL, '2026-05-12 16:27:24'),
(14, 15, 'uploads/kegiatan/img_6a0377ea99bf4.jpeg', NULL, '2026-05-12 18:56:42'),
(15, 16, 'uploads/kegiatan/img_6a037bfaf2262.png', NULL, '2026-05-12 19:14:02'),
(16, 17, 'uploads/kegiatan/img_6a03a8212abf0.jpeg', NULL, '2026-05-12 22:22:25'),
(17, 18, 'uploads/kegiatan/img_6a03be6261aa8.jpg', NULL, '2026-05-12 23:57:22'),
(20, 21, 'uploads/kegiatan/img_6a03e8aa7b990.jpg', NULL, '2026-05-13 02:57:46'),
(21, 22, 'uploads/kegiatan/img_6a03e93689f58.jpg', NULL, '2026-05-13 03:00:06'),
(22, 22, 'uploads/kegiatan/img_6a03e9368a15a.jpg', NULL, '2026-05-13 03:00:06'),
(23, 22, 'uploads/kegiatan/img_6a03e9368a2cc.jpeg', NULL, '2026-05-13 03:00:06'),
(24, 22, 'uploads/kegiatan/img_6a03e9368a56b.jpeg', NULL, '2026-05-13 03:00:06'),
(25, 22, 'uploads/kegiatan/img_6a03e9368a714.jpeg', NULL, '2026-05-13 03:00:06');

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
(1, 1, 1, '2026-10-10 13:00:00', '2026-10-10 15:00:00'),
(2, 2, 2, '2026-10-11 09:00:00', '2026-10-11 11:00:00'),
(3, 3, 3, '2026-11-08 10:00:00', '2026-11-08 12:00:00'),
(7, 8, 1, '2026-05-14 08:08:00', '2026-05-23 08:08:00'),
(8, 10, 1, '2029-05-24 06:21:00', '2030-05-13 06:21:00'),
(10, 13, 3, '2026-05-29 10:06:00', '2026-05-30 10:06:00');

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
(9, 2, 'uploads/surat/surat_6a03ec06ac546_MINGGU_12-2_D_E41251215_FAJARMUHARRAM.pdf', 'ra[at', '2026-05-13 03:12:06');

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
(9, 3, 'fff', 'fff', 'fff', 'fff', '2026-05-12 15:00:00', '2026-05-13 00:00:00', '2026-05-12 02:17:28', '2026-05-12 02:17:28'),
(10, 1, '1234rt5', '76879uhi', 'ed56r57i687', 'dr6ift7y8u9i', '2026-05-12 09:20:00', '2026-05-12 12:18:00', '2026-05-12 02:18:25', '2026-05-12 02:18:25'),
(11, 2, 'perayaan pertama', 'asda', 'gw', 'lab rsi', '2026-05-12 09:38:00', '2026-05-12 09:40:00', '2026-05-12 02:37:10', '2026-05-12 02:37:10'),
(12, 1, '23w456tyui', '7657yu', '67yu', 'e5r6f7yuik', '2026-05-12 09:53:00', '2026-05-12 12:51:00', '2026-05-12 02:51:23', '2026-05-12 02:51:23'),
(13, 2, 'Pengangguran Banyak Acara', 'lalalalalala', 'Apa', 'lab rsi', '2026-05-12 09:53:00', '2026-05-12 09:55:00', '2026-05-12 02:52:08', '2026-05-12 02:52:08'),
(14, 3, 'MAMAH MAU 5', '', 'Devis', 'Dirumah masing - masing', '2026-05-12 23:27:00', '2026-05-13 23:27:00', '2026-05-12 16:27:24', '2026-05-12 16:27:24'),
(15, 2, 'as', 'ss', 'Apa', 'lab rsi', '2026-05-13 01:58:00', '2026-05-13 01:59:00', '2026-05-12 18:56:42', '2026-05-12 18:56:42'),
(16, 2, 'awawas', 'xdxd', 'gw', 'lab rsi', '2026-05-13 02:15:00', '2026-05-13 02:16:00', '2026-05-12 19:14:02', '2026-05-12 19:14:02'),
(17, 2, 'awdadwasda', 'sda', 'awdw', 'lab rsi', '2026-05-13 05:23:00', '2026-05-13 05:25:00', '2026-05-12 22:22:25', '2026-05-12 22:22:25'),
(18, 2, 'awdwawdawda', 'wadqwdadas', 'awdafacawf', 'lab rsi', '2026-05-14 07:00:00', '2026-05-14 07:15:00', '2026-05-12 23:57:22', '2026-05-12 23:57:22'),
(21, 1, 'presentasi', 'rapat', 'Budi Santoso', 'disini', '2026-04-28 09:56:00', '2026-05-04 09:56:00', '2026-05-13 02:57:46', '2026-05-13 02:57:46'),
(22, 6, 'rapat 2', 'tr', 'satpam', 'disini', '2026-05-14 09:58:00', '2026-05-15 09:58:00', '2026-05-13 02:59:18', '2026-05-13 02:59:18');

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
(91, 9, 1, 'Kegiatan Baru: fff', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 15:00 di fff.', 1, '2026-05-12 02:32:01', '2026-05-12 02:17:28', 'manual', 1),
(92, 9, 6, 'Kegiatan Baru: fff', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 15:00 di fff.', 1, '2026-05-13 00:09:16', '2026-05-12 02:17:28', 'manual', 1),
(93, 9, 2, 'Kegiatan Baru: fff', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 15:00 di fff.', 1, '2026-05-12 18:55:35', '2026-05-12 02:17:28', 'manual', 1),
(94, 9, 3, 'Kegiatan Baru: fff', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 15:00 di fff.', 1, '2026-05-12 16:27:50', '2026-05-12 02:17:28', 'manual', 1),
(95, 9, 4, 'Kegiatan Baru: fff', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 15:00 di fff.', 0, NULL, '2026-05-12 02:17:28', 'manual', 1),
(96, 9, 8, 'Kegiatan Baru: fff', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 15:00 di fff.', 1, '2026-05-12 02:38:27', '2026-05-12 02:17:28', 'manual', 1),
(97, 9, 9, 'Kegiatan Baru: fff', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 15:00 di fff.', 1, '2026-05-12 23:51:40', '2026-05-12 02:17:28', 'manual', 1),
(98, 9, 5, 'Kegiatan Baru: fff', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 15:00 di fff.', 0, NULL, '2026-05-12 02:17:28', 'manual', 1),
(99, 9, 1, 'Pengingat 24 jam lagi: fff', 'Kegiatan \"fff\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-12 02:32:01', '2026-05-12 02:17:29', 'h-24', 1),
(100, 9, 6, 'Pengingat 24 jam lagi: fff', 'Kegiatan \"fff\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:17:29', 'h-24', 1),
(101, 9, 2, 'Pengingat 24 jam lagi: fff', 'Kegiatan \"fff\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:17:29', 'h-24', 1),
(102, 9, 3, 'Pengingat 24 jam lagi: fff', 'Kegiatan \"fff\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:17:29', 'h-24', 1),
(103, 9, 4, 'Pengingat 24 jam lagi: fff', 'Kegiatan \"fff\" akan dimulai dalam 24 jam lagi!', 0, NULL, '2026-05-12 02:17:29', 'h-24', 1),
(104, 9, 8, 'Pengingat 24 jam lagi: fff', 'Kegiatan \"fff\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-12 02:38:27', '2026-05-12 02:17:29', 'h-24', 1),
(105, 9, 9, 'Pengingat 24 jam lagi: fff', 'Kegiatan \"fff\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:17:29', 'h-24', 1),
(106, 9, 5, 'Pengingat 24 jam lagi: fff', 'Kegiatan \"fff\" akan dimulai dalam 24 jam lagi!', 0, NULL, '2026-05-12 02:17:29', 'h-24', 1),
(107, 9, 1, 'Pengingat 12 jam lagi: fff', 'Kegiatan \"fff\" akan dimulai dalam 12 jam lagi!', 1, '2026-05-12 02:32:01', '2026-05-12 02:17:29', 'h-12', 1),
(108, 9, 6, 'Pengingat 12 jam lagi: fff', 'Kegiatan \"fff\" akan dimulai dalam 12 jam lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:17:29', 'h-12', 1),
(109, 9, 2, 'Pengingat 12 jam lagi: fff', 'Kegiatan \"fff\" akan dimulai dalam 12 jam lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:17:29', 'h-12', 1),
(110, 9, 3, 'Pengingat 12 jam lagi: fff', 'Kegiatan \"fff\" akan dimulai dalam 12 jam lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:17:29', 'h-12', 1),
(111, 9, 4, 'Pengingat 12 jam lagi: fff', 'Kegiatan \"fff\" akan dimulai dalam 12 jam lagi!', 0, NULL, '2026-05-12 02:17:29', 'h-12', 1),
(112, 9, 8, 'Pengingat 12 jam lagi: fff', 'Kegiatan \"fff\" akan dimulai dalam 12 jam lagi!', 1, '2026-05-12 02:38:27', '2026-05-12 02:17:29', 'h-12', 1),
(113, 9, 9, 'Pengingat 12 jam lagi: fff', 'Kegiatan \"fff\" akan dimulai dalam 12 jam lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:17:29', 'h-12', 1),
(114, 9, 5, 'Pengingat 12 jam lagi: fff', 'Kegiatan \"fff\" akan dimulai dalam 12 jam lagi!', 0, NULL, '2026-05-12 02:17:29', 'h-12', 1),
(115, 9, 1, 'Pengingat 6 jam lagi: fff', 'Kegiatan \"fff\" akan dimulai dalam 6 jam lagi!', 1, '2026-05-12 02:32:01', '2026-05-12 02:17:29', 'h-6', 1),
(116, 9, 6, 'Pengingat 6 jam lagi: fff', 'Kegiatan \"fff\" akan dimulai dalam 6 jam lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:17:29', 'h-6', 1),
(117, 9, 2, 'Pengingat 6 jam lagi: fff', 'Kegiatan \"fff\" akan dimulai dalam 6 jam lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:17:29', 'h-6', 1),
(118, 9, 3, 'Pengingat 6 jam lagi: fff', 'Kegiatan \"fff\" akan dimulai dalam 6 jam lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:17:29', 'h-6', 1),
(119, 9, 4, 'Pengingat 6 jam lagi: fff', 'Kegiatan \"fff\" akan dimulai dalam 6 jam lagi!', 0, NULL, '2026-05-12 02:17:29', 'h-6', 1),
(120, 9, 8, 'Pengingat 6 jam lagi: fff', 'Kegiatan \"fff\" akan dimulai dalam 6 jam lagi!', 1, '2026-05-12 02:38:27', '2026-05-12 02:17:29', 'h-6', 1),
(121, 9, 9, 'Pengingat 6 jam lagi: fff', 'Kegiatan \"fff\" akan dimulai dalam 6 jam lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:17:29', 'h-6', 1),
(122, 9, 5, 'Pengingat 6 jam lagi: fff', 'Kegiatan \"fff\" akan dimulai dalam 6 jam lagi!', 0, NULL, '2026-05-12 02:17:29', 'h-6', 1),
(123, 10, 1, 'Kegiatan Baru: 1234rt5', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:20 di dr6ift7y8u9i.', 1, '2026-05-12 02:32:01', '2026-05-12 02:18:25', 'manual', 1),
(124, 10, 6, 'Kegiatan Baru: 1234rt5', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:20 di dr6ift7y8u9i.', 1, '2026-05-13 00:09:16', '2026-05-12 02:18:25', 'manual', 1),
(125, 10, 2, 'Kegiatan Baru: 1234rt5', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:20 di dr6ift7y8u9i.', 1, '2026-05-12 18:55:35', '2026-05-12 02:18:25', 'manual', 1),
(126, 10, 3, 'Kegiatan Baru: 1234rt5', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:20 di dr6ift7y8u9i.', 1, '2026-05-12 16:27:50', '2026-05-12 02:18:25', 'manual', 1),
(127, 10, 4, 'Kegiatan Baru: 1234rt5', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:20 di dr6ift7y8u9i.', 0, NULL, '2026-05-12 02:18:25', 'manual', 1),
(128, 10, 8, 'Kegiatan Baru: 1234rt5', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:20 di dr6ift7y8u9i.', 1, '2026-05-12 02:38:27', '2026-05-12 02:18:25', 'manual', 1),
(129, 10, 9, 'Kegiatan Baru: 1234rt5', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:20 di dr6ift7y8u9i.', 1, '2026-05-12 23:51:40', '2026-05-12 02:18:25', 'manual', 1),
(130, 10, 5, 'Kegiatan Baru: 1234rt5', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:20 di dr6ift7y8u9i.', 0, NULL, '2026-05-12 02:18:25', 'manual', 1),
(131, 10, 1, 'Pengingat 24 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-12 02:32:01', '2026-05-12 02:18:25', 'h-24', 1),
(132, 10, 6, 'Pengingat 24 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:18:25', 'h-24', 1),
(133, 10, 2, 'Pengingat 24 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:18:25', 'h-24', 1),
(134, 10, 3, 'Pengingat 24 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:18:25', 'h-24', 1),
(135, 10, 4, 'Pengingat 24 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 24 jam lagi!', 0, NULL, '2026-05-12 02:18:25', 'h-24', 1),
(136, 10, 8, 'Pengingat 24 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-12 02:38:27', '2026-05-12 02:18:25', 'h-24', 1),
(137, 10, 9, 'Pengingat 24 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:18:25', 'h-24', 1),
(138, 10, 5, 'Pengingat 24 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 24 jam lagi!', 0, NULL, '2026-05-12 02:18:25', 'h-24', 1),
(139, 10, 1, 'Pengingat 12 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 12 jam lagi!', 1, '2026-05-12 02:32:01', '2026-05-12 02:18:25', 'h-12', 1),
(140, 10, 6, 'Pengingat 12 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 12 jam lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:18:25', 'h-12', 1),
(141, 10, 2, 'Pengingat 12 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 12 jam lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:18:25', 'h-12', 1),
(142, 10, 3, 'Pengingat 12 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 12 jam lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:18:25', 'h-12', 1),
(143, 10, 4, 'Pengingat 12 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 12 jam lagi!', 0, NULL, '2026-05-12 02:18:25', 'h-12', 1),
(144, 10, 8, 'Pengingat 12 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 12 jam lagi!', 1, '2026-05-12 02:38:27', '2026-05-12 02:18:25', 'h-12', 1),
(145, 10, 9, 'Pengingat 12 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 12 jam lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:18:25', 'h-12', 1),
(146, 10, 5, 'Pengingat 12 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 12 jam lagi!', 0, NULL, '2026-05-12 02:18:25', 'h-12', 1),
(147, 10, 1, 'Pengingat 6 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 6 jam lagi!', 1, '2026-05-12 02:32:01', '2026-05-12 02:18:25', 'h-6', 1),
(148, 10, 6, 'Pengingat 6 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 6 jam lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:18:25', 'h-6', 1),
(149, 10, 2, 'Pengingat 6 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 6 jam lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:18:25', 'h-6', 1),
(150, 10, 3, 'Pengingat 6 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 6 jam lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:18:25', 'h-6', 1),
(151, 10, 4, 'Pengingat 6 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 6 jam lagi!', 0, NULL, '2026-05-12 02:18:25', 'h-6', 1),
(152, 10, 8, 'Pengingat 6 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 6 jam lagi!', 1, '2026-05-12 02:38:27', '2026-05-12 02:18:25', 'h-6', 1),
(153, 10, 9, 'Pengingat 6 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 6 jam lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:18:25', 'h-6', 1),
(154, 10, 5, 'Pengingat 6 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 6 jam lagi!', 0, NULL, '2026-05-12 02:18:25', 'h-6', 1),
(155, 10, 1, 'Pengingat 1 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 1 jam lagi!', 1, '2026-05-12 02:32:01', '2026-05-12 02:18:25', 'h-1', 1),
(156, 10, 6, 'Pengingat 1 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 1 jam lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:18:25', 'h-1', 1),
(157, 10, 2, 'Pengingat 1 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 1 jam lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:18:25', 'h-1', 1),
(158, 10, 3, 'Pengingat 1 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 1 jam lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:18:25', 'h-1', 1),
(159, 10, 4, 'Pengingat 1 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 1 jam lagi!', 0, NULL, '2026-05-12 02:18:25', 'h-1', 1),
(160, 10, 8, 'Pengingat 1 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 1 jam lagi!', 1, '2026-05-12 02:38:27', '2026-05-12 02:18:25', 'h-1', 1),
(161, 10, 9, 'Pengingat 1 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 1 jam lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:18:25', 'h-1', 1),
(162, 10, 5, 'Pengingat 1 jam lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 1 jam lagi!', 0, NULL, '2026-05-12 02:18:25', 'h-1', 1),
(163, 10, 1, 'Pengingat 30 menit lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 30 menit lagi!', 1, '2026-05-12 02:32:01', '2026-05-12 02:18:25', 'h-30m', 1),
(164, 10, 6, 'Pengingat 30 menit lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 30 menit lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:18:25', 'h-30m', 1),
(165, 10, 2, 'Pengingat 30 menit lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 30 menit lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:18:25', 'h-30m', 1),
(166, 10, 3, 'Pengingat 30 menit lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 30 menit lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:18:25', 'h-30m', 1),
(167, 10, 4, 'Pengingat 30 menit lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 30 menit lagi!', 0, NULL, '2026-05-12 02:18:25', 'h-30m', 1),
(168, 10, 8, 'Pengingat 30 menit lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 30 menit lagi!', 1, '2026-05-12 02:38:27', '2026-05-12 02:18:25', 'h-30m', 1),
(169, 10, 9, 'Pengingat 30 menit lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 30 menit lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:18:25', 'h-30m', 1),
(170, 10, 5, 'Pengingat 30 menit lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 30 menit lagi!', 0, NULL, '2026-05-12 02:18:25', 'h-30m', 1),
(171, 10, 1, 'Pengingat 10 menit lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 10 menit lagi!', 1, '2026-05-12 02:32:01', '2026-05-12 02:18:25', 'h-10m', 1),
(172, 10, 6, 'Pengingat 10 menit lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 10 menit lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:18:25', 'h-10m', 1),
(173, 10, 2, 'Pengingat 10 menit lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 10 menit lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:18:25', 'h-10m', 1),
(174, 10, 3, 'Pengingat 10 menit lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 10 menit lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:18:25', 'h-10m', 1),
(175, 10, 4, 'Pengingat 10 menit lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 10 menit lagi!', 0, NULL, '2026-05-12 02:18:25', 'h-10m', 1),
(176, 10, 8, 'Pengingat 10 menit lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 10 menit lagi!', 1, '2026-05-12 02:38:27', '2026-05-12 02:18:25', 'h-10m', 1),
(177, 10, 9, 'Pengingat 10 menit lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 10 menit lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:18:25', 'h-10m', 1),
(178, 10, 5, 'Pengingat 10 menit lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 10 menit lagi!', 0, NULL, '2026-05-12 02:18:25', 'h-10m', 1),
(179, 10, 1, 'Pengingat 5 menit lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 5 menit lagi!', 1, '2026-05-12 02:32:01', '2026-05-12 02:18:25', 'h-5m', 1),
(180, 10, 6, 'Pengingat 5 menit lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 5 menit lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:18:25', 'h-5m', 1),
(181, 10, 2, 'Pengingat 5 menit lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 5 menit lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:18:25', 'h-5m', 1),
(182, 10, 3, 'Pengingat 5 menit lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 5 menit lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:18:25', 'h-5m', 1),
(183, 10, 4, 'Pengingat 5 menit lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 5 menit lagi!', 0, NULL, '2026-05-12 02:18:25', 'h-5m', 1),
(184, 10, 8, 'Pengingat 5 menit lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 5 menit lagi!', 1, '2026-05-12 02:38:27', '2026-05-12 02:18:25', 'h-5m', 1),
(185, 10, 9, 'Pengingat 5 menit lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 5 menit lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:18:25', 'h-5m', 1),
(186, 10, 5, 'Pengingat 5 menit lagi: 1234rt5', 'Kegiatan \"1234rt5\" akan dimulai dalam 5 menit lagi!', 0, NULL, '2026-05-12 02:18:25', 'h-5m', 1),
(187, 10, 1, 'Kegiatan Dimulai: 1234rt5', 'Kegiatan \"1234rt5\" sedang berlangsung sekarang!', 1, '2026-05-12 02:40:00', '2026-05-12 02:35:39', 'mulai', 1),
(188, 10, 6, 'Kegiatan Dimulai: 1234rt5', 'Kegiatan \"1234rt5\" sedang berlangsung sekarang!', 1, '2026-05-13 00:09:16', '2026-05-12 02:35:39', 'mulai', 1),
(189, 10, 2, 'Kegiatan Dimulai: 1234rt5', 'Kegiatan \"1234rt5\" sedang berlangsung sekarang!', 1, '2026-05-12 18:55:35', '2026-05-12 02:35:39', 'mulai', 1),
(190, 10, 3, 'Kegiatan Dimulai: 1234rt5', 'Kegiatan \"1234rt5\" sedang berlangsung sekarang!', 1, '2026-05-12 16:27:50', '2026-05-12 02:35:39', 'mulai', 1),
(191, 10, 4, 'Kegiatan Dimulai: 1234rt5', 'Kegiatan \"1234rt5\" sedang berlangsung sekarang!', 0, NULL, '2026-05-12 02:35:39', 'mulai', 1),
(192, 10, 8, 'Kegiatan Dimulai: 1234rt5', 'Kegiatan \"1234rt5\" sedang berlangsung sekarang!', 1, '2026-05-12 02:38:27', '2026-05-12 02:35:39', 'mulai', 1),
(193, 10, 9, 'Kegiatan Dimulai: 1234rt5', 'Kegiatan \"1234rt5\" sedang berlangsung sekarang!', 1, '2026-05-12 23:51:40', '2026-05-12 02:35:39', 'mulai', 1),
(194, 10, 5, 'Kegiatan Dimulai: 1234rt5', 'Kegiatan \"1234rt5\" sedang berlangsung sekarang!', 0, NULL, '2026-05-12 02:35:39', 'mulai', 1),
(195, 11, 1, 'Kegiatan Baru: perayaan pertama', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:38 di lab rsi.', 1, '2026-05-12 02:40:00', '2026-05-12 02:37:10', 'manual', 1),
(196, 11, 6, 'Kegiatan Baru: perayaan pertama', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:38 di lab rsi.', 1, '2026-05-13 00:09:16', '2026-05-12 02:37:10', 'manual', 1),
(197, 11, 2, 'Kegiatan Baru: perayaan pertama', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:38 di lab rsi.', 1, '2026-05-12 18:55:35', '2026-05-12 02:37:10', 'manual', 1),
(198, 11, 3, 'Kegiatan Baru: perayaan pertama', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:38 di lab rsi.', 1, '2026-05-12 16:27:50', '2026-05-12 02:37:10', 'manual', 1),
(199, 11, 4, 'Kegiatan Baru: perayaan pertama', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:38 di lab rsi.', 0, NULL, '2026-05-12 02:37:10', 'manual', 1),
(200, 11, 8, 'Kegiatan Baru: perayaan pertama', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:38 di lab rsi.', 1, '2026-05-12 02:38:27', '2026-05-12 02:37:10', 'manual', 1),
(201, 11, 9, 'Kegiatan Baru: perayaan pertama', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:38 di lab rsi.', 1, '2026-05-12 23:51:40', '2026-05-12 02:37:10', 'manual', 1),
(202, 11, 5, 'Kegiatan Baru: perayaan pertama', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:38 di lab rsi.', 0, NULL, '2026-05-12 02:37:10', 'manual', 1),
(203, 11, 1, 'Pengingat 24 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-12 02:40:00', '2026-05-12 02:37:46', 'h-24', 1),
(204, 11, 6, 'Pengingat 24 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:37:46', 'h-24', 1),
(205, 11, 2, 'Pengingat 24 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:37:46', 'h-24', 1),
(206, 11, 3, 'Pengingat 24 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:37:46', 'h-24', 1),
(207, 11, 4, 'Pengingat 24 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 24 jam lagi!', 0, NULL, '2026-05-12 02:37:46', 'h-24', 1),
(208, 11, 8, 'Pengingat 24 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-12 02:38:27', '2026-05-12 02:37:46', 'h-24', 1),
(209, 11, 9, 'Pengingat 24 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:37:46', 'h-24', 1),
(210, 11, 5, 'Pengingat 24 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 24 jam lagi!', 0, NULL, '2026-05-12 02:37:46', 'h-24', 1),
(211, 11, 1, 'Pengingat 12 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 12 jam lagi!', 1, '2026-05-12 02:40:00', '2026-05-12 02:37:46', 'h-12', 1),
(212, 11, 6, 'Pengingat 12 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 12 jam lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:37:46', 'h-12', 1),
(213, 11, 2, 'Pengingat 12 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 12 jam lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:37:46', 'h-12', 1),
(214, 11, 3, 'Pengingat 12 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 12 jam lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:37:46', 'h-12', 1),
(215, 11, 4, 'Pengingat 12 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 12 jam lagi!', 0, NULL, '2026-05-12 02:37:46', 'h-12', 1),
(216, 11, 8, 'Pengingat 12 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 12 jam lagi!', 1, '2026-05-12 02:38:27', '2026-05-12 02:37:46', 'h-12', 1),
(217, 11, 9, 'Pengingat 12 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 12 jam lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:37:46', 'h-12', 1),
(218, 11, 5, 'Pengingat 12 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 12 jam lagi!', 0, NULL, '2026-05-12 02:37:46', 'h-12', 1),
(219, 11, 1, 'Pengingat 6 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 6 jam lagi!', 1, '2026-05-12 02:40:00', '2026-05-12 02:37:46', 'h-6', 1),
(220, 11, 6, 'Pengingat 6 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 6 jam lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:37:46', 'h-6', 1),
(221, 11, 2, 'Pengingat 6 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 6 jam lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:37:46', 'h-6', 1),
(222, 11, 3, 'Pengingat 6 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 6 jam lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:37:46', 'h-6', 1),
(223, 11, 4, 'Pengingat 6 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 6 jam lagi!', 0, NULL, '2026-05-12 02:37:46', 'h-6', 1),
(224, 11, 8, 'Pengingat 6 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 6 jam lagi!', 1, '2026-05-12 02:38:27', '2026-05-12 02:37:46', 'h-6', 1),
(225, 11, 9, 'Pengingat 6 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 6 jam lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:37:46', 'h-6', 1),
(226, 11, 5, 'Pengingat 6 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 6 jam lagi!', 0, NULL, '2026-05-12 02:37:46', 'h-6', 1),
(227, 11, 1, 'Pengingat 1 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 1 jam lagi!', 1, '2026-05-12 02:40:00', '2026-05-12 02:37:46', 'h-1', 1),
(228, 11, 6, 'Pengingat 1 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 1 jam lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:37:46', 'h-1', 1),
(229, 11, 2, 'Pengingat 1 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 1 jam lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:37:46', 'h-1', 1),
(230, 11, 3, 'Pengingat 1 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 1 jam lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:37:46', 'h-1', 1),
(231, 11, 4, 'Pengingat 1 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 1 jam lagi!', 0, NULL, '2026-05-12 02:37:46', 'h-1', 1),
(232, 11, 8, 'Pengingat 1 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 1 jam lagi!', 1, '2026-05-12 02:38:27', '2026-05-12 02:37:46', 'h-1', 1),
(233, 11, 9, 'Pengingat 1 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 1 jam lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:37:46', 'h-1', 1),
(234, 11, 5, 'Pengingat 1 jam lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 1 jam lagi!', 0, NULL, '2026-05-12 02:37:46', 'h-1', 1),
(235, 11, 1, 'Pengingat 30 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 30 menit lagi!', 1, '2026-05-12 02:40:00', '2026-05-12 02:37:46', 'h-30m', 1),
(236, 11, 6, 'Pengingat 30 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 30 menit lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:37:46', 'h-30m', 1),
(237, 11, 2, 'Pengingat 30 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 30 menit lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:37:46', 'h-30m', 1),
(238, 11, 3, 'Pengingat 30 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 30 menit lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:37:46', 'h-30m', 1),
(239, 11, 4, 'Pengingat 30 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 30 menit lagi!', 0, NULL, '2026-05-12 02:37:46', 'h-30m', 1),
(240, 11, 8, 'Pengingat 30 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 30 menit lagi!', 1, '2026-05-12 02:38:27', '2026-05-12 02:37:46', 'h-30m', 1),
(241, 11, 9, 'Pengingat 30 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 30 menit lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:37:46', 'h-30m', 1),
(242, 11, 5, 'Pengingat 30 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 30 menit lagi!', 0, NULL, '2026-05-12 02:37:46', 'h-30m', 1),
(243, 11, 1, 'Pengingat 10 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 10 menit lagi!', 1, '2026-05-12 02:40:00', '2026-05-12 02:37:46', 'h-10m', 1),
(244, 11, 6, 'Pengingat 10 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 10 menit lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:37:46', 'h-10m', 1),
(245, 11, 2, 'Pengingat 10 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 10 menit lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:37:46', 'h-10m', 1),
(246, 11, 3, 'Pengingat 10 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 10 menit lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:37:46', 'h-10m', 1),
(247, 11, 4, 'Pengingat 10 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 10 menit lagi!', 0, NULL, '2026-05-12 02:37:46', 'h-10m', 1),
(248, 11, 8, 'Pengingat 10 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 10 menit lagi!', 1, '2026-05-12 02:38:27', '2026-05-12 02:37:46', 'h-10m', 1),
(249, 11, 9, 'Pengingat 10 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 10 menit lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:37:46', 'h-10m', 1),
(250, 11, 5, 'Pengingat 10 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 10 menit lagi!', 0, NULL, '2026-05-12 02:37:46', 'h-10m', 1),
(251, 11, 1, 'Pengingat 5 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 5 menit lagi!', 1, '2026-05-12 02:40:00', '2026-05-12 02:37:46', 'h-5m', 1),
(252, 11, 6, 'Pengingat 5 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 5 menit lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:37:46', 'h-5m', 1),
(253, 11, 2, 'Pengingat 5 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 5 menit lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:37:46', 'h-5m', 1),
(254, 11, 3, 'Pengingat 5 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 5 menit lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:37:46', 'h-5m', 1),
(255, 11, 4, 'Pengingat 5 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 5 menit lagi!', 0, NULL, '2026-05-12 02:37:46', 'h-5m', 1),
(256, 11, 8, 'Pengingat 5 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 5 menit lagi!', 1, '2026-05-12 02:38:27', '2026-05-12 02:37:46', 'h-5m', 1),
(257, 11, 9, 'Pengingat 5 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 5 menit lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:37:46', 'h-5m', 1),
(258, 11, 5, 'Pengingat 5 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 5 menit lagi!', 0, NULL, '2026-05-12 02:37:46', 'h-5m', 1),
(259, 11, 1, 'Pengingat 1 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 1 menit lagi!', 1, '2026-05-12 02:40:00', '2026-05-12 02:37:46', 'h-1m', 1),
(260, 11, 6, 'Pengingat 1 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 1 menit lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:37:46', 'h-1m', 1),
(261, 11, 2, 'Pengingat 1 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 1 menit lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:37:46', 'h-1m', 1),
(262, 11, 3, 'Pengingat 1 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 1 menit lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:37:46', 'h-1m', 1),
(263, 11, 4, 'Pengingat 1 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 1 menit lagi!', 0, NULL, '2026-05-12 02:37:46', 'h-1m', 1),
(264, 11, 8, 'Pengingat 1 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 1 menit lagi!', 1, '2026-05-12 02:38:27', '2026-05-12 02:37:46', 'h-1m', 1),
(265, 11, 9, 'Pengingat 1 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 1 menit lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:37:46', 'h-1m', 1),
(266, 11, 5, 'Pengingat 1 menit lagi: perayaan pertama', 'Kegiatan \"perayaan pertama\" akan dimulai dalam 1 menit lagi!', 0, NULL, '2026-05-12 02:37:46', 'h-1m', 1),
(267, 11, 1, 'Kegiatan Dimulai: perayaan pertama', 'Kegiatan \"perayaan pertama\" sedang berlangsung sekarang!', 1, '2026-05-12 02:40:00', '2026-05-12 02:39:34', 'mulai', 1),
(268, 11, 6, 'Kegiatan Dimulai: perayaan pertama', 'Kegiatan \"perayaan pertama\" sedang berlangsung sekarang!', 1, '2026-05-13 00:09:16', '2026-05-12 02:39:34', 'mulai', 1),
(269, 11, 2, 'Kegiatan Dimulai: perayaan pertama', 'Kegiatan \"perayaan pertama\" sedang berlangsung sekarang!', 1, '2026-05-12 18:55:35', '2026-05-12 02:39:34', 'mulai', 1),
(270, 11, 3, 'Kegiatan Dimulai: perayaan pertama', 'Kegiatan \"perayaan pertama\" sedang berlangsung sekarang!', 1, '2026-05-12 16:27:50', '2026-05-12 02:39:34', 'mulai', 1),
(271, 11, 4, 'Kegiatan Dimulai: perayaan pertama', 'Kegiatan \"perayaan pertama\" sedang berlangsung sekarang!', 0, NULL, '2026-05-12 02:39:34', 'mulai', 1),
(272, 11, 8, 'Kegiatan Dimulai: perayaan pertama', 'Kegiatan \"perayaan pertama\" sedang berlangsung sekarang!', 1, '2026-05-12 16:46:49', '2026-05-12 02:39:34', 'mulai', 1),
(273, 11, 9, 'Kegiatan Dimulai: perayaan pertama', 'Kegiatan \"perayaan pertama\" sedang berlangsung sekarang!', 1, '2026-05-12 23:51:40', '2026-05-12 02:39:34', 'mulai', 1),
(274, 11, 5, 'Kegiatan Dimulai: perayaan pertama', 'Kegiatan \"perayaan pertama\" sedang berlangsung sekarang!', 0, NULL, '2026-05-12 02:39:34', 'mulai', 1),
(275, 11, 1, 'Kegiatan Selesai: perayaan pertama', 'Kegiatan \"perayaan pertama\" telah selesai dilaksanakan.', 1, '2026-05-12 02:52:02', '2026-05-12 02:45:45', 'selesai', 1),
(276, 11, 6, 'Kegiatan Selesai: perayaan pertama', 'Kegiatan \"perayaan pertama\" telah selesai dilaksanakan.', 1, '2026-05-13 00:09:16', '2026-05-12 02:45:45', 'selesai', 1),
(277, 11, 2, 'Kegiatan Selesai: perayaan pertama', 'Kegiatan \"perayaan pertama\" telah selesai dilaksanakan.', 1, '2026-05-12 18:55:35', '2026-05-12 02:45:45', 'selesai', 1),
(278, 11, 3, 'Kegiatan Selesai: perayaan pertama', 'Kegiatan \"perayaan pertama\" telah selesai dilaksanakan.', 1, '2026-05-12 16:27:50', '2026-05-12 02:45:45', 'selesai', 1),
(279, 11, 4, 'Kegiatan Selesai: perayaan pertama', 'Kegiatan \"perayaan pertama\" telah selesai dilaksanakan.', 0, NULL, '2026-05-12 02:45:45', 'selesai', 1),
(280, 11, 8, 'Kegiatan Selesai: perayaan pertama', 'Kegiatan \"perayaan pertama\" telah selesai dilaksanakan.', 1, '2026-05-12 16:46:49', '2026-05-12 02:45:45', 'selesai', 1),
(281, 11, 9, 'Kegiatan Selesai: perayaan pertama', 'Kegiatan \"perayaan pertama\" telah selesai dilaksanakan.', 1, '2026-05-12 23:51:40', '2026-05-12 02:45:45', 'selesai', 1),
(282, 11, 5, 'Kegiatan Selesai: perayaan pertama', 'Kegiatan \"perayaan pertama\" telah selesai dilaksanakan.', 0, NULL, '2026-05-12 02:45:45', 'selesai', 1),
(283, 12, 1, 'Kegiatan Baru: 23w456tyui', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:53 di e5r6f7yuik.', 1, '2026-05-12 02:52:02', '2026-05-12 02:51:23', 'manual', 1),
(284, 12, 6, 'Kegiatan Baru: 23w456tyui', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:53 di e5r6f7yuik.', 1, '2026-05-13 00:09:16', '2026-05-12 02:51:23', 'manual', 1),
(285, 12, 2, 'Kegiatan Baru: 23w456tyui', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:53 di e5r6f7yuik.', 1, '2026-05-12 18:55:35', '2026-05-12 02:51:23', 'manual', 1),
(286, 12, 3, 'Kegiatan Baru: 23w456tyui', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:53 di e5r6f7yuik.', 1, '2026-05-12 16:27:50', '2026-05-12 02:51:23', 'manual', 1),
(287, 12, 4, 'Kegiatan Baru: 23w456tyui', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:53 di e5r6f7yuik.', 0, NULL, '2026-05-12 02:51:23', 'manual', 1),
(288, 12, 8, 'Kegiatan Baru: 23w456tyui', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:53 di e5r6f7yuik.', 1, '2026-05-12 16:46:49', '2026-05-12 02:51:23', 'manual', 1),
(289, 12, 9, 'Kegiatan Baru: 23w456tyui', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:53 di e5r6f7yuik.', 1, '2026-05-12 23:51:40', '2026-05-12 02:51:23', 'manual', 1),
(290, 12, 5, 'Kegiatan Baru: 23w456tyui', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:53 di e5r6f7yuik.', 0, NULL, '2026-05-12 02:51:23', 'manual', 1),
(291, 12, 1, 'Pengingat 24 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-12 02:52:02', '2026-05-12 02:51:24', 'h-24', 1),
(292, 12, 6, 'Pengingat 24 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:51:24', 'h-24', 1),
(293, 12, 2, 'Pengingat 24 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:51:24', 'h-24', 1),
(294, 12, 3, 'Pengingat 24 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:51:24', 'h-24', 1),
(295, 12, 4, 'Pengingat 24 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 24 jam lagi!', 0, NULL, '2026-05-12 02:51:24', 'h-24', 1),
(296, 12, 8, 'Pengingat 24 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-12 16:46:49', '2026-05-12 02:51:24', 'h-24', 1),
(297, 12, 9, 'Pengingat 24 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:51:24', 'h-24', 1),
(298, 12, 5, 'Pengingat 24 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 24 jam lagi!', 0, NULL, '2026-05-12 02:51:24', 'h-24', 1),
(299, 12, 1, 'Pengingat 12 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 12 jam lagi!', 1, '2026-05-12 02:51:30', '2026-05-12 02:51:24', 'h-12', 1),
(300, 12, 6, 'Pengingat 12 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 12 jam lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:51:24', 'h-12', 1),
(301, 12, 2, 'Pengingat 12 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 12 jam lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:51:24', 'h-12', 1),
(302, 12, 3, 'Pengingat 12 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 12 jam lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:51:24', 'h-12', 1),
(303, 12, 4, 'Pengingat 12 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 12 jam lagi!', 0, NULL, '2026-05-12 02:51:24', 'h-12', 1),
(304, 12, 8, 'Pengingat 12 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 12 jam lagi!', 1, '2026-05-12 16:46:49', '2026-05-12 02:51:24', 'h-12', 1),
(305, 12, 9, 'Pengingat 12 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 12 jam lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:51:24', 'h-12', 1),
(306, 12, 5, 'Pengingat 12 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 12 jam lagi!', 0, NULL, '2026-05-12 02:51:24', 'h-12', 1),
(307, 12, 1, 'Pengingat 6 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 6 jam lagi!', 1, '2026-05-12 02:51:31', '2026-05-12 02:51:24', 'h-6', 1),
(308, 12, 6, 'Pengingat 6 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 6 jam lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:51:24', 'h-6', 1),
(309, 12, 2, 'Pengingat 6 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 6 jam lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:51:24', 'h-6', 1),
(310, 12, 3, 'Pengingat 6 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 6 jam lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:51:24', 'h-6', 1),
(311, 12, 4, 'Pengingat 6 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 6 jam lagi!', 0, NULL, '2026-05-12 02:51:24', 'h-6', 1),
(312, 12, 8, 'Pengingat 6 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 6 jam lagi!', 1, '2026-05-12 16:46:49', '2026-05-12 02:51:24', 'h-6', 1),
(313, 12, 9, 'Pengingat 6 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 6 jam lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:51:24', 'h-6', 1),
(314, 12, 5, 'Pengingat 6 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 6 jam lagi!', 0, NULL, '2026-05-12 02:51:24', 'h-6', 1),
(315, 12, 1, 'Pengingat 1 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 1 jam lagi!', 1, '2026-05-12 02:51:32', '2026-05-12 02:51:24', 'h-1', 1),
(316, 12, 6, 'Pengingat 1 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 1 jam lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:51:24', 'h-1', 1),
(317, 12, 2, 'Pengingat 1 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 1 jam lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:51:24', 'h-1', 1),
(318, 12, 3, 'Pengingat 1 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 1 jam lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:51:24', 'h-1', 1),
(319, 12, 4, 'Pengingat 1 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 1 jam lagi!', 0, NULL, '2026-05-12 02:51:24', 'h-1', 1),
(320, 12, 8, 'Pengingat 1 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 1 jam lagi!', 1, '2026-05-12 16:46:49', '2026-05-12 02:51:24', 'h-1', 1),
(321, 12, 9, 'Pengingat 1 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 1 jam lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:51:24', 'h-1', 1),
(322, 12, 5, 'Pengingat 1 jam lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 1 jam lagi!', 0, NULL, '2026-05-12 02:51:24', 'h-1', 1),
(323, 12, 1, 'Pengingat 30 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 30 menit lagi!', 1, '2026-05-12 02:52:02', '2026-05-12 02:51:24', 'h-30m', 1),
(324, 12, 6, 'Pengingat 30 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 30 menit lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:51:24', 'h-30m', 1),
(325, 12, 2, 'Pengingat 30 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 30 menit lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:51:24', 'h-30m', 1),
(326, 12, 3, 'Pengingat 30 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 30 menit lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:51:24', 'h-30m', 1),
(327, 12, 4, 'Pengingat 30 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 30 menit lagi!', 0, NULL, '2026-05-12 02:51:24', 'h-30m', 1),
(328, 12, 8, 'Pengingat 30 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 30 menit lagi!', 1, '2026-05-12 16:46:49', '2026-05-12 02:51:24', 'h-30m', 1),
(329, 12, 9, 'Pengingat 30 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 30 menit lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:51:24', 'h-30m', 1),
(330, 12, 5, 'Pengingat 30 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 30 menit lagi!', 0, NULL, '2026-05-12 02:51:24', 'h-30m', 1),
(331, 12, 1, 'Pengingat 10 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 10 menit lagi!', 1, '2026-05-12 02:52:02', '2026-05-12 02:51:24', 'h-10m', 1),
(332, 12, 6, 'Pengingat 10 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 10 menit lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:51:24', 'h-10m', 1),
(333, 12, 2, 'Pengingat 10 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 10 menit lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:51:24', 'h-10m', 1),
(334, 12, 3, 'Pengingat 10 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 10 menit lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:51:24', 'h-10m', 1),
(335, 12, 4, 'Pengingat 10 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 10 menit lagi!', 0, NULL, '2026-05-12 02:51:24', 'h-10m', 1),
(336, 12, 8, 'Pengingat 10 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 10 menit lagi!', 1, '2026-05-12 16:46:49', '2026-05-12 02:51:24', 'h-10m', 1),
(337, 12, 9, 'Pengingat 10 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 10 menit lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:51:24', 'h-10m', 1),
(338, 12, 5, 'Pengingat 10 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 10 menit lagi!', 0, NULL, '2026-05-12 02:51:24', 'h-10m', 1),
(339, 12, 1, 'Pengingat 5 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 5 menit lagi!', 1, '2026-05-12 02:52:02', '2026-05-12 02:51:24', 'h-5m', 1),
(340, 12, 6, 'Pengingat 5 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 5 menit lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:51:24', 'h-5m', 1),
(341, 12, 2, 'Pengingat 5 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 5 menit lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:51:24', 'h-5m', 1),
(342, 12, 3, 'Pengingat 5 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 5 menit lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:51:24', 'h-5m', 1),
(343, 12, 4, 'Pengingat 5 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 5 menit lagi!', 0, NULL, '2026-05-12 02:51:24', 'h-5m', 1),
(344, 12, 8, 'Pengingat 5 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 5 menit lagi!', 1, '2026-05-12 16:46:49', '2026-05-12 02:51:24', 'h-5m', 1),
(345, 12, 9, 'Pengingat 5 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 5 menit lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:51:24', 'h-5m', 1),
(346, 12, 5, 'Pengingat 5 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 5 menit lagi!', 0, NULL, '2026-05-12 02:51:24', 'h-5m', 1),
(347, 13, 1, 'Kegiatan Baru: Pengangguran Banyak Acara', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:53 di lab rsi.', 1, '2026-05-12 11:06:27', '2026-05-12 02:52:08', 'manual', 1),
(348, 13, 6, 'Kegiatan Baru: Pengangguran Banyak Acara', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:53 di lab rsi.', 1, '2026-05-13 00:09:16', '2026-05-12 02:52:08', 'manual', 1),
(349, 13, 2, 'Kegiatan Baru: Pengangguran Banyak Acara', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:53 di lab rsi.', 1, '2026-05-12 17:47:18', '2026-05-12 02:52:08', 'manual', 1),
(350, 13, 3, 'Kegiatan Baru: Pengangguran Banyak Acara', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:53 di lab rsi.', 1, '2026-05-12 16:27:50', '2026-05-12 02:52:08', 'manual', 1),
(351, 13, 4, 'Kegiatan Baru: Pengangguran Banyak Acara', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:53 di lab rsi.', 0, NULL, '2026-05-12 02:52:08', 'manual', 1),
(352, 13, 8, 'Kegiatan Baru: Pengangguran Banyak Acara', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:53 di lab rsi.', 1, '2026-05-12 16:46:49', '2026-05-12 02:52:08', 'manual', 1),
(353, 13, 9, 'Kegiatan Baru: Pengangguran Banyak Acara', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:53 di lab rsi.', 1, '2026-05-12 23:51:40', '2026-05-12 02:52:08', 'manual', 1),
(354, 13, 5, 'Kegiatan Baru: Pengangguran Banyak Acara', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 09:53 di lab rsi.', 0, NULL, '2026-05-12 02:52:08', 'manual', 1),
(355, 12, 1, 'Pengingat 1 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 1 menit lagi!', 1, '2026-05-12 11:06:27', '2026-05-12 02:52:08', 'h-1m', 1),
(356, 12, 6, 'Pengingat 1 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 1 menit lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:52:08', 'h-1m', 1),
(357, 12, 2, 'Pengingat 1 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 1 menit lagi!', 1, '2026-05-12 17:47:17', '2026-05-12 02:52:08', 'h-1m', 1),
(358, 12, 3, 'Pengingat 1 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 1 menit lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:52:08', 'h-1m', 1),
(359, 12, 4, 'Pengingat 1 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 1 menit lagi!', 0, NULL, '2026-05-12 02:52:08', 'h-1m', 1),
(360, 12, 8, 'Pengingat 1 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 1 menit lagi!', 1, '2026-05-12 16:46:49', '2026-05-12 02:52:08', 'h-1m', 1),
(361, 12, 9, 'Pengingat 1 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 1 menit lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:52:08', 'h-1m', 1),
(362, 12, 5, 'Pengingat 1 menit lagi: 23w456tyui', 'Kegiatan \"23w456tyui\" akan dimulai dalam 1 menit lagi!', 0, NULL, '2026-05-12 02:52:08', 'h-1m', 1),
(363, 13, 1, 'Pengingat 24 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-12 11:06:27', '2026-05-12 02:52:08', 'h-24', 1),
(364, 13, 6, 'Pengingat 24 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:52:08', 'h-24', 1),
(365, 13, 2, 'Pengingat 24 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:52:08', 'h-24', 1),
(366, 13, 3, 'Pengingat 24 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:52:08', 'h-24', 1),
(367, 13, 4, 'Pengingat 24 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 24 jam lagi!', 0, NULL, '2026-05-12 02:52:08', 'h-24', 1),
(368, 13, 8, 'Pengingat 24 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-12 16:46:49', '2026-05-12 02:52:08', 'h-24', 1),
(369, 13, 9, 'Pengingat 24 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:52:08', 'h-24', 1),
(370, 13, 5, 'Pengingat 24 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 24 jam lagi!', 0, NULL, '2026-05-12 02:52:08', 'h-24', 1),
(371, 13, 1, 'Pengingat 12 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 12 jam lagi!', 1, '2026-05-12 11:06:27', '2026-05-12 02:52:08', 'h-12', 1),
(372, 13, 6, 'Pengingat 12 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 12 jam lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:52:08', 'h-12', 1),
(373, 13, 2, 'Pengingat 12 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 12 jam lagi!', 1, '2026-05-12 17:47:16', '2026-05-12 02:52:08', 'h-12', 1),
(374, 13, 3, 'Pengingat 12 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 12 jam lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:52:08', 'h-12', 1),
(375, 13, 4, 'Pengingat 12 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 12 jam lagi!', 0, NULL, '2026-05-12 02:52:08', 'h-12', 1),
(376, 13, 8, 'Pengingat 12 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 12 jam lagi!', 1, '2026-05-12 16:46:49', '2026-05-12 02:52:08', 'h-12', 1),
(377, 13, 9, 'Pengingat 12 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 12 jam lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:52:08', 'h-12', 1),
(378, 13, 5, 'Pengingat 12 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 12 jam lagi!', 0, NULL, '2026-05-12 02:52:08', 'h-12', 1),
(379, 13, 1, 'Pengingat 6 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 6 jam lagi!', 1, '2026-05-12 11:06:27', '2026-05-12 02:52:08', 'h-6', 1),
(380, 13, 6, 'Pengingat 6 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 6 jam lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:52:08', 'h-6', 1),
(381, 13, 2, 'Pengingat 6 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 6 jam lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:52:08', 'h-6', 1),
(382, 13, 3, 'Pengingat 6 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 6 jam lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:52:08', 'h-6', 1),
(383, 13, 4, 'Pengingat 6 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 6 jam lagi!', 0, NULL, '2026-05-12 02:52:08', 'h-6', 1),
(384, 13, 8, 'Pengingat 6 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 6 jam lagi!', 1, '2026-05-12 16:46:49', '2026-05-12 02:52:08', 'h-6', 1);
INSERT INTO `notifikasi` (`id_notifikasi`, `id_kegiatan`, `id_anggota`, `judul`, `pesan`, `dibaca`, `dibaca_pada`, `created_at`, `tipe_notif`, `ditampilkan`) VALUES
(385, 13, 9, 'Pengingat 6 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 6 jam lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:52:08', 'h-6', 1),
(386, 13, 5, 'Pengingat 6 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 6 jam lagi!', 0, NULL, '2026-05-12 02:52:08', 'h-6', 1),
(387, 13, 1, 'Pengingat 1 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 1 jam lagi!', 1, '2026-05-12 11:06:27', '2026-05-12 02:52:08', 'h-1', 1),
(388, 13, 6, 'Pengingat 1 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 1 jam lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:52:08', 'h-1', 1),
(389, 13, 2, 'Pengingat 1 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 1 jam lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:52:08', 'h-1', 1),
(390, 13, 3, 'Pengingat 1 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 1 jam lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:52:08', 'h-1', 1),
(391, 13, 4, 'Pengingat 1 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 1 jam lagi!', 0, NULL, '2026-05-12 02:52:08', 'h-1', 1),
(392, 13, 8, 'Pengingat 1 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 1 jam lagi!', 1, '2026-05-12 16:46:49', '2026-05-12 02:52:08', 'h-1', 1),
(393, 13, 9, 'Pengingat 1 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 1 jam lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:52:08', 'h-1', 1),
(394, 13, 5, 'Pengingat 1 jam lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 1 jam lagi!', 0, NULL, '2026-05-12 02:52:08', 'h-1', 1),
(395, 13, 1, 'Pengingat 30 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 30 menit lagi!', 1, '2026-05-12 11:06:27', '2026-05-12 02:52:08', 'h-30m', 1),
(396, 13, 6, 'Pengingat 30 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 30 menit lagi!', 1, '2026-05-12 11:00:48', '2026-05-12 02:52:08', 'h-30m', 1),
(397, 13, 2, 'Pengingat 30 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 30 menit lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:52:08', 'h-30m', 1),
(398, 13, 3, 'Pengingat 30 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 30 menit lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:52:08', 'h-30m', 1),
(399, 13, 4, 'Pengingat 30 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 30 menit lagi!', 0, NULL, '2026-05-12 02:52:08', 'h-30m', 1),
(400, 13, 8, 'Pengingat 30 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 30 menit lagi!', 1, '2026-05-12 16:46:49', '2026-05-12 02:52:08', 'h-30m', 1),
(401, 13, 9, 'Pengingat 30 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 30 menit lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:52:08', 'h-30m', 1),
(402, 13, 5, 'Pengingat 30 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 30 menit lagi!', 0, NULL, '2026-05-12 02:52:08', 'h-30m', 1),
(403, 13, 1, 'Pengingat 10 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 10 menit lagi!', 1, '2026-05-12 11:06:27', '2026-05-12 02:52:08', 'h-10m', 1),
(404, 13, 6, 'Pengingat 10 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 10 menit lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:52:08', 'h-10m', 1),
(405, 13, 2, 'Pengingat 10 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 10 menit lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:52:08', 'h-10m', 1),
(406, 13, 3, 'Pengingat 10 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 10 menit lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:52:08', 'h-10m', 1),
(407, 13, 4, 'Pengingat 10 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 10 menit lagi!', 0, NULL, '2026-05-12 02:52:08', 'h-10m', 1),
(408, 13, 8, 'Pengingat 10 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 10 menit lagi!', 1, '2026-05-12 16:46:49', '2026-05-12 02:52:08', 'h-10m', 1),
(409, 13, 9, 'Pengingat 10 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 10 menit lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:52:08', 'h-10m', 1),
(410, 13, 5, 'Pengingat 10 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 10 menit lagi!', 0, NULL, '2026-05-12 02:52:08', 'h-10m', 1),
(411, 13, 1, 'Pengingat 5 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 5 menit lagi!', 1, '2026-05-12 11:06:27', '2026-05-12 02:52:08', 'h-5m', 1),
(412, 13, 6, 'Pengingat 5 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 5 menit lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:52:08', 'h-5m', 1),
(413, 13, 2, 'Pengingat 5 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 5 menit lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:52:08', 'h-5m', 1),
(414, 13, 3, 'Pengingat 5 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 5 menit lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:52:08', 'h-5m', 1),
(415, 13, 4, 'Pengingat 5 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 5 menit lagi!', 0, NULL, '2026-05-12 02:52:08', 'h-5m', 1),
(416, 13, 8, 'Pengingat 5 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 5 menit lagi!', 1, '2026-05-12 16:46:49', '2026-05-12 02:52:08', 'h-5m', 1),
(417, 13, 9, 'Pengingat 5 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 5 menit lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:52:08', 'h-5m', 1),
(418, 13, 5, 'Pengingat 5 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 5 menit lagi!', 0, NULL, '2026-05-12 02:52:08', 'h-5m', 1),
(419, 13, 1, 'Pengingat 1 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 1 menit lagi!', 1, '2026-05-12 11:06:27', '2026-05-12 02:52:08', 'h-1m', 1),
(420, 13, 6, 'Pengingat 1 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 1 menit lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 02:52:08', 'h-1m', 1),
(421, 13, 2, 'Pengingat 1 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 1 menit lagi!', 1, '2026-05-12 18:55:35', '2026-05-12 02:52:08', 'h-1m', 1),
(422, 13, 3, 'Pengingat 1 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 1 menit lagi!', 1, '2026-05-12 16:27:50', '2026-05-12 02:52:08', 'h-1m', 1),
(423, 13, 4, 'Pengingat 1 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 1 menit lagi!', 0, NULL, '2026-05-12 02:52:08', 'h-1m', 1),
(424, 13, 8, 'Pengingat 1 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 1 menit lagi!', 1, '2026-05-12 16:46:49', '2026-05-12 02:52:08', 'h-1m', 1),
(425, 13, 9, 'Pengingat 1 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 1 menit lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 02:52:08', 'h-1m', 1),
(426, 13, 5, 'Pengingat 1 menit lagi: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" akan dimulai dalam 1 menit lagi!', 0, NULL, '2026-05-12 02:52:08', 'h-1m', 1),
(427, 12, 1, 'Kegiatan Dimulai: 23w456tyui', 'Kegiatan \"23w456tyui\" sedang berlangsung sekarang!', 1, '2026-05-12 11:06:27', '2026-05-12 02:56:42', 'mulai', 1),
(428, 12, 6, 'Kegiatan Dimulai: 23w456tyui', 'Kegiatan \"23w456tyui\" sedang berlangsung sekarang!', 1, '2026-05-13 00:09:16', '2026-05-12 02:56:42', 'mulai', 1),
(429, 12, 2, 'Kegiatan Dimulai: 23w456tyui', 'Kegiatan \"23w456tyui\" sedang berlangsung sekarang!', 1, '2026-05-12 18:55:35', '2026-05-12 02:56:42', 'mulai', 1),
(430, 12, 3, 'Kegiatan Dimulai: 23w456tyui', 'Kegiatan \"23w456tyui\" sedang berlangsung sekarang!', 1, '2026-05-12 16:27:50', '2026-05-12 02:56:42', 'mulai', 1),
(431, 12, 4, 'Kegiatan Dimulai: 23w456tyui', 'Kegiatan \"23w456tyui\" sedang berlangsung sekarang!', 0, NULL, '2026-05-12 02:56:42', 'mulai', 1),
(432, 12, 8, 'Kegiatan Dimulai: 23w456tyui', 'Kegiatan \"23w456tyui\" sedang berlangsung sekarang!', 1, '2026-05-12 16:46:49', '2026-05-12 02:56:42', 'mulai', 1),
(433, 12, 9, 'Kegiatan Dimulai: 23w456tyui', 'Kegiatan \"23w456tyui\" sedang berlangsung sekarang!', 1, '2026-05-12 23:51:40', '2026-05-12 02:56:42', 'mulai', 1),
(434, 12, 5, 'Kegiatan Dimulai: 23w456tyui', 'Kegiatan \"23w456tyui\" sedang berlangsung sekarang!', 0, NULL, '2026-05-12 02:56:42', 'mulai', 1),
(435, 13, 1, 'Kegiatan Dimulai: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" sedang berlangsung sekarang!', 1, '2026-05-12 11:06:27', '2026-05-12 02:56:42', 'mulai', 1),
(436, 13, 6, 'Kegiatan Dimulai: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" sedang berlangsung sekarang!', 1, '2026-05-13 00:09:16', '2026-05-12 02:56:42', 'mulai', 1),
(437, 13, 2, 'Kegiatan Dimulai: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" sedang berlangsung sekarang!', 1, '2026-05-12 18:55:35', '2026-05-12 02:56:42', 'mulai', 1),
(438, 13, 3, 'Kegiatan Dimulai: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" sedang berlangsung sekarang!', 1, '2026-05-12 16:27:50', '2026-05-12 02:56:42', 'mulai', 1),
(439, 13, 4, 'Kegiatan Dimulai: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" sedang berlangsung sekarang!', 0, NULL, '2026-05-12 02:56:42', 'mulai', 1),
(440, 13, 8, 'Kegiatan Dimulai: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" sedang berlangsung sekarang!', 1, '2026-05-12 16:46:49', '2026-05-12 02:56:42', 'mulai', 1),
(441, 13, 9, 'Kegiatan Dimulai: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" sedang berlangsung sekarang!', 1, '2026-05-12 23:51:40', '2026-05-12 02:56:42', 'mulai', 1),
(442, 13, 5, 'Kegiatan Dimulai: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" sedang berlangsung sekarang!', 0, NULL, '2026-05-12 02:56:42', 'mulai', 1),
(443, 13, 1, 'Kegiatan Selesai: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" telah selesai dilaksanakan.', 1, '2026-05-12 11:06:27', '2026-05-12 02:56:42', 'selesai', 1),
(444, 13, 6, 'Kegiatan Selesai: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" telah selesai dilaksanakan.', 1, '2026-05-13 00:09:16', '2026-05-12 02:56:42', 'selesai', 1),
(445, 13, 2, 'Kegiatan Selesai: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" telah selesai dilaksanakan.', 1, '2026-05-12 18:55:35', '2026-05-12 02:56:42', 'selesai', 1),
(446, 13, 3, 'Kegiatan Selesai: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" telah selesai dilaksanakan.', 1, '2026-05-12 16:27:50', '2026-05-12 02:56:42', 'selesai', 1),
(447, 13, 4, 'Kegiatan Selesai: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" telah selesai dilaksanakan.', 0, NULL, '2026-05-12 02:56:42', 'selesai', 1),
(448, 13, 8, 'Kegiatan Selesai: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" telah selesai dilaksanakan.', 1, '2026-05-12 16:46:49', '2026-05-12 02:56:42', 'selesai', 1),
(449, 13, 9, 'Kegiatan Selesai: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" telah selesai dilaksanakan.', 1, '2026-05-12 23:51:40', '2026-05-12 02:56:42', 'selesai', 1),
(450, 13, 5, 'Kegiatan Selesai: Pengangguran Banyak Acara', 'Kegiatan \"Pengangguran Banyak Acara\" telah selesai dilaksanakan.', 0, NULL, '2026-05-12 02:56:42', 'selesai', 1),
(451, 9, 1, 'Kegiatan Dimulai: fff', 'Kegiatan \"fff\" sedang berlangsung sekarang!', 1, '2026-05-12 12:35:22', '2026-05-12 11:06:28', 'mulai', 1),
(452, 9, 6, 'Kegiatan Dimulai: fff', 'Kegiatan \"fff\" sedang berlangsung sekarang!', 1, '2026-05-13 00:09:16', '2026-05-12 11:06:28', 'mulai', 1),
(453, 9, 2, 'Kegiatan Dimulai: fff', 'Kegiatan \"fff\" sedang berlangsung sekarang!', 1, '2026-05-12 18:55:35', '2026-05-12 11:06:28', 'mulai', 1),
(454, 9, 3, 'Kegiatan Dimulai: fff', 'Kegiatan \"fff\" sedang berlangsung sekarang!', 1, '2026-05-12 16:27:50', '2026-05-12 11:06:28', 'mulai', 1),
(455, 9, 4, 'Kegiatan Dimulai: fff', 'Kegiatan \"fff\" sedang berlangsung sekarang!', 0, NULL, '2026-05-12 11:06:28', 'mulai', 1),
(456, 9, 8, 'Kegiatan Dimulai: fff', 'Kegiatan \"fff\" sedang berlangsung sekarang!', 1, '2026-05-12 16:46:49', '2026-05-12 11:06:28', 'mulai', 1),
(457, 9, 9, 'Kegiatan Dimulai: fff', 'Kegiatan \"fff\" sedang berlangsung sekarang!', 1, '2026-05-12 23:51:40', '2026-05-12 11:06:28', 'mulai', 1),
(458, 9, 5, 'Kegiatan Dimulai: fff', 'Kegiatan \"fff\" sedang berlangsung sekarang!', 0, NULL, '2026-05-12 11:06:28', 'mulai', 1),
(459, 14, 1, 'Kegiatan Baru: MAMAH MAU 5', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 23:27 di Dirumah masing - masing.', 1, '2026-05-12 23:51:00', '2026-05-12 16:27:24', 'manual', 1),
(460, 14, 6, 'Kegiatan Baru: MAMAH MAU 5', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 23:27 di Dirumah masing - masing.', 1, '2026-05-13 00:09:16', '2026-05-12 16:27:24', 'manual', 1),
(461, 14, 2, 'Kegiatan Baru: MAMAH MAU 5', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 23:27 di Dirumah masing - masing.', 1, '2026-05-12 18:55:35', '2026-05-12 16:27:24', 'manual', 1),
(462, 14, 3, 'Kegiatan Baru: MAMAH MAU 5', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 23:27 di Dirumah masing - masing.', 1, '2026-05-12 16:27:50', '2026-05-12 16:27:24', 'manual', 1),
(463, 14, 4, 'Kegiatan Baru: MAMAH MAU 5', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 23:27 di Dirumah masing - masing.', 0, NULL, '2026-05-12 16:27:24', 'manual', 1),
(464, 14, 8, 'Kegiatan Baru: MAMAH MAU 5', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 23:27 di Dirumah masing - masing.', 1, '2026-05-12 16:46:49', '2026-05-12 16:27:24', 'manual', 1),
(465, 14, 9, 'Kegiatan Baru: MAMAH MAU 5', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 23:27 di Dirumah masing - masing.', 1, '2026-05-12 23:51:40', '2026-05-12 16:27:24', 'manual', 1),
(466, 14, 5, 'Kegiatan Baru: MAMAH MAU 5', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 12 May 2026, 23:27 di Dirumah masing - masing.', 0, NULL, '2026-05-12 16:27:24', 'manual', 1),
(467, 14, 1, 'Kegiatan Dimulai: MAMAH MAU 5', 'Kegiatan \"MAMAH MAU 5\" sedang berlangsung sekarang!', 1, '2026-05-12 23:51:00', '2026-05-12 16:27:24', 'mulai', 1),
(468, 14, 6, 'Kegiatan Dimulai: MAMAH MAU 5', 'Kegiatan \"MAMAH MAU 5\" sedang berlangsung sekarang!', 1, '2026-05-13 00:09:16', '2026-05-12 16:27:24', 'mulai', 1),
(469, 14, 2, 'Kegiatan Dimulai: MAMAH MAU 5', 'Kegiatan \"MAMAH MAU 5\" sedang berlangsung sekarang!', 1, '2026-05-12 18:55:35', '2026-05-12 16:27:24', 'mulai', 1),
(470, 14, 3, 'Kegiatan Dimulai: MAMAH MAU 5', 'Kegiatan \"MAMAH MAU 5\" sedang berlangsung sekarang!', 1, '2026-05-12 16:27:50', '2026-05-12 16:27:24', 'mulai', 1),
(471, 14, 4, 'Kegiatan Dimulai: MAMAH MAU 5', 'Kegiatan \"MAMAH MAU 5\" sedang berlangsung sekarang!', 0, NULL, '2026-05-12 16:27:24', 'mulai', 1),
(472, 14, 8, 'Kegiatan Dimulai: MAMAH MAU 5', 'Kegiatan \"MAMAH MAU 5\" sedang berlangsung sekarang!', 1, '2026-05-12 16:46:49', '2026-05-12 16:27:24', 'mulai', 1),
(473, 14, 9, 'Kegiatan Dimulai: MAMAH MAU 5', 'Kegiatan \"MAMAH MAU 5\" sedang berlangsung sekarang!', 1, '2026-05-12 23:51:40', '2026-05-12 16:27:24', 'mulai', 1),
(474, 14, 5, 'Kegiatan Dimulai: MAMAH MAU 5', 'Kegiatan \"MAMAH MAU 5\" sedang berlangsung sekarang!', 0, NULL, '2026-05-12 16:27:24', 'mulai', 1),
(520, 15, 1, 'Kegiatan Dimulai: as', 'Kegiatan \"as\" sedang berlangsung sekarang!', 1, '2026-05-12 23:51:00', '2026-05-12 19:10:19', 'mulai', 1),
(521, 15, 6, 'Kegiatan Dimulai: as', 'Kegiatan \"as\" sedang berlangsung sekarang!', 1, '2026-05-13 00:09:16', '2026-05-12 19:10:19', 'mulai', 1),
(522, 15, 2, 'Kegiatan Dimulai: as', 'Kegiatan \"as\" sedang berlangsung sekarang!', 1, '2026-05-12 19:12:51', '2026-05-12 19:10:19', 'mulai', 1),
(523, 15, 3, 'Kegiatan Dimulai: as', 'Kegiatan \"as\" sedang berlangsung sekarang!', 0, NULL, '2026-05-12 19:10:19', 'mulai', 1),
(524, 15, 4, 'Kegiatan Dimulai: as', 'Kegiatan \"as\" sedang berlangsung sekarang!', 0, NULL, '2026-05-12 19:10:19', 'mulai', 1),
(525, 15, 8, 'Kegiatan Dimulai: as', 'Kegiatan \"as\" sedang berlangsung sekarang!', 1, '2026-05-12 19:12:40', '2026-05-12 19:10:19', 'mulai', 1),
(526, 15, 9, 'Kegiatan Dimulai: as', 'Kegiatan \"as\" sedang berlangsung sekarang!', 1, '2026-05-12 23:51:40', '2026-05-12 19:10:19', 'mulai', 1),
(528, 15, 5, 'Kegiatan Dimulai: as', 'Kegiatan \"as\" sedang berlangsung sekarang!', 0, NULL, '2026-05-12 19:10:19', 'mulai', 1),
(529, 16, 1, 'Kegiatan Baru: awawas', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 13 May 2026, 02:15 di lab rsi.', 1, '2026-05-12 23:51:00', '2026-05-12 19:14:02', 'manual', 1),
(530, 16, 6, 'Kegiatan Baru: awawas', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 13 May 2026, 02:15 di lab rsi.', 1, '2026-05-13 00:09:16', '2026-05-12 19:14:02', 'manual', 1),
(531, 16, 2, 'Kegiatan Baru: awawas', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 13 May 2026, 02:15 di lab rsi.', 0, NULL, '2026-05-12 19:14:02', 'manual', 1),
(532, 16, 3, 'Kegiatan Baru: awawas', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 13 May 2026, 02:15 di lab rsi.', 0, NULL, '2026-05-12 19:14:02', 'manual', 1),
(533, 16, 4, 'Kegiatan Baru: awawas', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 13 May 2026, 02:15 di lab rsi.', 0, NULL, '2026-05-12 19:14:02', 'manual', 0),
(534, 16, 8, 'Kegiatan Baru: awawas', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 13 May 2026, 02:15 di lab rsi.', 0, NULL, '2026-05-12 19:14:02', 'manual', 1),
(535, 16, 9, 'Kegiatan Baru: awawas', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 13 May 2026, 02:15 di lab rsi.', 1, '2026-05-12 23:51:40', '2026-05-12 19:14:02', 'manual', 1),
(537, 16, 5, 'Kegiatan Baru: awawas', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 13 May 2026, 02:15 di lab rsi.', 0, NULL, '2026-05-12 19:14:02', 'manual', 1),
(538, 16, 1, 'Pengingat 24 jam lagi: awawas', 'Kegiatan \"awawas\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-12 23:51:00', '2026-05-12 19:14:03', 'h-24', 1),
(539, 16, 6, 'Pengingat 24 jam lagi: awawas', 'Kegiatan \"awawas\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 19:14:03', 'h-24', 1),
(540, 16, 2, 'Pengingat 24 jam lagi: awawas', 'Kegiatan \"awawas\" akan dimulai dalam 24 jam lagi!', 0, NULL, '2026-05-12 19:14:03', 'h-24', 0),
(541, 16, 3, 'Pengingat 24 jam lagi: awawas', 'Kegiatan \"awawas\" akan dimulai dalam 24 jam lagi!', 0, NULL, '2026-05-12 19:14:03', 'h-24', 0),
(542, 16, 4, 'Pengingat 24 jam lagi: awawas', 'Kegiatan \"awawas\" akan dimulai dalam 24 jam lagi!', 0, NULL, '2026-05-12 19:14:03', 'h-24', 0),
(543, 16, 8, 'Pengingat 24 jam lagi: awawas', 'Kegiatan \"awawas\" akan dimulai dalam 24 jam lagi!', 0, NULL, '2026-05-12 19:14:03', 'h-24', 0),
(544, 16, 9, 'Pengingat 24 jam lagi: awawas', 'Kegiatan \"awawas\" akan dimulai dalam 24 jam lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 19:14:03', 'h-24', 1),
(546, 16, 5, 'Pengingat 24 jam lagi: awawas', 'Kegiatan \"awawas\" akan dimulai dalam 24 jam lagi!', 0, NULL, '2026-05-12 19:14:03', 'h-24', 0),
(547, 16, 1, 'Pengingat 1 jam lagi: awawas', 'Kegiatan \"awawas\" akan dimulai dalam 1 jam lagi!', 1, '2026-05-12 23:51:00', '2026-05-12 19:14:03', 'h-1', 1),
(548, 16, 6, 'Pengingat 1 jam lagi: awawas', 'Kegiatan \"awawas\" akan dimulai dalam 1 jam lagi!', 1, '2026-05-13 00:09:16', '2026-05-12 19:14:03', 'h-1', 1),
(549, 16, 2, 'Pengingat 1 jam lagi: awawas', 'Kegiatan \"awawas\" akan dimulai dalam 1 jam lagi!', 0, NULL, '2026-05-12 19:14:03', 'h-1', 1),
(550, 16, 3, 'Pengingat 1 jam lagi: awawas', 'Kegiatan \"awawas\" akan dimulai dalam 1 jam lagi!', 0, NULL, '2026-05-12 19:14:03', 'h-1', 0),
(551, 16, 4, 'Pengingat 1 jam lagi: awawas', 'Kegiatan \"awawas\" akan dimulai dalam 1 jam lagi!', 0, NULL, '2026-05-12 19:14:03', 'h-1', 0),
(552, 16, 8, 'Pengingat 1 jam lagi: awawas', 'Kegiatan \"awawas\" akan dimulai dalam 1 jam lagi!', 0, NULL, '2026-05-12 19:14:03', 'h-1', 1),
(553, 16, 9, 'Pengingat 1 jam lagi: awawas', 'Kegiatan \"awawas\" akan dimulai dalam 1 jam lagi!', 1, '2026-05-12 23:51:40', '2026-05-12 19:14:03', 'h-1', 1),
(555, 16, 5, 'Pengingat 1 jam lagi: awawas', 'Kegiatan \"awawas\" akan dimulai dalam 1 jam lagi!', 0, NULL, '2026-05-12 19:14:03', 'h-1', 0),
(556, 16, 1, 'Kegiatan Dimulai: awawas', 'Kegiatan \"awawas\" sedang berlangsung sekarang!', 1, '2026-05-12 23:51:00', '2026-05-12 19:15:08', 'mulai', 1),
(557, 16, 6, 'Kegiatan Dimulai: awawas', 'Kegiatan \"awawas\" sedang berlangsung sekarang!', 1, '2026-05-13 00:09:16', '2026-05-12 19:15:08', 'mulai', 1),
(558, 16, 2, 'Kegiatan Dimulai: awawas', 'Kegiatan \"awawas\" sedang berlangsung sekarang!', 1, '2026-05-12 19:15:15', '2026-05-12 19:15:08', 'mulai', 1),
(559, 16, 3, 'Kegiatan Dimulai: awawas', 'Kegiatan \"awawas\" sedang berlangsung sekarang!', 0, NULL, '2026-05-12 19:15:08', 'mulai', 1),
(560, 16, 4, 'Kegiatan Dimulai: awawas', 'Kegiatan \"awawas\" sedang berlangsung sekarang!', 0, NULL, '2026-05-12 19:15:08', 'mulai', 0),
(561, 16, 8, 'Kegiatan Dimulai: awawas', 'Kegiatan \"awawas\" sedang berlangsung sekarang!', 0, NULL, '2026-05-12 19:15:08', 'mulai', 1),
(562, 16, 9, 'Kegiatan Dimulai: awawas', 'Kegiatan \"awawas\" sedang berlangsung sekarang!', 1, '2026-05-12 23:51:40', '2026-05-12 19:15:08', 'mulai', 1),
(564, 16, 5, 'Kegiatan Dimulai: awawas', 'Kegiatan \"awawas\" sedang berlangsung sekarang!', 0, NULL, '2026-05-12 19:15:08', 'mulai', 1),
(565, 17, 1, 'Kegiatan Baru: awdadwasda', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 13 May 2026, 05:23 di lab rsi.', 1, '2026-05-12 23:51:00', '2026-05-12 22:22:25', 'manual', 1),
(566, 17, 6, 'Kegiatan Baru: awdadwasda', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 13 May 2026, 05:23 di lab rsi.', 1, '2026-05-13 00:09:16', '2026-05-12 22:22:25', 'manual', 1),
(567, 17, 2, 'Kegiatan Baru: awdadwasda', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 13 May 2026, 05:23 di lab rsi.', 0, NULL, '2026-05-12 22:22:25', 'manual', 1),
(568, 17, 3, 'Kegiatan Baru: awdadwasda', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 13 May 2026, 05:23 di lab rsi.', 0, NULL, '2026-05-12 22:22:25', 'manual', 1),
(569, 17, 4, 'Kegiatan Baru: awdadwasda', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 13 May 2026, 05:23 di lab rsi.', 0, NULL, '2026-05-12 22:22:25', 'manual', 0),
(570, 17, 8, 'Kegiatan Baru: awdadwasda', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 13 May 2026, 05:23 di lab rsi.', 1, '2026-05-12 22:22:32', '2026-05-12 22:22:25', 'manual', 1),
(571, 17, 9, 'Kegiatan Baru: awdadwasda', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 13 May 2026, 05:23 di lab rsi.', 1, '2026-05-12 23:51:40', '2026-05-12 22:22:25', 'manual', 1),
(573, 17, 5, 'Kegiatan Baru: awdadwasda', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 13 May 2026, 05:23 di lab rsi.', 0, NULL, '2026-05-12 22:22:25', 'manual', 1),
(574, 17, 1, 'Kegiatan Dimulai: awdadwasda', 'Kegiatan \"awdadwasda\" sedang berlangsung sekarang!', 1, '2026-05-12 23:51:00', '2026-05-12 22:23:10', 'mulai', 1),
(575, 17, 6, 'Kegiatan Dimulai: awdadwasda', 'Kegiatan \"awdadwasda\" sedang berlangsung sekarang!', 1, '2026-05-13 00:09:16', '2026-05-12 22:23:10', 'mulai', 1),
(576, 17, 2, 'Kegiatan Dimulai: awdadwasda', 'Kegiatan \"awdadwasda\" sedang berlangsung sekarang!', 0, NULL, '2026-05-12 22:23:10', 'mulai', 1),
(577, 17, 3, 'Kegiatan Dimulai: awdadwasda', 'Kegiatan \"awdadwasda\" sedang berlangsung sekarang!', 0, NULL, '2026-05-12 22:23:10', 'mulai', 1),
(578, 17, 4, 'Kegiatan Dimulai: awdadwasda', 'Kegiatan \"awdadwasda\" sedang berlangsung sekarang!', 0, NULL, '2026-05-12 22:23:10', 'mulai', 0),
(579, 17, 8, 'Kegiatan Dimulai: awdadwasda', 'Kegiatan \"awdadwasda\" sedang berlangsung sekarang!', 0, NULL, '2026-05-12 22:23:10', 'mulai', 1),
(580, 17, 9, 'Kegiatan Dimulai: awdadwasda', 'Kegiatan \"awdadwasda\" sedang berlangsung sekarang!', 1, '2026-05-12 23:51:40', '2026-05-12 22:23:10', 'mulai', 1),
(582, 17, 5, 'Kegiatan Dimulai: awdadwasda', 'Kegiatan \"awdadwasda\" sedang berlangsung sekarang!', 0, NULL, '2026-05-12 22:23:10', 'mulai', 1),
(583, 18, 1, 'Kegiatan Baru: awdwawdawda', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 14 May 2026, 07:00 di lab rsi.', 1, '2026-05-13 02:56:20', '2026-05-12 23:57:22', 'manual', 1),
(584, 18, 6, 'Kegiatan Baru: awdwawdawda', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 14 May 2026, 07:00 di lab rsi.', 1, '2026-05-13 00:09:16', '2026-05-12 23:57:22', 'manual', 1),
(585, 18, 2, 'Kegiatan Baru: awdwawdawda', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 14 May 2026, 07:00 di lab rsi.', 0, NULL, '2026-05-12 23:57:22', 'manual', 1),
(586, 18, 3, 'Kegiatan Baru: awdwawdawda', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 14 May 2026, 07:00 di lab rsi.', 0, NULL, '2026-05-12 23:57:22', 'manual', 1),
(587, 18, 4, 'Kegiatan Baru: awdwawdawda', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 14 May 2026, 07:00 di lab rsi.', 0, NULL, '2026-05-12 23:57:22', 'manual', 0),
(588, 18, 8, 'Kegiatan Baru: awdwawdawda', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 14 May 2026, 07:00 di lab rsi.', 1, '2026-05-12 23:57:26', '2026-05-12 23:57:22', 'manual', 1),
(589, 18, 9, 'Kegiatan Baru: awdwawdawda', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 14 May 2026, 07:00 di lab rsi.', 0, NULL, '2026-05-12 23:57:22', 'manual', 1),
(590, 18, 5, 'Kegiatan Baru: awdwawdawda', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 14 May 2026, 07:00 di lab rsi.', 0, NULL, '2026-05-12 23:57:22', 'manual', 1),
(607, 21, 1, 'Kegiatan Baru: presentasi', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 28 Apr 2026, 09:56 di disini.', 1, '2026-05-13 02:59:36', '2026-05-13 02:57:46', 'manual', 1),
(608, 21, 6, 'Kegiatan Baru: presentasi', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 28 Apr 2026, 09:56 di disini.', 0, NULL, '2026-05-13 02:57:46', 'manual', 0),
(609, 21, 12, 'Kegiatan Baru: presentasi', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 28 Apr 2026, 09:56 di disini.', 0, NULL, '2026-05-13 02:57:46', 'manual', 0),
(610, 21, 2, 'Kegiatan Baru: presentasi', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 28 Apr 2026, 09:56 di disini.', 0, NULL, '2026-05-13 02:57:46', 'manual', 1),
(611, 21, 3, 'Kegiatan Baru: presentasi', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 28 Apr 2026, 09:56 di disini.', 0, NULL, '2026-05-13 02:57:46', 'manual', 1),
(612, 21, 4, 'Kegiatan Baru: presentasi', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 28 Apr 2026, 09:56 di disini.', 0, NULL, '2026-05-13 02:57:46', 'manual', 0),
(613, 21, 8, 'Kegiatan Baru: presentasi', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 28 Apr 2026, 09:56 di disini.', 1, '2026-05-13 04:28:06', '2026-05-13 02:57:46', 'manual', 1),
(614, 21, 9, 'Kegiatan Baru: presentasi', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 28 Apr 2026, 09:56 di disini.', 0, NULL, '2026-05-13 02:57:46', 'manual', 0),
(615, 21, 5, 'Kegiatan Baru: presentasi', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 28 Apr 2026, 09:56 di disini.', 0, NULL, '2026-05-13 02:57:46', 'manual', 0),
(616, 21, 1, 'Kegiatan Dimulai: presentasi', 'Kegiatan \"presentasi\" sedang berlangsung sekarang!', 1, '2026-05-13 02:59:36', '2026-05-13 02:57:46', 'mulai', 1),
(617, 21, 6, 'Kegiatan Dimulai: presentasi', 'Kegiatan \"presentasi\" sedang berlangsung sekarang!', 0, NULL, '2026-05-13 02:57:46', 'mulai', 0),
(618, 21, 12, 'Kegiatan Dimulai: presentasi', 'Kegiatan \"presentasi\" sedang berlangsung sekarang!', 0, NULL, '2026-05-13 02:57:46', 'mulai', 0),
(619, 21, 2, 'Kegiatan Dimulai: presentasi', 'Kegiatan \"presentasi\" sedang berlangsung sekarang!', 0, NULL, '2026-05-13 02:57:46', 'mulai', 1),
(620, 21, 3, 'Kegiatan Dimulai: presentasi', 'Kegiatan \"presentasi\" sedang berlangsung sekarang!', 0, NULL, '2026-05-13 02:57:46', 'mulai', 1),
(621, 21, 4, 'Kegiatan Dimulai: presentasi', 'Kegiatan \"presentasi\" sedang berlangsung sekarang!', 0, NULL, '2026-05-13 02:57:46', 'mulai', 0),
(622, 21, 8, 'Kegiatan Dimulai: presentasi', 'Kegiatan \"presentasi\" sedang berlangsung sekarang!', 0, NULL, '2026-05-13 02:57:46', 'mulai', 1),
(623, 21, 9, 'Kegiatan Dimulai: presentasi', 'Kegiatan \"presentasi\" sedang berlangsung sekarang!', 0, NULL, '2026-05-13 02:57:46', 'mulai', 0),
(624, 21, 5, 'Kegiatan Dimulai: presentasi', 'Kegiatan \"presentasi\" sedang berlangsung sekarang!', 0, NULL, '2026-05-13 02:57:46', 'mulai', 0),
(625, 22, 1, 'Kegiatan Baru: rapat 2', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 14 May 2026, 09:58 di disini.', 1, '2026-05-13 02:59:36', '2026-05-13 02:59:18', 'manual', 1),
(626, 22, 6, 'Kegiatan Baru: rapat 2', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 14 May 2026, 09:58 di disini.', 0, NULL, '2026-05-13 02:59:18', 'manual', 0),
(627, 22, 12, 'Kegiatan Baru: rapat 2', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 14 May 2026, 09:58 di disini.', 0, NULL, '2026-05-13 02:59:18', 'manual', 0),
(628, 22, 2, 'Kegiatan Baru: rapat 2', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 14 May 2026, 09:58 di disini.', 0, NULL, '2026-05-13 02:59:18', 'manual', 1),
(629, 22, 3, 'Kegiatan Baru: rapat 2', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 14 May 2026, 09:58 di disini.', 0, NULL, '2026-05-13 02:59:18', 'manual', 1),
(630, 22, 4, 'Kegiatan Baru: rapat 2', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 14 May 2026, 09:58 di disini.', 0, NULL, '2026-05-13 02:59:18', 'manual', 0),
(631, 22, 8, 'Kegiatan Baru: rapat 2', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 14 May 2026, 09:58 di disini.', 0, NULL, '2026-05-13 02:59:18', 'manual', 1),
(632, 22, 9, 'Kegiatan Baru: rapat 2', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 14 May 2026, 09:58 di disini.', 0, NULL, '2026-05-13 02:59:18', 'manual', 0),
(633, 22, 5, 'Kegiatan Baru: rapat 2', 'Kegiatan baru telah ditambahkan. Pelaksanaan: 14 May 2026, 09:58 di disini.', 0, NULL, '2026-05-13 02:59:18', 'manual', 0);

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
(8, 9, 'irzalamru83', '$2y$10$h2wliVngcYq.Hm89yabDv.C6s9nP./UtUfYdyrKIO8WFV8rBKeeCK'),
(11, 12, 'e41251513', '$2y$10$2lOWZO3bndDmFOv/Eox2ZOndgTWQ6Ony8OtYsLGi0OI2WyvjnzTD6'),
(13, 14, 'amruirzal', '$2y$10$L.w2X65V9khixRhrLwnVmOr3wl/Ezz32ksFuXhYj50ypoVJyyCfx.');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pemasukan`
--

CREATE TABLE `pemasukan` (
  `id_pemasukan` int(11) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `id_kegiatan` int(11) DEFAULT NULL,
  `kode_pemasukan` varchar(20) NOT NULL,
  `sumber_dana` varchar(100) DEFAULT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `tanggal` date NOT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pemasukan`
--

INSERT INTO `pemasukan` (`id_pemasukan`, `id_anggota`, `id_kategori`, `id_kegiatan`, `kode_pemasukan`, `sumber_dana`, `jumlah`, `tanggal`, `bukti_pembayaran`, `status`) VALUES
(1, 4, 1, NULL, 'PMK-2026-001', 'Iuran anggota aktif', 500000.00, '2026-09-01', NULL, 'Disetujui'),
(4, 4, 1, NULL, 'PMK-2026-004', 'Iuran anggota aktif', 500000.00, '2026-10-01', NULL, 'Berhasil'),
(8, 1, 1, NULL, '23r4r', '32453', 312456777.00, '2026-05-13', 'uploads/keuangan/1778628525_f28b139091ba27daf3470d9b0b02acac.jpg', 'Berhasil'),
(10, 12, 1, NULL, 'kkk-hh-07-75', 'Jual Ginjal', 350000000.00, '2026-05-13', 'uploads/keuangan/1778635833_1395941.jpg', 'Menunggu'),
(11, 12, 1, NULL, 'dgaousgfwofgp', 'Jual Ginjal', 350000000.00, '2026-05-13', 'uploads/keuangan/1778635898_1395941.jpg', 'Menunggu'),
(13, 1, 1, NULL, '2345678', 'saya', 1234567890.00, '2026-05-13', 'uploads/keuangan/1778637441_Draft_Surat_tes (1).pdf', 'Berhasil'),
(14, 1, 1, NULL, '32456', 'ayam', 2345678.00, '2026-05-13', 'uploads/keuangan/1778638506_VOL.2+JUNI+2024+HAL+66-74 (2).pdf', 'Menunggu'),
(17, 8, 2, NULL, '12334455', 'iuran', 9999999999999.99, '2026-05-13', 'uploads/keuangan/1778639771_logonakama.jpeg', 'Berhasil'),
(18, 2, 1, NULL, 'i', 'dompet', 98765670.00, '2026-05-13', 'uploads/keuangan/1778642363_Blackbox testing.pdf', 'Ditolak'),
(19, 2, 1, NULL, 'd', 'isis 5900', 50000000.00, '2026-05-13', 'uploads/keuangan/1778642450_E41251215_FajarMuharram_D_WSIWEEK12.pdf', 'Berhasil');

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
(1, 'PR-0001', 1, 'Rapat BPH', NULL, 'Disetujui', '2026-05-06 08:44:00', '2026-05-06 08:44:00'),
(2, 'PR-0002', 2, 'Latihan Presentasi', NULL, 'Menunggu', '2026-05-06 08:44:00', '2026-05-06 08:44:00'),
(3, 'PR-0003', 3, 'Persiapan LKTI 2026', NULL, 'Disetujui', '2026-05-06 08:44:00', '2026-05-06 08:44:00'),
(8, 'PR-1208084', 6, 'w1t4myj', 'uploads/surat_peminjaman/sp_6a027d9d80814_11. Penulisan Daftar Pustaka APA (1).pdf', 'Disetujui', '2026-05-12 01:08:45', '2026-05-13 03:07:08'),
(10, 'PR-1306214', 6, '1324r3', NULL, 'Menunggu', '2026-05-12 23:21:42', '2026-05-12 23:21:42'),
(13, 'PR-1310064', 6, 'rapat', 'uploads/surat_peminjaman/sp_6a03eac348c7b_Draft_Surat_tes (2).pdf', 'Menunggu', '2026-05-13 03:06:43', '2026-05-13 03:06:43');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan_surat`
--

CREATE TABLE `pengajuan_surat` (
  `id_pengajuan` int(11) NOT NULL,
  `id_surat` int(11) NOT NULL,
  `tanggal_unggah` date NOT NULL,
  `tanggal_update` date DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pengajuan_surat`
--

INSERT INTO `pengajuan_surat` (`id_pengajuan`, `id_surat`, `tanggal_unggah`, `tanggal_update`, `status`) VALUES
(7, 7, '2026-05-12', '2026-05-13', 'Selesai'),
(9, 9, '2026-05-13', '2026-05-13', 'Selesai');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengeluaran`
--

CREATE TABLE `pengeluaran` (
  `id_pengeluaran` int(11) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `id_kegiatan` int(11) DEFAULT NULL,
  `kode_pengeluaran` varchar(20) NOT NULL,
  `penerima` varchar(100) DEFAULT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `tanggal` date NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pengeluaran`
--

INSERT INTO `pengeluaran` (`id_pengeluaran`, `id_anggota`, `id_kategori`, `id_kegiatan`, `kode_pengeluaran`, `penerima`, `jumlah`, `tanggal`, `status`) VALUES
(11, 1, 5, NULL, 'wa46se5uhi', '567yguih', 1223456.00, '2026-05-13', 'Disetujui'),
(13, 1, 5, NULL, '213435647', '2356457890', 132434567890.00, '2026-05-13', 'Disetujui'),
(14, 1, 5, NULL, '3546e56rtyighj', 'prabowo', 50000000.00, '2026-05-13', 'Disetujui'),
(15, 1, 4, NULL, 'makan', 'makan', 50000000.00, '2026-05-13', 'Disetujui');

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
(1, 'R-3-02', '3.2', 40, 40, 20, 1, 1, 1, NULL),
(2, 'R-3-03', '3.3', 40, 40, 20, 1, 1, 1, NULL),
(3, 'LAB-PL', 'Lab. PL', 30, 30, 30, 2, 1, 2, NULL),
(11, '', '3.11', 40, 40, 40, 1, 1, 1, 'uploads/ruangan/ruangan_6a03cf586f069.jpeg');

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
(3, 3, 'tok_devis_003ghi', '192.168.1.12', 'Mozilla/5.0 (Windows NT 10.0)', '2026-10-01 09:00:00', '2026-10-01 21:00:00', NULL),
(4, 1, 'tok_irzal_004jkl', '192.168.1.10', 'Mozilla/5.0 (iPhone)', '2026-11-01 07:45:00', '2026-11-01 19:45:00', '2026-11-01 12:00:00'),
(5, 4, 'tok_gabriel_005mno', '192.168.1.13', 'Mozilla/5.0 (Windows NT 10.0)', '2026-11-10 08:30:00', '2026-11-10 20:30:00', NULL),
(6, 4, '5c102b8bcfab13fbbd2b2c6ebf508d5e8e950bc85ed79e389bc38652e4595c6d', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-12 01:32:50', '2026-06-11 08:32:50', '2026-05-12 01:35:37'),
(7, 5, 'e56c6a22b6ecc035d1f06bd73fd61d2263d53701493969890ad88654096e06e1', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-05-12 01:35:46', '2026-06-11 08:35:46', NULL),
(8, 4, '80793802da14e96b73e9c802408c9ec9d5677a7eca5b5046328347c29d120add', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-12 01:36:11', '2026-06-11 08:36:11', NULL),
(9, 8, '132b6ac7ca010429f45822596db796083a9dbf1e4f376169b6f94142c99fd3d9', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 01:37:35', '2026-06-11 08:37:35', '2026-05-13 02:45:43'),
(10, 9, 'a47bf6988c09543e0c9b2a112a3a6ea11edc6d44d389896f96346de684e82db1', '2404:c0:3466:7bbf:a179:865a:2c49:7574', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-05-12 01:42:10', '2026-06-11 08:42:10', '2026-05-12 01:55:57'),
(11, 9, '51922e2bb3d98d92acc192aca6a593d9c2203012f0bfb081dec2c7ee0921a383', '2404:c0:3466:7bbf:a179:865a:2c49:7574', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-05-12 01:56:52', '2026-06-11 08:56:52', '2026-05-12 02:07:51'),
(12, 1, '43608b6cddeff4bbb1541aeb562e932faf159ddb47e7705d6e75ba39575b147e', '182.5.233.131', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-05-12 01:57:12', '2026-06-11 08:57:12', '2026-05-13 02:53:32'),
(13, 9, '2cc3141fcbe9fb6da18bf44f568d8bafd19e04bbd6edb9ff71a72a3744e62bc2', '182.5.233.131', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-05-12 02:10:17', '2026-06-11 09:10:17', '2026-05-12 23:52:09'),
(14, 6, 'cc46b2b37707709be0c6aff53d7e1154e4624720442c2a9f16da7b4424e16f78', '103.160.182.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-05-12 11:00:28', '2026-06-11 18:00:28', '2026-05-13 00:12:08'),
(15, 3, 'a94762fe75515eef7649905b8e57a5e359e0cba51c25749c0f1bb75002083d92', '2404:8000:1014:670:6189:5809:d360:6898', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-12 16:02:56', '2026-06-11 23:02:56', NULL),
(16, 3, '67a04c74eb303841a670a6b0911eaac87bf452a0264bafff39f18ef091b82f81', '38.81.163.37', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 16:17:55', '2026-06-11 23:17:55', '2026-05-12 16:21:04'),
(17, 3, '5f0940a771c86ca7d377e3a3ba35c2b3d736f091bd63811567848ead6f4040dd', '38.81.163.37', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 16:42:01', '2026-06-11 23:42:01', NULL),
(19, 2, '1566a9d2ff682aac9540f30e372bbcd483b9684157c6377a637087003335a9e8', '2001:448a:5130:591d:15dc:3593:2c9b:17bd', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 18:02:41', '2026-06-12 01:02:41', '2026-05-12 18:02:56'),
(20, 2, '400102b445ceaa73c021820d51760a565620c934e3f1003d058cb05621b32618', '2001:448a:5130:591d:15dc:3593:2c9b:17bd', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 18:12:39', '2026-06-12 01:12:39', '2026-05-12 18:12:59'),
(21, 2, '8ca3952f569ab690dc5d2f2814f3017f1fd314d10ab8c4471e9813274c0ed746', '2001:448a:5130:591d:15dc:3593:2c9b:17bd', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 18:17:57', '2026-06-12 01:17:57', '2026-05-12 18:18:26'),
(22, 2, '733a58da978ebcc0d02732414f54db5a1a4570b3c4b636e4790ef9fc9dea7831', '2001:448a:5130:591d:15dc:3593:2c9b:17bd', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 18:18:57', '2026-06-12 01:18:57', '2026-05-12 18:54:53'),
(23, 2, '4749cb57345eb82f394fae977b3ab69e2373efee67b0b13681ed39e662095885', '2001:448a:5130:591d:4019:9a5e:fd26:9637', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 18:55:21', '2026-06-12 01:55:21', '2026-05-12 18:58:10'),
(24, 2, 'c15837bbd404163982fc829f415d14035a8b15f039dc909dc11a0d8dd15b1ea2', '2001:448a:5130:591d:4019:9a5e:fd26:9637', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-12 18:58:35', '2026-06-12 01:58:35', '2026-05-13 03:08:16'),
(25, 9, 'dac4957e5fa4346760a0a22e2e189ce205e6f3138f3bdca3a053f288bb6e0d8d', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-05-12 23:54:01', '2026-06-12 06:54:01', '2026-05-13 00:04:07'),
(26, 9, 'ecf2d5b4815204c9bfd746bcd63e384247397049baa70cef513a1807a9bfc2c2', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-05-13 00:04:36', '2026-06-12 07:04:36', NULL),
(27, 6, '7e519c3809c9a39b8b54997e46a22f151519bc5335df1b8dce954eae695ce3ce', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-05-13 00:12:52', '2026-06-12 07:12:52', '2026-05-13 00:17:08'),
(28, 6, '841591936ccc4610dd38d11232f7d18a54805a4bee900a1e765b2e297e561a15', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-05-13 00:44:52', '2026-06-12 07:44:52', '2026-05-13 00:45:08'),
(29, 6, '2874a00cb50024f598264c18b5435a7bf9cb7129516838ee1517f3d0b6de804e', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-05-13 00:46:02', '2026-06-12 07:46:02', '2026-05-13 00:48:08'),
(30, 6, 'd002cfc55f1b3f02304314e1e3bc93726284d6c9a02d795bcc041d300471e54e', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-05-13 00:48:31', '2026-06-12 07:48:31', '2026-05-13 00:50:45'),
(31, 6, '70d0ee22bb4d4ba044457fa7a2d2a6e86518d84da6efb5b90f234f9357c23570', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-05-13 00:52:38', '2026-06-12 07:52:38', NULL),
(32, 8, '748075c003023b924d7c762f0808b74cfd9e2cd6096772ad81d52144907ed129', '2400:9800:702:c047:1e85:9457:100c:c89b', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-13 01:16:50', '2026-06-12 08:16:50', NULL),
(33, 12, '1dd19a6a6f7c1ac0342bbb4e11a155d5b107f750e67321bd1878ef5bc006258c', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-13 01:23:38', '2026-06-12 08:23:38', NULL),
(34, 2, 'b9cbf7a2454208682301f31ddf33db9424fe20f5e4a1ada58865f27c8f323fb9', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 02:54:09', '2026-06-12 09:54:09', '2026-05-13 03:08:16'),
(35, 1, '1902e5fc880d7cc3b687f459639580b9f73ce6a3180447fd61e4075d2ed7d8e3', '2404:c0:3075:cb4:90e1:7cf7:437f:62df', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-05-13 02:56:07', '2026-06-13 13:04:20', NULL),
(36, 2, '2cb008abad4c6004458acf213c088801b6f4d313d2ffd671950964f61688dc64', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 03:08:59', '2026-06-12 11:29:38', NULL),
(37, 2, 'ba522a5bfd2598918110ab577795b5fd1d15dd06f3ae7b60d367c739ea6c4274', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 03:10:29', '2026-06-12 10:10:29', NULL),
(38, 8, '8e6ee08ea768a1f24bd1eb80e096c4c7c267d5b6f9a600a32a21dcb6e5ea723e', '103.109.209.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 04:27:54', '2026-06-12 11:27:54', NULL);

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
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `status_history`
--

INSERT INTO `status_history` (`id_history`, `id_pengajuan`, `id_anggota`, `status_lama`, `status_baru`, `tanggal_perubahan`, `catatan`) VALUES
(8, 7, 5, 'Menunggu', 'Menunggu', '2026-05-13 01:09:52', NULL),
(9, 9, 1, 'Menunggu', 'Diproses', '2026-05-13 03:13:26', NULL),
(10, 9, 1, 'Diproses', 'Selesai', '2026-05-13 03:13:34', NULL),
(11, 7, 8, 'Menunggu', 'Selesai', '2026-05-13 04:28:37', NULL);

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

--
-- Dumping data untuk tabel `status_history_ruangan`
--

INSERT INTO `status_history_ruangan` (`id_history`, `id_peminjaman`, `id_anggota`, `status_lama`, `status_baru`, `tanggal_perubahan`, `catatan`) VALUES
(1, 1, 1, 'Menunggu', 'Disetujui', '2026-05-06 08:44:00', 'Disetujui oleh Ketua HMJ'),
(2, 3, 1, 'Menunggu', 'Disetujui', '2026-05-06 08:44:00', 'Disetujui untuk persiapan LKTI');

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
(9, 9, '001/HMJ-TI/V/2026', 'uploads/surat/surat_6a03ec06ac546_MINGGU_12-2_D_E41251215_FAJARMUHARRAM.pdf');

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
-- Stand-in struktur untuk tampilan `v_laporan_keuangan`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_laporan_keuangan` (
`tipe` varchar(11)
,`id_transaksi` int(11)
,`kode` varchar(20)
,`tanggal` date
,`jumlah` decimal(15,2)
,`status` varchar(20)
,`nama_kategori` varchar(100)
,`judul_kegiatan` varchar(200)
,`dicatat_oleh` varchar(150)
);

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
  ADD UNIQUE KEY `kode_pemasukan` (`kode_pemasukan`),
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
  ADD UNIQUE KEY `kode_pengeluaran` (`kode_pengeluaran`),
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
  MODIFY `id_anggota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `anggota_periode`
--
ALTER TABLE `anggota_periode`
  MODIFY `id_anggota_periode` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `bukti_kegiatan`
--
ALTER TABLE `bukti_kegiatan`
  MODIFY `id_bukti` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `divisi`
--
ALTER TABLE `divisi`
  MODIFY `id_divisi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `draft_surat`
--
ALTER TABLE `draft_surat`
  MODIFY `id_draft` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
  MODIFY `id_kegiatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id_notifikasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=634;

--
-- AUTO_INCREMENT untuk tabel `password`
--
ALTER TABLE `password`
  MODIFY `id_password` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `pemasukan`
--
ALTER TABLE `pemasukan`
  MODIFY `id_pemasukan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `peminjam`
--
ALTER TABLE `peminjam`
  MODIFY `id_peminjam` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `peminjaman_ruangan`
--
ALTER TABLE `peminjaman_ruangan`
  MODIFY `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `pengajuan_surat`
--
ALTER TABLE `pengajuan_surat`
  MODIFY `id_pengajuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `id_pengeluaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
  MODIFY `id_ruangan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `sesi_login`
--
ALTER TABLE `sesi_login`
  MODIFY `id_sesi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT untuk tabel `status_history`
--
ALTER TABLE `status_history`
  MODIFY `id_history` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
  MODIFY `id_surat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
-- Struktur untuk view `v_laporan_keuangan`
--
DROP TABLE IF EXISTS `v_laporan_keuangan`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u458429422_Nakama_HMJTI`@`127.0.0.1` SQL SECURITY DEFINER VIEW `v_laporan_keuangan`  AS SELECT 'pemasukan' AS `tipe`, `pm`.`id_pemasukan` AS `id_transaksi`, `pm`.`kode_pemasukan` AS `kode`, `pm`.`tanggal` AS `tanggal`, `pm`.`jumlah` AS `jumlah`, `pm`.`status` AS `status`, `k`.`nama_kategori` AS `nama_kategori`, `kg`.`judul` AS `judul_kegiatan`, `a`.`nama_lengkap` AS `dicatat_oleh` FROM (((`pemasukan` `pm` join `kategori` `k` on(`pm`.`id_kategori` = `k`.`id_kategori`)) join `anggota` `a` on(`pm`.`id_anggota` = `a`.`id_anggota`)) left join `kegiatan` `kg` on(`pm`.`id_kegiatan` = `kg`.`id_kegiatan`))union all select 'pengeluaran' AS `tipe`,`pk`.`id_pengeluaran` AS `id_transaksi`,`pk`.`kode_pengeluaran` AS `kode`,`pk`.`tanggal` AS `tanggal`,`pk`.`jumlah` AS `jumlah`,`pk`.`status` AS `status`,`k`.`nama_kategori` AS `nama_kategori`,`kg`.`judul` AS `judul_kegiatan`,`a`.`nama_lengkap` AS `dicatat_oleh` from (((`pengeluaran` `pk` join `kategori` `k` on(`pk`.`id_kategori` = `k`.`id_kategori`)) join `anggota` `a` on(`pk`.`id_anggota` = `a`.`id_anggota`)) left join `kegiatan` `kg` on(`pk`.`id_kegiatan` = `kg`.`id_kegiatan`)) order by `tanggal` desc  ;

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
