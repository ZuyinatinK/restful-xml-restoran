<?php
// Mengatur tingkat pelaporan kesalahan untuk tidak menampilkan semua peringatan
error_reporting(1);
// Mengatur header konten menjadi XML dengan charset UTF-8
header('Content-Type: text/xml; charset=UTF-8');

include "Database.php";
$abc = new Database();

/**
 * Function untuk memfilter data agar hanya mengandung karakter alfanumerik
 *
 * @param string $data Data yang akan difilter
 * @return string Data yang sudah difilter
 */
function filter($data)
{
    return preg_replace('/[^a-zA-Z0-9]/', '', $data);
}

/**
 * Function untuk memformat nama file dengan mengganti spasi dengan underscore
 *
 * @param string $name Nama yang akan diformat
 * @return string Nama yang sudah diformat
 */
function formatFileName($name)
{
    return str_replace(' ', '_', $name);
}

/**
 * Function untuk menyimpan gambar ke direktori images
 *
 * @param string $imageData Data gambar dalam format base64
 * @param string $nama_menu Nama menu yang akan digunakan sebagai nama file
 * @return string|false Path file gambar yang disimpan atau false jika gagal
 */
function saveImage($imageData, $nama_menu)
{
    $formattedName = formatFileName($nama_menu);
    $imagePath = 'images/' . $formattedName . '.png';

    // Memastikan direktori images ada
    if (!is_dir('images')) {
        mkdir('images', 0755, true);
    }

    // Menyimpan data gambar yang telah didekode ke file
    if (file_put_contents($imagePath, base64_decode($imageData)) === false) {
        error_log("Failed to save image.");
        return false;
    }
    return $imagePath;
}

// Mengecek apakah request method adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Membaca input XML dari body request
    $input = file_get_contents("php://input");
    $data = simplexml_load_string($input);

    // Mengambil data dari XML
    $aksi = $data->menu->aksi;
    $id_menu = $data->menu->id_menu;
    $nama_menu = $data->menu->nama_menu;
    $foto_menu = $data->menu->foto_menu;
    $jenis = $data->menu->jenis;
    $kategori = $data->menu->kategori;
    $deskripsi = $data->menu->deskripsi;
    $harga = $data->menu->harga;
    $ketersediaan = $data->menu->ketersediaan;

    // Menangani aksi berdasarkan nilai aksi (tambah, ubah, hapus)
    if ($aksi == 'tambah') {
        $fotoPath = saveImage($foto_menu, $nama_menu);
        if ($fotoPath !== false) {
            // Menambah data ke database
            $abc->tambah_data([
                'id_menu' => $id_menu,
                'nama_menu' => $nama_menu,
                'foto_menu' => $fotoPath,
                'jenis' => $jenis,
                'kategori' => $kategori,
                'deskripsi' => $deskripsi,
                'harga' => $harga,
                'ketersediaan' => $ketersediaan
            ]);
        } else {
            // Jika penyimpanan gambar gagal
            echo "Failed to save image.";
        }
    } elseif ($aksi == 'ubah') {
        $fotoPath = saveImage($foto_menu, $nama_menu);
        if ($fotoPath !== false) {
            // Mengubah data di database
            $abc->ubah_data([
                'id_menu' => $id_menu,
                'nama_menu' => $nama_menu,
                'foto_menu' => $fotoPath,
                'jenis' => $jenis,
                'kategori' => $kategori,
                'deskripsi' => $deskripsi,
                'harga' => $harga,
                'ketersediaan' => $ketersediaan
            ]);
        } else {
            echo "Failed to save image.";
        }
    } elseif ($aksi == 'hapus') {
        $abc->hapus_data($id_menu);
    }
}
// Memeriksa apakah metode request adalah GET
elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Menangani aksi untuk menampilkan data
    if (isset($_GET['aksi']) && $_GET['aksi'] == 'tampil' && isset($_GET['id_menu'])) {
        $id_menu = filter($_GET['id_menu']);
        $data = $abc->tampil_data($id_menu);
        // Menyusun data dalam format XML
        $xml = "<restoran><menu>
                    <id_menu>{$data['id_menu']}</id_menu>
                    <nama_menu>{$data['nama_menu']}</nama_menu>
                    <foto_menu>{$data['foto_menu']}</foto_menu>
                    <jenis>{$data['jenis']}</jenis>
                    <kategori>{$data['kategori']}</kategori>
                    <deskripsi>{$data['deskripsi']}</deskripsi>
                    <harga>{$data['harga']}</harga>
                    <ketersediaan>{$data['ketersediaan']}</ketersediaan>
                </menu></restoran>";
        echo $xml;
    } else {
        $data = $abc->tampil_semua_data();
        // Menyusun semua data dalam format XML
        $xml = "<restoran>";
        foreach ($data as $a) {
            $xml .= "<menu>";
            foreach ($a as $kolom => $value) {
                $xml .= "<$kolom>$value</$kolom>";
            }
            $xml .= "</menu>";
        }
        $xml .= "</restoran>";
        echo $xml;
    }
}
?>
