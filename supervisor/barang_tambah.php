<?php
    include_once '../helpers/isAccessAllowedHelper.php';

    // cek apakah user yang mengakses adalah supervisor?
    if (!isAccessAllowed('supervisor')) {
        session_destroy();
        echo "<meta http-equiv='refresh' content='0;" . base_url_return('index.php?msg=other_error') . "'>";
        return;
    }

    require_once '../vendor/htmlpurifier/HTMLPurifier.auto.php';
    include_once '../config/connection.php';

    // to sanitize user input
    $config   = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);
    
    $kode_barang = htmlspecialchars($purifier->purify($_POST['xkode_barang']));
    $nama_barang = htmlspecialchars($purifier->purify($_POST['xnama_barang']));
    $satuan = $_POST['xsatuan'];
    $is_allowed_satuan = $satuan && in_array($satuan, ['pcs', 'box', 'dus']);

    if (!$is_allowed_satuan) {
        $_SESSION['msg'] = 'Satuan yang dipilih tidak diperbolehkan!';
        echo "<meta http-equiv='refresh' content='0;barang.php?go=barang'>";
        return;
    }
    
    $stmt = mysqli_stmt_init($connection);

    mysqli_stmt_prepare($stmt, "INSERT INTO tbl_barang (kode_barang, nama_barang, satuan) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'sss', $kode_barang, $nama_barang, $satuan);

    $insert = mysqli_stmt_execute($stmt);

    !$insert
        ? $_SESSION['msg'] = 'other_error'
        : $_SESSION['msg'] = 'save_success';

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    echo "<meta http-equiv='refresh' content='0;barang.php?go=barang'>";
?>