<?php
    include_once '../helpers/isAccessAllowedHelper.php';

    // cek apakah user yang mengakses adalah admin?
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
    
    $id_barang_masuk = $_POST['xid_barang_masuk'];
    $id_barang = $_POST['xid_barang'];
    $tanggal = $_POST['xtanggal'];
    $jumlah = $_POST['xjumlah'];
    $keterangan = htmlspecialchars($purifier->purify($_POST['xketerangan']));

    $stmt = mysqli_stmt_init($connection);

    mysqli_stmt_prepare($stmt, "UPDATE tbl_barang_masuk SET id_barang=?, tanggal=?, jumlah=?, keterangan=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'isisi', $id_barang, $tanggal, $jumlah, $keterangan, $id_barang_masuk);

    $update = mysqli_stmt_execute($stmt);

    !$update
        ? $_SESSION['msg'] = 'other_error'
        : $_SESSION['msg'] = 'update_success';

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    echo "<meta http-equiv='refresh' content='0;barang_masuk.php?go=barang_masuk'>";
?>