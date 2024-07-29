<?php
    include_once '../helpers/isAccessAllowedHelper.php';

    // cek apakah user yang mengakses adalah admin?
    if (!isAccessAllowed('admin')) {
        session_destroy();
        echo "<meta http-equiv='refresh' content='0;" . base_url_return('index.php?msg=other_error') . "'>";
        return;
    }

    include_once '../config/connection.php';
    
    $id_pegawai = $_POST['id_pegawai'];

    $stmt1 = mysqli_stmt_init($connection);
    $query = 
        "SELECT
            a.id AS id_pegawai, a.nip, a.nama_pegawai, a.jk, a.alamat, a.tmp_lahir, a.tgl_lahir,
            b.id AS id_jabatan, b.nama_jabatan,
            f.id AS id_pengguna, f.username, f.hak_akses
        FROM tbl_pegawai AS a
        LEFT JOIN tbl_jabatan AS b
            ON a.id_jabatan = b.id
        LEFT JOIN tbl_pengguna AS f
            ON a.id_pengguna = f.id
        WHERE a.id=?";

    mysqli_stmt_prepare($stmt1, $query);
    mysqli_stmt_bind_param($stmt1, 'i', $id_pegawai);
    mysqli_stmt_execute($stmt1);

	$result = mysqli_stmt_get_result($stmt1);

    $pegawais = !$result
        ? array()
        : mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_stmt_close($stmt1);
    mysqli_close($connection);

    echo json_encode($pegawais);

?>