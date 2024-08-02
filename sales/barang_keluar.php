<?php
include '../helpers/isAccessAllowedHelper.php';

// cek apakah user yang mengakses adalah sales?
if (!isAccessAllowed('sales')) :
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
            
            <!-- Tools Cetak Laporan -->
            <div class="card mb-4 mt-5">
              <div class="card-header">
                <div>
                  <i data-feather="settings" class="me-2 mt-1"></i>
                  Tools Cetak Laporan
                </div>
              </div>
              <div class="card-body">
                <div class="row gx-3">
                  <div class="col-md-2 mb-3">
                    <label class="small mb-1" for="xtanggal_kirim">Tanggal</label>
                    <input class="form-control" id="xtanggal_kirim" type="date" name="xtanggal_kirim" required>
                  </div>
                  <div class="col-md-2 mb-3">
                    <label class="small mb-1 invisible" for="xcetak_laporan">Filter Button</label>
                    <button class="btn btn-primary w-100" id="xcetak_laporan" type="button">
                      <i data-feather="printer" class="me-1"></i>
                      Cetak
                    </button>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Main page content-->
            <div class="card card-header-actions mb-4 mt-5">
              <div class="card-header">
                <div>
                  <i data-feather="upload" class="me-2 mt-1"></i>
                  Data Barang Keluar
                </div>
                <button class="btn btn-sm btn-primary toggle_modal_tambah" type="button"><i data-feather="plus-circle" class="me-2"></i>Tambah Data</button>
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
                      <th>Aksi</th>
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
                        <td>
                          <button class="btn btn-datatable btn-icon btn-transparent-dark me-2 toggle_modal_ubah"
                            data-id_barang_keluar="<?= $barang_keluar['id_barang_keluar'] ?>"
                            data-id_barang="<?= $barang_keluar['id_barang'] ?>"
                            data-tanggal="<?= $barang_keluar['tanggal'] ?>"
                            data-jumlah="<?= $barang_keluar['jumlah'] ?>">
                            <i class="fa fa-pen-to-square"></i>
                          </button>
                          <button class="btn btn-datatable btn-icon btn-transparent-dark me-2 toggle_swal_hapus"
                            data-id_barang_keluar="<?= $barang_keluar['id_barang_keluar'] ?>"
                            data-nama_barang="<?= htmlspecialchars($barang_keluar['nama_barang']) ?>"
                            data-jumlah="<?= htmlspecialchars($barang_keluar['jumlah']) ?>">
                            <i class="fa fa-trash-can"></i>
                          </button>
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
    
    <!--============================= MODAL INPUT BARANG =============================-->
    <div class="modal fade" id="ModalInputBarangMasuk" tabindex="-1" role="dialog" aria-labelledby="ModalInputBarangMasukTitle" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="ModalInputBarangMasukTitle">Modal title</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form>
            <div class="modal-body">
              
              <input type="hidden" id="xid_barang_keluar" name="xid_barang_keluar">
              <input type="hidden" id="xcurrent_id_barang" name="xcurrent_id_barang">

              <div class="mb-3 d-none" id="xid_barang_display_container">
                <label class="small mb-1" for="xid_barang_display">Barang</label>
                <input type="text" name="xid_barang_display" class="form-control" id="xid_barang_display" readonly>
                <small class="text-danger">Stok barang yang dipilih 0, Anda hanya dapat mengubah tanggal atau menghapus data barang keluar ini.</small>
              </div>
              
              <div class="mb-3" id="xid_barang_container">
                <label class="small mb-1" for="xid_barang">Barang</label>
                <select name="xid_barang" class="form-control select2" id="xid_barang" required>
                  <option value="">-- Pilih --</option>
                  <?php
                  $query_barang = mysqli_query($connection, 
                    "SELECT
                        a.id, a.kode_barang, a.nama_barang, a.satuan,
                        IFNULL(b.total_masuk, 0) - IFNULL(c.total_keluar, 0) AS stok
                      FROM tbl_barang AS a
                      LEFT JOIN
                      (
                        SELECT id, SUM(jumlah) AS total_masuk, id_barang
                        FROM tbl_barang_masuk
                        GROUP BY id_barang
                      ) AS b
                        ON a.id = b.id_barang
                      LEFT JOIN
                      (
                        SELECT id, SUM(jumlah) AS total_keluar, id_barang
                        FROM tbl_barang_keluar
                        GROUP BY id_barang
                      ) AS c
                        ON a.id = c.id_barang
                      GROUP BY a.id
                      HAVING stok > 0
                      ORDER BY a.id DESC");
                  
                  while ($barang = mysqli_fetch_assoc($query_barang)):
                  ?>

                    <option value="<?= $barang['id'] ?>"><?= "{$barang['nama_barang']} -- {$barang['kode_barang']} -- Stok ({$barang['stok']})" ?></option>

                  <?php endwhile ?>
                  <?php mysqli_close($connection) ?>
                </select>
                <small class="text-muted">Barang yang ditampilkan hanya yang stoknya lebih dari 0.</small>
              </div>
            
              <div class="row gx-3">
                
                <div class="col-md-7 mb-3">
                  <label class="small mb-1" for="xjumlah">Jumlah</label>
                  <input type="number" name="xjumlah" min="0" class="form-control" id="xjumlah" placeholder="Enter jumlah" required />
                  <small class="text-danger">Perhatian: Jumlah keluar tidak boleh melebihi stok saat ini!</small>
                </div>

                <div class="col-md-5 mb-3">
                  <label class="small mb-1" for="xtanggal">Tanggal</label>
                  <input type="date" name="xtanggal" value="<?= date('Y-m-d') ?>" class="form-control" id="xtanggal" required />
                </div>

              </div>

            </div>
            <div class="modal-footer">
              <button class="btn btn-light border" type="button" data-bs-dismiss="modal">Batal</button>
              <button class="btn btn-primary" type="submit">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--/.modal-input-barang_keluar -->
    
    <?php include '_partials/script.php' ?>
    <?php include '../helpers/sweetalert2_notify.php' ?>
    
    <!-- PAGE SCRIPT -->
    <script>
      $(document).ready(function() {

        const handleStokBarangZero = function (isStokZero = false, barangDisplayText = '') {
          if (isStokZero) {
            $('#xjumlah').prop('readonly', true);
            
            $('#xid_barang').prop('required', false);
            $('#xid_barang_container').addClass('d-none');
            
            $('#xid_barang_display').val(barangDisplayText);
            $('#xid_barang_display_container').removeClass('d-none');
          } else {
            $('#xjumlah').prop('readonly', false);
  
            $('#xid_barang').prop('required', true);
            $('#xid_barang_container').removeClass('d-none');
  
            $('#xid_barang_display').val(barangDisplayText);
            $('#xid_barang_display_container').addClass('d-none');
          }
        }
        
        $('#xcetak_laporan').on('click', function() {
          const tanggal_kirim = $('#xtanggal_kirim').val();
          const url = `laporan_barang_keluar.php?tanggal_kirim=${tanggal_kirim}`;
          
          printExternal(url);
        });


        $('.toggle_modal_tambah').on('click', function() {
          $('#ModalInputBarangMasuk .modal-title').html(`<i data-feather="plus-circle" class="me-2 mt-1"></i>Tambah Barang Keluar`);
          $('#ModalInputBarangMasuk form').attr({action: 'barang_keluar_tambah.php', method: 'post'});
          
          let isStokZero = false;
          let barangDisplayText = '';

          handleStokBarangZero(isStokZero, barangDisplayText);

          // Set input barang to default
          $('#xid_barang').val('').trigger('change');

          // Re-init all feather icons
          feather.replace();
          
          $('#ModalInputBarangMasuk').modal('show');
        });


        $('.toggle_modal_ubah').on('click', function() {
          const data = $(this).data();

          $('#ModalInputBarangMasuk .modal-title').html(`<i data-feather="edit" class="me-2 mt-1"></i>Ubah Barang Keluar`);
          $('#ModalInputBarangMasuk form').attr({action: 'barang_keluar_ubah.php', method: 'post'});
          
          $.ajax({
            url: 'get_barang_and_stok.php',
            method: 'POST',
            dataType: 'JSON',
            data: {
              'id_barang': data.id_barang
            },
            success: function(data) {
              const barang = data[0];

              if (barang.stok <= 0) {
                let isStokZero = true;
                let barangDisplayText = `${barang.nama_barang} -- ${barang.kode_barang} -- Stok (${barang.stok})`;

                handleStokBarangZero(isStokZero, barangDisplayText);
              } else {
                let isStokZero = false;
                let barangDisplayText = '';

                handleStokBarangZero(isStokZero, barangDisplayText);
              }
            },
            error: function(request, status, error) {
              // console.log("ajax call went wrong:" + request.responseText);
              console.log("ajax call went wrong:" + error);
            }
          });
          
          $('#ModalInputBarangMasuk #xid_barang_keluar').val(data.id_barang_keluar);
          $('#ModalInputBarangMasuk #xcurrent_id_barang').val(data.id_barang);
          $('#ModalInputBarangMasuk #xid_barang').val(data.id_barang).trigger('change');
          $('#ModalInputBarangMasuk #xtanggal').val(data.tanggal);
          $('#ModalInputBarangMasuk #xjumlah').val(data.jumlah);
          
          // Re-init all feather icons
          feather.replace();
          
          $('#ModalInputBarangMasuk').modal('show');
        });
        

        $('#datatablesSimple').on('click', '.toggle_swal_hapus', function() {
          const id_barang_keluar = $(this).data('id_barang_keluar');
          const nama_barang = $(this).data('nama_barang');
          const jumlah = $(this).data('jumlah');
          
          Swal.fire({
            title: "Konfirmasi Tindakan?",
            html: `<div class="mb-1">Hapus data barang keluar: </div><strong>${nama_barang} (Jumlah keluar ${jumlah})?</strong>`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, konfirmasi!"
          }).then((result) => {
            if (result.isConfirmed) {
              Swal.fire({
                title: "Tindakan Dikonfirmasi!",
                text: "Halaman akan di-reload untuk memproses.",
                icon: "success",
                timer: 3000
              }).then(() => {
                window.location = `barang_keluar_hapus.php?xid_barang_keluar=${id_barang_keluar}`;
              });
            }
          });
        });
        
      });
    </script>

  </body>

  </html>

<?php endif ?>