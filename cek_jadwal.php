<?php
/**
 * cek_jadwal.php
 * Endpoint AJAX — cek konflik jadwal peminjaman ruangan.
 * Letakkan file ini di folder root yang sama dengan peminjaman.php
 * (satu level di atas folder admin/).
 *
 * Request  : GET  ?id_ruangan=1&waktu_mulai=2026-05-03T10:00&waktu_selesai=2026-05-03T12:00
 * Response : JSON { konflik: bool, pesan: string, data: [...] }
 */

require_once __DIR__ . '/includes/session_config.php';
require_once __DIR__ . '/classes/Database.php';

header('Content-Type: application/json');

// Hanya izinkan session yang valid
if (!isset($_SESSION['user'])) {
    echo json_encode(['konflik' => false, 'pesan' => 'Unauthorized']);
    exit;
}

$db = new Database();

$id_ruangan  = intval($_GET['id_ruangan']  ?? 0);
$waktu_mulai = $_GET['waktu_mulai']  ?? '';
$waktu_selesai = $_GET['waktu_selesai'] ?? '';

// Validasi input minimal
if (!$id_ruangan || !$waktu_mulai || !$waktu_selesai) {
    echo json_encode(['konflik' => false, 'pesan' => '']);
    exit;
}

// Konversi format datetime-local → MySQL
$mulai   = str_replace('T', ' ', $waktu_mulai)   . ':00';
$selesai = str_replace('T', ' ', $waktu_selesai) . ':00';

// Validasi urutan waktu
if ($selesai <= $mulai) {
    echo json_encode([
        'konflik' => true,
        'pesan'   => 'Waktu selesai harus setelah waktu mulai.',
        'data'    => []
    ]);
    exit;
}

/**
 * Cek overlap: booking yang SUDAH DISETUJUI atau MASIH MENUNGGU
 * pada ruangan yang sama dalam rentang waktu yang diminta.
 *
 * Overlap terjadi jika:
 *   mulai_baru  < selesai_lama  AND  selesai_baru > mulai_lama
 */
$stmt = $db->conn->prepare("
    SELECT
        pr.kode_peminjaman,
        pr.status,
        dp.waktu_mulai,
        dp.waktu_selesai,
        a.nama_lengkap AS nama_peminjam,
        pr.tujuan_peminjaman
    FROM detail_peminjaman dp
    JOIN peminjaman_ruangan pr ON dp.id_peminjaman = pr.id_peminjaman
    JOIN peminjam pm ON pr.id_peminjam = pm.id_peminjam
    JOIN anggota a   ON pm.id_anggota  = a.id_anggota
    WHERE dp.id_ruangan = ?
      AND pr.status IN ('Menunggu', 'Disetujui')
      AND ? < dp.waktu_selesai
      AND ? > dp.waktu_mulai
    ORDER BY dp.waktu_mulai ASC
    LIMIT 5
");

$stmt->bind_param("iss", $id_ruangan, $mulai, $selesai);
$stmt->execute();
$res = $stmt->get_result();

$konflikData = [];
while ($row = $res->fetch_assoc()) {
    $konflikData[] = [
        'kode'         => $row['kode_peminjaman'],
        'status'       => $row['status'],
        'mulai'        => date('d M Y, H:i', strtotime($row['waktu_mulai'])),
        'selesai'      => date('d M Y, H:i', strtotime($row['waktu_selesai'])),
        'peminjam'     => $row['nama_peminjam'],
        'keperluan'    => $row['tujuan_peminjaman'],
    ];
}

if (count($konflikData) > 0) {
    // Buat pesan ringkas
    $first = $konflikData[0];
    $statusLabel = $first['status'] === 'Disetujui' ? '✅ sudah disetujui' : '⏳ sedang menunggu persetujuan';
    $pesan = "Ruangan sudah dibooking oleh <strong>{$first['peminjam']}</strong> "
           . "({$statusLabel}) pada <strong>{$first['mulai']} – {$first['selesai']}</strong>.";
    if (count($konflikData) > 1) {
        $pesan .= " (+" . (count($konflikData) - 1) . " booking lain bertabrakan)";
    }

    echo json_encode([
        'konflik' => true,
        'pesan'   => $pesan,
        'data'    => $konflikData,
    ]);
} else {
    echo json_encode([
        'konflik' => false,
        'pesan'   => 'Ruangan tersedia pada waktu yang dipilih.',
        'data'    => [],
    ]);
}
