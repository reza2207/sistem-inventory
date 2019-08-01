
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" id="main">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Report Harian</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url();?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url().'barang';?>">Report</a></li>
            <li class="breadcrumb-item active">Harian</li>
          </ol>
        </nav>
      </div>
      <div id="loader" class="sr-only">
        <i class="fa fa-spinner fa-spin"></i>
      </div>
      <div class="alert sr-only" id="alert">
      </div>
     
      <div class="form-group row">
        <label class="col-sm-2 col-form-label">Pilih Tanggal</label>
        <div class="col-sm-2">
          <input name="tanggal" id="tanggal" class="form-control datepicker" type="text">
            
        </div>
       
        <div class="col-sm-2">
          <button class="btn btn-primary" id="go" type="submit">GO</button>
          <button class="btn btn-success" id="pdf" type="submit">GET PDF</button>
        </div>
      </div>
      

      <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered"  id="table" style="font-family:'Times New Roman', Times, serif; font-size: 12px;width: 100%">
          <thead class="bg-warning">
            <tr>
              <th class="text-center align-middle">No.</th>
              <th class="text-center align-middle">Nama Barang</th>
              <th class="text-center align-middle">Stok Awal</th>
              <th class="text-center align-middle">Stok Masuk</th>
              <th class="text-center align-middle">Stok Keluar</th>
              <th class="text-center align-middle">Stok Akhir</th>
            </tr>
          </thead>
          <tbody id="isi">
          </tbody>
        </table>
      </div>
    </main>
  
    <script>
      $(document).ready(function() {
       $( ".datepicker" ).datepicker();
       $( ".datepicker" ).datepicker("option", "dateFormat", "dd-mm-yy");
        $('#go').on('click', function(e){
          $('#loader').removeClass('sr-only');
          e.preventDefault();
          $('#isi').html('');
          let tanggal = $('#tanggal').val();
          if(tanggal == ''){
            swal('Please Select a Date')

          }else{
            $.ajax({
              type: 'POST',
              url: '<?= base_url()."Report/get_days_in_a_month";?>',
              data: {tanggal: tanggal},
              success: function(result){
                $('#loader').addClass('sr-only');
                let data = JSON.parse(result);
                
                let html = '';
                let no = 0;
                for( i = 0;i<data.length;i++){
                  no++
                  html += '<tr>'+
                            '<td>'+no+'</td>'+
                            '<td>'+data[i].nama_barang+'</td>'+
                            '<td class="text-right">'+bilangan(data[i].stokawal)+'</td>'+
                            '<td class="text-right">'+bilangan(data[i].stokmasuk)+'</td>'+
                            '<td class="text-right">'+bilangan(data[i].stokkeluar)+'</td>'+
                            '<td class="text-right">'+bilangan(data[i].stokakhir)+'</td>'+
                          '</tr>';
                  }
                $('#isi').html(html);
              }
            })
          }
        })
        $('#pdf').on('click', function(e){
          
          let tanggal = $('#tanggal').val();
          if(tanggal == ''){
            swal('Please Select a Date')

          }else{
            window.open("<?= base_url().'report/bulanan/pdf?tanggal=';?>"+tanggal, '_blank');
          }
          
        })
      
      })
    </script>