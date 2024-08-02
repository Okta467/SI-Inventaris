<?php
include '../helpers/isAccessAllowedHelper.php';

// cek apakah user yang mengakses adalah admin?
if (!isAccessAllowed('admin')) :
  session_destroy();
  echo "<meta http-equiv='refresh' content='0;" . base_url_return('index.php?msg=other_error') . "'>";
else :
  include_once '../config/connection.php';

  $tanggal_kirim = $_GET['tanggal_kirim'] ?? null;

  if (!$tanggal_kirim) {
    echo 'Tanggal tidak terisi!';
    return;
  }

  $stmt_barang_keluar = mysqli_stmt_init($connection);
  $query_barang_keluar =
    "SELECT
      a.id AS id_barang_keluar, a.tanggal, a.jumlah,
      b.id AS id_barang, b.kode_barang, b.nama_barang, b.satuan,
      SUM(IF(b.satuan='pcs', a.jumlah, 0)) AS sml,
      SUM(IF(b.satuan='box', a.jumlah, 0)) AS mid,
      SUM(IF(b.satuan='dus', a.jumlah, 0)) AS lrg
    FROM tbl_barang_keluar AS a
    INNER JOIN tbl_barang AS b
      ON b.id = a.id_barang
    WHERE a.tanggal=?
    GROUP BY b.id
    ORDER BY a.id DESC";

  mysqli_stmt_prepare($stmt_barang_keluar, $query_barang_keluar);
  mysqli_stmt_bind_param($stmt_barang_keluar, 's', $tanggal_kirim);
  mysqli_stmt_execute($stmt_barang_keluar);

  $result = mysqli_stmt_get_result($stmt_barang_keluar);
  $barang_keluars = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>


  <!DOCTYPE html>
  <html lang="en">

  <head>
    <?php include '_partials/head.php' ?>

    <meta name="description" content="Data Pengumuman" />
    <meta name="author" content="" />
    <title>Bon Pengembalian Barang - <?= $tanggal_kirim ?></title>
  </head>

  <body class="bg-white text-black" style="font-size: 12px;">

    <!-- Kop Laporan -->
    <div class="row">
      <!-- Top left -->
      <div class="col-6">
        <p class="m-0 fw-bold" style="font-size: 1rem;">Pinus Murah Abadi, PT</p>
        <p class="m-0 fw-bold" style="font-size: 1rem;">PMA Prabumulih</p>
      </div>

      <!-- Top right -->
      <div class="col-2">
        <p class="m-0 fw-bold">Tanggal Cetak</p>
        <p class="m-0 fw-bold">Halaman</p>
        <p class="m-0 fw-bold">Cetakan Ke</p>
      </div>
      <div class="col-4">
        <p class="m-0 fw-bold">: <?= date('d.m.Y - H:i:s') ?></p>
        <p class="m-0 fw-bold">: </p>
        <p class="m-0 fw-bold">: </p>
      </div>

      <!-- Middle -->
      <div class="col-12">
        <h2 class="text-center text-black text-uppercase py-3">Bon Pengeluaran Barang (BPB)</h2>
      </div>

      <!-- Bottom left -->
      <div class="col-2">
        <p class="m-0 fw-bold">Tanggal Kirim</p>
        <p class="m-0 fw-bold">Kendaraan</p>
        <p class="m-0 fw-bold">Driver / Helper</p>
      </div>
      <div class="col-4">
        <p class="m-0 fw-bold">: <?= $tanggal_kirim ?></p>
        <p class="m-0 fw-bold">: </p>
        <p class="m-0 fw-bold text-uppercase">: </p>
      </div>

      <!-- Bottom right -->
      <div class="col-2">
        <p class="m-0 fw-bold">No. BPB</p>
        <p class="m-0 fw-bold">Pengiriman</p>
        <p class="m-0 fw-bold">Jumlah Toko</p>
      </div>
      <div class="col-4">
        <p class="m-0 fw-bold">: </p>
        <p class="m-0 fw-bold">: </p>
        <p class="m-0 fw-bold">: </p>
      </div>
    </div>

    <!-- Tabel -->
    <div class="row mt-3">
      <div class="col-12">
        <table class="table table-bordered table-sm text-black border-black">
          <thead>
            <tr>
              <th class="align-middle text-center" rowspan="2">No.</th>
              <th class="align-middle text-center text-uppercase" rowspan="2">Kode Produk</th>
              <th class="align-middle text-center text-uppercase" rowspan="2">Nama Produk</th>
              <th class="align-middle text-center text-uppercase" colspan="3">Jml Dibawa</th>
              <th class="align-middle text-center text-uppercase" colspan="3">Jml Kembali</th>
              <th class="align-middle text-center text-uppercase" colspan="3">Selisih</th>
            </tr>
            <tr>
              <!-- Jml Dibawa -->
              <th class="align-middle text-center">LRG</th>
              <th class="align-middle text-center">MID</th>
              <th class="align-middle text-center">SML</th>
              <!-- Jml kembali -->
              <th class="align-middle text-center">LRG</th>
              <th class="align-middle text-center">MID</th>
              <th class="align-middle text-center">SML</th>
              <!-- Selisih -->
              <th class="align-middle text-center">LRG</th>
              <th class="align-middle text-center">MID</th>
              <th class="align-middle text-center">SML</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1 ?>
            <?php foreach ($barang_keluars as $barang_keluar) : ?>
              <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= $barang_keluar['kode_barang'] ?></td>
                <td><?= $barang_keluar['nama_barang'] ?></td>
                <!-- Jml Dibawa -->
                <td class="text-center"><?= $barang_keluar['lrg'] ?></td>
                <td class="text-center"><?= $barang_keluar['mid'] ?></td>
                <td class="text-center"><?= $barang_keluar['sml'] ?></td>
                <!-- Jml Kembali -->
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <!-- Selisih -->
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
              </tr>
            <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Tandatangan -->
    <div class="row mt-3">
      <div class="col-2 text-center">
        <p class="m-0">Dibuat Oleh,</p>
      </div>
      <div class="col-2 text-center">
        <p class="m-0">Dibuat Oleh,</p>
      </div>
      <div class="col-2 text-center">
        <p class="m-0">Diperiksa Oleh,</p>
      </div>
      <div class="col-4 text-center">
        <p class="m-0">Diketahui Oleh,</p>
      </div>
      <div class="col-2 text-center">
        <p class="m-0">Diterima Kembali Oleh,</p>
      </div>
    </div>
    <div class="row mt-5"></div>
    <div class="row mt-5">
      <div class="col-2 text-center">
        <p class="m-0">SA</p>
      </div>
      <div class="col-2 text-center">
        <p class="m-0">Driver/Helper</p>
      </div>
      <div class="col-2 text-center">
        <p class="m-0">Checker</p>
      </div>
      <div class="col-4 text-center">
        <p class="m-0">Kepala Gudang SBH/SPV Log/BM</p>
      </div>
      <div class="col-2 text-center">
        <p class="m-0">Kepala Gudang</p>
      </div>
    </div>

  </body>

  </html>

<?php endif ?>