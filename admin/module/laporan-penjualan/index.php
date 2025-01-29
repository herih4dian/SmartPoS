

<?php
	$from = isset($_POST['from']) ? htmlentities($_POST['from']) : date('Y-m-d');
	$to = isset($_POST['to']) ? htmlentities($_POST['to']) : date('Y-m-d');
	$id_kategori = isset($_POST['id_kategori']) ? htmlentities($_POST['id_kategori']) : '';
	$id_supplier = isset($_POST['id_supplier']) ? htmlentities($_POST['id_supplier']) : '';
	$id_merk = isset($_POST['id_merk']) ? htmlentities($_POST['id_merk']) : '';
?>
<div class="row">
	<div class="col-md-12">
		<h4>
			<?php if(!empty($_GET['cari'])){ ?>
			Data Laporan Penjualan <?php echo $from." s.d. ".$to;?>
			<?php }?>

		</h4>
		<br />
		<div class="card">
			<div class="card-header">
				<h5 class="card-title mt-2">Cari Laporan Penjualan</h5>
			</div>
			<div class="card-body p-0">
				
				<form method="post" action="index.php?page=laporan-penjualan&cari=ok">
					<table class="table table-striped">
						<tr>
							<th>
								Tanggal Mulai
							</th>
							<th>
								Tanggal Akhir
							</th>
							<th>
								Kategori
							</th>
							<th>
								Supplier
							</th>
							<th>
								Merk
							</th>
						</tr>
						<tr>
							<td>
								<input type="date" class="form-control" name="from" value="<?=$from?>">
							</td>
							<td>
								<input type="date" class="form-control" name="to" value="<?=$to?>">
							</td>
							<td>
								<select name="id_kategori" class="form-control">
									<option value="">Pilih Kategori</option>
									<?php  
									$kat = $lihat->kategori(); 
									$selectedKategori = $id_kategori; // Ambil kategori yang terpilih dari hasil query

									foreach($kat as $isi) {  
										// Cek apakah kategori saat ini adalah yang terpilih
										$selected = ($isi['id'] == $selectedKategori) ? 'selected' : ''; 
									?>
										<option value="<?php echo $isi['id']; ?>" <?php echo $selected; ?>>
											<?php echo $isi['nama_kategori']; ?>
										</option>
									<?php } ?>
								</select>
							</td>
							<td>
								<select name="id_supplier" class="form-control">
									<option value="">Pilih Supplier</option>
									<?php  
									$kat = $lihat->supplier(); 
									$selectedSupplier = $id_supplier; // Ambil ID supplier yang terpilih sebelumnya

									foreach ($kat as $isi) {  
										// Cek apakah supplier saat ini adalah yang terpilih
										$selected = ($isi['id'] == $selectedSupplier) ? 'selected' : ''; 
									?>
										<option value="<?php echo $isi['id']; ?>" <?php echo $selected; ?>>
											<?php echo $isi['nama_supplier']; ?>
										</option>
									<?php } ?>
								</select>
							</td>
							<td>
								<select name="id_merk" class="form-control">
									<option value="">Pilih Merk</option>
									<?php  
									$merkList = $lihat->merk(); 
									$selectedMerk = $id_merk; // Ambil ID merk yang terpilih sebelumnya

									foreach ($merkList as $isi) {  
										// Cek apakah merk saat ini adalah yang terpilih
										$selected = ($isi['id'] == $selectedMerk) ? 'selected' : ''; 
									?>
										<option value="<?php echo $isi['id']; ?>" <?php echo $selected; ?>>
											<?php echo $isi['nama_merk']; ?>
										</option>
									<?php } ?>
								</select>
							</td>
							
						</tr>
						<tr>
							<td colspan="5">
								<input type="hidden" name="periode" value="ya">
									<button class="btn btn-primary">
										<i class="fa fa-search"></i> Cari
									</button>
									<a href="index.php?page=laporan-penjualan" class="btn btn-success">
										<i class="fa fa-refresh"></i> Refresh</a>

									<?php if(!empty($_GET['cari'])){?>
									<a href="excel.php?cari=ok&from=<?=$from;?>&to=<?=$to;?>&id_kategori=<?=$id_kategori;?>&id_supplier=<?=$id_supplier;?>&id_merk=<?=$id_merk;?>" class="btn btn-info"><i
											class="fa fa-download"></i>
										Excel</a>
									<?php }?>
							</td>
						</tr>
					</table>
				</form>
			</div>
		</div>
         <br />
         <br />
         <!-- view barang -->
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered w-100 table-sm" id="example1">
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
									$from = $_POST['from'];
									$to = $_POST['to'];
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
								<td><?php echo $isi['qty'];?> </td>
								<td>Rp.<?php echo number_format($isi['total_modal']);?>,-</td>
								<td>Rp.<?php echo number_format($isi['total_jual']);?>,-</td>
								<td>Rp.<?php echo number_format($isi['laba']);?>,-</td>
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
			</div>
		</div>
     </div>
 </div>