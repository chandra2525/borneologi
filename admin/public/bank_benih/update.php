<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/BankBenih.php";

secureSessionStart();
verifyCsrfToken();

$bankBenihModel = new BankBenih($pdo);

$data = [
    "nomor_aksesi" => $_POST["nomor_aksesi"],
    "id_tanah" => $_POST["id_tanah"],
    "id_negara" => $_POST["id_negara"],
    "nama_lokal" => $_POST["nama_lokal"],
    "nama_ilmiah" => $_POST["nama_ilmiah"],
    "famili_tanaman" => $_POST["famili_tanaman"],
    "provenance" => $_POST["provenance"],
    "id_tipe_penyimpanan_benih" => $_POST["id_tipe_penyimpanan_benih"],
    "tanggal_masuk" => $_POST["tanggal_masuk"],
    "jumlah_stok" => $_POST["jumlah_stok"],
    "satuan_stok" => $_POST["satuan_stok"],
    "kadar_air_persen" => $_POST["kadar_air_persen"],
    "viabilitas_persen" => $_POST["viabilitas_persen"],
    "ketinggian_mdpl" => $_POST["ketinggian_mdpl"],
    "masa_berlaku_sampai" => $_POST["masa_berlaku_sampai"],
    "lokasi_penyimpanan" => $_POST["lokasi_penyimpanan"],
    "titik_koleksi_lat" => $_POST["titik_koleksi_lat"],
    "titik_koleksi_lng" => $_POST["titik_koleksi_lng"],
    "catatan" => $_POST["catatan"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$bankBenihModel->update($_POST["id"], $data);

header("Location: index.php?success=updated");