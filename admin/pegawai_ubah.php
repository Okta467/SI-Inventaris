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
    
    $id_pegawai          = $_POST['xid_pegawai'];
    $id_pengguna         = $_POST['xid_pengguna'];
    $nip                 = $_POST['xnip'];
    $nama_pegawai        = htmlspecialchars($purifier->purify($_POST['xnama_pegawai']));
    $username            = $nip;
    $password            = $_POST['xpassword'] ? password_hash($_POST['xpassword'], PASSWORD_DEFAULT) : null;
    $hak_akses           = $_POST['xhak_akses'];
    $jk                  = $_POST['xjk'];
    $alamat              = htmlspecialchars($purifier->purify($_POST['xalamat']));
    $tmp_lahir           = htmlspecialchars($purifier->purify($_POST['xtmp_lahir']));
    $tgl_lahir           = $_POST['xtgl_lahir'];
    $id_jabatan          = $_POST['xid_jabatan'];

    $is_allowed_hak_akses = $hak_akses && in_array($hak_akses, ['sales', 'supervisor', 'tidak_ada']);

    if (!$is_allowed_hak_akses) {
        $_SESSION['msg'] = 'Hak akses yang dipilih tidak diperbolehkan!';
        echo "<meta http-equiv='refresh' content='0;pegawai.php?go=pegawai'>";
        return;
    }

    mysqli_autocommit($connection, false);

    $success = true;

    try {
        if ($hak_akses === 'tidak_ada') {
            $stmt_pengguna = mysqli_stmt_init($connection);

            mysqli_stmt_prepare($stmt_pengguna, "DELETE FROM tbl_pengguna WHERE id=?");
            mysqli_stmt_bind_param($stmt_pengguna, 's', $id_pengguna);

            if (!mysqli_stmt_execute($stmt_pengguna)) {
                $_SESSION['msg'] = 'Statement Pengguna preparation failed: ' . mysqli_stmt_error($stmt_pengguna);
                echo "<meta http-equiv='refresh' content='0;pegawai.php?go=pegawai'>";
                return;
            }

            $id_pengguna = NULL;
        } else {
            $stmt_pengguna = mysqli_stmt_init($connection);

            if (!$password) {
                // Set default password for insertion into tbl_pengguna if data is not exists
                $password = password_hash('123456', PASSWORD_DEFAULT);        
                
                $query_pengguna = 
                    "INSERT INTO tbl_pengguna (username, hak_akses, password)
                    VALUES (?, ?, ?)
                    ON DUPLICATE KEY UPDATE username=VALUES(username), hak_akses=VALUES(hak_akses)";
            } else {
                $query_pengguna = 
                    "INSERT INTO tbl_pengguna (username, hak_akses, password)
                    VALUES (?, ?, ?)
                    ON DUPLICATE KEY UPDATE username=VALUES(username), hak_akses=VALUES(hak_akses), password=VALUES(password)";
            }
            
            if (!mysqli_stmt_prepare($stmt_pengguna, $query_pengguna)) {
                $_SESSION['msg'] = 'Statement Pengguna preparation failed: ' . mysqli_stmt_error($stmt_pengguna);
                echo "<meta http-equiv='refresh' content='0;pegawai.php?go=pegawai'>";
                return;
            }
            
            mysqli_stmt_bind_param($stmt_pengguna, 'sss', $username, $hak_akses, $password);

            if (!mysqli_stmt_execute($stmt_pengguna)) {
                $_SESSION['msg'] = 'Statement Pengguna preparation failed: ' . mysqli_stmt_error($stmt_pengguna);
                echo "<meta http-equiv='refresh' content='0;pegawai.php?go=pegawai'>";
                return;
            }
            
            $id_pengguna = !$id_pengguna ? mysqli_insert_id($connection) : $id_pengguna;
        }
        
        // Pegawai statement preparation and execution
        $stmt_pegawai  = mysqli_stmt_init($connection);
        $query_pegawai = "UPDATE tbl_pegawai SET
            id_pengguna = ?
            , id_jabatan = ?
            , nip = ?
            , nama_pegawai = ?
            , jk = ?
            , alamat = ?
            , tmp_lahir = ?
            , tgl_lahir = ?
        WHERE id = ?";
        
        if (!mysqli_stmt_prepare($stmt_pegawai, $query_pegawai)) {
            $_SESSION['msg'] = 'Statement Pegawai preparation failed: ' . mysqli_stmt_error($stmt_pegawai);
            echo "<meta http-equiv='refresh' content='0;pegawai.php?go=pegawai'>";
            return;
        }
        
        mysqli_stmt_bind_param($stmt_pegawai, 'iiisssssi', $id_pengguna, $id_jabatan, $nip, $nama_pegawai, $jk, $alamat, $tmp_lahir, $tgl_lahir, $id_pegawai);
        
        if (!mysqli_stmt_execute($stmt_pegawai)) {
            $_SESSION['msg'] = 'Statement Pegawai preparation failed: ' . mysqli_stmt_error($stmt_pegawai);
            echo "<meta http-equiv='refresh' content='0;pegawai.php?go=pegawai'>";
            return;
        }

        // Commit the transaction if all statements succeed
        if (!mysqli_commit($connection)) {
            $_SESSION['msg'] = 'Transaction commit failed: ' . mysqli_stmt_error($stmt_pengguna);
            echo "<meta http-equiv='refresh' content='0;pegawai.php?go=pegawai'>";
            return;
        }

    } catch (Exception $e) {
        // Roll back the transaction if any statement fails
        $success = false;
        mysqli_rollback($connection);
    }

    if ($hak_akses !== 'tidak_ada') {
        mysqli_stmt_close($stmt_pengguna);
    }

    mysqli_stmt_close($stmt_pegawai);

    mysqli_autocommit($connection, true);
    mysqli_close($connection);

    !$success
        ? $_SESSION['msg'] = 'other_error'
        : $_SESSION['msg'] = 'save_success';

    echo "<meta http-equiv='refresh' content='0;pegawai.php?go=pegawai'>";
?>
