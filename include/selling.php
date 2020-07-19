<?php
/*
 *  Copyright (C) 2018 Laksamadi Guko.
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
session_start();
// hide all error
error_reporting(0);

if(!isset($_SESSION["$userhost"])){
	echo "<meta http-equiv='refresh' content='0;url=./' />";
}else{


$idhr = $_GET['idhr'];
$idbl = $_GET['idbl'];
$remdata = ($_POST['remdata']);

if(isset($remdata)){
  if(strlen($idhr) > "0"){
	if ($API->connect( $iphost, $userhost, decrypt($passwdhost))) {
	  $API->write('/system/script/print', false);
	  $API->write('?source='.$idhr.'', false);
	  $API->write('=.proplist=.id');
	  $ARREMD = $API->read();
	  for ($i=0;$i<count($ARREMD);$i++) {
	  $API->write('/system/script/remove', false);
	  $API->write('=.id=' . $ARREMD[$i]['.id']);
	  $READ = $API->read();
	    
	}
	}
  }elseif(strlen($idbl) > "0"){
  if ($API->connect( $iphost, $userhost, decrypt($passwdhost))) {
	  $API->write('/system/script/print', false);
	  $API->write('?owner='.$idbl.'', false);
	  $API->write('=.proplist=.id');
	  $ARREMD = $API->read();
	  for ($i=0;$i<count($ARREMD);$i++) {
	  $API->write('/system/script/remove', false);
	  $API->write('=.id=' . $ARREMD[$i]['.id']);
	  $READ = $API->read();
	    
	}
	}
  
}
  echo "<script>window.location='./?hotspot=selling'</script>";
}
  
}


if(strlen($idhr) > "0"){
  if ($API->connect( $iphost, $userhost, decrypt($passwdhost))) {
	$API->write('/system/script/print', false);
	$API->write('?=source='.$idhr.'');
	$ARRAY = $API->read();
	$API->disconnect();
  }
	$filedownload = $idhr;
	$shf = "hidden";
	$shd = "submit";
}elseif(strlen($idbl) > "0"){
  if ($API->connect( $iphost, $userhost, decrypt($passwdhost))) {
	$API->write('/system/script/print', false);
	$API->write('?=owner='.$idbl.'');
	$ARRAY = $API->read();
	$API->disconnect();
  }
	$filedownload = $idbl;
	$shf = "hidden";
	$shd = "submit";
}elseif($idhr == "" || $idbl == ""){
  if ($API->connect( $iphost, $userhost, decrypt($passwdhost))) {
	$API->write('/system/script/print', false);
	$API->write('?=comment=mikhmon');
	$ARRAY = $API->read();
	$API->disconnect();
}
$filedownload = "all";
$shf = "text";
$shd = "hidden";
}
?>
		<script>
			function downloadCSV(csv, filename) {
			  var csvFile;
			  var downloadLink;
			  // CSV file
			  csvFile = new Blob([csv], {type: "text/csv"});
			  // Download link
			  downloadLink = document.createElement("a");
			  // File name
			  downloadLink.download = filename;
			  // Create a link to the file
			  downloadLink.href = window.URL.createObjectURL(csvFile);
			  // Hide download link
			  downloadLink.style.display = "none";
			  // Add the link to DOM
			  document.body.appendChild(downloadLink);
			  // Click download link
			  downloadLink.click();
			  }
			  
			  function exportTableToCSV(filename) {
			    var csv = [];
			    var rows = document.querySelectorAll("#selling tr");
			    
			   for (var i = 0; i < rows.length; i++) {
			      var row = [], cols = rows[i].querySelectorAll("td, th");
			   for (var j = 0; j < cols.length; j++)
            row.push(cols[j].innerText);
        csv.push(row.join(","));
        }
        // Download CSV file
        downloadCSV(csv.join("\n"), filename);
        }
        
        window.onload=function() {
          var sum = 0;
          var dataTable = document.getElementById("selling");
          
          // use querySelector to find all second table cells
          var cells = document.querySelectorAll("td + td + td + td");
          for (var i = 0; i < cells.length; i++)
          sum+=parseFloat(cells[i].firstChild.data);
          
          var th = document.getElementById('total');
          th.innerHTML = th.innerHTML + (sum) ;
        }
		</script>
<div>
<section class="content p-0 bg-trp">
<div class="col-12 p-1">
<div class="card">
<div class="card-header p-2">
	<h3 class="card-title pull-left">Selling Report</h3>
</div>
<!-- /.card-header -->
<div class="card-body p-1">
<div class="row">
<div class="col-sm-12">
			  
				 <p> 
		    <?php if($curency == "Rp" || $curency == "rp" || $curency == "IDR" || $curency == "idr"){?>
		      <ul>
		        <li>Filter berdasarkan hari klik pada [19/2018].</li>
		        <li>Filter berdasarkan bulan klik pada [jan/].</li>
		        <li>Click CSV untuk mengunduh.</li>
		        <li>Disarankan untuk menghapus laporan penjualan setelah mengunduh laporan CSV.</li>
		      </ul>
		    <?php }else{?>
		      <ul>
		        <li>Filter by day click on [19/2018].</li>
		        <li>Filter by month day click on [jan/].</li>
		        <li>Klik CSV to download.</li>
		        <li>It is recommended to delete the sales report after download  the CSV report.</li>
		      </ul>
		    <?php }?>
			</p>
		<div>	   
		  <input class="form-control form-control-sm mx-1 my-1" style="float:left; max-width: 150px;" type="<?php echo $shf;?>" id="filterData" onkeyup="fTgl()" placeholder="Filter date" title="Filter selling date"> &nbsp;
		  <button class="btn btn-sm btn-primary mx-1 my-1" onclick="exportTableToCSV('report-mikhmon-<?php echo $filedownload;?>.csv')" title="Download selling report"><i class="fa fa-download"></i> CSV</button>
		  <button class="btn btn-sm btn-primary mx-1 my-1" onclick="location.href='./?hotspot=selling';" title="Reload all data"><i class="fa fa-search"></i> ALL</button>
		  <input type="<?php echo $shd;?>"  data-toggle="modal" data-target="#remdata" name="remdata" class="btn btn-sm btn-danger mx-1 my-1" onclick="location.href='#remdata';" title="Delete Data <?php echo $filedownload;?>" value="Delete data <?php echo $filedownload;?>">
		</div>
		  <div style="padding-top:10px; overflow-x:auto; overflow-y:auto; max-height: 70vh;">
			<table id="selling" class="table table-sm table-bordered table-hover text-nowrap">
				<thead class="thead-light">
				<tr>
				  <th colspan=2 >Selling report <?php echo $filedownload;?><b style="font-size:0;">,</b></th>
				  <th style="text-align:right;">Total</b></th>
				  <th style="text-align:right;" id="total"></th>
				</tr>
				<tr>
					<th >Date</th>
					<th >Time</th>
					<th >Username</th>
					<th style="text-align:right;">Price <?php echo $curency;?></th>
				</tr>
				</thead>
				<?php
					$TotalReg = count($ARRAY);

						for ($i=0; $i<$TotalReg; $i++){
						  $regtable = $ARRAY[$i];
						  echo "<tr>";
							echo "<td>";
							$getname = explode("-|-",$regtable['name']);
							$getowner = $regtable['owner'];
							$tgl = $getname[0];
							$getdy = explode("/",$tgl);
							$m = $getdy[0];
							$dy = $getdy[1]."/".$getdy[2];
							echo "<a style='color:#000;' href='./?hotspot=selling&idbl=".$getowner ."' title='Filter selling report month : ".$getowner."'>$m/</a><a style='color:#000;' href='./?hotspot=selling&idhr=".$tgl ."' title='Filter selling report day : ".$tgl."'>$dy</a>";
							echo "</td>";
							echo "<td>";
							$ltime = $getname[1];
							echo $ltime;
							echo "</td>";
							echo "<td>";
							$username = $getname[2];
							echo $username;
							echo "</td>";
							echo "<td style='text-align:right;'>";
							$price = $getname[3];
							echo $price;
							echo "</td>";
							echo "</tr>";
							}
				?>
			</table>
		</div>
</div>
<!-- /.card-body -->
</div>
<!-- /.card -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->
</section>

<!-- Modal -->
<div class="modal fade" id="remdata" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title p-0" id="exampleModalLabel">Are you sure to Delete?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	<p>
        <?php if($curency == "Rp" || $curency == "rp" || $curency == "IDR" || $curency == "idr"){?>
		      <ul>
		        <li>Menghapus Selling Report akan menghapus User Log juga.</li>
		        <li>Disarankan untuk mengunduh User Log terlebih dahulu.</li>
		      </ul>
		    <?php }else{?>
		      <ul>
		        <li>Deleting the Selling Report will delete the User Log as well. </li>
		        <li>It is recommended to download User Log first. </li>
		      </ul>
		    <?php }?>
		</p>
	<?php

	echo "<form autocomplete='off' method='post' action=''>";
	echo "<center>";
	echo "<input type='submit' name='remdata' title='Yes' class='btn btn-primary' value='Yes'/>&nbsp;";
	echo '<button type="button" class="btn btn-danger" data-dismiss="modal" title="No">No</button>';
	echo "</center>";
	echo "</form>";

  	?>
      </div>
    </div>
  </div>
</div>
</div>		
		
	<script>
	function fTgl() {
  var input, filter, table, tr, td, i;
  input = document.getElementById("filterData");
  filter = input.value.toUpperCase();
  table = document.getElementById("selling");
  tr = table.getElementsByTagName("tr");
  for (i = 1; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
	</script>
