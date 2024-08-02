<?php
    include_once '../helpers/isAccessAllowedHelper.php';

    // cek apakah user yang mengakses adalah admin?
    if (!isAccessAllowed('admin')) {
        session_destroy();
        echo "<meta http-equiv='refresh' content='0;" . base_url_return('index.php?msg=other_error') . "'>";
        return;
    }

    include_once '../config/connection.php';
    
    $id_barang_masuk = $_GET['xid_barang_masuk'];
    $id_barang = $_GET['xid_barang'];

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

    if ($barang['stok'] - $barang_masuk['jumlah'] < 0) {
        $_SESSION['msg'] = 'Stok - jumlah barang masuk saat ini kurang dari 0!';
        echo "<meta http-equiv='refresh' content='0;barang_masuk.php?go=barang_masuk'>";
        return; 
    }
    
    $stmt = mysqli_stmt_init($connection);

    mysqli_stmt_prepare($stmt, "DELETE FROM tbl_barang_masuk WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'i', $id_barang_masuk);

    $delete = mysqli_stmt_execute($stmt);

    !$delete
        ? $_SESSION['msg'] = 'other_error'
        : $_SESSION['msg'] = 'delete_success';

    mysqli_stmt_close($stmt_barang_masuk);
    mysqli_stmt_close($stmt_barang);
    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    echo "<meta http-equiv='refresh' content='0;barang_masuk.php?go=barang_masuk'>";
?>