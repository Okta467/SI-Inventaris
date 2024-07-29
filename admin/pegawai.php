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

    <meta name="description" content="Data Pegawai" />
    <meta name="author" content="" />
    <title>Pegawai - <?= SITE_NAME ?></title>
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
                <h1 class="mb-0">Pegawai</h1>
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
                  <i data-feather="user" class="me-2 mt-1"></i>
                  Data Pegawai
                </div>
                <button class="btn btn-sm btn-primary toggle_modal_tambah" type="button"><i data-feather="user-plus" class="me-2"></i>Tambah Pegawai</button>
              </div>
              <div class="card-body">
                <table id="datatablesSimple">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Aksi</th>
                      <th>Nama/NIP</th>
                      <th>Jabatan</th>
                      <th>Hak Akses</th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php
                    $no = 1;
                    $query_pegawai = mysqli_query($connection, 
                      "SELECT
                        a.id AS id_pegawai, a.nip, a.nama_pegawai, a.jk, a.alamat, a.tmp_lahir, a.tgl_lahir,
                        b.id AS id_jabatan, b.nama_jabatan,
                        f.id AS id_pengguna, f.username, f.hak_akses
                      FROM tbl_pegawai AS a
                      LEFT JOIN tbl_jabatan AS b
                        ON a.id_jabatan = b.id
                      LEFT JOIN tbl_pengguna AS f
                        ON a.id_pengguna = f.id
                      ORDER BY a.id DESC");

                    while ($pegawai = mysqli_fetch_assoc($query_pegawai)):
                      $formatted_hak_akses = ucwords(str_replace('_', ' ', $pegawai['hak_akses']));
                    ?>

                      <tr>
                        <td><?= $no++ ?></td>
                        <td>
                          <div class="dropdown">
                            <button class="btn btn-sm btn-outline-blue dropdown-toggle" id="dropdownFadeIn" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Aksi</button>
                            <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownFadeIn">

                              <button class="dropdown-item toggle_modal_ubah" type="button"
                                data-id_pegawai="<?= $pegawai['id_pegawai'] ?>">
                                <i data-feather="edit" class="me-1"></i>
                                Ubah
                              </button>

                              <button class="dropdown-item text-danger toggle_swal_hapus" type="button"
                                data-id_pegawai="<?= $pegawai['id_pegawai'] ?>"
                                data-nama_pegawai="<?= $pegawai['nama_pegawai'] ?>">
                                <i data-feather="trash-2" class="me-1"></i>
                                Hapus
                              </button>

                              <div class="dropdown-divider"></div>
                              
                              <button class="dropdown-item toggle_modal_detail_pegawai" type="button"
                                data-id_pegawai="<?= $pegawai['id_pegawai'] ?>"
                                data-nama_pegawai="<?= $pegawai['nama_pegawai'] ?>"
                                data-username="<?= $pegawai['username'] ?>"
                                data-hak_akses="<?= $pegawai['hak_akses'] ? ucfirst(str_replace('_', ' ', $pegawai['hak_akses'], )) : null ?>"
                                data-alamat="<?= $pegawai['alamat'] ?>"
                                data-tmp_lahir="<?= $pegawai['tmp_lahir'] ?>"
                                data-tgl_lahir="<?= $pegawai['tgl_lahir'] ?>">
                                <i data-feather="list" class="me-1"></i>
                                Detail
                              </button>

                            </div>
                          </div>
                        </td>
                        <td>
                          <?= htmlspecialchars($pegawai['nama_pegawai']); ?>
                          <?= !empty($pegawai['nip'])
                            ? "<br><small class='text-muted'>({$pegawai['nip']})</small>"
                            : "<br><small class='text-muted'>(Tidak ada NIP)</small>"; ?>
                        </td>
                        <td>
                          <div class="ellipsis toggle_tooltip" title="<?= $pegawai['nama_jabatan'] ?>">
                            <?= $pegawai['nama_jabatan'] ?>
                          </div>
                        </td>
                        <td>
                            
                          <?php if ($pegawai['hak_akses'] === 'supervisor'): ?>
                            
                            <span class="badge bg-blue-soft text-blue"><?= $formatted_hak_akses ?></span>
                            
                          <?php elseif ($pegawai['hak_akses'] === 'sales'): ?>
                            
                            <span class="badge bg-purple-soft text-purple"><?= $formatted_hak_akses ?></span>

                          <?php else: ?>

                            <small class="text-muted">Tidak ada</small>
                            
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
    
    <!--============================= MODAL DETAIL PEGAWAI =============================-->
    <div class="modal fade" id="ModalDetailPegawai" tabindex="-1" role="dialog" aria-labelledby="ModalDetailPegawai" aria-hidden="true">
      <div class="modal-dialog" role="document" style="max-width: 600px;">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i data-feather="info" class="me-2"></i>Detail Pegawai</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            
            <div class="p-4">
              <h4><i data-feather="star" class="me-2"></i>Pegawai</h4>
              <p class="mb-0 xnama_pegawai"></p>
            </div>
            
            <div class="p-4">
              <h4><i data-feather="key" class="me-2"></i>Username</h4>
              <p class="mb-0 xusername"></p>
            </div>
            
            <div class="p-4">
              <h4><i data-feather="key" class="me-2"></i>Hak Akses</h4>
              <p class="mb-0 xhak_akses"></p>
            </div>
            
            <div class="p-4">
              <h4><i data-feather="home" class="me-2"></i>Alamat</h4>
              <p class="mb-0 xalamat"></p>
            </div>
            
            <div class="p-4">
              <h4><i data-feather="gift" class="me-2"></i>Tempat, Tanggal Lahir</h4>
              <p class="mb-0 xtmp_tgl_lahir"></p>
            </div>
          
          </div>
          <div class="modal-footer">
            <button class="btn btn-light border" type="button" data-bs-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>
    <!--/.modal-detail-pegawai -->
      
    <!--============================= MODAL INPUT PEGAWAI =============================-->
    <div class="modal fade" id="ModalInputPegawai" tabindex="-1" role="dialog" aria-labelledby="ModalInputPegawaiTitle" aria-hidden="true" data-focus="false">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="ModalInputPegawaiTitle">Modal title</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form>
            <div class="modal-body">
    
              <input type="hidden" name="xid_pegawai" id="xid_pegawai" required>
              <input type="hidden" name="xid_pengguna" id="xid_pengguna" required>
    
              <div class="mb-3">
                <label class="small mb-1" for="xnip">NIP (18 Digit) <span class="text-danger fw-bold">*</span></label>
                <input class="form-control mb-1" id="xnip" type="text" name="xnip" minlength="18" maxlength="18" placeholder="Enter NIP" required>
                <small class="text-danger">Tidak boleh sama dengan pegawai lain!</small>
              </div>
    
              <div class="mb-3">
                <label class="small mb-1" for="xnama_pegawai">Nama Pegawai <span class="text-danger fw-bold">*</span></label>
                <input class="form-control" id="xnama_pegawai" type="text" name="xnama_pegawai" placeholder="Enter nama pegawai" required>
              </div>
    
              <div class="mb-3">
                <label class="small mb-1" for="xpassword">Password <span class="text-danger fw-bold">*</span></label>
                <div class="input-group input-group-joined mb-1">
                  <input class="form-control mb-1" id="xpassword" type="password" name="xpassword" placeholder="Enter password" autocomplete="new-password" required>
                  <button class="input-group-text" id="xpassword_toggle" type="button"><i class="fa-regular fa-eye"></i></button>
                </div>
                <small class="text-muted" id="xpassword_help"></small>
              </div>
              
              <div class="mb-3">
                <label class="small mb-1" for="xhak_akses">Hak Akses <span class="text-danger fw-bold">*</span></label>
                <select name="xhak_akses" class="form-control select2" id="xhak_akses" required>
                  <option value="">-- Pilih --</option>
                  <option value="supervisor">Supervisor</option>
                  <option value="sales">Sales</option>
                  <option value="tidak_ada">Tidak ada</option>
                </select>
                <small class="text-muted">Dengan memilih hak akses `tidak ada` pegawai yang di-input tidak akan memiliki akun pengguna</small>
              </div>
    
    
              <div class="row gx-3">
    
                <div class="col-md-6">
                  <div class="form-check form-check-solid mb-3">
                    <input class="form-check-input" id="xjk_l" type="radio" name="xjk" value="l" checked required>
                    <label class="form-check-label" for="xjk_l">Laki-laki</label>
                  </div>
                </div>
    
                <div class="col-md-6">
                  <div class="form-check form-check-solid mb-3">
                    <input class="form-check-input" id="xjk_p" type="radio" name="xjk" value="p" required>
                    <label class="form-check-label" for="xjk_p">Perempuan</label>
                  </div>
                </div>
    
              </div>
    
    
              <div class="mb-3">
                <label class="small mb-1" for="xalamat">Alamat <span class="text-danger fw-bold">*</span></label>
                <input class="form-control" id="xalamat" type="text" name="xalamat" placeholder="Enter alamat" required>
              </div>
    
    
              <div class="row gx-3">
    
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="small mb-1" for="xtmp_lahir">Tempat Lahir</label>
                    <input class="form-control" id="xtmp_lahir" type="text" name="xtmp_lahir" placeholder="Enter tempat lahir" required>
                  </div>
                </div>
    
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="small mb-1" for="xtgl_lahir">Tanggal Lahir</label>
                    <input class="form-control" id="xtgl_lahir" type="date" name="xtgl_lahir" required>
                  </div>
                </div>
    
              </div>
    
    
              <div class="mb-3">
                <label class="small mb-1" for="xid_jabatan">Jabatan</label>
                <select name="xid_jabatan" class="form-control select2" id="xid_jabatan" required>
                  <option value="">-- Pilih --</option>
                  <?php $query_jabatan = mysqli_query($connection, "SELECT * FROM tbl_jabatan") ?>
                  <?php while ($jabatan = mysqli_fetch_assoc($query_jabatan)) : ?>
    
                    <option value="<?= $jabatan['id'] ?>"><?= $jabatan['nama_jabatan'] ?></option>
    
                  <?php endwhile ?>
                </select>
              </div>
    
            </div>
            <div class="modal-footer">
              <button class="btn btn-light border" type="button" data-bs-dismiss="modal">Batal</button>
              <button class="btn btn-primary" type="submit" id="toggle_swal_submit">Simpan</button>
            </div>
          </form>
        </div>
      </di>
    </div>
    <!--/.modal-input-pegawai -->
    
    <?php include '_partials/script.php' ?>
    <?php include '../helpers/sweetalert2_notify.php' ?>

    <!-- PAGE SCRIPT -->
    <script>
      let password = document.getElementById('xpassword');
      let passwordToggle = document.getElementById('xpassword_toggle');
      let passwordHelp = document.getElementById('xpassword_help');
      
      passwordToggle.addEventListener('click', function() {
        initTogglePassword(password, passwordToggle);
      });
    </script>

    <script>
      $(document).ready(function() {
        // Datatables initialise
        $('.datatables').DataTable({
          responsive: true,
          pageLength: 5,
          lengthMenu: [
            [3, 5, 10, 25, 50, 100],
            [3, 5, 10, 25, 50, 100],
          ]
        });
        
        const selectModalInputPegawai = $('#ModalInputPegawai .select2');
        initSelect2(selectModalInputPegawai, {
          width: '100%',
          dropdownParent: "#ModalInputPegawai .modal-content .modal-body"
        });

        
        $('.toggle_modal_tambah').on('click', function() {
          $('#ModalInputPegawai .modal-title').html(`<i data-feather="plus-circle" class="me-2 mt-1"></i>Tambah Pegawai`);
          $('#ModalInputPegawai form').attr({action: 'pegawai_tambah.php', method: 'post'});
          $('#ModalInputPegawai #xpassword').attr('required', true);
          $('#ModalInputPegawai #xpassword_help').html('');

          // Re-init all feather icons
          feather.replace();
          
          $('#ModalInputPegawai').modal('show');
        });

        
        $('.toggle_modal_ubah').on('click', function() {
          const id_pegawai   = $(this).data('id_pegawai');
          const nama_pegawai = $(this).data('nama_pegawai');
          
          $('#ModalInputPegawai .modal-title').html(`<i data-feather="edit" class="me-2 mt-1"></i>Ubah Pegawai`);
          $('#ModalInputPegawai form').attr({action: 'pegawai_ubah.php', method: 'post'});
          $('#ModalInputPegawai #xpassword').attr('required', false);
          $('#ModalInputPegawai #xpassword_help').html('Kosongkan jika tidak ingin ubah password.');
        
          $.ajax({
            url: 'get_pegawai.php',
            method: 'POST',
            dataType: 'JSON',
            data: {
              'id_pegawai': id_pegawai
            },
            success: function(data) {
              let hak_akses = data[0].hak_akses;
              
              if (!hak_akses || !['pengelola_surat', 'kepala_desa', 'sekretaris_desa'].includes(hak_akses)) {
                hak_akses = 'tidak_ada'
              }

              $('#ModalInputPegawai #xid_pegawai').val(data[0].id_pegawai);
              $('#ModalInputPegawai #xid_pengguna').val(data[0].id_pengguna);
              $('#ModalInputPegawai #xnip').val(data[0].nip);
              $('#ModalInputPegawai #xnama_pegawai').val(data[0].nama_pegawai);
              $('#ModalInputPegawai #xhak_akses').val(hak_akses).trigger('change');
              $(`#ModalInputPegawai input[name="xjk"][value="${data[0].jk}"]`).prop('checked', true)
              $('#ModalInputPegawai #xalamat').val(data[0].alamat);
              $('#ModalInputPegawai #xtmp_lahir').val(data[0].tmp_lahir);
              $('#ModalInputPegawai #xtgl_lahir').val(data[0].tgl_lahir);
              $('#ModalInputPegawai .select2#xid_jabatan').val(data[0].id_jabatan).trigger('change');
              
              // Re-init all feather icons
              feather.replace();
              
              $('#ModalInputPegawai').modal('show');
            },
            error: function(request, status, error) {
              // console.log("ajax call went wrong:" + request.responseText);
              console.log("ajax call went wrong:" + error);
            }
          });
        });

        
        $('.toggle_modal_detail_pegawai').on('click', function() {
          const data = $(this).data();
        
          $('#ModalDetailPegawai .xnama_pegawai').html(data.nama_pegawai);
          $('#ModalDetailPegawai .xusername').html(data.username || 'Tidak ada (akun belum dibuat)');
          $('#ModalDetailPegawai .xhak_akses').html(data.hak_akses || 'Tidak ada (akun belum dibuat)');
          $('#ModalDetailPegawai .xalamat').html(data.alamat);
          $('#ModalDetailPegawai .xtmp_tgl_lahir').html(`${data.tmp_lahir}, ${moment(data.tgl_lahir).format("DD MMMM YYYY")}`);
        
          $('#ModalDetailPegawai').modal('show');
        });
        

        $('#datatablesSimple').on('click', '.toggle_swal_hapus', function() {
          console.log('aa')
          const id_pegawai   = $(this).data('id_pegawai');
          const id_pengguna  = $(this).data('id_pengguna');
          const nama_pegawai = $(this).data('nama_pegawai');
          
          Swal.fire({
            title: "Konfirmasi Tindakan?",
            html: `Hapus data pegawai: <strong>${nama_pegawai}?</strong>`,
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
                window.location = `pegawai_hapus.php?xid_pegawai=${id_pegawai}&xid_pengguna=${id_pengguna}`;
              });
            }
          });
        });
        

        const formSubmitBtn = $('#toggle_swal_submit');
        const eventName = 'click';
        
        toggleSwalSubmit(formSubmitBtn, eventName);
        
      });
    </script>

  </body>

  </html>

<?php endif ?>