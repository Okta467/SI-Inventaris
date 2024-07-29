<?php
    include_once '../helpers/isAccessAllowedHelper.php';

    // cek apakah user yang mengakses adalah supervisor?
    if (!isAccessAllowed('supervisor')) {
        session_destroy();
        echo "<meta http-equiv='refresh' content='0;" . base_url_return('index.php?msg=other_error') . "'>";
        return;
    }

    require_once '../helpers/fileUploadHelper.php';
    require_once '../helpers/getHashedFileNameHelper.php';
    include_once '../config/connection.php';

    $id_pegawai                  = $_SESSION['id_pegawai'];
    $halaman_saat_ini            = $_POST['xhalaman_saat_ini'] ?? 'profil';
    $is_allowed_halaman_saat_ini = in_array($halaman_saat_ini, ['profil', 'password']);
    $foto_profil                 = $_FILES['xfoto_profil'];
    $is_foto_profil_uploaded     = file_exists($foto_profil['tmp_name']) || is_uploaded_file($foto_profil['tmp_name']);

    if ($halaman_saat_ini === 'profil') {
        $halaman_saat_ini = 'profil.php?go=profil';
    } else if ($halaman_saat_ini === 'password') {
        $halaman_saat_ini = 'profil_password.php?go=profil';
    } else {
        $halaman_saat_ini = null;
    }

    if (!$is_allowed_halaman_saat_ini) {
        $_SESSION['msg'] = 'other_error';
        echo "<meta http-equiv='refresh' content='0;{$halaman_saat_ini}'>";
        return;
    }

    if (!$is_foto_profil_uploaded) {
        $_SESSION['msg'] = 'Foto profil tidak diunggah!';
        echo "<meta http-equiv='refresh' content='0;{$halaman_saat_ini}'>";
        return;
    }

    $stmt_pegawai = mysqli_stmt_init($connection);

    mysqli_stmt_prepare($stmt_pegawai, "SELECT id, foto_profil FROM tbl_pegawai WHERE id=?");
    mysqli_stmt_bind_param($stmt_pegawai, 'i', $id_pegawai);
    mysqli_stmt_execute($stmt_pegawai);

    $result = mysqli_stmt_get_result($stmt_pegawai);
    $pegawai = mysqli_fetch_assoc($result);

    if (!$pegawai) {
        $_SESSION['msg'] = 'Pegawai tidak ditemukan!';
        echo "<meta http-equiv='refresh' content='0;{$halaman_saat_ini}'>";
        return;
    }

    // Set upload configuration
    $target_dir    = '../assets/uploads/foto_profil/';
    $max_file_size = 500 * 1024; // 500KB in bytes
    $allowed_types = ['jpg', 'png'];

    // Upload foto_profil using the configuration
    $upload_foto_profil = fileUpload($foto_profil, $target_dir, $max_file_size, $allowed_types);
    $nama_berkas        = $upload_foto_profil['hashedFilename'];
    $is_upload_success  = $upload_foto_profil['isUploaded'];
    $upload_messages    = $upload_foto_profil['messages'];

    // Check is file uploaded?
    if (!$is_upload_success) {
        $_SESSION['msg'] = $upload_messages;
        echo "<meta http-equiv='refresh' content='0;{$halaman_saat_ini}'>";
        return;
    }

    // Delete old file if exists
    $old_foto_profil = $pegawai['foto_profil'];
    $file_path_to_unlink = $target_dir . $old_foto_profil;
    
    // Delete the old foto_profil
    if ($old_foto_profil && file_exists($file_path_to_unlink)) {
        unlink("{$target_dir}{$old_foto_profil}");
    }

    $stmt_update = mysqli_stmt_init($connection);

    mysqli_stmt_prepare($stmt_update, "UPDATE tbl_pegawai SET foto_profil=? WHERE id=?");
    mysqli_stmt_bind_param($stmt_update, 'si', $nama_berkas, $id_pegawai);

    $update = mysqli_stmt_execute($stmt_update);

    !$update
        ? $_SESSION['msg'] = 'other_error'
        : $_SESSION['msg'] = 'update_success';

    mysqli_stmt_close($stmt_pegawai);
    mysqli_stmt_close($stmt_update);
    mysqli_close($connection);
    
    echo "<meta http-equiv='refresh' content='0;{$halaman_saat_ini}'>";
?>
