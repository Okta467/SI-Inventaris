<?php
    include_once '../helpers/isAccessAllowedHelper.php';

    // cek apakah user yang mengakses adalah sales?
    if (!isAccessAllowed('sales')) {
        session_destroy();
        echo "<meta http-equiv='refresh' content='0;" . base_url_return('index.php?msg=other_error') . "'>";
        return;
    }

    include_once '../config/connection.php';

    $id_barang_keluar = $_POST['xid_barang_keluar'];
    $current_id_barang = $_POST['xcurrent_id_barang'];
    $id_barang = $_POST['xid_barang'] ?? $current_id_barang ?? null;
    $tanggal = $_POST['xtanggal'];
    $jumlah = $_POST['xjumlah'];

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

    if (!$barang) {
        $_SESSION['msg'] = 'Barang yang dipilih tidak ada!';
        $_SESSION['msg'] = 'Jumlah barang keluar tidak boleh melebihi stok!';
        echo "<meta http-equiv='refresh' content='0;barang_keluar.php?go=barang_keluar'>";
        return;
    }
    
    if ($barang['stok'] > 0 && $jumlah > $barang['stok']) {
        $_SESSION['msg'] = 'Jumlah barang keluar tidak boleh melebihi stok!';
        echo "<meta http-equiv='refresh' content='0;barang_keluar.php?go=barang_keluar'>";
        return;
    }

    $stmt_update = mysqli_stmt_init($connection);

    if ($barang['stok'] > 0) {
        mysqli_stmt_prepare($stmt_update, "UPDATE tbl_barang_keluar SET id_barang=?, tanggal=?, jumlah=? WHERE id=?");
        mysqli_stmt_bind_param($stmt_update, 'isii', $id_barang, $tanggal, $jumlah, $id_barang_keluar);
    } else {
        mysqli_stmt_prepare($stmt_update, "UPDATE tbl_barang_keluar SET tanggal=? WHERE id=?");
        mysqli_stmt_bind_param($stmt_update, 'si', $tanggal, $id_barang_keluar);
    }

    $update = mysqli_stmt_execute($stmt_update);

    !$update
        ? $_SESSION['msg'] = 'other_error'
        : $_SESSION['msg'] = 'save_success';

    mysqli_stmt_close($stmt_barang);
    mysqli_stmt_close($stmt_update);
    mysqli_close($connection);

    echo "<meta http-equiv='refresh' content='0;barang_keluar.php?go=barang_keluar'>";
?>