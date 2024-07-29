<?php
include '../helpers/isAccessAllowedHelper.php';

// cek apakah user yang mengakses adalah supervisor?
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

    <meta name="description" content="Data Barang Masuk" />
    <meta name="author" content="" />
    <title>Barang Masuk - <?= SITE_NAME ?></title>
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
                <h1 class="mb-0">Barang Masuk</h1>
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
                  <i data-feather="download" class="me-2 mt-1"></i>
                  Data Barang Masuk
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
                      <th>Keterangan</th>
                      <th>Satuan</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    $query_barang_masuk = mysqli_query($connection, 
                      "SELECT
                        a.id AS id_barang_masuk, a.tanggal, a.jumlah, a.keterangan,
                        b.id AS id_barang, b.kode_barang, b.nama_barang, b.satuan
                      FROM tbl_barang_masuk AS a
                      INNER JOIN tbl_barang AS b
                        ON b.id = a.id_barang
                      ORDER BY a.id DESC");

                    while ($barang_masuk = mysqli_fetch_assoc($query_barang_masuk)):
                    ?>

                      <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($barang_masuk['kode_barang']) ?></td>
                        <td><?= htmlspecialchars($barang_masuk['nama_barang']) ?></td>
                        <td><?= $barang_masuk['tanggal'] ?></td>
                        <td><?= $barang_masuk['jumlah'] ?></td>
                        <td>
                          <div class="ellipsis toggle_tooltip" title="<?= $barang_masuk['keterangan'] ?>">
                            <?= $barang_masuk['keterangan'] ?>
                          </div>
                        </td>
                        <td>
                          <?php if ($barang_masuk['satuan'] === 'dus'): ?>
                            <span class="text-danger"><?= $barang_masuk['satuan'] ?></span>
                          <?php elseif ($barang_masuk['satuan'] === 'box'): ?>
                            <span class="text-primary"><?= $barang_masuk['satuan'] ?></span>
                          <?php elseif ($barang_masuk['satuan'] === 'pcs'): ?>
                            <span class="text-success"><?= $barang_masuk['satuan'] ?></span>
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