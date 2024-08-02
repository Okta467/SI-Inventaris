<?php
    include_once '../helpers/isAccessAllowedHelper.php';

    // cek apakah user yang mengakses adalah admin?
    if (!isAccessAllowed('admin')) {
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

    $stmt_barang_masuk = mysqli_stmt_init($connection);
    $query_barang_masuk = "SELECT jumlah FROM tbl_barang_masuk WHERE id=?";

    mysqli_stmt_prepare($stmt_barang_masuk, $query_barang_masuk);
    mysqli_stmt_bind_param($stmt_barang_masuk, 'i', $id_barang_masuk);
    mysqli_stmt_execute($stmt_barang_masuk);

    $result = mysqli_stmt_get_result($stmt_barang_masuk);
    $barang_masuk = mysqli_fetch_assoc($result);

    $stmt_barang = mysqli_stmt_init($connection);
    $query_barang = 
        "SELECT
            a.id AS id_barang, a.kode_barang, a.nama_barang, a.satuan,
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
        WHERE a.id=?
        GROUP BY a.id";

    mysqli_stmt_prepare($stmt_barang, $query_barang);
    mysqli_stmt_bind_param($stmt_barang, 'i', $id_barang);
    mysqli_stmt_execute($stmt_barang);

    $result = mysqli_stmt_get_result($stmt_barang);
    $barang = mysqli_fetch_assoc($result);

    if (!$barang || !$barang_masuk) {
        $_SESSION['msg'] = 'Data Barang atau Barang Masuk tidak ada!';
        echo "<meta http-equiv='refresh' content='0;barang_masuk.php?go=barang_masuk'>";
        return;
    }

    if ($barang['stok'] - ($barang_masuk['jumlah'] - $jumlah) < 0) {
        $_SESSION['msg'] = 'Stok - (jumlah saat ini - jumlah input) kurang dari 0!';
        echo "<meta http-equiv='refresh' content='0;barang_masuk.php?go=barang_masuk'>";
        return; 
    }

    $stmt = mysqli_stmt_init($connection);

    mysqli_stmt_prepare($stmt, "UPDATE tbl_barang_masuk SET id_barang=?, tanggal=?, jumlah=?, keterangan=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'isisi', $id_barang, $tanggal, $jumlah, $keterangan, $id_barang_masuk);

    $update = mysqli_stmt_execute($stmt);

    !$update
        ? $_SESSION['msg'] = 'other_error'
        : $_SESSION['msg'] = 'update_success';

    mysqli_stmt_close($stmt_barang_masuk);
    mysqli_stmt_close($stmt_barang);
    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    echo "<meta http-equiv='refresh' content='0;barang_masuk.php?go=barang_masuk'>";
?>