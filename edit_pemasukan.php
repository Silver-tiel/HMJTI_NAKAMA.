<?php
require_once 'includes/session_config.php';
require_once 'classes/Database.php';

if (!isset($_SESSION['user'])) {
    header("location:login.php");
    exit;
}

$db = new Database();
$pdo = $db->pdo;

$id_pemasukan = $_GET['id'] ?? null;
if (!$id_pemasukan) {
    header("Location: keuangan.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM pemasukan WHERE id_pemasukan = ?");
$stmt->execute([$id_pemasukan]);
$pemasukan = $stmt->fetch();

if (!$pemasukan) {
    $_SESSION['error'] = "Data pemasukan tidak ditemukan.";
    header("Location: keuangan.php");
    exit;
}

// Fetch Anggota
$stmtAnggota = $pdo->query("SELECT id_anggota, nama_lengkap FROM anggota ORDER BY nama_lengkap ASC");
$anggotaList = $stmtAnggota->fetchAll();

// Fetch Kategori Pemasukan
$stmtKategori = $pdo->query("SELECT id_kategori, nama_kategori FROM kategori WHERE jenis LIKE 'Pemasukan%' ORDER BY nama_kategori ASC");
$kategoriList = $stmtKategori->fetchAll();
?>
<?php include 'partials/header.php'; ?>
<?php include 'partials/navbar.php'; ?>
<?php include 'partials/sidebar.php'; ?>

<div id="main-content" class="h-full w-full bg-gray-50 relative overflow-y-auto lg:ml-64">
  <main>
    <div class="pt-6 px-4">
      <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Pemasukan</h1>
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
          <input type="hidden" name="action" value="edit_pemasukan">
          <input type="hidden" name="id_pemasukan" value="<?= $pemasukan['id_pemasukan'] ?>">
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Pencatat (Bendahara) *</label>
              <select name="id_anggota" required
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
                <option value="">Pilih Pencatat</option>
                <?php foreach ($anggotaList as $a): ?>
                  <option value="<?= $a['id_anggota'] ?>" <?= $a['id_anggota'] == $pemasukan['id_anggota'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($a['nama_lengkap']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Kategori *</label>
              <select name="id_kategori" required
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
                <option value="">Pilih Kategori</option>
                <?php foreach ($kategoriList as $k): ?>
                  <option value="<?= $k['id_kategori'] ?>" <?= $k['id_kategori'] == $pemasukan['id_kategori'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($k['nama_kategori']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Kode Pemasukan *</label>
              <input type="text" name="kode_pemasukan" required value="<?= htmlspecialchars($pemasukan['kode_pemasukan']) ?>"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
            </div>

            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Jumlah (Rp) *</label>
              <input type="number" name="jumlah" required min="0" step="0.01" value="<?= htmlspecialchars($pemasukan['jumlah']) ?>"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
            </div>

            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Sumber Dana</label>
              <input type="text" name="sumber_dana" value="<?= htmlspecialchars($pemasukan['sumber_dana']) ?>"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
            </div>

            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Tanggal *</label>
              <input type="date" name="tanggal" required value="<?= htmlspecialchars($pemasukan['tanggal']) ?>"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
            </div>

            <div class="md:col-span-2">
              <label class="block mb-2 text-sm font-medium text-gray-900">Nama Kegiatan Terkait</label>
              <input type="text" name="nama_kegiatan" value="<?= htmlspecialchars($pemasukan['nama_kegiatan']) ?>"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
            </div>

            <div class="md:col-span-2">
              <label class="block mb-2 text-sm font-medium text-gray-900">Bukti Pembayaran Baru (Opsional)</label>
              <input type="file" name="bukti_pembayaran" accept="image/*"
                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
              <p class="mt-1 text-xs text-gray-500">Biarkan kosong jika tidak ingin mengubah bukti pembayaran.</p>
              
              <?php if ($pemasukan['bukti_pembayaran']): ?>
                <div class="mt-3">
                  <p class="text-sm font-medium text-gray-700 mb-2">Bukti Saat Ini:</p>
                  <img src="<?= htmlspecialchars($pemasukan['bukti_pembayaran']) ?>" alt="Bukti" class="h-32 object-contain rounded border">
                </div>
              <?php endif; ?>
            </div>

            <div class="md:col-span-2">
              <label class="block mb-2 text-sm font-medium text-gray-900">Status *</label>
              <select name="status" required
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
                <option value="Menunggu" <?= $pemasukan['status'] === 'Menunggu' ? 'selected' : '' ?>>Menunggu</option>
                <option value="Disetujui" <?= $pemasukan['status'] === 'Disetujui' ? 'selected' : '' ?>>Disetujui</option>
                <option value="Ditolak" <?= $pemasukan['status'] === 'Ditolak' ? 'selected' : '' ?>>Ditolak</option>
              </select>
            </div>

          </div>
          <div class="mt-6">
            <button type="submit"
              class="text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
              Simpan Perubahan
            </button>
          </div>
        </form>
      </div>
    </div>
  </main>
<?php include 'includes/footer.php'; ?>
