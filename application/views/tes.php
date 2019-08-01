<?php 
$bulan = '7';
$tahun = '2019';
$string = $bulan.'-'.$tahun;
?>
<?php 
echo '<pre>';
print_r($barang);
echo '</pre>';?>
<table border="1">
  <thead>
    <tr>
      <td rowspan="2">No.</td>
      <td rowspan="2">Nama Barang</td>
      <td colspan="<?= jmldaribulan($string);?>" class="text-center">Tanggal</td>
      <td rowspan="2">Total</td>
    </tr>
    <tr>
      <?php for($i=1;$i<=jmldaribulan($string);$i++):?>
      <td><?php echo $i;?></td>
      <?php endfor;?>
    </tr>
  </thead>
  <tbody>
    <tr>

    </tr>
  </tbody>
  
</table>
