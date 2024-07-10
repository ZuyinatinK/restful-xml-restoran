<?php
/**
 * Kelas Database untuk mengelola koneksi dan operasi database.
 */
class Database
{
    // Properti untuk menyimpan informasi koneksi database
    private $host = "localhost";
    private $dbname = "db_restoran";
    private $user = "root";
    private $password = "";
    private $port = "3306";
    private $conn;

    /**
     * Construct untuk membuat koneksi ke database
     */
    public function __construct()
    {
        try {
            // Membuat koneksi PDO ke database
            $this->conn = new PDO("mysql:host=$this->host;port=$this->port;dbname=$this->dbname;charset=utf8", $this->user, $this->password);
        } catch (PDOException $e) {
            // Menangani kesalahan koneksi
            echo "Koneksi gagal: " . $e->getMessage();
        }
    }

    /**
     * Function untuk mengambil semua data dari tabel tb_menu
     * 
     * @return array Semua data dari tabel tb_menu
     */
    public function tampil_semua_data()
    {
        // Menyiapkan dan mengeksekusi query untuk mengambil semua data
        $query = $this->conn->prepare("SELECT id_menu, nama_menu, foto_menu, jenis, kategori, deskripsi, harga, ketersediaan FROM tb_menu ORDER BY id_menu");
        $query->execute();
        // Mengembalikan semua data sebagai array asosiatif
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Function untuk mengambil data berdasarkan id_menu tertentu
     * 
     * @param int $id_menu ID menu yang akan diambil datanya
     * @return array Data dari tabel tb_menu yang sesuai dengan id_menu
     */
    public function tampil_data($id_menu)
    {
        // Menyiapkan dan mengeksekusi query untuk mengambil data berdasarkan id_menu
        $query = $this->conn->prepare("SELECT id_menu, nama_menu, foto_menu, jenis, kategori, deskripsi, harga, ketersediaan FROM tb_menu WHERE id_menu = ?");
        $query->execute([$id_menu]);
        // Mengembalikan data sebagai array asosiatif
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Function untuk menambahkan data baru ke tabel tb_menu
     * 
     * @param array $data Data baru yang akan ditambahkan ke tabel tb_menu
     */
    public function tambah_data($data)
    {
        // Menyiapkan dan mengeksekusi query untuk menambahkan data baru
        $query = $this->conn->prepare("INSERT INTO tb_menu (id_menu, nama_menu, foto_menu, jenis, kategori, deskripsi, harga, ketersediaan) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $query->execute([$data['id_menu'], $data['nama_menu'], $data['foto_menu'], $data['jenis'], $data['kategori'], $data['deskripsi'], $data['harga'], $data['ketersediaan']]);
    }

    /**
     * Function untuk mengubah data yang sudah ada di tabel tb_menu
     * 
     * @param array $data Data yang akan diubah di tabel tb_menu
     */
    public function ubah_data($data)
    {
        // Menyiapkan dan mengeksekusi query untuk mengubah data yang sudah ada
        $query = $this->conn->prepare("UPDATE tb_menu SET nama_menu = ?, foto_menu = ?, jenis = ?, kategori = ?, deskripsi = ?, harga = ?, ketersediaan = ? WHERE id_menu = ?");
        $query->execute([$data['nama_menu'], $data['foto_menu'], $data['jenis'], $data['kategori'], $data['deskripsi'], $data['harga'], $data['ketersediaan'], $data['id_menu']]);
    }

    /**
     * Function untuk menghapus data berdasarkan id_menu tertentu
     * 
     * @param int $id_menu ID menu yang akan dihapus datanya
     */
    public function hapus_data($id_menu)
    {
        // Menyiapkan dan mengeksekusi query untuk menghapus data berdasarkan id_menu
        $query = $this->conn->prepare("DELETE FROM tb_menu WHERE id_menu = ?");
        $query->execute([$id_menu]);
    }
}
?>
