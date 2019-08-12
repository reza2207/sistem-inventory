
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" id="main">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Transaksi Barang Keluar</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url();?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url().'barang';?>">Transaksi</a></li>
            <li class="breadcrumb-item active">Barang Keluar</li>
          </ol>
        </nav>
      </div>
      <div id="loader" class="sr-only">
        <i class="fa fa-spinner fa-spin"></i>
      </div>
      <div class="alert sr-only" id="alert">
      </div>
      
      <?php $attr = array('id'=>'form_barang_keluar', 'class'=>'sr-only');?>
      <?= form_open('',$attr);?>
        <div class="row ">
          <div class="col-12 col-lg-12">
            
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">No. Faktur</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="no_faktur">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Tgl. Faktur</label>
              <div class="col-sm-4">
                <input type="text" class="form-control datepicker" name="tgl_faktur">
              </div> 
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Nama Customer</label>
              <div class="col-sm-4">
                <select type="text" class="form-control custom-select" name="id_customer" id="select-cus" style="width:100%">
                  <option value="">--pilih--</option>
                  <?php foreach($customer AS $row):?>
                  <option value="<?= $row->id_customer;?>"><?= $row->nama_customer;?></option>
                  <?php endforeach ;?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Tgl. Keluar</label>
              <div class="col-sm-4">
                <input type="text" class="form-control datepicker" name="tgl_keluar">
              </div> 
            </div>
            <div class="form-group row">
              
              <div class="col-sm-4">
                <button id="tmbh-item">+tambah row</button>
              </div> 
            </div>
            <div class="form-group row">
              <div class="col-sm-3">
              Nama Barang
              </div>
              <div class="col-sm-1">
              Satuan
              </div>
              <div class="col-sm-2">
              Sisa
              </div>
              <div class="col-sm-2">
              Jumlah Keluar
              </div>
              <div class="col-sm-1">
              Harga
              </div>
              <div class="col-sm-2">
              Total Harga
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
                      <td><b>No. Faktur</b></td>
                      <td>:</td>
                      <td id="d_faktur"></td>
                    </tr>
                    <tr>
                      <td><b>Tanggal Faktur</b></td>
                      <td>:</td>
                      <td id="d_tgl_faktur"></td>
                    </tr>
                    <tr>
                      <td><b>Nama Customer</b></td>
                      <td>:</td>
                      <td id="d_nama_customer"></td>
                    </tr>
                    <tr>
                      <td><b>Tanggal Keluar</b></td>
                      <td>:</td>
                      <td id="d_tgl_keluar"></td>
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
              <th class="text-center align-middle">No. Faktur</th>
              <th class="text-center align-middle">Tanggal Faktur</th>
              <th class="text-center align-middle">Nama Customer</th>
              <th class="text-center align-middle">Tanggal Keluar</th>
              <th class="text-center align-middle">Status</th>
              <th class="text-center align-middle">Action</th>
            </tr>
          </thead>
        </table>
      </div>
    </main>
  
    <script>
      $(document).ready(function() {
       $('#select-brg, #select-cus').select2({
              placeholder: 'Select an option',
            }) 
      $( ".datepicker" ).datepicker();
       $( ".datepicker" ).datepicker("option", "dateFormat", "dd-mm-yy");
        var table = $('#table').DataTable({
          "lengthMenu"  : [[5,10,25, 50, -1],[5,10,25,50, "All"]],
          "stateSave"   :false,
          "processing"  : true,
          "serverSide"  : true,
          "order"       : [],
          "ajax"        :{
            "url"       : "<?= site_url('barang/get_data_barang_keluar');?>",
            "type"      : "POST",
          },
          "columns":[
            {"data": ['no']},
            {"data": ['no_faktur']},
            {"data": ['tgl_faktur']},
            {"data": ['nama_customer']},
            {"data": ['tgl_keluar']},
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
                    ],
          "processing": true,
          "createdRow" : function(row, data, index){
              $(row).addClass('row-data');
              $(row).attr('data-id',data['id_barang_keluar']);
              $(row).attr('data-status', data['status']);
          },
          "columnDefs": [
          <?php if($role == 'operator'){?>
            {
              "targets":-1,"data":null,"orderable":false,"width":"100px", "defaultContent":"<div class='btn-group'><button class='btn btn-sm btn-primary btn-detail'><i class='fa fa-eye'></i></div>",
            },
          <?php }else{?>
            {
              "targets":-1,"data":null,"orderable":false,"width":"100px", "defaultContent":"<div class='btn-group'><button class='btn btn-sm btn-primary btn-detail'><i class='fa fa-eye'></i></div>",
            },

          <?php }?>
            {
              "targets":0,"width":"50px","class":"text-center"
            },
           
            {
              "targets":[2,3,4,5,6],"class":"text-center"
            },

          ],
        })
        
        let html = '';
        let no = 1;
        $('#tmbh-item').on('click', function(e){
          e.preventDefault();
          no++;
          html = '<div class="form-group row"><div class="col-sm-3">'+
                      '<select type="text" class="form-control selectbarang" name="namabarang[]" style="width:100%" id="select-brg'+no+'" data-id="'+no+'">'+
                      '<option value="">--pilih barang--</option>'+
                      <?php foreach($barang AS $rowb):?>
                      '<option value="<?= $rowb->id_barang;?>" data-id="<?= $rowb->stok_akhir;?>" data-satuan="<?= $rowb->satuan;?>"><?= $rowb->nama_barang;?></option>'+
                      <?php endforeach ;?>
                      '</select>'+
                      '</div>'+
                      '<div class="col-sm-1">'+
                      '<input type="text" class="form-control form-control-sm" name="satuan[]" placeholder="satuan" id="satuan'+no+'" readonly>'+
                      '</div>'+
                      '<div class="col-sm-2">'+
                      '<input class="form-control form-control-sm" id="sisa'+no+'" type="text" value="" readonly>'+
                      '</div>'+
                      '<div class="col-sm-2">'+
                      '<input type="number" class="form-control form-control-sm jml" name="jumlah[]" data-id="'+no+'" id="jml'+no+'" placeholder="jumlah" min="1">'+
                      '</div>'+
                      '<div class="col-sm-1">'+
                      '<input type="text" class="form-control form-control-sm hrg" name="harga[]" data-id="'+no+'" id="harga'+no+'" placeholder="harga" min="1">'+
                      '</div>'+
                      '<div class="col-sm-2">'+
                      '<input class="form-control form-control-sm" id="jmlharga'+no+'" type="text" value="" readonly>'+
                      '</div>'+
                      '<button class="hapus-row">x</button></div>'
                      ;

          $('#row-barang').append(html);
          $(".selectbarang").select2({
            placeholder: 'Select an option',
          })
           


          /*$('.jml').on('keyup', function(e){
            let no = $(this).attr('data-id');
            let jml = this.value;
            let idsisa = '#sisa'+no;

          })*/
          $(".hrg").on('keyup', function(e){
            let no = $(this).attr('data-id');
            let harga = $(this).val();
            let idjml = '#jml'+no;
            let jml = $(idjml).val();
            
            let idjmlhrg = '#jmlharga'+no;
            $(idjmlhrg).val(jml*harga);
          })
          $('.selectbarang').on('change', function(e){
            let id = $(this).attr('data-id');
            let idsatuan = '#satuan'+id;
            let sisa = $("option:selected",this).attr('data-id');
            let idsisa = '#sisa'+id;
            let idjml = '#jml'+id;
            $(idsisa).val(sisa);
            $(idjml).attr('max',sisa);
            
          })
          $(".selectbarang").select2().on('select2:select', function (e) {
            let no = $(this).attr('data-id');
            let idsatuan = '#satuan'+no;
            //let id = $(e.params.data.element).data('id');
            let satuan = $(this).find(":selected").attr("data-satuan");
            $(idsatuan).val(satuan)
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
          $('#form_barang_keluar').toggleClass('sr-only');
        })
        

        $('#table').on('click', '.btn-detail', function(e){
          e.preventDefault();
          let id = $(this).parent().parent().parent().attr('data-id');
          let status = $(this).parent().parent().parent().attr('data-status');
          $('#modalDetail').modal('show');
          
          $('.btn-approve, #btn-hapus').show();
          $('#btn-save-pdf, .btn-approve, #btn-hapus').attr('data-id', id);
          $.ajax({
            type: 'POST',
            data: {id: id},
            url : "<?= base_url().'Barang/get_data_id_barang_keluar';?>",
            success: function(result){
              let data = JSON.parse(result)
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
              $('#d_faktur').text(data[0].no_faktur);
              $('#d_tgl_faktur').text(tanggal(data[0].tgl_faktur));
              $('#d_nama_customer').text(data[0].nama_customer);
              $('#d_tgl_keluar').text(tanggal(data[0].tgl_keluar));
              /*let idbarang = data.id_barang.split('|');
              let qtybarang = data.qty.split('|');
              let hargabarang = data.harga.split('|');
              let subjumlah = data.jumlah.split('|');
              let namabarang = data.nama.split('|');*/
              let html = '';
              let no = 0;
              for(i = 0;i < data.length;i++){
                no++;
                html += '<tr>'+
                          '<td>'+no+'</td>'+
                          '<td>'+data[i].nama+'</td>'+
                          '<td class="text-right">'+bilangan(data[i].qty)+'</td>'+
                          '<td class="text-right">'+bilangan(data[i].harga)+'</td>'+
                          '<td class="text-right">'+bilangan(data[i].jumlah)+'</td>'+
                        '</tr>';
              }
              $('#tbody-detail').html(html);
              $('#total').text(bilangan(data[0].jumlahtotal))

            }
          })

        })
        $('#btn-save-pdf').on('click', function(e){
          let id = $(this).attr('data-id');
          
          window.open("<?= base_url().'barang/get_pdf/out/';?>"+id, '_blank');
          
        })
       

        $('#form_barang_keluar').on('submit', function(e){
          e.preventDefault();
          $('#loader').removeClass('sr-only');
          $.ajax({
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'JSON',
            url: '<?= base_url()."Barang/submit_barang_keluar";?>',
            success: function(result){
              $('#alert').removeClass('sr-only');
              console.log(result)
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
                $('#form_barang_keluar').addClass('sr-only');
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
              data: {id:id, trans:'keluar'},
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
              data : {id: id, table: 'Pengeluaran', status: status},
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