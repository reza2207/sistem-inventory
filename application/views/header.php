<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Aplikasi Monitoring Stok Barang">
    <meta name="author" content="">

    <link rel="icon" href="../../../../favicon.ico">

    <title><?= isset($title) ? $title : 'untitled' ;?></title>

    <!-- Bootstrap core CSS -->

    <link href="<?= base_url().'assets/css/bootstrap.min.css';?>" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?= base_url().'assets/css/dashboard.css';?>" rel="stylesheet">
     <link href="<?= base_url().'assets/css/sweetalert2.min.css';?>" rel="stylesheet">

    <link href="<?= base_url().'assets/css/bootstrap-datetimepicker.min.css';?>" rel="stylesheet">

      <link href="<?= base_url().'assets/select2-4.0.4/dist/css/select2.min.css';?>" rel="stylesheet">
    <!-- Datatables -->
    <link href="<?= base_url().'assets/datatables/datatables.min.css';?>" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= base_url().'assets/datatables/DataTables-1.10.18/css/dataTables.bootstrap4.min.css';?>"/>
    <link href="<?= base_url().'assets/css/jquery-ui.css'?>" rel="stylesheet">
  </head>

  <body>
    
    <nav class="navbar navbar-dark navbar-expand-lg fixed-top flex-md-nowrap p-0 shadow " style="background:#9ACD32">
      <button class="navbar-toggler" type="button" id="btn-menu" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand col-9 col-md-2 col-lg-2" href="#"><b>PT.Suryaindah Wiraperkasa</b></a>
     
      <!-- <ul class="navbar-nav col-4 col-md-10 px-3 d-lg-inline-block d-none d-lg-block">
        <button class="btn btn-dark Visible only on lg" id="btn-menu-lg"><i class="fa fa-bars"></i></button>  
      </ul> -->
    </nav>
     

    <div class="container-fluid" >
      <div class="row">
        <!-- <nav class="col-3 col-md-2 d-none bg-light sidebar"> -->
        <nav class="col-6 col-md-2 bg-light sidebar d-md-block d-none d-lg-block" id="navbarNavDropdown">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link<?php if(isset($page)){ if($page_active== 'home'){ echo ' act';}};?>" href="<?= base_url().'home';?>"><i class="fa fa-home"></i> Home</a>
              </li>
              <li class="nav-item">
                
                <li class="nav-item">
                  <a class="nav-link"><i class="fa fa-user"></i> Hi <?= $fullname;?> !</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#" id="sign_out"><i class="fa fa-sign-out-alt"></i> Sign Out </a>
                </li>
                <div class="dropdown-divider"></div>
                <?php if($_SESSION['role'] == 'admin'){?>
                <div class="accordion">
                  <button class="btn btn-link" data-toggle="collapse" data-target="#supplier-tab">
                    Master Data
                    <i class="fa fa-angle-down"></i>
                  </button>
                </div>
                <div id="supplier-tab" class="collapse<?php if(isset($page_active)){ if($page_active == 'master'){ echo ' show';}};?>" >
                  <a href="<?= base_url().'supplier/master';?>" class="nav-link<?php if(isset($title)){ if($title == 'Master Supplier'){ echo ' act';}};?>"><i class="fa fa-angle-right"></i> Supplier</a>
                  <a href="<?= base_url().'barang/master';?>" class="nav-link<?php if(isset($title)){ if($title == 'Master Barang'){ echo ' act';}};?>"><i class="fa fa-angle-right"></i> Barang</a>
                  <a href="<?= base_url().'customer/master';?>" class="nav-link<?php if(isset($title)){ if($title == 'Master Customer'){ echo ' act';}};?>"><i class="fa fa-angle-right"></i> Customer</a>
                </div>
                <?php }?>
                <?php if($_SESSION['role'] != 'admin'){?>
                <div class="accordion">
                  <button class="btn btn-link" href="#" data-toggle="collapse" data-target="#barang-tab">
                    Transaksi
                    <i class="fa fa-angle-down" id="ikon-barang"></i>
                  </button>
                </div>
                <div id="barang-tab" class="collapse<?php if(isset($page_active)){ if($page_active == 'transaksi'){ echo ' show';}};?>">
                  
                  <a href="<?= base_url().'barang/penerimaan';?>" class="nav-link<?php if(isset($title)){ if($title == 'Penerimaan Barang'){ echo ' act';}};?>"><i class="fa fa-angle-right"></i> Penerimaan Barang</a>
                  <a href="<?= base_url().'barang/pengeluaran';?>" class="nav-link<?php if(isset($title)){ if($title == 'Pengeluaran Barang'){ echo ' act';}};?>"><i class="fa fa-angle-right"></i> Pengeluaran Barang</a>
                  
                </div>
                <div class="accordion">
                  <button class="btn btn-link" href="#" data-toggle="collapse" data-target="#return-tab">
                    Return
                    <i class="fa fa-angle-down" id="ikon-customer"></i>
                  </button>
                </div>
                <div id="return-tab" class="collapse<?php if(isset($page_active)){ if($page_active == 'return'){ echo ' show';}};?>">
                  <a href="<?= base_url().'retur/masuk';?>" class="nav-link<?php if(isset($title)){ if($title == 'Retur Barang Masuk'){ echo ' act';}};?>"><i class="fa fa-angle-right"></i> Retur Barang Masuk</a>
                  <a href="<?= base_url().'retur/keluar';?>" class="nav-link<?php if(isset($title)){ if($title == 'Retur Barang Keluar'){ echo ' act';}};?>"><i class="fa fa-angle-right"></i> Retur Barang Keluar</a>
                </div>

                <?php }?>
                <?php if($_SESSION['role'] != 'operator'){?>
                <div class="accordion">
                  <button class="btn btn-link" href="#" data-toggle="collapse" data-target="#reporting-tab">
                    Reporting
                    <i class="fa fa-angle-down"></i>
                  </button>
                </div>
                <div id="reporting-tab" class="collapse<?php if(isset($page_active)){ if($page_active == 'report'){ echo ' show';}};?>">
                  <a href="<?= base_url().'report/bulanan';?>" class="nav-link<?php if(isset($title)){ if($title == 'Report Bulanan'){ echo ' act';}};?>"><i class="fa fa-angle-right"></i> Report Bulanan</a>
                  <a href="<?= base_url().'report/harian';?>" class="nav-link<?php if(isset($title)){ if($title == 'Report Harian'){ echo ' act';}};?>"><i class="fa fa-angle-right"></i> Report Harian</a>
                </div>
                <?php }?>
                
                <li class="nav-item">
                  <a class="nav-link" href="<?= base_url().'Stock_opname';?>" style="padding-left: 13px">Stock Opname</a>
                </li>
                
              </li>
            <?php if($_SESSION['role'] == 'admin'){?>
              <div class="accordion">
                <button class="btn btn-link" href="#" data-toggle="collapse" data-target="#user-tab">
                  Kelola User
                  <i class="fa fa-angle-down"></i>
                </button>
              </div>
              <div id="user-tab" class="collapse<?php if(isset($page_active)){ if($page_active == 'user'){ echo ' show';}};?>">
                  <a href="<?= base_url().'user';?>" class="nav-link<?php if(isset($title)){ if($title == 'Register'){ echo ' act';}};?>"><i class="fa fa-angle-right"></i> Tambah User</a>
                  <a href="<?= base_url().'log_user';?>" class="nav-link<?php if(isset($title)){ if($title == 'Log User'){ echo ' act';}};?>"><i class="fa fa-angle-right"></i> Log User</a>
              </div>
            <?php }?>
            </ul>
          </div>
        </nav>
      </div>
    </div>

  </body>
</html>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

    <script src="<?= base_url().'assets/js/jquery-3.3.1.min.js';?>"></script>
    <script src="<?= base_url().'assets/js/jquery-ui.js';?>"></script>
    <script src="<?= base_url().'assets/select2-4.0.4/dist/js/select2.min.js';?>"></script>
    <script src="<?= base_url().'assets/js/moment.js';?>"></script>
    <script src="<?= base_url().'assets/js/bootstrap.min.js';?>"></script>
    <script src="<?= base_url().'assets/js/bootstrap-datetimepicker.min.js';?>"></script>
    <script src="<?= base_url().'assets/datatables/DataTables-1.10.18/js/jquery.dataTables.js';?>"></script>
    <script src="<?= base_url().'assets/datatables/DataTables-1.10.18/js/dataTables.bootstrap4.js';?>"></script>
    
    
    
    <!-- 
    <script src="<?= base_url().'assets/js/popper.min.js';?>"></script> -->
    <script src="<?= base_url().'assets/js/sweetalert2.min.js';?>"></script>
    
    <script src="<?= base_url().'assets/fontawesome-free-5.5.0-web/js/all.min.js';?>"></script>
    <script type="text/javascript" language="javascript" src="<?= base_url().'assets/datatables/Buttons-1.5.4/js/dataTables.buttons.js';?>"></script>
    <script src="<?= base_url().'assets/datatables/buttons.html5.min.js';?>"></script>
    <script src="<?= base_url().'assets/js/test.js';?>"></script>
    <script src="<?= base_url().'assets/datatables/Buttons-1.5.4/js/buttons.colVis.min.js';?>"></script>


    <script>
      $(document).ready(function(){
        
        $("#btn-menu").on('click', function(){
            $("#navbarNavDropdown").toggle('slow').removeClass('d-none d-lg-block');
          })

        $("#btn-menu-lg").on('click', function(){
          $("#navbarNavDropdown").toggleClass('sr-only');
          $('#main').toggleClass('col-lg-12');


        })


        $('#sign_out').on('click', function(e){
          swal({
            type: 'question',
            text: 'Logout?',
            showCancelButton: 'true',
          }).then(function(){
            window.location.href="<?=base_url().'User/logout';?>"; 
          })
        })
      })
    </script>
    <!-- Icons -->
    <!-- <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
      feather.replace()
    </script>
     -->
