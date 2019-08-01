
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" id="main">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Master Supplier</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url();?>">Home</a></li>
            <li class="breadcrumb-item active">Supplier</li>
          </ol>
        </nav>
      </div>
      <div id="loader" class="sr-only">
        <i class="fa fa-spinner fa-spin"></i>
      </div>
      <div class="alert sr-only" id="alert">
      </div>
     
      <?php $attr = array('id'=>'form_supplier', 'class'=>'sr-only');?>
      <?= form_open('',$attr);?>
        <div class="row ">
          <div class="col-12 col-lg-12">
            
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Nama Supplier</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="nama_supplier">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Alamat</label>
              <div class="col-sm-4">
                <textarea type="text" class="form-control" name="alamat"></textarea>
              </div> 
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Telepon</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="telepon">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-6 text-right">
                <button type="reset" class="btn btn-warning">Cancel</button>
                <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Submit</button>
              </div>
            </div>
          </div>
        </div>
      <?= form_close();?>

      <div class="modal fade" tabindex="-1" role="dialog" id="modalUpdate">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header bg-primary text-white">
              <div class="d-flex flex-column bd-highlight">
                <h5 class="modal-title">EDIT</h5>
                <h6 class="modal-title" id="title-id-supplier"></h6>
              </div>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <?php $attre = array('id'=>'form_editsupplier');?>
              <?= form_open('',$attre);?>
                <div class="row">
                  <div class="col-12 col-lg-12">
                    <input type="text" class="form-control sr-only" name="id_supplier" id="idSupplierEdit">
                       
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label">Status</label>
                      <div class="col-sm-10">
                        <select class="form-control custom-select" name="status" id="statusEdit">
                          <option value="active">Active</option>
                          <option value="non active">Non Active</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-sm-12 text-right">
                        <button type="reset" class="btn btn-warning">Cancel</button>
                        <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Submit</button>
                      </div>
                    </div>
                  </div>
                </div>
              <?= form_close();?>
            </div>
          </div>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered"  id="table" style="font-family:'Times New Roman', Times, serif; font-size: 12px;width: 100%">
          <thead class="bg-warning">
            <tr>
              <th class="text-center align-middle">No.</th>
              <th class="text-center align-middle">Nama</th>
              <th class="text-center align-middle">Alamat</th>
              <th class="text-center align-middle">Telpon</th>
              <th class="text-center align-middle">Status</th>
              <?php if($role == 'admin'){?>
              <th class="text-center align-middle">Action</th>
              <?php }?>
            </tr>
          </thead>
        </table>
      </div>
    </main>

    <script>
      $(document).ready(function() {

        
        var table = $('#table').DataTable({
          "lengthMenu"  : [[5,10,25, 50, -1],[5,10,25,50, "All"]],
          "stateSave"   :false,
          "processing"  : true,
          "serverSide"  : true,
          "order"       : [],
          "ajax"        :{
            "url"       : "<?= site_url('supplier/get_data_supplier');?>",
            "type"      : "POST",
          },
          "columns":[
            {"data": ['no']},
            {"data": ['nama_supplier']},
            {"data": ['alamat_supplier']},
            {"data": ['telepon_supplier']},
            {"data": ['status']},
            <?php if($role == 'admin'){?>
            {"data": []}
            <?php }?>
          ],
          "dom": 'Bflrtip',
                 buttons: [
                  { className: 'btn btn-sm btn-success', text: '<i class="fa fa-sync"></i>', attr: {id: 'reload'}},
                   <?php if($role == 'admin'){?>
                { className: 'btn btn-sm btn-primary', text: '[+] Add Data', attr: {id: 'toggle_add'} },
              <?php }?>
                { extend: 'copy', className: 'btn btn-sm btn-outline-primary', text: '<i class="fa fa-copy"></i>'},
                { extend: 'csv', className: 'btn btn-sm btn-outline-danger'},
                { extend: 'excel', className: 'btn btn-sm btn-outline-danger', text: '<i class="fa fa-file-excel-o"><i>'},
                    ],
          "processing": true,
          /*"language":{
            "processing": "<div class='warning-alert'><i class='fa fa-circle-o-notch fa-spin'></i> Please wait........",
            "buttons": {
              "copyTitle": "<div class='row'><div class='col push-l3 l9' style='font-size:15px'>Copy to clipboard</div></div>",
              "copyKeys":"Press <i>ctrl</i> or <i>\u2318</i> + <i>C</i> to copy the table data<br>to your system clipboard.<br>To cance, click this message or press escape.",
              "copySuccess":{
                "_": "%d line tercopy",
                "1": "1 line tercopy"
              }
            }
          }, */
          "createdRow" : function(row, data, index){
              $(row).addClass('row-data');
              $(row).attr('data-id',data['id_supplier']);
          },
          "columnDefs": [
            {
              "targets":-1,"data":null,"orderable":false,"width":"100px", "defaultContent":"<div class='btn-group'><button class='btn btn-warning btn-sm btn-edit'>Edit</button>","class":"text-center"
            },
            {
              "targets":0,"width":"50px","class":"text-center"
            },
            {
              "targets":4,"class":"text-center"
            }
          ],
        })
   
         /*
        $('#searchnew').on('keyup change', function(){
            table
              .search(this.value)
              .draw();
        })*/
        $('#reload').on('click', function(){ //reload
          $('#table').DataTable().ajax.reload();
        })
        $('#toggle_add').on('click', function(){
          $('#form_supplier').toggleClass('sr-only');
        })
        $('#table').on('click','.btn-edit', function(e){
          let id = $(this).parent().parent().parent().attr('data-id');
          $('#modalUpdate').modal('show');
          $('#idSupplierEdit').val(id);
          $.ajax({
            type: 'GET',
            url : '<?= base_url()."supplier/get_data/";?>'+id,
            dataType: 'JSON',
            success: function(data){
              $('#namaSupplierEdit').val(data.nama_supplier);
              $('#alamatEdit').val(data.alamat_supplier);
              $('#teleponEdit').val(data.telepon_supplier);
              $("#statusEdit option[value='"+data.status+"']").prop('selected', true);
            }

          })
          

        })
        $('#table').on('click','.btn-hapus', function(e){
          let id = $(this).parent().parent().parent().attr('data-id');
          $('#loader').removeClass('sr-only');
          swal({
            type: 'question',
            text: 'yakin untuk menghapus data ini?',
            showCancelButton: true,
            closeOnCancel: true
          }).then(function(){
            $.ajax({
              type : 'POST',
              data : {id : id},
              dataType : 'JSON',
              url : '<?= base_url()."Supplier/hapus_data";?>',
              success : function(data){
                swal({
                  type: data.type,
                  text: data.pesan,
                })
                $('#loader').addClass('sr-only');
                $('#table').DataTable().ajax.reload();
              }
            })
          })
        })
        $('#form_editsupplier').on('submit', function(e){
          e.preventDefault();
          $('#loader').removeClass('sr-only');
          swal({
            type: 'question',
            text: 'yakin untuk mengubah data ini?',
            showCancelButton: true,
            closeOnCancel: true
          }).then(function(){
            $.ajax({
              type : 'POST',
              data : $('#form_editsupplier').serialize(),
              dataType: 'JSON',
              url: '<?= base_url()."Supplier/edit_supplier";?>',
              success: function(data){ 
                swal({
                  type: data.type,
                  text: data.pesan,
                  allowOutsideClick: false,
                  allowEscapeKey: false,
                  allowEnterKey: false
                }).then(function(){
                  $('#loader').addClass('sr-only');
                  $('#table').DataTable().ajax.reload();
                  $('#modalUpdate').modal('hide');
                })
              }
            })
           
          })
        })

        $('#form_supplier').on('submit', function(e){
          e.preventDefault();
          $('#loader').removeClass('sr-only');
          $.ajax({
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'JSON',
            url: '<?= base_url()."Supplier/submit_supplier";?>',
            success: function(result){
              $('#alert').removeClass('sr-only');
              if(result.type == 'error'){
                swal({
                  type: result.type,
                  text: result.pesan,
                })
                $('#loader').addClass('sr-only');
                $('#alert').html(result.pesan);
                $('#alert').addClass('alert-danger');
                
              }else{
                swal({
                  type: result.type,
                  text: result.pesan,
                  allowOutsideClick: false,
                  allowEscapeKey: false,
                  allowEnterKey: false
                })
                $('#table').DataTable().ajax.reload();
                $('#loader').addClass('sr-only');
                $('#alert').html(result.pesan);
                $('#alert').addClass('alert-success');
                $('#form_supplier input').val('');
                $('#form_supplier').addClass('sr-only');
                setTimeout(function(){$('#alert').addClass('sr-only');},10000)
              }
            }
          })
        })

       
      })
    </script>