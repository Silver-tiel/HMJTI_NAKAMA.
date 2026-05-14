<?php
require_once 'includes/session_config.php';
require_once 'classes/Database.php';

if (!isset($_SESSION['user'])) {
    header("location:login.php");
    exit;
}

$db = new Database();
$pdo = $db->pdo;

$id_anggota = $_GET['id'] ?? null;
if (!$id_anggota) {
    header("Location: anggota.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM anggota WHERE id_anggota = ?");
$stmt->execute([$id_anggota]);
$anggota = $stmt->fetch();

if (!$anggota) {
    $_SESSION['error'] = "Anggota tidak ditemukan.";
    header("Location: anggota.php");
    exit;
}

// Fetch Jabatan
$stmtJabatan = $pdo->query("SELECT id_jabatan, nama_jabatan FROM jabatan ORDER BY nama_jabatan ASC");
$jabatanList = $stmtJabatan->fetchAll();

// Fetch Kode Pos
$stmtKodePos = $pdo->query("SELECT kode_pos, kecamatan, kelurahan FROM kode_pos ORDER BY kecamatan, kelurahan ASC");
$kodePosList = $stmtKodePos->fetchAll();
?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<div id="main-content" class="h-full w-full bg-gray-50 relative overflow-y-auto lg:ml-64">
  <main>
    <div class="pt-6 px-4">
      <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Anggota</h1>
        <a href="anggota.php" class="text-white bg-gray-600 hover:bg-gray-700 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
          Kembali
        </a>
      </div>

      <?php if (isset($_SESSION['error'])): ?>
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
          <?= htmlspecialchars($_SESSION['error']) ?>
          <?php unset($_SESSION['error']); ?>
        </div>
      <?php endif; ?>

      <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8">
        <form action="actions/anggota_action.php" method="POST">
          <input type="hidden" name="action" value="edit">
          <input type="hidden" name="id_anggota" value="<?= htmlspecialchars($anggota['id_anggota']) ?>">
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">NIM *</label>
              <input type="text" name="nim" value="<?= htmlspecialchars($anggota['nim']) ?>" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
            </div>
            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Nama Lengkap *</label>
              <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($anggota['nama_lengkap']) ?>" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
            </div>
            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Email</label>
              <input type="email" name="email" value="<?= htmlspecialchars($anggota['email'] ?? '') ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
            </div>
            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">No. Telepon</label>
              <input type="text" name="no_telp" value="<?= htmlspecialchars($anggota['no_telp'] ?? '') ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
            </div>
            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Jabatan *</label>
              <select name="id_jabatan" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
                <option value="">-- Pilih Jabatan --</option>
                <?php foreach ($jabatanList as $j): ?>
                  <option value="<?= $j['id_jabatan'] ?>" <?= ($j['id_jabatan'] == $anggota['id_jabatan']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($j['nama_jabatan']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Status Keanggotaan *</label>
              <select name="status_keanggotaan" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
                <option value="Aktif" <?= ($anggota['status_keanggotaan'] === 'Aktif') ? 'selected' : '' ?>>Aktif</option>
                <option value="Alumni" <?= ($anggota['status_keanggotaan'] === 'Alumni') ? 'selected' : '' ?>>Alumni</option>
              </select>
            </div>
            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Jurusan</label>
              <input type="text" name="jurusan" value="<?= htmlspecialchars($anggota['jurusan'] ?? 'Teknologi Informasi') ?>" readonly
                class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 cursor-not-allowed">
            </div>
            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Program Studi *</label>
              <select name="program_studi" required
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
                <option value="">-- Pilih Program Studi --</option>
                <option value="Teknik Informatika" <?= ($anggota['program_studi'] === 'Teknik Informatika') ? 'selected' : '' ?>>Teknik Informatika</option>
                <option value="Sistem Informasi" <?= ($anggota['program_studi'] === 'Sistem Informasi') ? 'selected' : '' ?>>Sistem Informasi</option>
                <option value="Rekayasa Perangkat Lunak" <?= ($anggota['program_studi'] === 'Rekayasa Perangkat Lunak') ? 'selected' : '' ?>>Rekayasa Perangkat Lunak</option>
              </select>
            </div>
            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Angkatan</label>
              <input type="text" name="angkatan" value="<?= htmlspecialchars($anggota['angkatan'] ?? '') ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
            </div>
            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Kode Pos (Kecamatan/Kelurahan) *</label>
              <select name="kode_pos" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
                <option value="">-- Pilih Lokasi --</option>
                <?php foreach ($kodePosList as $k): ?>
                  <option value="<?= $k['kode_pos'] ?>" <?= ($k['kode_pos'] == $anggota['kode_pos']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($k['kode_pos'] . ' - ' . $k['kecamatan'] . '/' . $k['kelurahan']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="mt-6">
            <button type="submit" class="text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
              Simpan Perubahan
            </button>
          </div>
        </form>
      </div>
    </div>
  </main>
<?php include 'includes/footer.php'; ?>
