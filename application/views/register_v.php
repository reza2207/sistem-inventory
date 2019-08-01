
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Register</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url();?>">Home</a></li>
            <li class="breadcrumb-item active">Register</li>
          </ol>
        </nav>
      </div>
      <div id="loader" class="sr-only">
        <i class="fa fa-spinner fa-spin"></i>
      </div>
      <div class="alert sr-only" id="alert">
      </div>
      <?php $attr = array('id'=>'form_register');?>
      <?= form_open('',$attr);?>
        <div class="row">
          <div class="col-12 col-lg-12">
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Username</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="username" >
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Password</label>
              <div class="col-sm-4">
                <input type="password" class="form-control" name="password">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Fullname</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="fullname">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Role</label>
              <div class="col-sm-4">
                <select class="form-control custom-select" name="role" id="role">
                  <option value="">--pilih--</option>
                  <option value="admin">Admin</option>
                  <option value="operator">Operator</option>
                  <option value="kacab">Kepala Cabang</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-6 text-right">
                <button type="reset" class="btn btn-warning">Cancel</button>
                <button class="btn btn-primary" id="submit"><i class="fa fa-save"></i> Submit</button>
              </div>
            </div>
          </div>
        </div>
      <?= form_close();?>
    </main>

    <script>
      $(document).ready(function() {

        $('#submit').on('click', function(e){
          e.preventDefault();
          $('#loader').removeClass('sr-only');
          $.ajax({
            type: 'POST',
            data: $('#form_register').serialize(),
            dataType: 'JSON',
            url: '<?= base_url()."User/submit_user";?>',
            success: function(result){
              $('#alert').removeClass('sr-only');
              let message = result.pesan;
              if(result.type == 'error'){
                swal({
                  type: result.type,
                  text: result.pesan,
                })
                $('#loader').addClass('sr-only');
                $('#alert').html(message);
                $('#alert').addClass('alert-danger');
              }else{
                swal({
                  type: result.type,
                  text: result.pesan,
                })
                $('#loader').addClass('sr-only');
                $('#alert').html(message);
                $('#alert').addClass('alert-success');
              }
            }
          })
        })

       
      })
    </script>