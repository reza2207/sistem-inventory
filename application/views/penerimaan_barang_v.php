
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" id="main">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Transaksi Barang Masuk</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url();?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url().'barang';?>">Transaksi</a></li>
            <li class="breadcrumb-item active">Barang Masuk</li>
          </ol>
        </nav>
      </div>
      <div id="loader" class="sr-only">
        <i class="fa fa-spinner fa-spin"></i>
      </div>
      <div class="alert sr-only" id="alert">
      </div>
      
      <?php $attr = array('id'=>'form_barang_masuk', 'class'=>'sr-only');?>
      <?= form_open('',$attr);?>
        <div class="row ">
          <div class="col-12 col-lg-12">
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">No. PO</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="no_po">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Tgl. PO</label>
              <div class="col-sm-4">
                <input type="text" class="form-control datepicker" name="tgl_po">
              </div> 
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">No. Surat jalan</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="no_sj">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Tgl. Surat Jalan</label>
              <div class="col-sm-4">
                <input type="text" class="form-control datepicker" name="tgl_sj">
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
              <label class="col-sm-2 col-form-label">Tgl. Masuk</label>
              <div class="col-sm-4">
                <input type="text" class="form-control datepicker" name="tgl_masuk">
              </div> 
            </div>
            <div class="form-group row">
              <div class="col-sm-4">
                <button id="tmbh-item">+tambah row</button>
              </div> 
            </div>
            <div  id="row-barang">
             
            </div>
            <div class="form-group row">
              <div class="col-sm-6">
                <button type="reset" class="btn btn-warning">Cancel</button>
                <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Submit</button>
              </div>
            </div>
          </div>
        </div>
      <?= form_close();?>
      
      <div class="modal fade" tabindex="-1" role="dialog" id="modalDetail">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header bg-primary text-white">
              <div class="d-flex flex-column bd-highlight">
                <h5 class="modal-title">Detail</h5>
              </div>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row" id="surat-jalan">
                <div class="col-12 col-lg-12">
                  <table class="table">
                    <tr>
                      <td><b>No. PO</b></td>
                      <td>:</td>
                      <td id="d_no_po"></td>
                    </tr>
                    <tr>
                      <td><b>Tanggal PO</b></td>
                      <td>:</td>
                      <td id="d_tgl_po"></td>
                    </tr>
                    <tr>
                      <td><b>No. Surat Jalan</b></td>
                      <td>:</td>
                      <td id="d_no_sj"></td>
                    </tr>
                    <tr>
                      <td><b>Tanggal Surat Jalan</b></td>
                      <td>:</td>
                      <td id="d_tgl_sj"></td>
                    </tr>
                    <tr>
                      <td><b>Nama Supplier</b></td>
                      <td>:</td>
                      <td id="d_nama_supplier"></td>
                    </tr>
                  </table>
                  <table style="width:100%" class="table table-hover table-bordered">
                    <thead>
                      <tr>
                        <td><b>No.</b></td>
                        <td><b>Nama Barang</b></td>
                        <td><b>Qty</b></td>
                        <td><b>Harga</b></td>
                        <td><b>Jumlah</b></td>
                      </tr>
                    </thead>
                    <tbody id="tbody-detail">
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="4"></td>
                        <td id="total" class="text-right"></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <div class="row">
                <div class="col-12 col-lg-12">
                  
                  <button class="btn btn-sm btn-success btn-approve sr-only" data-status="Approve">Approve</button>
                  <button class="btn btn-sm btn-warning btn-approve sr-only" data-status="Decline">Decline</button>
                 
                  <button class="btn btn-sm btn-danger sr-only" id="btn-hapus">Hapus Data</button>
                  <button class="btn btn-sm btn-info" id="btn-save-pdf">save pdf</button>
                </div>
              </div>
            </div>
          </div>
          
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered"  id="table" style="font-family:'Times New Roman', Times, serif; font-size: 12px;width: 100%">
          <thead class="bg-warning">
            <tr>
              <th class="text-center align-middle">No.</th>

              <th class="text-center align-middle">No. PO</th>
              <th class="text-center align-middle">Tgl. PO</th>
              <th class="text-center align-middle">No. Surat Jalan</th>
              <th class="text-center align-middle">Tgl. Surat Jalan</th>
              <th class="text-center align-middle">Nama Supplier</th>
              <th class="text-center align-middle">Tgl. Masuk</th>
              <th class="text-center align-middle">Status</th>
              <th class="text-center align-middle">Action</th>
             
            </tr>
          </thead>
        </table>
      </div>
    </main>
  
    <script>
      $(document).ready(function() {
       
      $( ".datepicker" ).datepicker();
           $( ".datepicker" ).datepicker("option", "dateFormat", "dd-mm-yy");
        var table = $('#table').DataTable({
          "lengthMenu"  : [[5,10,25, 50, -1],[5,10,25,50, "All"]],
          "stateSave"   :false,
          "processing"  : true,
          "serverSide"  : true,
          "order"       : [],
          "ajax"        :{
            "url"       : "<?= site_url('barang/get_data_barang_masuk');?>",
            "type"      : "POST",
          },
          "columns":[
            {"data": ['no']},
            {"data": ['no_po']},
            {"data": ['tgl_po']},
            {"data": ['no_surat_jalan']},
            {"data": ['tgl_surat_jalan']},
            {"data": ['nama_supplier']},
            {"data": ['tgl_masuk']},
            {"data": ['status']},
            {"data": []}
          ],
          "dom": 'Bflrtip',
                 buttons: [
                { className: 'btn btn-sm btn-success', text: '<i class="fa fa-sync"></i>', attr: {id: 'reload'}},
                <?php if($role == 'operator'){?>
                { className: 'btn btn-sm btn-primary', text: '[+] Add Data', attr: {id: 'toggle_add'} },
                <?php }?>
                { extend: 'copy', className: 'btn btn-sm btn-outline-primary', text: '<i class="fa fa-copy"></i>'},
                { extend: 'csv', className: 'btn btn-sm btn-outline-danger'},
                { extend: 'excel', className: 'btn btn-sm btn-outline-danger', text: '<i class="fa fa-file-excel-o"><i>'},
                    ],
          "processing": true,
          "createdRow" : function(row, data, index){
              $(row).addClass('row-data');
              $(row).attr('data-id',data['id_barang_masuk']);
              $(row).attr('data-status', data['status']);
          },
          "columnDefs": [
            {
              "targets":-1,"data":null,"orderable":false,"width":"100px", "defaultContent":"<div class='btn-group'><button class='btn btn-sm btn-primary btn-detail'><i class='fa fa-eye'></i></div>",
            },
            {
              "targets":0,"width":"50px","class":"text-center"
            },
            {
              "targets":[1,3,2,-1,4,5,6,7],"class":"text-center"
            },
            

          ],
        })
        
        let html = '';
        let no = 0;
        $('#tmbh-item').on('click', function(e){
          e.preventDefault();
          no++;
          html = '<div class="form-group row"><div class="col-sm-4">'+
                      '<select type="text" class="form-control selectbarang" name="namabarang[]" data-id="'+no+'" id="select'+no+'" style="width:100%">'+
                      '<option value="">--pilih barang--</option>'+
                      <?php foreach($barang AS $rowb):?>
                      '<option value="<?= $rowb->id_barang;?>" data-satuan="<?= $rowb->satuan;?>"><?= $rowb->nama_barang;?></option>'+
                      <?php endforeach ;?>
                      '</select>'+
                      '</div>'+
                      '<div class="col-sm-2">'+
                      '<input type="text" class="form-control form-control-sm" name="satuan[]" placeholder="satuan" id="satuan'+no+'" readonly>'+
                      '</div>'+
                      '<div class="col-sm-2">'+
                      '<input type="text" class="form-control form-control-sm" name="jumlah[]" placeholder="jumlah">'+
                      '</div>'+
                      '<div class="col-sm-2">'+
                      '<input type="text" class="form-control form-control-sm" name="harga[]" placeholder="harga">'+
                      '</div><button class="hapus-row">x</button></div>';

            $('#row-barang').append(html);
             $(".selectbarang").select2({
              placeholder: 'Select an option',
            }) 
            $(".selectbarang").select2().on('select2:select', function (e) {
              let id = $(this).attr('data-id');
              let idsatuan = '#satuan'+id;
              //let id = $(e.params.data.element).data('id');
              let satuan = $(this).find(":selected").attr("data-satuan");
              $(idsatuan).val(satuan)
              console.log(id)
            })
            $('.hapus-row').on('click', function(e){
          
          e.preventDefault()
          $(this).parent().remove();
          })
        })
        
        $('#reload').on('click', function(){ //reload
          $('#table').DataTable().ajax.reload();
        })
         $('#toggle_add').on('click', function(){
          $('#form_barang_masuk').toggleClass('sr-only');
        })


        $('#table').on('click', '.btn-detail', function(e){
          e.preventDefault();
          let id = $(this).parent().parent().parent().attr('data-id');
          let status = $(this).parent().parent().parent().attr('data-status');
          
          $('#modalDetail').modal('show');
          $('#d_id_sj').text(id);
          $('#btn-save-pdf, .btn-approve, #btn-hapus').attr('data-id', id);
          $.ajax({
            type: 'POST',
            data: {id: id},
            url : "<?= base_url().'Barang/get_data_id_barang_masuk';?>",
            success: function(result){
              let data = JSON.parse(result);
              
              if('<?= $_SESSION['role'];?>' == 'operator'){
                if(status == 'Pending'){
                  $('.btn-approve').addClass('sr-only');
                  $('#btn-hapus').removeClass('sr-only');
                  $('#btn-save-pdf').addClass('sr-only')
                }else if(status == 'Decline'){
                  $('.btn-approve').addClass('sr-only');
                  $('#btn-hapus').addClass('sr-only');
                  $('#btn-save-pdf').addClass('sr-only');
                }else{
                  $('.btn-approve').addClass('sr-only');
                  $('#btn-hapus').addClass('sr-only');
                  $('#btn-save-pdf').removeClass('sr-only')
                }
              }else if('<?= $_SESSION['role'];?>' == 'kacab'){
                if(status == 'Pending'){
                  $('.btn-approve').removeClass('sr-only');
                  $('#btn-hapus').addClass('sr-only');
                  $('#btn-save-pdf').addClass('sr-only');
                }else if(status == 'Decline'){
                  $('.btn-approve').addClass('sr-only');
                  $('#btn-hapus').addClass('sr-only');
                  $('#btn-save-pdf').addClass('sr-only');
                }else{
                  $('.btn-approve').addClass('sr-only');
                  $('#btn-hapus').addClass('sr-only');
                  $('#btn-save-pdf').removeClass('sr-only')
                }
              }
              $('#d_tgl_sj').text(tanggal(data[0].tgl_surat_jalan));
              $('#d_tgl_po').text(tanggal(data[0].tgl_po));
              $('#d_no_sj').text(data[0].no_surat_jalan);
              
              $('#d_no_po').text(data[0].no_po);
              $('#d_nama_supplier').text(data[0].nama_supplier);
              
             /* let idbarang = data.id_barang.split('|');
              let qtybarang = data.qty.split('|');
              let hargabarang = data.harga.split('|');
              let subjumlah = data.jumlah.split('|');
              let namabarang = data.nama.split('|');*/
              let html = '';
              let no = 0;
              for(i = 0;i < data.length;i++){
                no++;
                html += '<tr>'+
                          '<td class="text-center">'+no+'</td>'+
                          '<td>'+data[i].nama+'</td>'+
                          '<td class="text-right">'+bilangan(data[i].qty)+'</td>'+
                          '<td class="text-right">'+bilangan(data[i].harga)+'</td>'+
                          '<td class="text-right">'+bilangan(data[i].jumlah)+'</td>'+
                        '</tr>';
              }
              $('#tbody-detail').html(html);
              $('#total').text(bilangan(data[0].jumlahtotal))
              if(data.status == 'Pending'){
                $('#btn-hapus').show();
                $('#btn-hapus').attr('data-id',id);
              }else{
                $('#btn-hapus').hide();
                $('#btn-hapus').removeAttr('data-id');
              }
            }
          })

        })
        
        $('#btn-save-pdf').on('click', function(e){
          let id = $(this).attr('data-id');
          
          window.open("<?= base_url().'barang/get_pdf/in/';?>"+id, '_blank');
          
        })
        
        $('#form_barang_masuk').on('submit', function(e){
          e.preventDefault();
          $('#loader').removeClass('sr-only');
          $.ajax({
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'JSON',
            url: '<?= base_url()."Barang/submit_barang_masuk";?>',
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
                $('#form_barang_masuk input').val('');
                $('#row-barang').html('');
                $('#form_barang_masuk').addClass('sr-only');
                setTimeout(function(){$('#alert').addClass('sr-only');},10000)
              }
            }
          })
        })

        $('#btn-hapus').on('click', function(e){
          let id = $(this).attr('data-id');
          swal({
            type: 'question',
            text: 'Yakin akan menghapus data ini?',
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            showCancelButton: true
          }).then(function(){
            $.ajax({
              type: 'POST',
              url : '<?= base_url()."barang/hapus_data_transaksi";?>',
              data: {id:id, trans:'terima'},
              success : function(result){
                let data = JSON.parse(result)
                swal({
                  type: data.type,
                  text: data.pesan,
                  allowOutsideClick: false,
                  allowEscapeKey: false,
                  allowEnterKey: false
                }).then(function(){
                $('#table').DataTable().ajax.reload();
                $('#modalDetail').modal('hide');
                })
              }
            })
          })
        })

        $('.btn-approve').on('click', function(e){
          let id = $(this).attr('data-id');
          let status = $(this).attr('data-status');
          swal({
            type: 'question',
            text: 'Yakin?',
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            showCancelButton: true
          }).then(function(){
            $.ajax({
              type: 'POST',
              url: '<?= base_url()."Barang/approve_trans";?>',
              data : {id: id, table: 'Penerimaan', status: status},
              success: function(result){
                let data = JSON.parse(result)
                swal({
                  type: data.type,
                  text: data.pesan,
                  allowOutsideClick: false,
                  allowEscapeKey: false,
                  allowEnterKey: false
                }).then(function(){
                  $('#table').DataTable().ajax.reload();
                  $('#modalDetail').modal('hide');
                })
              }
            })
          })
        })
        
      })
    </script>