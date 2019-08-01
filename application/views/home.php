
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-8">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <div class="col-12 col-lg-12 ">
          <ul class="list-group" >
            <li class="list-group-item active" style="background: #585858 ;border-color:inherit">
            <h1 class="h2">Welcome <?= isset($fullname) ? $fullname : '-';?> <i class="fa fa-smile"></i></h1>
            </li>
          </ul>
        </div>
       
      </div>
      <div class="col-12 col-lg-12 ">
              
        <div class="alert bg-danger text-white sr-only" id="alert">
          <i class="fa fa-exclamation"></i><b> Bulan ini belum melakukan stok opname. <a href="<?= base_url().'Stock_opname/get_card';?>" target="_blank">klik disini</a> untuk download Kartu Stok</b>
        </div>
        <div class="alert bg-danger text-white sr-only" id="alert-min"><b> 
          <i class="fa fa-exclamation"></i> ADA <i id="jmlmin"></i> BARANG YANG SUDAH MENCAPAI MINIMUM. <a href="#" id="btn-brg-min">KLIK DISINI</a>
        </b>
        </div>
        <div id="list-min" class="sr-only">
          <table class="table table-hover table-striped table-bordered">
            <thead class="bg-primary text-white">
              <tr>
                <td>No</td>
                <td>Nama Barang</td>
                <td>Stok Akhir</td>
                <td>Minimum Stok</td>
              </tr>
            </thead>
            <tbody id="body-min">

            </tbody>
          </table>
        </div>
        <div class="alert bg-danger text-white sr-only" id="alert-max"><b> 
          <i class="fa fa-exclamation"></i> ADA <i id="jmlmax"></i> BARANG YANG SUDAH MENCAPAI MAKSIMUM. <a href="#" id="btn-brg-max">KLIK DISINI</a>
          </b>
        </div>
        <div id="list-max" class="sr-only">
          <table class="table table-hover table-striped table-bordered">
            <thead class="bg-primary text-white">
              <tr>
                <td>No</td>
                <td>Nama Barang</td>
                <td>Stok Akhir</td>
                <td>Maksimum Stok</td>
              </tr>
            </thead>
            <tbody id="body-max">

            </tbody>
          </table>
        </div>
      </div>
      <div class="col-12 col-lg-12 ">
       <img src="<?= base_url('picture/d136873aa3962dcb04dbd49db201ea92.jpeg');?>" class="img-responsive" width="100%" height="236">
        
        </div>
      </div>
    </main>

    <script>
      $(document).ready(function() {
        cek_so();
        cek_max();
        cek_min();
        $('#btn-brg-max').on('click', function(e){
          e.preventDefault();
          $.ajax({
            type: 'POST',
            url: '<?= base_url()."Barang/get_list_max";?>',
            dataType: 'JSON',
            success: function (result){
              $('#list-max').removeClass('sr-only')
              let html = "";
              let no = 0;
              for(i = 0; i < data.length ; i++){
              no++
              html += "<tr>"+
                        "<td>"+no+"</td>"+
                        "<td>"+data[i].nama_barang+"</td>"+
                        "<td>"+data[i].stok_akhir+"</td>"+
                        "<td>"+data[i].max+"</td>"+
                      "</tr>";
              }

              $('#body-max').html(html);
            }
          })
        })
        $('#btn-brg-min').on('click', function(e){
          e.preventDefault();
          $.ajax({
            type: 'POST',
            url: '<?= base_url()."Barang/get_list_min";?>',
            dataType: 'JSON',
            success: function (data){
              $('#list-min').removeClass('sr-only')
              let html = "";
              let no = 0;
              for(i = 0; i < data.length ; i++){
              no++
              html += "<tr>"+
                        "<td>"+no+"</td>"+
                        "<td>"+data[i].nama_barang+"</td>"+
                        "<td>"+data[i].stok_akhir+"</td>"+
                        "<td>"+data[i].min+"</td>"+
                      "</tr>";
              }

              
              $('#body-min').html(html);
            }
          })
        })
      })


      function cek_max(){
        $.ajax({
          dataType: 'JSON',
          url: '<?= site_url()."Barang/cek_max";?>',
          success: function(result){
            if(result != '-'){
              $('#alert-max').removeClass('sr-only');
              swal({
                type: 'warning',
                text: 'ADA '+result+' BARANG YANG SUDAH MENCAPAI MAKSIMUM.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
              })
              $('#jmlmax').text(result);
            }else{
              $('#alert-max').addClass('sr-only');
            }
          }
        })
      }
      function cek_min(){
        $.ajax({
          dataType: 'JSON',
          url: '<?= site_url()."Barang/cek_min";?>',
          success: function(result){
            if(result != '-'){
              $('#alert-min').removeClass('sr-only');
              swal({
                type: 'warning',
                text: 'ADA '+result+' BARANG YANG SUDAH MENCAPAI MINIMUM.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
              })
              $('#jmlmin').text(result);
            }else{
              $('#alert-min').addClass('sr-only');
            }
          }
        })
      }
      function cek_so(){
        $.ajax({
          dataType: 'JSON',
          url: '<?= site_url()."Stock_opname/cek_so";?>',
          success: function(result){
            if(result == '-'){
              $('#alert').removeClass('sr-only');
              
            }else{
              $('#alert').addClass('sr-only');
            }
          }
        })
      }
      
    </script>