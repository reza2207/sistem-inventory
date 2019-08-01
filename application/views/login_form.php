<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>Suryaindah Wiraperkasa</title>

    <!-- Bootstrap core CSS -->
    <link href="<?= base_url().'assets/css/bootstrap.min.css';?>" rel="stylesheet">
    <link href="<?= base_url().'assets/css/sweetalert2.min.css';?>" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="<?= base_url().'assets/css/dashboard.css';?>" rel="stylesheet">
  </head>
  <style>
  </style>
  <body class="bg-info">

    <div class="container" style="padding-top: 8%" id="border-form" >
      <div class="row justify-content-md-center ">
        <div class="col-lg-5 col-sm-12">
          <div class="text-center text-white"><h2>PT.Suryaindah Wiraperkasa</h2></div>
          <div class="card border-primary shadow p-3 mb-5 bg-white rounded">
            <div class="card-header text-center font-weight-bold bg-primary text-white">
              LOGIN
            </div>
            <div class="card-body">
              <?php $attr = array('id'=>'form_login');?>
              <?= form_open('',$attr);?>
                <div class="form-group">
                  <label>Username</label>
                  <input type="text" class="form-control" name="username" placeholder="Enter Username">
                </div>
                <div class="form-group">
                  <label>Password</label>
                  <input type="password" class="form-control" name="password" placeholder="Enter Password">
                </div>
                <button type="submit" id='btn-submit' class="btn btn-primary btn-block">Submit</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="<?= base_url().'assets/js/jquery-3.3.1.min.js';?>"></script>
<script src="<?= base_url().'assets/js/bootstrap.min.js';?>"></script>
<script src="<?= base_url().'assets/js/sweetalert2.min.js';?>"></script>
<script src="<?= base_url().'assets/js/popper.min.js';?>"></script>
<script src="<?= base_url().'assets/fontawesome-free-5.5.0-web/js/all.min.js';?>"></script>

<script>
  
  $(document).ready(function(){

    //login button on click
    $('#form_login').on('submit', function(e){
      e.preventDefault();
      $.ajax({
        type : 'POST',
        url : '<?=base_url()."User/submit_login";?>',
        dataType: 'JSON',
        data : $(this).serialize(),
        success: function(result){
          console.log(result);
          if(result.type == 'error'){
            swal({
              type: result.type,
              text: result.pesan,
            })
          }else{
            swal({
              type: result.type,
              text: result.pesan,
              allowOutsideClick: false,
              allowEscapeKey: false,
              allowEnterKey: false
            }).then(function(){
              window.location.href="<?=base_url().'Welcome';?>"; 
            })
          }
        }
      })
    })
  })
</script>