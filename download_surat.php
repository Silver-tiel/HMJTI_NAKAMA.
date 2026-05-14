<?php
require_once 'includes/session_config.php';
require_once 'classes/Database.php';

if (!isset($_SESSION['user'])) {
    header("location:login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id_pengajuan = (int)$_GET['id'];
    $db = new Database();

    $query = "SELECT ds.file as file_draft, ds.deskripsi_kegiatan
              FROM pengajuan_surat ps 
              JOIN surat s ON ps.id_surat = s.id_surat 
              JOIN draft_surat ds ON s.id_draft = ds.id_draft 
              WHERE ps.id_pengajuan = ?";

    $stmt = $db->conn->prepare($query);
    $stmt->bind_param("i", $id_pengajuan);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();

    if ($data) {
        // Coba path relatif dari root dulu, fallback ke path absolut
        $filepath = __DIR__ . '/' . ltrim($data['file_draft'], '/');

        if (!file_exists($filepath)) {
            // Fallback: coba path apa adanya dari DB
            $filepath = $data['file_draft'];
        }

        if (file_exists($filepath)) {
            $ekstensi = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));

            // Tentukan MIME type yang tepat sesuai ekstensi
            $mimeTypes = [
                'pdf'  => 'application/pdf',
                'doc'  => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'xls'  => 'application/vnd.ms-excel',
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'ppt'  => 'application/vnd.ms-powerpoint',
                'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            ];
            $mimeType = $mimeTypes[$ekstensi] ?? 'application/octet-stream';

            $nama_file_download = "Draft_Surat_" . preg_replace('/[^A-Za-z0-9\-]/', '_', $data['deskripsi_kegiatan']) . "." . $ekstensi;

            // Pastikan tidak ada output sebelum header
            if (ob_get_length()) ob_clean();

            header('Content-Description: File Transfer');
            header('Content-Type: ' . $mimeType);
            header('Content-Disposition: attachment; filename="' . $nama_file_download . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filepath));
            flush();

            readfile($filepath);
            exit;
        } else {
            echo "<script>alert('Error: File fisik tidak ditemukan di server.\nPath: " . addslashes($data['file_draft']) . "'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Error: Data surat tidak ditemukan.'); window.history.back();</script>";
    }
}
?>