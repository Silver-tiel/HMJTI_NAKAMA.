<?php
require_once __DIR__ . '/../includes/session_config.php';
require_once __DIR__ . '/../classes/Database.php';

if (!isset($_SESSION['user'])) {
    header("location:../login.php");
    exit;
}

$user = $_SESSION['user'];
$role = strtolower($user['role_derived'] ?? 'anggota');
if (!in_array($role, ['ketua', 'sekretaris', 'bendahara'])) {
    die("Akses ditolak.");
}

$db = new Database();

$pemasukan = $db->pdo->query("
    SELECT p.tanggal, p.sumber_dana, a.nama_lengkap, p.jumlah, p.status 
    FROM pemasukan p
    JOIN anggota a ON p.id_anggota = a.id_anggota
    WHERE p.status IN ('Berhasil', 'Disetujui')
    ORDER BY p.tanggal ASC
")->fetchAll(PDO::FETCH_ASSOC);

$pengeluaran = $db->pdo->query("
    SELECT px.tanggal, px.penerima, a.nama_lengkap, px.jumlah, px.status 
    FROM pengeluaran px
    JOIN anggota a ON px.id_anggota = a.id_anggota
    WHERE px.status IN ('Berhasil', 'Disetujui')
    ORDER BY px.tanggal ASC
")->fetchAll(PDO::FETCH_ASSOC);

$totalP  = array_sum(array_column($pemasukan,  'jumlah'));
$totalPx = array_sum(array_column($pengeluaran,'jumlah'));
$namaUser = $user['nama_lengkap'] ?? 'Admin';

// Tanggal → format d-m-Y agar Excel baca sebagai teks
function tgl($t) {
    if (!$t) return '-';
    return date('d-m-Y', strtotime($t));
}

// Jumlah → integer murni, biarkan Excel format sendiri
function jml($n) { return number_format((int)$n, 0, ',', '.'); }

function esc($s) { return htmlspecialchars($s ?? '', ENT_XML1 | ENT_QUOTES); }

$filename = 'Laporan_Keuangan_' . date('Y-m-d_H-i-s') . '.xls';
header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:x="urn:schemas-microsoft-com:office:excel"
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta charset="UTF-8">
<!--[if gte mso 9]><xml>
<x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>
<x:Name>Laporan Keuangan</x:Name>
<x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions>
</x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook>
</xml><![endif]-->
<style>
    td, th  { font-family: Arial; font-size: 11pt; }
    .judul  { font-size: 14pt; font-weight: bold; }
    .bold   { font-weight: bold; }
    .italic { font-style: italic; font-size: 9pt; color: #555555; }
    .center { text-align: center; }
    .right  { text-align: right; }
    /* Paksa kolom dibaca sebagai teks — mencegah konversi tanggal & angka */
    .teks   { mso-number-format:"\@"; }
    /* Format angka ribuan tanpa desimal */
    .angka  { mso-number-format:"\#\,\#\#\#\,\#\#0"; text-align: right; }
</style>
</head>
<body>
<table>
<?php
// ===== JUDUL =====
echo "<tr><td colspan='6' class='judul'>LAPORAN KEUANGAN HMJ TI NAKAMA</td></tr>\n";
echo "<tr><td colspan='6'>Periode: " . date('d-m-Y') . "</td></tr>\n";
echo "<tr><td colspan='6'></td></tr>\n";

// ===== PEMASUKAN =====
echo "<tr><td colspan='6' class='bold'>DETAIL PEMASUKAN</td></tr>\n";
echo "<tr>
    <th class='bold center'>NO.</th>
    <th class='bold'>TANGGAL</th>
    <th class='bold'>SUMBER DANA</th>
    <th class='bold'>NAMA PENCATAT</th>
    <th class='bold right'>JUMLAH (Rp)</th>
    <th class='bold'>STATUS</th>
</tr>\n";

if (empty($pemasukan)) {
    echo "<tr><td colspan='6' class='center'>Tidak ada data</td></tr>\n";
} else {
    $no = 1;
    foreach ($pemasukan as $r) {
        echo "<tr>
            <td class='center teks'>{$no}</td>
            <td class='teks'>" . esc(tgl($r['tanggal'])) . "</td>
            <td class='teks'>" . esc($r['sumber_dana']) . "</td>
            <td class='teks'>" . esc($r['nama_lengkap']) . "</td>
            <td x:str style='text-align:right'>" . jml($r['jumlah']) . "</td>
            <td class='teks'>" . esc($r['status']) . "</td>
        </tr>\n";
        $no++;
    }
}

echo "<tr>
    <td></td><td></td><td></td>
    <td class='bold right'>TOTAL PEMASUKAN</td>
    <td x:str style='font-weight:bold;text-align:right'>" . jml($totalP) . "</td>
    <td></td>
</tr>\n";
echo "<tr><td colspan='6'></td></tr>\n";
echo "<tr><td colspan='6'></td></tr>\n";

// ===== PENGELUARAN =====
echo "<tr><td colspan='6' class='bold'>DETAIL PENGELUARAN</td></tr>\n";
echo "<tr>
    <th class='bold center'>NO.</th>
    <th class='bold'>TANGGAL</th>
    <th class='bold'>PENERIMA</th>
    <th class='bold'>NAMA PENCATAT</th>
    <th class='bold right'>JUMLAH (Rp)</th>
    <th class='bold'>STATUS</th>
</tr>\n";

if (empty($pengeluaran)) {
    echo "<tr><td colspan='6' class='center'>Tidak ada data</td></tr>\n";
} else {
    $no = 1;
    foreach ($pengeluaran as $r) {
        echo "<tr>
            <td class='center teks'>{$no}</td>
            <td class='teks'>" . esc(tgl($r['tanggal'])) . "</td>
            <td class='teks'>" . esc($r['penerima']) . "</td>
            <td class='teks'>" . esc($r['nama_lengkap']) . "</td>
            <td x:str style='text-align:right'>" . jml($r['jumlah']) . "</td>
            <td class='teks'>" . esc($r['status']) . "</td>
        </tr>\n";
        $no++;
    }
}

echo "<tr>
    <td></td><td></td><td></td>
    <td class='bold right'>TOTAL PENGELUARAN</td>
    <td x:str style='font-weight:bold;text-align:right'>" . jml($totalPx) . "</td>
    <td></td>
</tr>\n";
echo "<tr><td colspan='6'></td></tr>\n";
echo "<tr><td colspan='6'></td></tr>\n";

// ===== RINGKASAN =====
echo "<tr><td colspan='2' class='bold'>RINGKASAN KEUANGAN</td></tr>\n";
echo "<tr><th class='bold'>KETERANGAN</th><th class='bold right'>JUMLAH (Rp)</th></tr>\n";
echo "<tr><td class='teks'>Total Pemasukan</td><td x:str style='text-align:right'>" . jml($totalP) . "</td></tr>\n";
echo "<tr><td class='teks'>Total Pengeluaran</td><td x:str style='text-align:right'>" . jml($totalPx) . "</td></tr>\n";
echo "<tr><td class='bold teks'>SALDO AKHIR</td><td x:str style='font-weight:bold;text-align:right'>" . jml($totalP - $totalPx) . "</td></tr>\n";
echo "<tr><td colspan='6'></td></tr>\n";
echo "<tr><td colspan='6' class='italic'>Dicetak pada: " . date('d-m-Y H:i:s') . "</td></tr>\n";
echo "<tr><td colspan='6' class='italic'>Oleh: " . esc($namaUser) . "</td></tr>\n";
?>
</table>
</body>
</html>