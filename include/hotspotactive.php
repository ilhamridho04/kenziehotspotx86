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
include_once('./config.php');
if(!isset($_SESSION["$userhost"])){
  echo "<style>table.zebra{color:#ffffff;}</style>";
	echo "<meta http-equiv='refresh' content='0;url=./' />";
}else{

// routeros api
include_once('./config.php');
include_once('../lib/routeros_api.class.php');
include_once('../lib/formatbytesbites.php');
$API = new RouterosAPI();
$API->debug = false;
$API->connect( $iphost, $userhost, decrypt($passwdhost));

  $gethotspotactive = $API->comm("/ip/hotspot/active/print");
	$TotalReg = count($gethotspotactive);
	
	$counthotspotactive = $API->comm("/ip/hotspot/active/print", array(
	  "count-only" => "",));

}
?>
<div id="reloadHotspotActive">

	<section class="content p-0 bg-trp">
        <div class="col-12 p-1">
          <div class="card">
            <div class="card-header p-2">
              <h3 class="card-title pull-left"><?php
  if($counthotspotactive < 2 ){echo "$counthotspotactive item";
  }elseif($counthotspotactive > 1){
  echo "$counthotspotactive items";};echo"</th>";
?></h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-1">

                <div class="row">
                  <div class="col-sm-12">
			  
<div class="div-t">			   
<table id="tFilter" class="table table-sm table-bordered table-hover text-nowrap">
  <thead>
  <tr>
    <th></th>
    <th>Server</th>
    <th>User</th>
    <th>Address</th>
    <th>Mac Address</th>
    <th>Uptime</th>
    <th>Bytes Out</th>
    <th>Login By</th>
  </tr>
  </thead>
  <tbody>
<?php
	for ($i=0; $i<$TotalReg; $i++){
	$hotspotactive = $gethotspotactive[$i];
	$id = $hotspotactive['.id'];
	$server = $hotspotactive['server'];
	$user = $hotspotactive['user'];
	$address = $hotspotactive['address'];
	$mac = $hotspotactive['mac-address'];
	$uptime = formatDTM($hotspotactive['uptime']);
	$byteso = formatBytes($hotspotactive['bytes-out'], 2);
	$loginby = $hotspotactive['login-by'];
	
	echo "<tr>";
	echo "<td style='text-align:center;'><a  title='Remove ". $user . "' href='./?remove-user-active=". $id . "'><i class='fa fa-minus-square text-danger'></i></a></td>";
	echo "<td>" . $server . "</td>";
	echo "<td><a title='Open User " .$user. "' style='color:#000;' href=./?hotspot-user=" .$user. "><i class='fa fa-edit'></i> " .$user."</a></td>";
	echo "<td>" . $address . "</td>";
	echo "<td>" . $mac . "</td>";
	echo "<td style='text-align:right;'>" . $uptime . "</td>";
	echo "<td style='text-align:right;'>" . $byteso . "</td>";
	echo "<td>" . $loginby . "</td>";
	echo "</tr>";
	}
?>
  </tbody>
</table>
</div>
</div>
</div>
</div>
<!-- /.card-body -->
</div>
<!-- /.card -->
</div>
<!-- /.col -->
</section>
</div>
