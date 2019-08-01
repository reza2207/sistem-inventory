
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" id="main">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Master Barang</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url();?>">Home</a></li>
            <li class="breadcrumb-item active">Barang</li>
          </ol>
        </nav>
      </div>
      <div id="loader" class="sr-only">
        <i class="fa fa-spinner fa-spin"></i>
      </div>
      <div class="alert sr-only" id="alert">
      </div>
      
      <?php $attr = array('id'=>'form_barang', 'class'=>'sr-only');?>
      <?= form_open('',$attr);?>
        <div class="row ">
          <div class="col-12 col-lg-12">
            
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Nama Barang</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="nama_barang">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Satuan</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="satuan">
              </div> 
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Nama Supplier</label>
              <div class="col-sm-4">
                <select type="text" class="form-control custom-select" name="id_supplier">
                  <option value="">--pilih--</option>
                  <?php foreach($supplier AS $row):?>
                  <option value="<?= $row->id_supplier;?>"><?= $row->nama_supplier;?></option>
                  <?php endforeach ;?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Min. Stock</label>
              <div class="col-sm-4">
                <input type="number" class="form-control" name="min" min="1">
              </div> 
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Max. Stock</label>
              <div class="col-sm-4">
                <input type="number" class="form-control" name="max" min="1">
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
              <?php $attre = array('id'=>'form_editbarang');?>
              <?= form_open('',$attre);?>
                <div class="row">
                  <div class="col-12 col-lg-12">
                    <input type="text" class="form-control sr-only" name="id_barang" id="idBarangEdit">
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label">Status</label>
                      <div class="col-sm-10">
                        <select type="text" class="form-control custom-select" name="status" id="statusEdit">
                          <option value="Active">Active</option>
                          <option value="Non Active">Non Active</option>
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
              <th class="text-center align-middle">Nama Barang</th>
              <th class="text-center align-middle">Satuan</th>
              <th class="text-center align-middle">Minimum Stok</th>
              <th class="text-center align-middle">Maximum Stok</th>
              <th class="text-center align-middle">Nama Supplier</th>
              <th class="text-center align-middle">Status</th>
              <th class="text-center align-middle">Action</th>
           
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
            "url"       : "<?= site_url('barang/get_data_barang');?>",
            "type"      : "POST",
          },
          "columns":[
            {"data": ['no']},
            {"data": ['nama_barang']},
            {"data": ['satuan']},
            {"data": ['min']},
            {"data": ['max']},
            {"data": ['nama_supplier']},
            {"data": ['status']},
            {"data": []}
          
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
              $(row).attr('data-id',data['id_barang']);
          },
          "columnDefs": [
            {
              "targets":-1,"data":null,"orderable":false,"width":"100px", "defaultContent":"<div class='btn-group'><button class='btn btn-warning btn-sm btn-edit'>Edit</button></div>",//<button class='btn btn-danger btn-sm btn-hapus'>Hapus</button>
            },
            {
              "targets":[5],"width":"150px","class":"text-center"
            },
            
            {
              "targets":[2,-1,4,3,6, 0],"width":"50px","class":"text-center"
            },

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
          $('#form_barang').toggleClass('sr-only');
        })
        $('#table').on('click','.btn-edit', function(e){
          let id = $(this).parent().parent().parent().attr('data-id');
          $('#modalUpdate').modal('show');
          $('#idBarangEdit').val(id);
          $.ajax({
            type: 'GET',
            url : '<?= base_url()."barang/get_data/";?>'+id,
            dataType: 'JSON',
            success: function(data){
              $("#statusEdit option[value='"+data.status+"']").prop('selected', true);
            }

          })
          

        })
        
        $('#form_editbarang').on('submit', function(e){
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
              data : $('#form_editbarang').serialize(),
              dataType: 'JSON',
              url: '<?= base_url()."Barang/edit_barang";?>',
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

        $('#form_barang').on('submit', function(e){
          e.preventDefault();
          $('#loader').removeClass('sr-only');
          $.ajax({
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'JSON',
            url: '<?= base_url()."Barang/submit_barang";?>',
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
                $('#form_barang input').val('');
                $('#form_barang').addClass('sr-only');
                setTimeout(function(){$('#alert').addClass('sr-only');},10000)
              }
            }
          })
        })

       
      })
    </script>