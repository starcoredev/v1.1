<?php
include 'koneksi.php';
//echo json_encode($_SERVER);
$domain = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
if(!isset($_SESSION["user"])){
	//echo "no sesi";
	header("location: login.php");
	exit;
}

if(isset($_GET["get"])){
	//$query = "select * from data";
	
	//$page = $_POST["page"];
	
	$column = "id, name as 'nama', address as 'detail', latitude as 'lat', longitude as 'lng', tag, images, 'hide' as 'display'";
	$query = "select [column] from basic_info";
	
	if(isset($_GET["key"])){
		$query .= " where id = '".$_GET["key"]."' ";
	}
	
	/*if(isset($_POST["tag"]) || isset($_POST["keyword"])){
		$query .= " where ";
		if(isset($_POST["tag"])){
			$tag = $_POST["tag"];
			if($tag == "")$tag = "---";
			$tag = explode(",", $tag);
			
			$t = "";
			for($i = 0; $i < count($tag); $i++){
				$t .= " tag like '%" . $tag[$i] . "%' ";
				if($i < count($tag) - 1){
					$t .= " or ";
				}
			}
			//$t = substr($t, 0, strlen($t) - 1);
			$t = " (" . $t . ") ";
			
			$query .= $t;
		}
		if(isset($_POST["keyword"])){
			if(isset($_POST["tag"]))$query .= " and ";
			$query .= " name like '%".$_POST["keyword"]."%' ";
		}
	}*/
	
	//$query = $query . "  limit ".$page.", 10 ";
	
	//echo $query;
	
	//$query .= " limit 0, 10 ";
	//$query .= " where tag like '%observation%' limit 0, 5 ";
	//$query .= " where id not like '%jdn%' limit 0, 5 ";
	//$query .= " where id = 'fd1e39f1-7e97-48b9-a18f-141394424611' ";
	//$query .= " where address = 'DESA SEPEMPANG RT AIRMERAH RT 01/01' ";
	//$query .= " where address = 'JL. RAYA LEKUNIK MOKDALE' or  address = 'DESA SEPEMPANG RT AIRMERAH RT 01/01' ";
	
	$count = execqueryreturn("data", str_replace("[column]", "count(*)", $query));
	$data = execqueryreturnall("data", str_replace("[column]", $column, $query));
	$markers = array(); //execqueryreturnall("data", str_replace("[column]", $column, $query));
	
	for($i = 0; $i < count($data); $i++){
		if(strpos($data[$i]["tag"], 'bengkel motor') !== false)$data[$i]["icon"] = 'ic_e';
		else if(strpos($data[$i]["tag"], 'badan usaha') !== false)$data[$i]["icon"] = 'ic_c';
		else if(strpos($data[$i]["tag"], 'bumdes') !== false)$data[$i]["icon"] = 'ic_b';
		else if(strpos($data[$i]["tag"], 'layanan publik') !== false)$data[$i]["icon"] = 'ic_a';
		else if(strpos($data[$i]["tag"], 'pelaku usaha kecil') !== false)$data[$i]["icon"] = 'ic_a';
		
		//$markers[$i]["detail_info"] = execqueryreturnall("data", "select * from detail_info where basic_id = '".$markers[$i]["id"]."'");
		
	}
	
	//$result = array("data"=>$data, "markers"=>$markers, "count"=> ceil($count / 6), "page"=>$page);
	$result = array("data"=>$data);
	echo json_encode($result);
	exit;
}

?>
<!DOCTYPE html>
<html lang="id">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>WEBGIS</title>
	  
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
   integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
   crossorigin=""/>
   <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
   integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
   crossorigin=""></script>
   
		<link href="assets/bootstrap/css/bootstrap.css" rel="stylesheet">
		<link rel='stylesheet' id='vc_google_fonts_raleway100200300regular500600700800900-css'  href='https://fonts.googleapis.com/css?family=Raleway%3A100%2C200%2C300%2Cregular%2C500%2C600%2C700%2C800%2C900&#038;ver=6.0.3' type='text/css' media='all' />
				
		<link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
		<script src="assets/bootstrap/js/jquery.min.js"></script>
		<link rel="stylesheet" type="text/css" href="assets/css/animate.css">
		
		<script src="assets/bootstrap/js/bootstrap.js"></script>
		
		<!-- datatables -->
		<script type="text/javascript" src="assets/datatables/dataTables.bootstrap.min.js" ></script>
		<script type="text/javascript" src="assets/datatables/jquery.dataTables.min.js" ></script>
		<link href="assets/datatables/jquery.dataTables.min.css" rel="stylesheet">
		<!-- // datatables -->
		
		<!-- scrollbar -->
		<script type="text/javascript" src="assets/scrollbar/jquery.scrollbar.js" ></script>
		<link href="assets/scrollbar/scrollbar.css?<?php echo date("His"); ?>" rel="stylesheet">
		<!-- // scrollbar -->
		
		<!-- flexslider -->
		<script type="text/javascript" src="assets/flexslider/jquery.flexslider.js" ></script>
		<link href="assets/flexslider/flexslider.css" rel="stylesheet">
		<!-- // flexslider -->
		
		<link rel="stylesheet" type="text/css" href="assets/sidebar/sidebar.css?<?php echo date("His"); ?>">
		
		
		
		<link href="assets/css/styles.css?<?php echo date("His"); ?>" rel="stylesheet">
		<!--<link href="assets/css/mobile.css?<?php echo date("His"); ?>" rel="stylesheet">
		<script src="assets/js/script.js?<?php echo date("His"); ?>"></script>-->
		
		
	</head>
	<body>
		<div class="logo">
			<a href="javascript:void(0)" onclick="showAbout()" class="img"><img src="assets/images/logo.png" /></a>
			<a href="logout.php" class="btn btn-sm btn-danger logout"><i class="fa fa-exit"></i> Sign Out</a>
		</div>
		<div class="dialog" onclick="showAbout();">
			<div class="dialog-content">
				<table>
					<tr>
						<td>
							<img src="assets/images/logo.png" />
							<img src="assets/images/about.png" />
						</td>
						<td>
							<p>Our talented people combine art and science to uncover the patterns in big data flood while looking for fresh ideas hidden in small data to gain valueable insights.</p>
							
							<a href="https://www.starcore.co" target="_blank">www.starcore.co</a>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<?php include("sidebar.php"); ?>
		<div id="map"></div>
		<div id="preview" class="close" ><a href="#" ><i class="fa fa-close"></i></a><div></div></div>
		<div class="mapControl">
			<ul>
				<!--<li><a href="javascript:void(0)"><i class="fa fa-globe"></i></a></li>
				<li><a href="javascript:void(0)"><i class="fa fa-crosshairs"></i></a></li>-->
				<li class="p"><a href="javascript:void(0)" onclick="mymap.setZoom(mymap.getZoom() + 1)"><i class="fa fa-plus"></i></a></li>
				<li class="m"><a href="javascript:void(0)" onclick="mymap.setZoom(mymap.getZoom() - 1)"><i class="fa fa-minus"></i></a></li>
				<!--<li><a href="javascript:void(0)"><i class="fa fa-male"></i></a></li>-->
			</ul>
		</div>
		
		
<script>
	var marker;
	var markers={};
	var data = [];
	var dataMarker = [];
	var dTable;
	
	var mbAttr = '',
		mbUrl = 'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw';

	var satellite   = L.tileLayer(mbUrl, {id: 'mapbox/satellite-v9', attribution: mbAttr}),
		streets  = L.tileLayer(mbUrl, {id: 'mapbox/streets-v11', attribution: mbAttr});

	var mymap = L.map('map', {
		zoomControl: false,
		center: [-2.752950, 122.283331],
		zoom: 5,
		layers: [satellite]
	});

	var baseLayers = {
		"Satellite": satellite,
		"Streets": streets
	};

	var overlays = {};

	L.control.layers(baseLayers, overlays, {position: 'bottomright'}).addTo(mymap);
	
	
	//$('.leaflet-control-layers').hide();
	
	setTimeout(function(){
		refreshMap();
	}, 500);
	
	$(document).ready(function(){
		$("#s").keyup(function(e){
			e.preventDefault();
			setTimeout(function(e){
				page = 1;
				refreshMap();
			}, 500);
		});
		
		$("#preview > a").click(function(){
			$("#preview").addClass("close");
		});
		
		$(".sidebar-layers .checkbox").change(function(e){
			page = 1;
			refreshMap();
		});
		 
		  /*$('#slider .flexslider').flexslider({
			animation: "slide",
			controlNav: false,
			animationLoop: false,
			slideshow: false,
			sync: "#carousel"
		  });*/
		  
		/*dTable = $('.sidebar-lists-list').DataTable({
			"searching": false,
			"lengthChange": false,
			"ordering": false,
			"bInfo" : false,
			"oLanguage": {
				"oPaginate": {
					"sNext": '<i class="fa fa-angle-right"></i>',
					"sPrevious": '<i class="fa fa-angle-left"></i>'
				}
			},
			"drawCallback": function( settings ) {
				 $("#selector thead").remove();
			 }  
		});*/
		
	});
	
	var ajax = null;
	function refreshMap(){
		var ddd = {}; //getFilterData();
		if(ajax != null)ajax.abort();
		
		$(".sidebar-lists-list tr:not(.loading)").remove();
		$(".sidebar-lists-list tr.loading").show();
		
		
		
		$.each(markers, function(key, value) {
			mymap.removeLayer(value);
		});
		
		ajax = $.ajax({type: "POST", url: "index.php?get=true", data: ddd, dataType: 'json',
			success: function(d){
				data = d["data"];
				//dataMarker = d["markers"];
				//alert(d["count"] + ', ' + d["page"]);
				//buildPagination(data.length / , d["page"]);
				refreshAllMap();
			},
			error: function (data) {
				alert("Err: " + JSON.stringify(data));
			},
			complete : function () {
				//$('html, body').animate({ scrollTop: $("body").offset().top }, 500);
			}
		});
	}
	
	function refreshAllMap(){
		var items = "";
		$(".sidebar-lists-list tr.loading").hide();
		
		$.each(data, function(index, array){
			var id = array["id"];
			var lat = array["lat"] * 1;
			var lng = array["lng"] * 1;
			
			//alert(lat + ", " + lng);
			//alert(array["icon"]);
			
			var greenIcon = L.icon({
				iconUrl: 'assets/images/'+array["icon"]+'.png',

				iconSize:     [25, 35], // size of the icon\
				iconAnchor:   [9, 34], // point of the icon which will correspond to marker's location
				popupAnchor:  [5, -35] // point from which the popup should open relative to the iconAnchor
			});
			
			var content  = '';
				/*content += '<img src = "images/' + id + '/gambar.jpg" />';*/
				content += '	<div class="list-contentx">';
				content += '		<label style="font-weight:bold;">'+array["nama"]+'</label>';
				content += '		<span style="display:block; color:green">'+array["tag"]+'</span>';
				/*items += '		<p>Auto Repair Shop</p>';*/
				content += '		<span style="display:block; color:#aaa">'+array["detail"]+'</span>';
				content += '	</div>';
			
			var m = L.marker([lat, lng], {icon: greenIcon}).addTo(mymap);
			m.bindPopup(content);
			m.on('mouseover', function (e) {
				this.openPopup();
			});
			m.on('mouseout', function (e) {
				this.closePopup();
			});
			m.on('click', function (e) {
				preview(index);
			});
			
			markers[array["id"]] = m;
			
			//items += '<li>';
			/*items += '<tr ><td>';
			items += '	<a ['+array["id"]+'] href="javascript:void(0)" class="x" onclick="preview('+index+')">';
			items += '		<label>'+array["nama"]+'</label>';
			items += '		<span>'+array["tag"]+'</span>';
			items += '		<p>'+array["detail"]+'</p>';
			items += '	</a>';
			items += '</td></tr>';*/
			//items += '</li>';
			
		});
		
		//$(".sidebar-lists-list").children("tbody").html(items);
		page = 1;
		buildListData();
	}
	
	
	var page = 1;
	var pageCount = 0;
	var displayedPage = 6;
	var displayedItemPerPage = 10;
	function buildListData(){
		pageCount = data.length / displayedPage;
		showDisplayedData();
		//buildPagination(pageCount, page);
	}
	
	function buildPagination(count, current){
		//count = 20;
		//current = 1;
		
		
		var f = Math.ceil(current / displayedPage) * displayedPage;
		
		var first = '<li class="page-item first"><a class="page-link" href="javascript:void(0)" onclick="buildPagination('+count+', 1)"><i class="fa fa-angle-double-left"></i></a></li>';
		var prev  = '<li class="page-item prev"><a class="page-link" href="javascript:void(0)" onclick="buildPagination('+count+', '+(f-displayedPage-1)+')"><i class="fa fa-angle-left"></i></a></li>';
		var next  = '<li class="page-item next"><a class="page-link" href="javascript:void(0)" onclick="buildPagination('+count+', '+(f+1)+')"><i class="fa fa-angle-right"></i></a></li>';
		var last  = '<li class="page-item last"><a class="page-link" href="javascript:void(0)" onclick="buildPagination('+count+', '+count+')"><i class="fa fa-angle-double-right"></i></a></li>';
		
		var items = '';
		
		if(f > (displayedPage * 2))items += first;
		if(f > displayedPage)items += prev;
		
		var ff = f - (displayedPage - 1);
		if(count <= displayedPage){
			ff = 1; f = 1;
		}
		
		for(var i = ff; i <= f; i++){
			var active = '';
			if(i == page)active = ' active ';
			items += '<li class="page-item '+active+'"><a class="page-link" href="javascript:void(0)" onclick="page='+i+'; showDisplayedData(); /*buildPagination('+count+', '+i+');*/">'+i+'</a></li>';
		}
		
		if(f < count)items += next;
		if(f < count - displayedPage)items += last;
		
		if(data.length <= 0)items = '';
		
		//items = prev + items + next;
		$(".pagination").html(items);
		
		//showDisplayedData();
	}
	
	
	function showDisplayedData(){
		
		var items = "";
		var n = 0;
		$(".sidebar-lists-list").children("tbody").html("");
		
		$.each(data, function(index, array){
			var add = false;
			
			for(var i = 1; i <= 7; i++){
				if($("#l" + i + " input").is(":checked")){
					if(array["tag"].indexOf($("#l" + i + " input").val()) !== -1){
						if(array["nama"].toLowerCase().indexOf(name) !== -1){
							add = true;
							n++;
							break;
						}
					}
				}
			}
			
			if(!(index >= (((page - 1) * displayedItemPerPage) + 1) && index <= page * displayedItemPerPage)){
				if(add)add = false;
			}
			
			if(add){
				if(n <= displayedItemPerPage){
					items += '<tr ><td>';
					items += '	<a ['+array["id"]+'] href="javascript:void(0)" class="x" onclick="preview('+index+')">';
					items += '		<label>'+array["nama"]+'</label>';
					items += '		<span>'+array["tag"]+'</span>';
					items += '		<p>'+array["detail"]+'</p>';
					items += '	</a>';
					items += '</td></tr>';				
				}
			}
		});
		
		$(".sidebar-lists-list").children("tbody").html(items);
		alert(n);
		buildPagination(n, page);
	}
	
	function getFilterData(){
		var f = $("#s").val().toLowerCase();
		var t = "";
		for(var i = 1; i <= 7; i++){
			if($("#l" + i + " input").is(":checked")){
				t += $("#l" + i + " input").val() + ',';
			}
		}
		t = t.substring(0, t.length - 1);
		
		var ff = {"keyword":f, "tag":t, "page": page};
		
		return ff;
	}
	
	function filterData3(){
		try{
			var f = $("#s").val().toLowerCase();
		
			//dtable.fnFilter(name);
			
			for(var i = 1; i <= 7; i++){
				if($("#l" + i + " input").is(":checked")){
					f += "|" + $("#l" + i + " input").val();
					//break;
				}
			}
			
			if(f == '')f = '---';
			//alert(f);
			
			dTable.search(f,true,false).draw();
			
			var d = dTable.rows({filter: 'applied'}).data();
			
			var idS = [];
			
			for(var i = 0; i < d.length; i++){
				var str = d[i][0];
				var id = str.substring(str.lastIndexOf('[') + 1, str.lastIndexOf(']'));
				id = id.toLowerCase();
				
				idS.push(id);
			}
			
			$.each(markers, function(key, value) {
				if(idS.indexOf(key.toLowerCase()) !== -1){
					mymap.addLayer(value);
				}
				else{
					mymap.removeLayer(value);
				}
			});
		}
		catch(e){
			//alert('err: '+ e.message);
		}
	}
	
	function filterData2(){
		var name = $("#s").val().toLowerCase();
		var tag = "";
		var items = "";
		$.each(data, function(index, array){
			var add = false;
			if(array["nama"].toLowerCase().indexOf(name) !== -1)add = true;
			else add = false;
			
			if(add){
				for(var i = 1; i <= 7; i++){
					if($("#l" + i + " input").is(":checked")){
						if(array["tag"].indexOf($("#l" + i + " input").val()) !== -1){
							add = true;
							break;
						}
						else add = false;
					}
				}
			}
			
			if(add){
				//$(".sidebar-lists-list tbody tr#" + array["id"]).removeClass("ended").addClass("display");
				$(".sidebar-lists-list tr#" + array["id"]).removeClass("display").addClass("ended");
				/*items += '<li>';
				items += '	<a href="javascript:void(0)" onclick="preview('+index+')">';
				items += '		<label>'+array["nama"]+'</label>';
				items += '		<span>'+array["tag"]+'</span>';
				items += '		<p>'+array["detail"]+'</p>';
				items += '	</a>';
				items += '</li>';*/
			}
			else{
				$(".sidebar-lists-list tr#" + array["id"]).removeClass("display").addClass("ended");
			}
		});
		//$(".sidebar-lists ul.sidebar-lists-list").html(items);
	}
	
	function goHome(){
		$(".sidebar .sidebar-detail").removeClass("open");
		
		mymap.flyTo([-2.752950, 122.283331], 5, {
			animate: true,
			duration: 0.5
		});
	}
	
	function preview(d){
		
		$("#d-title").html(data[d]["nama"]);
		$("#d-tag").html(data[d]["tag"]);
		$("#d-name").html(data[d]["nama"]);
		$("#d-address").html(data[d]["detail"]);
		
		var item = '';
		$.each(data[d]["detail_info"], function(index, array){
			var ic 	= '';
			var key = array["tipe"];
			var c 	= array["value"];
			var a	= false;
			
			if(key.toLowerCase().indexOf("pegawai") !== -1){
				ic = 'ic_employee.png';
				a = true;
			}
			else if(key.toLowerCase().indexOf("klasifikasi") !== -1){
				ic = 'ic_clasification.png';
				a = true;
			}
			else if(key.toLowerCase().indexOf("jenis") !== -1 || key.toLowerCase().indexOf("target") !== -1 || 
					key.toLowerCase().indexOf("tipe") !== -1){
				ic = 'ic_type.png';
				c = array["tipe"] + " " + c;
				a = true;
			}
			if(a){
				item += '<div class="form-group"><img src="assets/images/'+ic+'" /><label>'+c+'</label></div>';
			}
		});
		$("#detail_info .sidebar-detail-content").html(item);
		
		$(".sidebar .sidebar-detail").addClass("open");
				
		var latLngs = [ markers[data[d]["id"]].getLatLng() ];
		//alert(latLngs);
		var markerBounds = L.latLngBounds(latLngs);
		setTimeout(function(e){
			mymap.flyToBounds(markerBounds, {'duration':0.5});
			mymap.flyToBounds(markerBounds, {'duration':0.5});
		}, 500);
		
		var images = data[d]["images"];
		var items = '';
		for(var i = 1; i <= images; i++){
			items += '<li><img src="assets/images/basic/'+data[d]["id"]+'/'+i+'.jpg" /></li>';
		}
		if(items == '')items = '<li><div class="default"><img src="assets/images/no-image.jpg" /></div>';
		
		$('#slider .flexslider').remove();
		$("#slider").html('<div class="flexslider""><ul class="slides">' + items + '</ul></div>');
		
		//$('#slider').addClass("flexslider");
		if(items != ''){
			setTimeout(function(){
				$('#slider .flexslider').flexslider({
					animation: "slide",
					controlNav: false,
					animationLoop: false,
					slideshow: false,
					sync: "#carousel"
				  });
			}, 500);
		}
		
		$('#slider .flexslider ul li img').click(function(){
			var src = $(this).attr('src');
			$("#preview > div").css("background-image", "url("+src+")");
			
			$("#preview").removeClass("close");
		});
	}
	
	function showAbout(){
		if($(".dialog").is(":visible")){
			$(".dialog").hide();
		}
		else{
			$(".dialog").show();
		}
	}
	
</script>
		
		<!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDzwDOOMZxnprAA5JTJFktTdlFMmh_qRW0&callback=initMap" async defer></script>
		<script>
		var map;
		function initMap() {
			map = new google.maps.Map(document.getElementById('map'), {
				center: {lat: -7.340632, lng: 112.735607},
				zoom: 18,
				zoomControl: true,
				mapTypeControl: true,
				mapTypeControlOptions: {
   style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
   position: google.maps.ControlPosition.CENTER
}
			});
		}
		</script>-->
		
		<style>
		.sidebar{
			
		}
		#preview{
			position:fixed;
			width:100%;
			height:100%;
			padding:5%;
			padding-left:400px;
			top:0;
			background:rgba(0,0,0,0.8);
			z-index:1999;
			
		}
		#preview.close{
			display:none;
		}
		#preview > a{
			position:absolute;
			color:#fff;
			background:#333;
			width:30px;
			height:30px;
			margin-left:-10px;
			margin-top:-10px;
			text-align:center;
			padding-top:3px;
			-webkit-border-radius: 50px;
-moz-border-radius: 50px;
border-radius: 50px;
		}
		#preview > a:hover{
			background:#000;
		}
		#preview > div{
			height:90%;
			background-size:cover;
			background-position:center;
		}
		</style>
	</body>
	
</html>