<div style="display:table; width:100%;">
<div class="row">
<?php 
	$filter = "";
	echo "<div class=\"cell table_cell\" style=\"border:0px\">".form_open($form_submit);
	
	echo "Filter by ".form_dropdown("status", $list_status , $status)." "; 
	echo form_hidden("name", $search["name"]);
	echo form_hidden("industry_id", $search["industry_id"]);
	echo form_hidden("date_add", $search["date_add"]);
	echo form_submit("submit", "Submit");
	echo form_close()."</div>";
	echo "<div class=\"cell table_cell\" style=\"border:0px; text-align:right\"><a href=\"$search_link\" id=\"search_form\" rel=\"simpleDialog\">Search</a></div>";

	foreach($search as $key => $val)
	{ $filter .= urlencode($val)."/"; }
	
	echo form_open($form_submit);
?>
</div>
</div>
<div class="table">
<div class="row">
	<div class="cell table_head" style="width:50px"><a href="<?php echo base_url()."admin/company/manage/$page/$next_order"."_comp_id/$filter"; ?>">ID</a></div>	
	<div class="cell table_head">Creator</div>
	<div class="cell table_head"><a href="<?php echo base_url()."admin/company/manage/$page/$next_order"."_name/$filter"; ?>">Company Name</a></div>
	<div class="cell table_head"><a href="<?php echo base_url()."admin/company/manage/$page/$next_order"."_industry_title/$filter"; ?>">Industry</a></div>
	<div class="cell table_head"><a href="<?php echo base_url()."admin/company/manage/$page/$next_order"."_date_add/$filter"; ?>">Joining Date</a></div>
	<div class="cell table_head"><a href="<?php echo base_url()."admin/company/manage/$page/$next_order"."_status/$filter"; ?>">Status</a></div>
</div>
<?php
	$a = "";
	foreach ($results as $company)
	{
		echo "<div class=\"row\">\n";
		//echo "<div class=\"cell table_cell\">".form_checkbox("del[]",$company["comp_id"], ($company["status"]=="2") ? TRUE : "")." ".$company["comp_id"]."</div>\n";
		echo "<div class=\"cell table_cell\">".$company["comp_id"]."</div>\n";
		echo "<div class=\"cell table_cell\">".$company["username"]."</div>\n";
		echo "<div class=\"cell table_cell\"><a href=\"".base_url()."admin/company/edit/".$company["comp_id"]."\">". $company["company_name"]."</a></div>\n";
		echo "<div class=\"cell table_cell\">".$company["industry_title"]."</div>\n";
		echo "<div class=\"cell table_cell\">".$company["date_add"]."</div>\n";
		if ($company["status"] == "1")
			echo "<div class=\"cell table_cell\">Active</div>\n";
		else
			echo "<div class=\"cell table_cell\">Inactive</div>\n";
		echo "</div>\n";
		$a .= $company["comp_id"].",";
	}
	
	$a = substr($a, 0, strlen($a)-1);
	
	echo "</div>"; // CLOSE TABLE
	echo form_hidden("all_id", $a);
	
	$paging = "";
	
	if ($npage > 1)
		for ($a = 1; $a <=$npage; $a++) 
			if ($a == $page)
				$paging .= "<b>$a</b> - ";
			else
				$paging .= "<a href=\"".base_url()."admin/company/manage/$a/$order/$filter\">$a</a> - ";
		
	$paging = substr($paging,0,strlen($paging)-3);
	echo $paging."<br><br>";
	echo form_close();
	echo "<br><br><a href=\"".base_url()."admin/company/save_csv/$order/$filter\">Save as CSV</a>";
?>
</div>