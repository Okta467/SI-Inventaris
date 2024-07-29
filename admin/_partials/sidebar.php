<?php
$current_page = $_GET['go'] ?? '';
$user_logged_in = $_SESSION['nama_pegawai'] ?? $_SESSION['nama_guest'] ?? $_SESSION['username'];
?>

<nav class="sidenav shadow-right sidenav-light">
  <div class="sidenav-menu">
    <div class="nav accordion" id="accordionSidenav">
      <!-- Sidenav Menu Heading (Core)-->
      <div class="sidenav-menu-heading">Core</div>
      
      <a class="nav-link <?php if ($current_page === 'dashboard') echo 'active' ?>" href="index.php?go=dashboard">
        <div class="nav-link-icon"><i data-feather="activity"></i></div>
        Dashboard
      </a>

      <div class="sidenav-menu-heading">Pengguna</div>
      
      <a class="nav-link <?php if ($current_page === 'pengguna') echo 'active' ?>" href="pengguna.php?go=pengguna">
        <div class="nav-link-icon"><i data-feather="users"></i></div>
        Pengguna
      </a>

      <div class="sidenav-menu-heading">Barang</div>
      
      <a class="nav-link <?php if ($current_page === 'barang') echo 'active' ?>" href="barang.php?go=barang">
        <div class="nav-link-icon"><i data-feather="package"></i></div>
        Barang
      </a>
      
      <a class="nav-link <?php if ($current_page === 'barang_masuk') echo 'active' ?>" href="barang_masuk.php?go=barang_masuk">
        <div class="nav-link-icon"><i data-feather="download"></i></div>
        Barang Masuk
      </a>
      
      <a class="nav-link <?php if ($current_page === 'barang_keluar') echo 'active' ?>" href="barang_keluar.php?go=barang_keluar">
        <div class="nav-link-icon"><i data-feather="upload"></i></div>
        Barang keluar
      </a>
      
      <div class="sidenav-menu-heading">Pegawai</div>
      
      <a class="nav-link <?php if ($current_page === 'pegawai') echo 'active' ?>" href="pegawai.php?go=pegawai">
        <div class="nav-link-icon"><i data-feather="user"></i></div>
        Pegawai
      </a>
      
      <a class="nav-link <?php if ($current_page === 'jabatan') echo 'active' ?>" href="jabatan.php?go=jabatan">
        <div class="nav-link-icon"><i data-feather="briefcase"></i></div>
        Jabatan
      </a>
      
      <div class="sidenav-menu-heading">Lainnya</div>
      
      <a class="nav-link" href="<?= base_url('logout.php') ?>">
        <div class="nav-link-icon"><i data-feather="log-out"></i></div>
        Keluar
      </a>

    </div>
  </div>
  <!-- Sidenav Footer-->
  <div class="sidenav-footer">
    <div class="sidenav-footer-content">
      <div class="sidenav-footer-subtitle">Anda masuk sebagai:</div>
      <div class="sidenav-footer-title"><?= ucwords($user_logged_in) ?></div>
    </div>
  </div>
</nav>