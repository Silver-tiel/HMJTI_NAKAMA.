<?php
require_once 'includes/session_config.php';
require_once 'classes/Database.php';

if (!isset($_SESSION['user'])) {
    header("location:login.php");
    exit;
}

$db = new Database();
$pdo = $db->pdo;

// Fetch Anggota
$stmtAnggota = $pdo->query("SELECT id_anggota, nama_lengkap FROM anggota ORDER BY nama_lengkap ASC");
$anggotaList = $stmtAnggota->fetchAll();

// Fetch Kategori Pemasukan
$stmtKategori = $pdo->query("SELECT id_kategori, nama_kategori FROM kategori WHERE jenis LIKE 'Pemasukan%' ORDER BY nama_kategori ASC");
$kategoriList = $stmtKategori->fetchAll();
?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<div id="main-content" class="h-full w-full bg-gray-50 relative overflow-y-auto lg:ml-64">
  <main>
    <div class="pt-6 px-4">
      <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Tambah Pemasukan</h1>
        <a href="keuangan.php"
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
        <form action="actions/keuangan_action.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="action" value="tambah_pemasukan">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Pencatat (Bendahara) *</label>
              <select name="id_anggota" required
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
                <option value="">Pilih Pencatat</option>
                <?php foreach ($anggotaList as $a): ?>
                  <option value="<?= $a['id_anggota'] ?>"><?= htmlspecialchars($a['nama_lengkap']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Kategori *</label>
              <select name="id_kategori" required
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
                <option value="">Pilih Kategori</option>
                <?php foreach ($kategoriList as $k): ?>
                  <option value="<?= $k['id_kategori'] ?>"><?= htmlspecialchars($k['nama_kategori']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Kode Pemasukan *</label>
              <input type="text" name="kode_pemasukan" required placeholder="Contoh: PMK-2025-001"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
            </div>

            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Jumlah (Rp) *</label>
              <input type="number" name="jumlah" required min="0" step="0.01"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
            </div>

            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Sumber Dana</label>
              <input type="text" name="sumber_dana" placeholder="Misal: Iuran Kas, Donatur"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
            </div>

            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Tanggal *</label>
              <input type="date" name="tanggal" required value="<?= date('Y-m-d') ?>"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
            </div>

            <div class="md:col-span-2">
              <label class="block mb-2 text-sm font-medium text-gray-900">Nama Kegiatan Terkait</label>
              <input type="text" name="nama_kegiatan" placeholder="Boleh dikosongkan jika tidak terkait kegiatan spesifik"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
            </div>

            <div class="md:col-span-2">
              <label class="block mb-2 text-sm font-medium text-gray-900">Bukti Pembayaran (Gambar)</label>
              <input type="file" name="bukti_pembayaran" accept="image/*"
                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
              <p class="mt-1 text-xs text-gray-500">Format: JPG, JPEG, PNG.</p>
            </div>

            <div class="md:col-span-2">
              <label class="block mb-2 text-sm font-medium text-gray-900">Status *</label>
              <select name="status" required
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
                <option value="Menunggu">Menunggu</option>
                <option value="Disetujui">Disetujui</option>
                <option value="Ditolak">Ditolak</option>
              </select>
            </div>

          </div>
          <div class="mt-6">
            <button type="submit"
              class="text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
              Simpan Pemasukan
            </button>
          </div>
        </form>
      </div>
    </div>
  </main>
<?php include 'includes/footer.php'; ?>
