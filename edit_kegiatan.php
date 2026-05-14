<?php
require_once 'includes/session_config.php';
require_once 'classes/Database.php';

if (!isset($_SESSION['user'])) {
  header("location:login.php");
  exit;
}

$db = new Database();
$pdo = $db->pdo;

$id_kegiatan = $_GET['id'] ?? null;
if (!$id_kegiatan) {
  header("Location: kegiatan.php");
  exit;
}

$stmt = $pdo->prepare("SELECT * FROM kegiatan WHERE id_kegiatan = ?");
$stmt->execute([$id_kegiatan]);
$kegiatan = $stmt->fetch();

if (!$kegiatan) {
  $_SESSION['error'] = "Kegiatan tidak ditemukan.";
  header("Location: kegiatan.php");
  exit;
}

// Fetch Ketua (Penanggung Jawab) only - exact match 'Ketua' only
$stmtAnggota = $pdo->prepare("
    SELECT DISTINCT a.id_anggota, a.nama_lengkap, a.nim
    FROM anggota a
    INNER JOIN anggota_periode ap ON a.id_anggota = ap.id_anggota
    INNER JOIN jabatan j ON ap.id_jabatan = j.id_jabatan
    WHERE j.nama_jabatan IN ('Ketua', 'Sekretaris')
    ORDER BY a.nama_lengkap ASC
");
$stmtAnggota->execute();
$anggotaList = $stmtAnggota->fetchAll();

$stmtBukti = $pdo->prepare("SELECT id_bukti, file_bukti FROM bukti_kegiatan WHERE id_kegiatan = ? ORDER BY id_bukti ASC");
$stmtBukti->execute([$id_kegiatan]);
$buktiList = $stmtBukti->fetchAll();
?>
<?php include 'partials/header.php'; ?>
<?php include 'partials/navbar.php'; ?>
<?php include 'partials/sidebar.php'; ?>

<div id="main-content" class="h-full w-full bg-gray-50 relative overflow-y-auto lg:ml-64">
  <main>
    <div class="pt-6 px-4">
      <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Kegiatan</h1>
        <a href="kegiatan.php"
          class="text-white bg-gray-600 hover:bg-gray-700 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
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
        <form action="actions/kegiatan_action.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="action" value="edit">
          <input type="hidden" name="id_kegiatan" value="<?= htmlspecialchars($kegiatan['id_kegiatan']) ?>">

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="col-span-1 md:col-span-2">
              <label class="block mb-2 text-sm font-medium text-gray-900">Judul Kegiatan *</label>
              <input type="text" name="judul" value="<?= htmlspecialchars($kegiatan['judul']) ?>" required
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
            </div>

            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Waktu Mulai</label>
              <input type="datetime-local" name="waktu_mulai"
                value="<?= $kegiatan['waktu_mulai'] ? date('Y-m-d\TH:i', strtotime($kegiatan['waktu_mulai'])) : '' ?>"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
            </div>
            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Waktu Selesai</label>
              <input type="datetime-local" name="waktu_selesai"
                value="<?= $kegiatan['waktu_selesai'] ? date('Y-m-d\TH:i', strtotime($kegiatan['waktu_selesai'])) : '' ?>"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
            </div>

            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Tempat</label>
              <input type="text" name="tempat" value="<?= htmlspecialchars($kegiatan['tempat'] ?? '') ?>"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
            </div>
            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Status *</label>
              <select name="status" required
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
                <option value="Rencana" <?= ($kegiatan['status'] === 'Rencana') ? 'selected' : '' ?>>Rencana (To Do)
                </option>
                <option value="Berjalan" <?= ($kegiatan['status'] === 'Berjalan') ? 'selected' : '' ?>>Berjalan (In
                  Progress)</option>
                <option value="Selesai" <?= ($kegiatan['status'] === 'Selesai') ? 'selected' : '' ?>>Selesai (Done)
                </option>
              </select>
            </div>

            <div class="col-span-1 md:col-span-2">
              <label class="block mb-2 text-sm font-medium text-gray-900">Deskripsi Lengkap</label>
              <textarea name="deskripsi" rows="3"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"><?= htmlspecialchars($kegiatan['deskripsi'] ?? '') ?></textarea>
            </div>

            <div class="col-span-1 md:col-span-2 mt-4 pt-4 border-t border-gray-200">
              <h3 class="text-lg font-bold text-gray-900 mb-4">Tim & Dokumentasi</h3>
            </div>

            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Penanggung Jawab *</label>
              <select name="id_anggota" required
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
                <option value="">-- Pilih Ketua / Sekretaris --</option>
                <?php foreach ($anggotaList as $a): ?>
                  <option value="<?= $a['id_anggota'] ?>" <?= ($a['id_anggota'] == $kegiatan['id_anggota']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($a['nama_lengkap'] . ' (' . $a['nim'] . ')') ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Ketua Pelaksana (Label)</label>
              <input type="text" name="penanggung_jawab"
                value="<?= htmlspecialchars($kegiatan['penanggung_jawab'] ?? '') ?>"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
            </div>

            <div class="col-span-1 md:col-span-2">
              <label class="block mb-2 text-sm font-medium text-gray-900">Foto Bukti Saat Ini</label>
              <?php if (count($buktiList) > 0): ?>
                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-4">
                  <?php foreach ($buktiList as $b): ?>
                    <div
                      class="relative group rounded-lg overflow-hidden border border-gray-200 bg-gray-100 flex items-center justify-center p-1">
                      <img src="<?= htmlspecialchars($b['file_bukti']) ?>" class="w-full h-24 object-contain">
                      <a href="actions/kegiatan_action.php?action=hapus_foto&id_bukti=<?= $b['id_bukti'] ?>&id_kegiatan=<?= $kegiatan['id_kegiatan'] ?>"
                        onclick="return confirm('Hapus foto ini?')"
                        class="absolute top-1 right-1 bg-red-600 text-white p-1 rounded hover:bg-red-700 text-xs shadow-sm opacity-80 hover:opacity-100 transition-opacity">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                          </path>
                        </svg>
                      </a>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php else: ?>
                <p
                  class="text-sm text-gray-500 italic border border-dashed border-gray-300 p-4 rounded bg-gray-50 text-center">
                  Belum ada foto yang diunggah.</p>
              <?php endif; ?>
            </div>

            <div class="col-span-1 md:col-span-2 mt-4">
              <label class="block mb-2 text-sm font-medium text-gray-900">Tambahkan Foto Baru</label>
              <p class="text-xs text-gray-500 mb-2">Pilih lebih dari 1 foto jika diperlukan. Gambar akan diubah
                ukurannya menjadi 250x250 pixels.</p>
              <input type="file" name="bukti[]" multiple accept="image/*"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5 cursor-pointer">
            </div>
          </div>

          <div class="mt-8 flex items-center justify-end">
            <button type="submit"
              class="text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm px-6 py-3 text-center transition-colors">
              Simpan Perubahan
            </button>
          </div>
        </form>
      </div>
    </div>
  </main>
  <?php include 'includes/footer.php'; ?>