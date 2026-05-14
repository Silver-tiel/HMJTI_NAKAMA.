<?php
require_once 'includes/session_config.php';
require_once 'classes/Database.php';

if (!isset($_SESSION['user'])) {
    header("location:login.php");
    exit;
}

$db = new Database();
$pdo = $db->pdo;

// Fetch Ketua (Penanggung Jawab) only - exact match 'Ketua' only
$stmtAnggota = $pdo->prepare("
    SELECT DISTINCT a.id_anggota, a.nama_lengkap, a.nim
    FROM anggota a
    INNER JOIN anggota_periode ap ON a.id_anggota = ap.id_anggota
    INNER JOIN jabatan j ON ap.id_jabatan = j.id_jabatan
    WHERE j.nama_jabatan = 'Ketua' AND j.id_jabatan = 1
    ORDER BY a.nama_lengkap ASC
");
$stmtAnggota->execute();
$anggotaList = $stmtAnggota->fetchAll();
?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<div id="main-content" class="h-full w-full bg-gray-50 relative overflow-y-auto lg:ml-64">
  <main>
    <div class="pt-6 px-4">
      <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Tambah Kegiatan</h1>
        <a href="kegiatan.php" class="text-white bg-gray-600 hover:bg-gray-700 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
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
          <input type="hidden" name="action" value="tambah">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="col-span-1 md:col-span-2">
              <label class="block mb-2 text-sm font-medium text-gray-900">Judul Kegiatan *</label>
              <input type="text" name="judul" required placeholder="Masukkan judul kegiatan..."
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
            </div>
            
            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Waktu Mulai</label>
              <input type="datetime-local" name="waktu_mulai"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
            </div>
            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Waktu Selesai</label>
              <input type="datetime-local" name="waktu_selesai"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
            </div>

            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Tempat</label>
              <input type="text" name="tempat" placeholder="Contoh: Aula Utama"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
            </div>
            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Status *</label>
              <select name="status" required
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
                <option value="Rencana">Rencana (To Do)</option>
                <option value="Berjalan">Berjalan (In Progress)</option>
                <option value="Selesai">Selesai (Done)</option>
              </select>
            </div>

            <div class="col-span-1 md:col-span-2">
              <label class="block mb-2 text-sm font-medium text-gray-900">Deskripsi Lengkap</label>
              <textarea name="deskripsi" rows="3" placeholder="Jelaskan detail kegiatan..."
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"></textarea>
            </div>

            <div class="col-span-1 md:col-span-2 mt-4 pt-4 border-t border-gray-200">
              <h3 class="text-lg font-bold text-gray-900 mb-4">Tim & Dokumentasi</h3>
            </div>

            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Penanggung Jawab (Ketua) *</label>
              <select name="id_anggota" required
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
                <option value="">-- Pilih Ketua --</option>
                <?php foreach ($anggotaList as $a): ?>
                  <option value="<?= $a['id_anggota'] ?>">
                    <?= htmlspecialchars($a['nama_lengkap'] . ' (' . $a['nim'] . ')') ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Ketua Pelaksana (Label)</label>
              <input type="text" name="penanggung_jawab" placeholder="Contoh: Budi Santoso"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
            </div>

            <div class="col-span-1 md:col-span-2">
              <label class="block mb-2 text-sm font-medium text-gray-900">Bukti / Foto Kegiatan</label>
              <p class="text-xs text-gray-500 mb-2">Anda dapat memilih lebih dari 1 foto sekaligus. Foto pertama akan menjadi cover (thumbnail) di Kanban Board.</p>
              <input type="file" name="bukti[]" multiple accept="image/*"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5 cursor-pointer">
            </div>
          </div>

          <div class="mt-8 flex items-center justify-end">
            <button type="submit"
              class="text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm px-6 py-3 text-center transition-colors">
              Simpan Kegiatan
            </button>
          </div>
        </form>
      </div>
    </div>
  </main>
  <?php include 'includes/footer.php'; ?>
