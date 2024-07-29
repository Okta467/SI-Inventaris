<?php
    include_once '../helpers/isAccessAllowedHelper.php';

    // cek apakah user yang mengakses adalah admin?
    if (!isAccessAllowed('sales')) {
        session_destroy();
        echo "<meta http-equiv='refresh' content='0;" . base_url_return('index.php?msg=other_error') . "'>";
        return;
    }

    include_once '../config/connection.php';

    $id_barang = $_POST['xid_barang'];
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
        GROUP BY a.id
        ORDER BY a.id DESC";

    mysqli_stmt_prepare($stmt_barang, $query_barang);
    mysqli_stmt_bind_param($stmt_barang, 'i', $id_barang);
    mysqli_stmt_execute($stmt_barang);

    $result = mysqli_stmt_get_result($stmt_barang);
    $barang = mysqli_fetch_assoc($result);

    if ($jumlah > $barang['stok']) {
        $_SESSION['msg'] = 'Jumlah barang keluar tidak boleh melebihi stok!';
        echo "<meta http-equiv='refresh' content='0;barang_keluar.php?go=barang_keluar'>";
        return;
    }

    $stmt_insert = mysqli_stmt_init($connection);

    mysqli_stmt_prepare($stmt_insert, "INSERT INTO tbl_barang_keluar (id_barang, tanggal, jumlah) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt_insert, 'isi', $id_barang, $tanggal, $jumlah);

    $insert = mysqli_stmt_execute($stmt_insert);

    !$insert
        ? $_SESSION['msg'] = 'other_error'
        : $_SESSION['msg'] = 'save_success';

    mysqli_stmt_close($stmt_barang);
    mysqli_stmt_close($stmt_insert);
    mysqli_close($connection);

    echo "<meta http-equiv='refresh' content='0;barang_keluar.php?go=barang_keluar'>";
?>