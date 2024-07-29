<?php
include '../helpers/isAccessAllowedHelper.php';

// cek apakah user yang mengakses adalah admin?
if (!isAccessAllowed('admin')) :
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
                  <i data-feather="package" class="me-2 mt-1"></i>
                  Data Barang Masuk
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
                      <th>Keterangan</th>
                      <th>Satuan</th>
                      <th>Aksi</th>
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
                        <td>
                          <button class="btn btn-datatable btn-icon btn-transparent-dark me-2 toggle_modal_ubah"
                            data-id_barang_masuk="<?= $barang_masuk['id_barang_masuk'] ?>"
                            data-id_barang="<?= $barang_masuk['id_barang'] ?>"
                            data-tanggal="<?= $barang_masuk['tanggal'] ?>"
                            data-jumlah="<?= $barang_masuk['jumlah'] ?>"
                            data-keterangan="<?= $barang_masuk['keterangan'] ?>">
                            <i class="fa fa-pen-to-square"></i>
                          </button>
                          <button class="btn btn-datatable btn-icon btn-transparent-dark me-2 toggle_swal_hapus"
                            data-id_barang_masuk="<?= $barang_masuk['id_barang_masuk'] ?>"
                            data-nama_barang="<?= htmlspecialchars($barang_masuk['nama_barang']) ?>"
                            data-jumlah="<?= htmlspecialchars($barang_masuk['jumlah']) ?>">
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
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="ModalInputBarangMasukTitle">Modal title</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form>
            <div class="modal-body">
              
              <input type="hidden" id="xid_barang_masuk" name="xid_barang_masuk">
              
              <div class="mb-3">
                <label class="small mb-1" for="xid_barang">Barang</label>
                <select name="xid_barang" class="form-control select2" id="xid_barang" required>
                  <option value="">-- Pilih --</option>
                  <?php $query_barang = mysqli_query($connection, "SELECT id, kode_barang, nama_barang FROM tbl_barang ORDER BY nama_barang") ?>
                  <?php while ($barang = mysqli_fetch_assoc($query_barang)): ?>

                    <option value="<?= $barang['id'] ?>"><?= "{$barang['nama_barang']} -- {$barang['kode_barang']}" ?></option>

                  <?php endwhile ?>
                  <?php mysqli_close($connection) ?>
                </select>
              </div>
            
              <div class="mb-3">
                <label class="small mb-1" for="xtanggal">Tanggal</label>
                <input type="date" name="xtanggal" value="<?= date('Y-m-d') ?>" class="form-control" id="xtanggal" required />
              </div>
            
              <div class="mb-3">
                <label class="small mb-1" for="xjumlah">Jumlah</label>
                <input type="number" name="xjumlah" min="0" class="form-control" id="xjumlah" placeholder="Enter jumlah" required />
              </div>
            
              <div class="mb-3">
                <label class="small mb-1" for="xketerangan">Keterangan</label>
                <textarea name="xketerangan" rows="4" class="form-control" id="xketerangan" placeholder="Enter keterangan"></textarea>
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
    <!--/.modal-input-barang_masuk -->
    
    <?php include '_partials/script.php' ?>
    <?php include '../helpers/sweetalert2_notify.php' ?>
    
    <!-- PAGE SCRIPT -->
    <script>
      $(document).ready(function() {
        $('.toggle_modal_tambah').on('click', function() {
          $('#ModalInputBarangMasuk .modal-title').html(`<i data-feather="plus-circle" class="me-2 mt-1"></i>Tambah Barang Masuk`);
          $('#ModalInputBarangMasuk form').attr({action: 'barang_masuk_tambah.php', method: 'post'});

          // Re-init all feather icons
          feather.replace();
          
          $('#ModalInputBarangMasuk').modal('show');
        });


        $('.toggle_modal_ubah').on('click', function() {
          const data = $(this).data();
          
          $('#ModalInputBarangMasuk .modal-title').html(`<i data-feather="edit" class="me-2 mt-1"></i>Ubah Barang Masuk`);
          $('#ModalInputBarangMasuk form').attr({action: 'barang_masuk_ubah.php', method: 'post'});

          $('#ModalInputBarangMasuk #xid_barang_masuk').val(data.id_barang_masuk);
          $('#ModalInputBarangMasuk #xid_barang').val(data.id_barang).trigger('change');
          $('#ModalInputBarangMasuk #xtanggal').val(data.tanggal);
          $('#ModalInputBarangMasuk #xjumlah').val(data.jumlah);
          $('#ModalInputBarangMasuk #xketerangan').val(data.keterangan);

          // Re-init all feather icons
          feather.replace();
          
          $('#ModalInputBarangMasuk').modal('show');
        });
        

        $('#datatablesSimple').on('click', '.toggle_swal_hapus', function() {
          const id_barang_masuk = $(this).data('id_barang_masuk');
          const nama_barang = $(this).data('nama_barang');
          const jumlah = $(this).data('jumlah');
          
          Swal.fire({
            title: "Konfirmasi Tindakan?",
            html: `<div class="mb-1">Hapus data barang masuk: </div><strong>${nama_barang} (Jumlah masuk ${jumlah})?</strong>`,
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
                window.location = `barang_masuk_hapus.php?xid_barang_masuk=${id_barang_masuk}`;
              });
            }
          });
        });
        
      });
    </script>

  </body>

  </html>

<?php endif ?>