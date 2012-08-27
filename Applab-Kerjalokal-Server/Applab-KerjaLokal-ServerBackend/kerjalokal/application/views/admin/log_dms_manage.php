<style>
.cell {
	font-size:8pt;
}
</style>
<div class="table">
<div class="row">
	<div class="cell table_head" style="width:50px">Log ID</div>
	<div class="cell table_head">Date Time</div>
	<div class="cell table_head">MDN</div>
	<!-- <div class="cell table_head">Lang</div> -->
	<div class="cell table_head">Title</div>
	<!-- <div class="cell table_head">Cmd</div>
	<div class="cell table_head">Status</div> -->
	<div class="cell table_head">Source</div>
	<div class="cell table_head">Request</div>
	<!-- <div class="cell table_head"></div> -->
	<div class="cell table_head"></div>
	
</div>
<?php
	foreach ($logs["results"] as $log)
	{
		echo "<div class=\"row\">\n";
		echo "<div class=\"cell table_cell\">".$log["log_id"]."</div>\n";
		echo "<div class=\"cell table_cell\">".$log["time"]."</div>\n";
		echo "<div class=\"cell table_cell\">".$log["mdn"]."</div>\n";
		//echo "<div class=\"cell table_cell\">".$log["lang"]."</div>\n";
		echo "<div class=\"cell table_cell\">".$log["page_title"]."</div>\n";
		//echo "<div class=\"cell table_cell\">".$log["cmd"]."</div>\n";
		//echo "<div class=\"cell table_cell\">".$log["status"]."</div>\n";
		echo "<div class=\"cell table_cell\">".$log["pageidsrc"]."</div>\n";
		echo "<div class=\"cell table_cell\">".$log["pageidreq"]."</div>\n";
		//echo "<div class=\"cell table_cell\"><a href=\"".base_url()."func/log_dmsdetail.php?webdms=dms&id=".$log["log_id"]."\" class=\"log_detail\" rel=\"simpleDialog\">detail</a></div>\n";
		//echo "<div style=\"width:30px\" class=\"cell table_cell\"><a href=\"".base_url()."admin/log/detail/dmsweb/".$log["tx_id"]."\" class=\"log_detail\" rel=\"simpleDialog\">WEB</a></div>\n";
		echo "<div style=\"width:30px\" class=\"cell table_cell\"><a href=\"".base_url()."admin/log/detail/dms/".$log["tx_id"]."\" class=\"log_detail\" rel=\"simpleDialog\">DMS</a></div>\n";
		//echo "<div class=\"cell table_cell\" style=\"width:16px\"><a href=\"".base_url()."admin/log/detail/dms/".$log["tx_id"]."\" rel=\"simpleDialog\"><img class=\"log_detail\" src=\"".base_url()."images/detail.jpg\" border=0></a></div>\n";
		echo "</div>\n";
	}
	echo "</div>";
	
	$paging = "";
	
	if ($logs["npage"] > 1)
		for ($a = 1; $a <=$logs["npage"]; $a++) 
			if ($a == $logs["page"])
				$paging .= "<b>$a</b> - ";
			else
				$paging .= "<a href=\"".base_url()."admin/log/dms/$a\">$a</a> - ";
		
	$paging = substr($paging,0,strlen($paging)-3);
	echo $paging."<br><br>";
?>
</div>