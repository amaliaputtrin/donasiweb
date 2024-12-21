<?php
include '../template/header.php';
include '../template/navbar.php'; 
include '../template/sidebar.php'; 

// Include database connection
require_once '../db/db.php';

// Cek apakah ada id_donasi di URL untuk operasi Update
$isUpdate = isset($_GET['id']);
$donasiData = null;

if ($isUpdate) {
    $id_donasi = $_GET['id'];
    
    // Ambil data donasi berdasarkan id
    $sql = "SELECT * FROM donasi WHERE id_donasi = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $id_donasi]);
    $donasiData = $stmt->fetch();
}

?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Kelola Donasi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item">Donasi</li>
                <li class="breadcrumb-item active">Kelola</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <!-- Form untuk Create atau Update -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $isUpdate ? 'Update' : 'Tambah'; ?> Donasi</h5>

                        <!-- Form -->
                        <form enctype="multipart/form-data" method="POST" action="controller.php">
                            <input type="hidden" name="action"
                                value="<?php echo $isUpdate ? 'update-donasi' : 'add-donasi'; ?>">
                            <input type="hidden" name="id_donasi"
                                value="<?php echo $isUpdate ? $donasiData['id_donasi'] : ''; ?>">

                            <!-- Judul -->
                            <div class="mb-3">
                                <label for="judul" class="form-label">Judul</label>
                                <input type="text" class="form-control" id="judul" name="judul"
                                    value="<?php echo $isUpdate ? $donasiData['judul'] : ''; ?>" required>
                            </div>

                            <!-- Kategori -->
                            <div class="mb-3">
                                <label for="kategori" class="form-label">Kategori</label>
                                <select class="form-select" id="kategori" name="kategori" required>
                                    <option value="">--- Pilih Kategori ---</option>
                                    <option value="Bencana"
                                        <?php echo ($isUpdate && $donasiData['kategori'] == 'Bencana') ? 'selected' : ''; ?>>
                                        Bencana</option>
                                    <option value="Kesehatan"
                                        <?php echo ($isUpdate && $donasiData['kategori'] == 'Kesehatan') ? 'selected' : ''; ?>>
                                        Kesehatan</option>
                                    <option value="Edukasi"
                                        <?php echo ($isUpdate && $donasiData['kategori'] == 'Edukasi') ? 'selected' : ''; ?>>
                                        Edukasi</option>
                                    <option value="Panti Asuhan"
                                        <?php echo ($isUpdate && $donasiData['kategori'] == 'Panti Asuhan') ? 'selected' : ''; ?>>
                                        Panti Asuhan</option>
                                    <option value="Difabel/Disabilitas"
                                        <?php echo ($isUpdate && $donasiData['kategori'] == 'Difabel/Disabilitas') ? 'selected' : ''; ?>>
                                        Difabel/Disabilitas</option>
                                    <option value="Lingkungan"
                                        <?php echo ($isUpdate && $donasiData['kategori'] == 'Lingkungan') ? 'selected' : ''; ?>>
                                        Lingkungan</option>
                                </select>
                            </div>

                            <!-- Target -->
                            <div class="mb-3">
                                <label for="target" class="form-label">Target</label>
                                <input type="number" class="form-control" id="target" name="target" min="0"
                                    value="<?php echo $isUpdate ? $donasiData['target'] : ''; ?>" required>
                            </div>

                            <!-- Lokasi -->
                            <div class="mb-3">
                                <label for="lokasi" class="form-label">Lokasi</label>
                                <input type="text" class="form-control" id="lokasi" name="lokasi"
                                    value="<?php echo $isUpdate ? $donasiData['lokasi'] : ''; ?>" required>
                            </div>

                            <!-- Tanggal Tenggat -->
                            <div class="mb-3">
                                <label for="tanggal_tenggat" class="form-label">Tanggal Tenggat</label>
                                <input type="date" class="form-control" id="tanggal_tenggat" name="tanggal_tenggat"
                                    value="<?php echo $isUpdate ? $donasiData['tanggal_tenggat'] : ''; ?>"
                                    min="<?php echo date('Y-m-d'); ?>" required>
                            </div>

                            <!-- ID Bank (Select) -->
                            <div class="mb-3">
                                <label for="id_bank" class="form-label">Bank</label>
                                <select class="form-select" id="id_bank" name="id_bank" required>
                                    <option value="">--- Pilih Bank ---</option>
                                    <?php
                                    // Ambil data bank dari tabel bank
                                    $sql = "SELECT * FROM bank";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute();
                                    $banks = $stmt->fetchAll();

                                    // Tampilkan semua bank dalam pilihan
                                    foreach ($banks as $bank) {
                                        $selected = $isUpdate && $donasiData['id_bank'] == $bank['id'] ? 'selected' : '';
                                        echo "<option value='{$bank['id']}' {$selected}>{$bank['payment']}:
                                        {$bank['no_rekening']}-{$bank['nama_akun']}
                                        </option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- Keterangan -->
                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3"
                                    required><?php echo $isUpdate ? $donasiData['keterangan'] : ''; ?></textarea>
                            </div>

                            <!-- Status -->
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="1"
                                        <?php echo $isUpdate && $donasiData['status'] == 1 ? 'selected' : ''; ?>>Aktif
                                    </option>
                                    <option value="0"
                                        <?php echo $isUpdate && $donasiData['status'] == 0 ? 'selected' : ''; ?>>Tidak
                                        Aktif</option>
                                </select>
                            </div>

                            <!-- Gambar -->
                            <div class="mb-3">
                                <label for="gambar" class="form-label">Upload Gambar</label>
                                <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*"
                                    <?php echo $isUpdate ? '' : 'required'; ?>>
                                <?php if ($isUpdate && $donasiData['gambar']): ?>
                                <p class="mt-3">Gambar Sebelumnya: <img src="<?php echo $donasiData['gambar']; ?>"
                                        alt="Gambar Donasi" width="100"></p>
                                <?php endif; ?>
                            </div>

                            <button type="submit"
                                class="btn btn-primary"><?php echo $isUpdate ? 'Update' : 'Simpan'; ?></button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>

</main><!-- End #main -->

<?php include '../template/footer.php'; ?>