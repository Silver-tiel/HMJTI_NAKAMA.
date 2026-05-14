<?php
class Auth {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
        if (session_status() == PHP_SESSION_NONE) {
            require_once __DIR__ . '/../includes/session_config.php';
        }
    }

    public function login($identifier, $password) {
        $identifier = trim($identifier);
        $password = trim($password);

        // SELECT * FROM v_login tidak ada kolom email, jadi kita JOIN ke anggota
        $stmt = $this->conn->prepare("
            SELECT v.*, a.email 
            FROM v_login v
            JOIN anggota a ON v.id_anggota = a.id_anggota
            WHERE a.email=? OR v.username=?
        ");
        $stmt->bind_param("ss", $identifier, $identifier);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user'] = $user; 
                return true;
            }
        }
        return false;
    }

    public function register($name, $email, $password) {
        $name = trim($name);
        $email = trim($email);
        $password = trim($password);
        $username = explode('@', $email)[0];

        $check = $this->conn->prepare("SELECT id_anggota FROM anggota WHERE email=?");
        $check->bind_param("s", $email);
        $check->execute();
        if ($check->get_result()->num_rows > 0) return false;

        $this->conn->begin_transaction();
        try {
            // Kita pake id_jabatan 11 (Anggota Administrasi) 
            $id_jabatan_default = 11; 
            $kode_pos_default = '68121';
            $nim_dummy = 'E41' . rand(1000000, 9999999);
            
            // Dapatkan periode aktif
            $resPeriode = $this->conn->query("SELECT MAX(id_periode) as active_periode FROM periode");
            $rowPeriode = $resPeriode->fetch_assoc();
            $id_periode = $rowPeriode['active_periode'] ?? 1;

            $stmt1 = $this->conn->prepare("INSERT INTO anggota (kode_pos, nim, nama_lengkap, email, status_keanggotaan) VALUES (?, ?, ?, ?, 'Aktif')");
            $stmt1->bind_param("ssss", $kode_pos_default, $nim_dummy, $name, $email);
            $stmt1->execute();

            $id_anggota = $this->conn->insert_id;

            // Masukkan ke anggota_periode
            $stmt_ap = $this->conn->prepare("INSERT INTO anggota_periode (id_anggota, id_periode, id_jabatan) VALUES (?, ?, ?)");
            $stmt_ap->bind_param("iii", $id_anggota, $id_periode, $id_jabatan_default);
            $stmt_ap->execute();

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt2 = $this->conn->prepare("INSERT INTO password (id_anggota, username, password) VALUES (?, ?, ?)");
            $stmt2->bind_param("iss", $id_anggota, $username, $hashedPassword);
            $stmt2->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function findByEmail(string $email): ?array {
        $email = trim($email);
        $stmt = $this->conn->prepare("
            SELECT v.*, a.email 
            FROM v_login v
            JOIN anggota a ON v.id_anggota = a.id_anggota
            WHERE a.email = ?
            LIMIT 1
        ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return ($result && $result->num_rows > 0) ? $result->fetch_assoc() : null;
    }
}