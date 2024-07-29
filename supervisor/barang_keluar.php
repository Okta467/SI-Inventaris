<?php
include '../helpers/isAccessAllowedHelper.php';

// cek apakah user yang mengakses adalah admin?
if (!isAccessAllowed('supervisor')) :
  session_destroy();
  echo "<meta http-equiv='refresh' content='0;" . base_url_return('index.php?msg=other_error') . "'>";
else :
  include_once '../config/connection.php';
?>


  <!DOCTYPE html>
  <html lang="en">

  <head>
    <?php include '_partials/head.php' ?>

    <meta name="description" content="Data Barang Keluar" />
    <meta name="author" content="" />
    <title>Barang Keluar - <?= SITE_NAME ?></title>
  </head>

  <body class="nav-fixed">
    <!--============================= TOPNAV =============================-->
    <?php include '_partials/topnav.php' ?>
    <!--//END TOPNAV -->
    <div id="layoutSidenav">
      <div id="layoutSidenav_nav">
        <!--============================= SIDEBAR =============================-->
        <?php include '_partials/sidebar.php' ?>
        <!--//END SIDEBAR -->
      </div>
      <div id="layoutSidenav_content">
        <main>
          <!-- Main page content-->
          <div class="container-xl px-4 mt-5">

            <!-- Custom page header alternative example-->
            <div class="d-flex justify-content-between align-items-sm-center flex-column flex-sm-row mb-4">
              <div class="me-4 mb-3 mb-sm-0">
                <h1 class="mb-0">Barang Keluar</h1>
                <div class="small">
                  <span class="fw-500 text-primary"><?= date('D') ?></span>
                  &middot; <?= date('M d, Y') ?> &middot; <?= date('H:i') ?> WIB
                </div>
              </div>

              <!-- Date range picker example-->
              <div class="input-group input-group-joined border-0 shadow w-auto">
                <span class="input-group-text"><i data-feather="calendar"></i></span>
                <input class="form-control ps-0 pointer" id="litepickerRangePlugin" value="Tanggal: <?= date('d M Y') ?>" readonly />
              </div>

            </div>
            
            <!-- Main page content-->
            <div class="card card-header-actions mb-4 mt-5">
              <div class="card-header">
                <div>
                  <i data-feather="upload" class="me-2 mt-1"></i>
                  Data Barang Keluar
                </div>
              </div>
              <div class="card-body">
                <table id="datatablesSimple">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Kode Barang</th>
                      <th>Nama Barang</th>
                      <th>Tanggal</th>
                      <th>Jumlah</th>
                      <th>Satuan</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    $query_barang_keluar = mysqli_query($connection, 
                      "SELECT
                        a.id AS id_barang_keluar, a.tanggal, a.jumlah,
                        b.id AS id_barang, b.kode_barang, b.nama_barang, b.satuan
                      FROM tbl_barang_keluar AS a
                      INNER JOIN tbl_barang AS b
                        ON b.id = a.id_barang
                      ORDER BY a.id DESC");

                    while ($barang_keluar = mysqli_fetch_assoc($query_barang_keluar)):
                    ?>

                      <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($barang_keluar['kode_barang']) ?></td>
                        <td><?= htmlspecialchars($barang_keluar['nama_barang']) ?></td>
                        <td><?= $barang_keluar['tanggal'] ?></td>
                        <td><?= $barang_keluar['jumlah'] ?></td>
                        <td>
                          <?php if ($barang_keluar['satuan'] === 'dus'): ?>
                            <span class="text-danger"><?= $barang_keluar['satuan'] ?></span>
                          <?php elseif ($barang_keluar['satuan'] === 'box'): ?>
                            <span class="text-primary"><?= $barang_keluar['satuan'] ?></span>
                          <?php elseif ($barang_keluar['satuan'] === 'pcs'): ?>
                            <span class="text-success"><?= $barang_keluar['satuan'] ?></span>
                          <?php endif ?>
                        </td>
                      </tr>

                    <?php endwhile ?>
                  </tbody>
                </table>
              </div>
            </div>
            
          </div>
        </main>
        
        <!--============================= FOOTER =============================-->
        <?php include '_partials/footer.php' ?>
        <!--//END FOOTER -->

      </div>
    </div>
    
    <?php include '_partials/script.php' ?>
    <?php include '../helpers/sweetalert2_notify.php' ?>
    
    <!-- PAGE SCRIPT -->
    <script>
    </script>

  </body>

  </html>

<?php endif ?>