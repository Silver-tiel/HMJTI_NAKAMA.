<?php
require_once 'includes/session_config.php';
require_once 'classes/Database.php';

if (!isset($_SESSION['user'])) {
    header("location:login.php");
    exit;
}

$db  = new Database();
$pdo = $db->pdo;

// Hitung saldo kas terkini
$saldoQuery = $pdo->query("
    SELECT 
        (SELECT COALESCE(SUM(jumlah), 0) FROM pemasukan  WHERE status IN ('Berhasil','Disetujui')) -
        (SELECT COALESCE(SUM(jumlah), 0) FROM pengeluaran WHERE status IN ('Berhasil','Disetujui'))
    AS saldo
");
$saldo = (float)($saldoQuery->fetch(PDO::FETCH_ASSOC)['saldo'] ?? 0);

// Fetch Anggota
$anggotaList = $pdo->query(
    "SELECT id_anggota, nama_lengkap FROM anggota ORDER BY nama_lengkap ASC"
)->fetchAll();

// Fetch Kategori Pengeluaran
$kategoriList = $pdo->query(
    "SELECT id_kategori, nama_kategori FROM kategori WHERE jenis = 'Pengeluaran' ORDER BY nama_kategori ASC"
)->fetchAll();
?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<div id="main-content" class="h-full w-full bg-gray-50 relative overflow-y-auto lg:ml-64">
  <main>
    <div class="pt-6 px-4">
      <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Tambah Pengeluaran</h1>
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
      <div class="p-4 mb-4 rounded-lg border flex items-center gap-3"
           style="background:#f0fdf4; border-color:#86efac;">
        <span style="font-size:1.4rem;">💰</span>
        <div>
          <p class="text-xs text-gray-500 font-medium">Saldo Kas Tersedia</p>
          <p class="text-green-700 font-bold text-lg">Rp <?= number_format($saldo, 0, ',', '.') ?></p>
        </div>
      </div>

      <!-- Peringatan saldo tidak cukup (awalnya tersembunyi) -->
      <div id="warnSaldo"
           class="p-4 mb-4 text-sm font-semibold rounded-lg border border-red-300 bg-red-50 text-red-700 hidden"
           role="alert">
        ⚠ Jumlah pengeluaran melebihi saldo kas! Status tidak dapat disetujui.
        Pengeluaran akan disimpan dengan status <strong>Menunggu</strong>.
      </div>

      <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8">
        <form action="actions/keuangan_action.php" method="POST" id="formTambahPengeluaran">
          <input type="hidden" name="action" value="tambah_pengeluaran">

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
              <label class="block mb-2 text-sm font-medium text-gray-900">Kode Pengeluaran *</label>
              <input type="text" name="kode_pengeluaran" required placeholder="Contoh: PKL-2025-001"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
            </div>

            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Jumlah (Rp) *</label>
              <input type="number" name="jumlah" id="inputJumlah" required min="0" step="0.01"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                oninput="cekSaldoForm()">
              <!-- Sisa saldo setelah pengeluaran -->
              <p id="sisaSaldo" class="mt-1 text-xs text-gray-500 hidden">
                Sisa saldo setelah transaksi: <span id="nilaiSisa" class="font-semibold"></span>
              </p>
            </div>

            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900">Penerima Dana</label>
              <input type="text" name="penerima" placeholder="Misal: Toko ABC, Budi"
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
              <label class="block mb-2 text-sm font-medium text-gray-900">Status *</label>
              <select name="status" id="selectStatus" required
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                onchange="cekSaldoForm()">
                <option value="Menunggu">Menunggu</option>
                <option value="Disetujui">Disetujui</option>
                <option value="Ditolak">Ditolak</option>
              </select>
              <p class="mt-1 text-xs text-gray-400">
                Pengeluaran dapat disetujui hanya jika jumlah tidak melebihi saldo kas.
              </p>
            </div>

          </div>

          <div class="mt-6">
            <button type="submit" id="btnSimpan"
              class="text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-opacity">
              Simpan Pengeluaran
            </button>
          </div>
        </form>
      </div>
    </div>
  </main>
  <?php include 'includes/footer.php'; ?>
</div>

<script>
const SALDO_KAS = <?= (float)$saldo ?>;

function cekSaldoForm() {
    const jumlah     = parseFloat(document.getElementById('inputJumlah').value) || 0;
    const status     = document.getElementById('selectStatus').value;
    const warn       = document.getElementById('warnSaldo');
    const btnSimpan  = document.getElementById('btnSimpan');
    const sisaEl     = document.getElementById('sisaSaldo');
    const nilaiSisa  = document.getElementById('nilaiSisa');

    const perlu_validasi = (status === 'Disetujui' || status === 'Berhasil');
    const melebihi       = perlu_validasi && (jumlah > SALDO_KAS);

    // Tampilkan sisa saldo
    if (jumlah > 0) {
        const sisa = SALDO_KAS - jumlah;
        sisaEl.classList.remove('hidden');
        nilaiSisa.innerText = 'Rp ' + sisa.toLocaleString('id-ID');
        nilaiSisa.style.color = sisa < 0 ? '#dc2626' : '#16a34a';
    } else {
        sisaEl.classList.add('hidden');
    }

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

// Validasi final saat submit
document.getElementById('formTambahPengeluaran').addEventListener('submit', function(e) {
    const jumlah = parseFloat(document.getElementById('inputJumlah').value) || 0;
    const status = document.getElementById('selectStatus').value;

    if ((status === 'Disetujui' || status === 'Berhasil') && jumlah > SALDO_KAS) {
        e.preventDefault();
        alert(
            '❌ Tidak dapat menyetujui!\n\n' +
            'Jumlah pengeluaran : Rp ' + jumlah.toLocaleString('id-ID') + '\n' +
            'Saldo kas tersedia : Rp ' + SALDO_KAS.toLocaleString('id-ID') + '\n\n' +
            'Gunakan status "Menunggu" dan setujui setelah saldo mencukupi.'
        );
        return false;
    }
});
</script>