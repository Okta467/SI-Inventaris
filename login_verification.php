<?php
	session_start();
	include_once 'config/connection.php';

	// cek apakah tombol submit ditekan sebelum memproses verifikasi login
	if (!isset($_POST['xsubmit'])) {
		$_SESSION['msg'] = 'other_error';
		echo "<meta http-equiv='refresh' content='0;index.php'>";
		return;
	}

	$username = $_POST['xusername'];
	$password = $_POST['xpassword'];


	// jalankan mysql prepare statement untuk mencegah SQL Inject
	$stmt = mysqli_stmt_init($connection);

	mysqli_stmt_prepare($stmt, "SELECT * FROM tbl_pengguna WHERE username=?");
	mysqli_stmt_bind_param($stmt, 's', $username);
	mysqli_stmt_execute($stmt);

	$result = mysqli_stmt_get_result($stmt);
	$user   = mysqli_fetch_assoc($result);

	mysqli_stmt_close($stmt);


	// redirect ke halaman login jika pengguna tidak ditemukan
	if (!$user) {
		$_SESSION['msg'] = 'user_not_found';
		echo "<meta http-equiv='refresh' content='0;index.php'>";
		return;
	}

	// cek apakah passwordnya benar?
	if (!password_verify($password, $user['password'])) {
		$_SESSION['msg'] = 'wrong_password';
		echo "<meta http-equiv='refresh' content='0;index.php'>";
		return;
	}

    // Get id_pegawai if hak akses is pengelola_surat, pegawai, or sekretaris_desa
    if (in_array($user['hak_akses'], ['pengelola_surat', 'kepala_desa', 'sekretaris_desa'])) {
        $query_pegawai = mysqli_query($connection, "SELECT id, nama_pegawai FROM tbl_pegawai WHERE id_pengguna = {$user['id']} LIMIT 1");
        $pegawai = mysqli_fetch_assoc($query_pegawai);
    }

	// set sesi user sekarang
	$_SESSION['id_pengguna']  = $user['id'];
	$_SESSION['id_pegawai']   = $pegawai['id'] ?? null;
	$_SESSION['nama_pegawai'] = $pegawai['nama_pegawai'] ?? null;
	$_SESSION['username']     = $user['username'];
	$_SESSION['hak_akses']    = $user['hak_akses'];
	$_SESSION['email']        = 'default@gmail.com';

	// Update last login user
	$last_login = date('Y-m-d H:i:s');
	$query_update = mysqli_query($connection, "UPDATE tbl_pengguna SET last_login = '{$last_login}' WHERE id = {$user['id']}");

	// alihkan user ke halamannya masing-masing
	switch ($user['hak_akses']) {
		case 'admin':
			header("location:admin?go=dashboard");
			break;

		case 'pengelola_surat':
			header("location:pengelola_surat/?go=dashboard");
			break;

		case 'kepala_desa':
			header("location:kepala_desa/?go=dashboard");
			break;

		case 'sekretaris_desa':
			header("location:sekretaris_desa/?go=dashboard");
			break;
		
		default:
			header("location:logout.php");
			break;
	}
?>