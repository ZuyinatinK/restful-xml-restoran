<?php
// Mengatur tingkat pelaporan kesalahan untuk tidak menampilkan semua peringatan
error_reporting(1);

// class Client untuk mengelola interaksi dengan server melalui cURL
class Client
{
    // Properti untuk menyimpan URL server
    private $url;

    /**
     * Construct untuk menginisialisasi URL server
     *
     * @param string $url URL server yang akan diakses
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Function untuk memfilter data agar hanya mengandung karakter alfanumerik
     *
     * @param string $data Data yang akan difilter
     * @return string Data yang sudah difilter
     */
    public function filter($data)
    {
        return preg_replace('/[^a-zA-Z0-9]/', '', $data);
    }

    /**
     * Function untuk mengambil semua data dari server
     *
     * @return SimpleXMLElement Data dari server dalam format XML
     */
    public function tampil_semua_data()
    {
        // Menginisialisasi cURL
        $client = curl_init($this->url);
        curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($client);  // Mengeksekusi cURL dan mendapatkan respons
        curl_close($client); // Menutup koneksi cURL
        return simplexml_load_string($response); // Mengembalikan respons sebagai SimpleXMLElement
    }

    /**
     * Function untuk mengambil data berdasarkan id_menu tertentu dari server
     *
     * @param int $id_menu ID menu yang akan diambil datanya
     * @return SimpleXMLElement Data dari server dalam format XML
     */
    public function tampil_data($id_menu)
    {
        $id_menu = $this->filter($id_menu);
        // Menginisialisasi cURL dengan URL yang sudah disertakan parameter id_menu
        $client = curl_init($this->url . "?aksi=tampil&id_menu=" . $id_menu); 
        curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($client); // Mengeksekusi cURL dan mendapatkan respons
        curl_close($client); // Menutup koneksi cURL
        return simplexml_load_string($response); // Mengembalikan respons sebagai SimpleXMLElement
    }

    /**
     * Function untuk menambahkan data baru ke server
     *
     * @param array $data Data baru yang akan ditambahkan ke server
     */
    public function tambah_data($data)
    {
        // Menyusun data dalam format XML
        $xml_data = "<restoran><menu>
                        <id_menu>{$data['id_menu']}</id_menu>
                        <nama_menu>{$data['nama_menu']}</nama_menu>
                        <foto_menu>{$data['foto_menu']}</foto_menu>
                        <jenis>{$data['jenis']}</jenis>
                        <kategori>{$data['kategori']}</kategori>
                        <deskripsi>{$data['deskripsi']}</deskripsi>
                        <harga>{$data['harga']}</harga>
                        <ketersediaan>{$data['ketersediaan']}</ketersediaan>
                        <aksi>{$data['aksi']}</aksi>
                    </menu></restoran>";
        // Menginisialisasi cURL
        $c = curl_init($this->url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $xml_data);
        curl_exec($c); // Mengeksekusi cURL
        curl_close($c);
    }

    /**
     * Function untuk mengubah data yang sudah ada di server
     *
     * @param array $data Data yang akan diubah di server
     */
    public function ubah_data($data)
    {
        // Menyusun data dalam format XML
        $xml_data = "<restoran><menu>
                        <id_menu>{$data['id_menu']}</id_menu>
                        <nama_menu>{$data['nama_menu']}</nama_menu>
                        <foto_menu>{$data['foto_menu']}</foto_menu>
                        <jenis>{$data['jenis']}</jenis>
                        <kategori>{$data['kategori']}</kategori>
                        <deskripsi>{$data['deskripsi']}</deskripsi>
                        <harga>{$data['harga']}</harga>
                        <ketersediaan>{$data['ketersediaan']}</ketersediaan>
                        <aksi>{$data['aksi']}</aksi>
                    </menu></restoran>";
        // Menginisialisasi cURL
        $c = curl_init($this->url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $xml_data);
        curl_exec($c); // Mengeksekusi cURL
        curl_close($c);
    }

    /**
     * Function untuk menghapus data berdasarkan id_menu tertentu dari server
     *
     * @param int $id_menu ID menu yang akan dihapus datanya
     */
    public function hapus_data($id_menu)
    {
        $id_menu = $this->filter($id_menu);
        // Menyusun data dalam format XML untuk menghapus
        $xml_data = "<restoran><menu>
                        <id_menu>{$id_menu}</id_menu>
                        <aksi>hapus</aksi>
                    </menu></restoran>";
        // Menginisialisasi cURL
        $c = curl_init($this->url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $xml_data);
        curl_exec($c); // Mengeksekusi cURL
        curl_close($c);
    }

    /**
     * Destruktor untuk membersihkan properti URL
     */
    public function __destruct()
    {
        unset($this->url);
    }
}

// Menginisialisasi URL server dan membuat objek Client
$url = 'http://192.168.10.13/restful-xml-restoran/server/server.php';
$abc = new Client($url);
?>
