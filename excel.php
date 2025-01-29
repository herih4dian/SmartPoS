<?php 
	@ob_start();
	session_start();
	if(!empty($_SESSION['admin'])){ }else{
		echo '<script>window.location="login.php";</script>';
        exit;
	}
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=data-laporan-".date('Y-m-d').".xls");  //File name extension was wrong
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false); 

    require 'config.php';
    include $view;
    $lihat = new view($config);

    $from = isset($_GET['from']) ? htmlentities($_GET['from']) : date('Y-m-d');
    $to = isset($_GET['to']) ? htmlentities($_GET['to']) : date('Y-m-d');
    $id_kategori = isset($_GET['id_kategori']) ? htmlentities($_GET['id_kategori']) : '';
    $id_supplier = isset($_GET['id_supplier']) ? htmlentities($_GET['id_supplier']) : '';
    $id_merk = isset($_GET['id_merk']) ? htmlentities($_GET['id_merk']) : '';
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
	<!-- view barang -->	
    <!-- view barang -->	
    <div class="modal-view">
        <h3 style="text-align:center;"> 
        <?php if(!empty($_GET['cari'])){ ?>
			Data Laporan Penjualan <?php echo $from." s.d. ".$to;?>
		<?php }?>

        </h3>
        <table border="1" width="100%" cellpadding="3" cellspacing="4">
            <thead>
                <tr style="background:#DFF0D8;color:#333;">
                    <th style="width:5%;"> No</th>
                    <th style="width:10%;"> Kode. Transaksi </th>
                    <th style="width:10%;"> Nama Barang </th>
                    <th style="width:5%;"> Qty </th>
                    <th style="width:10%;"> Total Modal </th>
                    <th style="width:10%;"> Total Jual </th>
                    <th style="width:10%;"> Laba </th>
                    <th style="width:10%;"> Tanggal Transaksi </th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $no=1; 
                    if(!empty($_GET['cari'])){
                        $no=1; 
                        $jumlah = 0;
                        $bayar = 0;
                        $hasil = $lihat->periode_jual($from, $to, $id_kategori, $id_supplier, $id_merk);
                    }
                ?>
                <?php 
                    $bayar = 0;
                    $jumlah = 0;
                    $modal = 0;
                    foreach($hasil as $isi){ 
                        $bayar += $isi['total_jual'];
                        $modal += $isi['total_modal'];
                        $jumlah += $isi['qty'];
                ?>
                <tr>
                    <td><?php echo $no;?></td>
                    <td><?php echo $isi['kode_transaksi'];?></td>
                    <td><?php echo $isi['nama_barang'];?></td>
                    <td><?php echo $isi['qty'];?></td>
                    <td><?php echo $isi['total_modal'];?></td>
                    <td><?php echo $isi['total_jual'];?></td>
                    <td><?php echo $isi['laba'];?></td>
                    <td><?php echo $isi['tanggal_transaksi'];?></td>
                </tr>
                <?php $no++; }?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Total Terjual</td>
                    <th><?php echo $jumlah;?></td>
                    <th>Rp.<?php echo number_format($modal);?>,-</th>
                    <th>Rp.<?php echo number_format($bayar);?>,-</th>
                    <th style="background:#0bb365;color:#fff;">Keuntungan</th>
                    <th style="background:#0bb365;color:#fff;">
                        Rp.<?php echo number_format($bayar-$modal);?>,-</th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>
