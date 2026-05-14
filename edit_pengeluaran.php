<?php
require_once 'includes/session_config.php';
require_once 'classes/Database.php';

if (!isset($_SESSION['user'])) {
    header("location:login.php");
    exit;
}

$db  = new Database();
$pdo = $db->pdo;

$id_pengeluaran = $_GET['id'] ?? null;
if (!$id_pengeluaran) {
    header("Location: keuangan.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM pengeluaran WHERE id_pengeluaran = ?");
$stmt->execute([$id_pengeluaran]);
$pengeluaran = $stmt->fetch();

if (!$pengeluaran) {
    $_SESSION['error'] = "Data pengeluaran tidak ditemukan.";
    header("Location: keuangan.php");
    exit;
}

// Hitung saldo kas terkini
$saldoQuery = $pdo->query("
    SELECT 
        (SELECT COALESCE(SUM(jumlah), 0) FROM pemasukan  WHERE status IN ('Berhasil','Disetujui')) -
        (SELECT COALESCE(SUM(jumlah), 0) FROM pengeluaran WHERE status IN ('Berhasil','Disetujui'))
    AS saldo
");
$saldo = (float)($saldoQuery->fetch(PDO::FETCH_ASSOC)['saldo'] ?? 0);

// Saldo efektif: jika pengeluaran ini sudah Disetujui/Berhasil,
// kembalikan jumlahnya agar tidak double-count saat re-evaluasi.
$statusLama    = $pengeluaran['status'];
$jumlahLama    = (float)$pengeluaran['jumlah'];
$saldoEfektif  = $saldo;
if (in_array($statusLama, ['Disetujui', 'Berhasil'])) {
    $saldoEfektif = $saldo + $jumlahLama;
}

// Fetch Anggota
$anggotaList = $pdo->query(
    "SELECT id_anggota, nama_lengkap FROM anggota ORDER BY nama_lengkap ASC"
)->fetchAll();

// Fetch Kategori Pengeluaran
$kategoriList = $pdo->query(
    "SELECT id_kategori, nama_kategori FROM kategori WHERE jenis = 'Pengeluaran' ORDER BY nama_kategori ASC"
)->fetchAll();
?>
<?php include 'partials/header.php'; ?>
<?php include 'partials/navbar.php'; ?>
<?php include 'partials/sidebar.php'; ?>

<div id="main-content" class="h-full w-full bg-gray-50 relative overflow-y-auto lg:ml-64">
  <main>
    <div class="pt-6 px-4">
      <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Pengeluaran</h1>
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

      <!-- Info Saldo Kas -->
      <div class="p-4 mb-4 rounded-lg border flex items-center gap-4"
           style="background:#f0fdf4; border-color:#86efac;">
        <span style="font-size:1.4rem;">💰</span>
        <div class="flex-1">
          <p class="text-xs text-gray-500 font-medium">Saldo Kas Tersedia (setelah memperhitungkan pengeluaran ini)</p>
          <p class="text-green-700 font-bold text-lg">Rp <?= number_format($saldoEfektif, 0, ',', '.') ?></p>
        </div>
        <div class="text-right">
          <p class="text-xs text-gray-500 font-medium">Jumlah Pengeluaran Ini</p>
          <p class="text-red-600 font-bold text-lg">Rp <?= number_format($jumlahLama, 0, ',', '.') ?></p>
        </div>
      </div>

      <!-- Peringatan jika jumlah melebihi saldo (ditampilkan langsung jika memang melebihi) -->
      <div id="warnSaldo"
           class="p-4 mb-4 text-sm font-semibold rounded-lg border border-red-300 bg-red-50 text-red-700 <?= ($jumlahLama > $saldoEfektif) ? '' : 'hidden' ?>"
           role="alert">
        ⚠ Jumlah pengeluaran (Rp <?= number_format($jumlahLama, 0, ',', '.') ?>) melebihi saldo kas
        (Rp <?= number_format($saldoEfektif, 0, ',', '.') ?>).
        Status tidak dapat diubah menjadi <strong>Disetujui</strong>.
      </div>

      <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8">
        <form action="actions/keuangan_action.php" method="POST" id="formEditPengeluaran">
          <input type="hidden" name="action"         value="edit_pengeluaran">
          <input type="hidden" name="id_pengeluaran" value="<?= $pengeluaran['id_pengeluaran'] ?>">

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Pencatat (Bendahara) *</label>
              <select name="id_anggota" required
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
                <option value="">Pilih Pencatat</option>
                <?php foreach ($anggotaList as $a): ?>
                  <option value="<?= $a['id_anggota'] ?>" <?= $a['id_anggota'] == $pengeluaran['id_anggota'] ? 'selected' : '' ?>>
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
                  <option value="<?= $k['id_kategori'] ?>" <?= $k['id_kategori'] == $pengeluaran['id_kategori'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($k['nama_kategori']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Kode Pengeluaran *</label>
              <input type="text" name="kode_pengeluaran" required
                value="<?= htmlspecialchars($pengeluaran['kode_pengeluaran']) ?>"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
            </div>

            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Jumlah (Rp) *</label>
              <input type="number" name="jumlah" required min="0" step="0.01"
                value="<?= htmlspecialchars($pengeluaran['jumlah']) ?>"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
              <p class="mt-1 text-xs text-gray-400">
                Jumlah tidak dapat diubah melebihi saldo kas tersedia saat disetujui.
              </p>
            </div>

            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Penerima Dana</label>
              <input type="text" name="penerima"
                value="<?= htmlspecialchars($pengeluaran['penerima']) ?>"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
            </div>

            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Tanggal *</label>
              <input type="date" name="tanggal" required
                value="<?= htmlspecialchars($pengeluaran['tanggal']) ?>"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
            </div>

            <div class="md:col-span-2">
              <label class="block mb-2 text-sm font-medium text-gray-900">Nama Kegiatan Terkait</label>
              <input type="text" name="nama_kegiatan"
                value="<?= htmlspecialchars($pengeluaran['nama_kegiatan'] ?? '') ?>"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
            </div>

            <div class="md:col-span-2">
              <label class="block mb-2 text-sm font-medium text-gray-900">Status *</label>
              <select name="status" id="selectStatus" required
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                onchange="cekSaldoEdit()">
                <option value="Menunggu"  <?= $pengeluaran['status'] === 'Menunggu'  ? 'selected' : '' ?>>Menunggu</option>
                <option value="Disetujui" <?= $pengeluaran['status'] === 'Disetujui' ? 'selected' : '' ?>>Disetujui</option>
                <option value="Ditolak"   <?= $pengeluaran['status'] === 'Ditolak'   ? 'selected' : '' ?>>Ditolak</option>
              </select>
              <p class="mt-1 text-xs text-gray-400">
                Pengeluaran dapat disetujui hanya jika jumlah tidak melebihi saldo kas.
              </p>
            </div>

          </div>

          <div class="mt-6">
            <button type="submit" id="btnSimpan"
              class="text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-opacity">
              Simpan Perubahan
            </button>
          </div>
        </form>
      </div>
    </div>
  </main>
  <?php include 'includes/footer.php'; ?>
</div>

<script>
// Saldo efektif sudah dihitung di PHP (memperhitungkan status lama)
const SALDO_EFEKTIF = <?= (float)$saldoEfektif ?>;
const JUMLAH_LAMA   = <?= (float)$jumlahLama ?>;

function cekSaldoEdit() {
    const status    = document.getElementById('selectStatus').value;
    const warn      = document.getElementById('warnSaldo');
    const btnSimpan = document.getElementById('btnSimpan');

    const perlu_validasi = (status === 'Disetujui' || status === 'Berhasil');
    const melebihi       = perlu_validasi && (JUMLAH_LAMA > SALDO_EFEKTIF);

    if (melebihi) {
        warn.classList.remove('hidden');
        btnSimpan.disabled = true;
        btnSimpan.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        warn.classList.add('hidden');
        btnSimpan.disabled = false;
        btnSimpan.classList.remove('opacity-50', 'cursor-not-allowed');
    }
}

// Jalankan saat halaman load (sesuaikan dengan status saat ini)
cekSaldoEdit();

// Validasi final saat submit
document.getElementById('formEditPengeluaran').addEventListener('submit', function(e) {
    const status = document.getElementById('selectStatus').value;

    if ((status === 'Disetujui' || status === 'Berhasil') && JUMLAH_LAMA > SALDO_EFEKTIF) {
        e.preventDefault();
        alert(
            '❌ Tidak dapat menyetujui!\n\n' +
            'Jumlah pengeluaran : Rp ' + JUMLAH_LAMA.toLocaleString('id-ID') + '\n' +
            'Saldo kas tersedia : Rp ' + SALDO_EFEKTIF.toLocaleString('id-ID') + '\n\n' +
            'Pengeluaran melebihi saldo kas yang tersedia.'
        );
        return false;
    }
});
</script>