<?php
    include_once '../helpers/isAccessAllowedHelper.php';

    // cek apakah user yang mengakses adalah sales?
    if (!isAccessAllowed('sales')) {
        session_destroy();
        echo "<meta http-equiv='refresh' content='0;" . base_url_return('index.php?msg=other_error') . "'>";
        return;
    }

    require_once '../vendor/htmlpurifier/HTMLPurifier.auto.php';
    include_once '../config/connection.php';

    // to sanitize user input
    $config   = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);
    
    $id_pegawai = $_SESSION['id_pegawai'];
    $alamat     = htmlspecialchars($purifier->purify($_POST['xalamat']));
    $tmp_lahir  = htmlspecialchars($purifier->purify($_POST['xtmp_lahir']));
    $tgl_lahir  = $_POST['xtgl_lahir'];
    $id_jabatan = $_POST['xid_jabatan'];

    $stmt = mysqli_stmt_init($connection);
    $query = "UPDATE tbl_pegawai SET
        id_jabatan = ?
        , alamat = ?
        , tmp_lahir = ?
        , tgl_lahir = ?
    WHERE id = ?";

    mysqli_stmt_prepare($stmt, $query);
    mysqli_stmt_bind_param($stmt, 'isssi', $id_jabatan, $alamat, $tmp_lahir, $tgl_lahir, $id_pegawai);
    mysqli_stmt_execute($stmt);

    $update = mysqli_stmt_execute($stmt);

    !$update
        ? $_SESSION['msg'] = 'other_error'
        : $_SESSION['msg'] = 'update_success';

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    echo "<meta http-equiv='refresh' content='0;profil.php?go=profil'>";
?>
