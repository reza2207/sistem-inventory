
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" id="main">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Stock Opname</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url();?>">Home</a></li>
            <li class="breadcrumb-item active">Stock Opname</li>
          </ol>
        </nav>
      </div>
      <div id="loader" class="sr-only">
        <i class="fa fa-spinner fa-spin"></i>
      </div>
      <div class="alert bg-danger text-white sr-only" id="alert">
        <i class="fa fa-exclamation"></i><b> Bulan ini belum melakukan stok opname. <a href="<?= base_url().'Stock_opname/get_card';?>" target="_blank">klik disini</a> untuk download Kartu Stok</b>
      </div>
      
      <div class="form-group sr-only" id="form_card">
        <div class="table-responsive">
         <?php $attr = array('id'=>'form_add_card');?>
          <?= form_open('',$attr);?>
          <table class="table table-hover table-striped table-bordered" style="font-family:'Times New Roman', Times, serif; font-size: 12px;width: 100%">
            <thead class="bg-warning">
              <tr>
                <th class="text-center align-middle">No.</th>
                <th class="text-center align-middle">Nama Barang</th>
                <th class="text-center align-middle">Stok Terakhir</th>
                <th class="text-center align-middle">Stok Sebenarnya</th>
                <th class="text-center align-middle">Jumlah Rusak</th>
                <th class="text-center align-middle">Selisih</th>
                <th class="text-center align-middle">Jumlah Retur Terakhir</th>
                <th class="text-center align-middle">Jumlah Retur Sebenarnya</th>
              </tr>
            </thead>
           
              <tbody id="isi">
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="7">
                  </td>
                  <td class="text-right">
                    <button id="savecard" class="btn btn-primary" type="submit">Proses</button>
                  </td>
                </tr>
              </tfoot>
            
          </table>
          <?= form_close();?>
        </div>
      </div>
      
      
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
                      <td width="30%"><b>ID. Stock Opname</b></td>
                      <td>:</td>
                      <td id="d_so"></td>
                    </tr>
                    <tr>
                      <td><b>Tanggal Stock Opname</b></td>
                      <td>:</td>
                      <td id="d_tgl_so"></td>
                    </tr>
                  </table>
                  <table style="width:100%" class="table table-hover table-bordered">
                    <thead>
                      <tr>
                        <th class="text-center align-middle">No.</th>
                        <th class="text-center align-middle">Nama Barang</th>
                        <th class="text-center align-middle">Stok Terakhir</th>
                        <th class="text-center align-middle">Stok Sebenarnya</th>
                        <th class="text-center align-middle">Jumlah Rusak</th>
                        <th class="text-center align-middle">Selisih</th>
                        <th class="text-center align-middle">Jumlah Retur</th>
                      </tr>
                    </thead>
                    <tbody id="tbody-detail">
                    </tbody>
                    
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

      <!-- end-->
      <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered"  id="table" style="font-family:'Times New Roman', Times, serif; font-size: 12px;width: 100%">
          <thead class="bg-warning">
            <tr>
              <th class="text-center align-middle">No.</th>
              <th class="text-center align-middle">Tanggal SO</th>
              <th class="text-center align-middle">Action</th>
            </tr>
          </thead>
          <tbody id="isi">
          </tbody>
        </table>
      </div>
    </main>
  
    <script>
      $(document).ready(function() {
      
          
        $('#isi').html('');
        cek_so();
        var table = $('#table').DataTable({
          "lengthMenu"  : [[5,10,25, 50, -1],[5,10,25,50, "All"]],
          "stateSave"   :false,
          "processing"  : true,
          "serverSide"  : true,
          "order"       : [],
          "ajax"        :{
            "url"       : "<?= site_url('Stock_opname/get_data_so');?>",
            "type"      : "POST",
          },
          "columns":[
            {"data": ['no']},
            {"data": ['tgl_so']},
            {"data": []},
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
              $(row).attr('data-id',data['id_so']);
          },
          "columnDefs": [
            {
              "targets":-1,"data":null,"orderable":false,"width":"100px", "defaultContent":"<div class='btn-group'><button class='btn btn-sm btn-primary btn-detail'><i class='fa fa-eye'></i></div>",
            },
            {
              "targets":0,"width":"50px","class":"text-center"
            },
            {
              "targets":[1,-1],"class":"text-center"
            },
            

          ],
        })

        $('#toggle_add').on('click', function(e){
          $('#form_card').toggleClass('sr-only');
          e.preventDefault();
          get_card();

        })
        $('#table').on('click', '.btn-detail', function(e){
          e.preventDefault();
          let id = $(this).parent().parent().parent().attr('data-id');
          $('#btn-save-pdf').attr('data-id', id);
          $('#modalDetail').modal('show');
          $.ajax({
            type: 'POST',
            data: {id: id},
            dataType: 'JSON',
            url : "<?= base_url().'Stock_opname/get_data_id_so';?>",
            success: function(result){
              let data = result
              $('#d_so').text(data[0].id_so)
              $('#d_tgl_so').text(data[0].tgl_so)
              let html = '';
              let no = 0;
              for(i = 0;i < data.length;i++){
                no++;
                html += '<tr>'+
                          '<td>'+no+'</td>'+
                          '<td>'+data[i].nama_barang+'</td>'+
                          '<td class="text-right">'+bilangan(data[i].qtystok)+'</td>'+
                          '<td class="text-right">'+bilangan(data[i].qtybenar)+'</td>'+
                          '<td class="text-right">'+bilangan(data[i].jumlah_rusak)+'</td>'+
                          
                          '<td class="text-right">'+bilangan(data[i].qtybenar-data[i].qtystok)+'</td>'+
                          '<td class="text-right">'+bilangan(data[i].jumlah_retur)+'</td>'+
                        '</tr>';
              }
              $('#tbody-detail').html(html);
            }
          })
        })
        function get_card(){
          $('#loader').removeClass('sr-only');
          $('#isi').html('');
          $.ajax({
            type: 'POST',
            url: '<?= base_url()."Report/get_days_a_month";?>',
            success: function(result){
              $('#loader').addClass('sr-only');
              let data = JSON.parse(result);
               $('#loader').addClass('sr-only');
              let html = '';
              let no = 0;
              html = ''; 
              for( i = 0;i<data.length;i++){
                no++
                html += '<tr>'+
                          '<td>'+no+'</td>'+
                          '<td>'+data[i].nama_barang+'</td>'+
                          '<td class="text-right">'+bilangan(data[i].stokakhir)+'</td>'+
                          '<td class="text-right"><input type="text" id="so'+no+'" hidden readonly value="'+data[i].stokakhir+'" name="so_akhir[]"><input type="text" hidden readonly value="'+data[i].id_barang+'" name="id_brg[]"><input type="text" class="benar" name="stok_benar[]" data-no="'+no+'" id="bnr'+no+'"></td>'+
                          '<td class="text-right"><input type="text" name="jml_rusak[]"></td>'+
                          '<td class="text-right"><input type="text" id="sls'+no+'" name="selisih[]" readonly></td>'+
                          '<td class="text-right">'+bilangan(data[i].returlast)+'</td>'+
                           '<td class="text-right"><input type="text" id="retur'+no+'" name="returtrue[]" value=""></td>'+
                        '</tr>';
                }
              
              $('#isi').html(html);
              $('.benar').on('keyup', function(e){
                e.preventDefault();
                let no = $(this).attr('data-no');
                let idbenar = "#bnr"+no;
                let idso = "#so"+no;
                let sls = $(idbenar).val()-$(idso).val();
                let idsls = '#sls'+no;
                $(idsls).val(sls).attr('readonly',true);
              })  
            }
          })
        }
       $('#form_add_card').on('submit', function(e){
          e.preventDefault();
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
              url: '<?= base_url()."Stock_opname/submit_so";?>',
              data : $('#form_add_card').serialize(),
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
                  $('#alert').addClass('sr-only');
                  $('#toggle_add').hide();
                  $('#form_card').addClass('sr-only');
                })
              }
            })
          })
        })
       $('#btn-save-pdf').on('click', function(e){
          let id = $(this).attr('data-id');
          
          window.open("<?= base_url().'Stock_opname/get_pdf/?id=';?>"+id, '_blank');
          
        })

        function cek_so(){
          $.ajax({
            dataType: 'JSON',
            url: '<?= site_url()."Stock_opname/cek_so";?>',
            success: function(result){
              if(result != '-'){
                $('#alert').addClass('sr-only');
                $('#toggle_add').hide();
              }else{
                $('#alert').removeClass('sr-only');
                $('#toggle_add').show();
              }
            }
          })
        }


      
      })
    </script>