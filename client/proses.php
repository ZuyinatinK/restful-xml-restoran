<?php
// Menyertakan file Client.php yang mendefinisikan kelas Client dan menginisialisasi objek $abc
include "Client.php"; // Pastikan Client.php menginisialisasi $abc

/**
 * Function untuk mengkonversi file gambar menjadi base64 encoding
 *
 * @param array $file Array yang berisi informasi file yang diunggah
 * @return string Base64 encoded string dari data gambar
 */
function base64_encode_image($file)
{
    $imageData = file_get_contents($file['tmp_name']);
    return base64_encode($imageData);
}

// Memeriksa aksi yang dilakukan berdasarkan data POST atau GET
if ($_POST['aksi'] == 'tambah') {
    // Mengambil gambar dan mengkonversinya ke base64
    $foto_menu = base64_encode_image($_FILES['foto_menu']);
    // Menyusun data untuk ditambahkan
    $data = array(
        "nama_menu" => $_POST['nama_menu'],
        "foto_menu" => $foto_menu,
        "jenis" => $_POST['jenis'],
        "kategori" => $_POST['kategori'],
        "deskripsi" => $_POST['deskripsi'],
        "harga" => $_POST['harga'],
        "ketersediaan" => $_POST['ketersediaan'],
        "aksi" => $_POST['aksi']
    );
    // Memanggil method tambah_data pada objek $abc untuk menambahkan data ke server
    $abc->tambah_data($data); // Pastikan $abc diinisialisasi
    header('location:dashboard.php');
    exit();
} elseif ($_POST['aksi'] == 'ubah') {
    // Mengambil gambar dan mengkonversinya ke base64
    $foto_menu = base64_encode_image($_FILES['foto_menu']);
    // Menyusun data untuk diubah
    $data = array(
        "id_menu" => $_POST['id_menu'],
        "nama_menu" => $_POST['nama_menu'],
        "foto_menu" => $foto_menu,
        "jenis" => $_POST['jenis'],
        "kategori" => $_POST['kategori'],
        "deskripsi" => $_POST['deskripsi'],
        "harga" => $_POST['harga'],
        "ketersediaan" => $_POST['ketersediaan'],
        "aksi" => $_POST['aksi']
    );
    // Memanggil method ubah_data pada objek $abc untuk mengubah data di server
    $abc->ubah_data($data); // Pastikan $abc diinisialisasi
    header('location:dashboard.php');
    exit();
} elseif ($_GET['aksi'] == 'hapus') {
    // Memanggil method hapus_data pada objek $abc untuk menghapus data di server
    $abc->hapus_data($_GET['id_menu']); // Pastikan $abc diinisialisasi
    header('location:dashboard.php');
    exit();
}
?>
