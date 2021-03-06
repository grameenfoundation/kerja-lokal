<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('Madmin');
		
	}
   
	function index()
	{
		$this->template->write("title", "Master");
		
		$this->template->render();
		
	}
	
	function area()
	{
		$useraccess = array("superadmin", "admin");
		if ($this->Madmin->check_access($_SESSION["userlevel"], $useraccess) == "1")
		{			
			$tx_id = $this->Madmin->get_uuid(current_url());
			if (sizeof($_POST) > 0)
			{
				$opprefix = $_POST["opprefix"];
				$data = CORE_URL."update_prefix.php?tx_id=$tx_id&opprefix=$opprefix";
				$data = $this->Madmin->get_data($data);
				$this->template->write("msg", "<div class=msg>Service area has been updated.</div>");
			}

			$this->template->write("title", "Service Area");
			$data = CORE_URL."get_prefix.php?tx_id=$tx_id";
			$data = $this->Madmin->get_data($data);
			$data["form_action"] = base_url()."admin/master/area";
			//echo "<pre>"; print_r($data); echo "</pre>";
			
			$this->template->write_view("content", "admin/prefix_edit", $data, TRUE);
		}
		else
			$this->template->write("msg", $this->Madmin->check_access($_SESSION["userlevel"], $useraccess)); 
		$this->template->render();
		
	}
	
	function tariff()
	{
		$useraccess = array("superadmin", "admin");
		if ($this->Madmin->check_access($_SESSION["userlevel"], $useraccess) == "1")
		{			
			$tx_id = $this->Madmin->get_uuid(current_url());
			if (sizeof($_POST) > 0)
			{
				$tarif = $_POST["tarif"];
				$data = CORE_URL."update_tariff.php?tx_id=$tx_id&tarif=$tarif";
				$data = $this->Madmin->get_data($data);
				$this->template->write("msg", "<div class=msg>Tariff has been updated.</div>");
			}

			$this->template->write("title", "Tariff");
			$data = CORE_URL."get_tariff.php?tx_id=$tx_id";
			$data = $this->Madmin->get_data($data);
			$data["form_action"] = base_url()."admin/master/tariff";
			//echo "<pre>"; print_r($data); echo "</pre>";
			
			$this->template->write_view("content", "admin/tariff_edit", $data, TRUE);
		}
		else
			$this->template->write("msg", $this->Madmin->check_access($_SESSION["userlevel"], $useraccess)); 
		$this->template->render();
		
	}
	
	function industry($stat="0", $page=1, $order="a_industry_title")
	{
		switch ($stat)
		{
			case "manage" :
			{
				
				$tx_id = $this->Madmin->get_uuid(current_url());
				$ndata = 10;
				$data = CORE_URL."get_industries.php?tx_id=$tx_id&ndata=$ndata&country_id=".$_SESSION["curr_country"];
				$data = $this->Madmin->get_data($data);
				//echo "<pre>"; print_r($data); echo "</pre>";
				
				$useraccess = array("superadmin", "admin");
				if ($this->Madmin->check_access($_SESSION["userlevel"], $useraccess) == "1")
				{
					
					if (sizeof($_POST) > 0)
					{
						$del = !is_null($this->input->post("del")) ? ($this->input->post("del")) : "";
						$all_id = !is_null($this->input->post("all_id")) ? ($this->input->post("all_id")) : "";
						$all_id = explode(",",$all_id);
						//echo "<pre>"; print_r($all_id); echo "</pre>";
						
						foreach ($all_id as $a)
						{
							if(!empty($del)){
								if (in_array($a, $del))
									$status = 2;
								else
									$status = 1;
								$json = CORE_URL."update_industries.php?industry_id=$a&tx_id=$tx_id&status=$status";
								
								$json = $this->Madmin->get_data($json);
							}
							else
							{
								$json = CORE_URL."update_industries.php?industry_id=$a&tx_id=$tx_id&status=1";
								//die($json);
								$json = $this->Madmin->get_data($json);

							}
						}
						$this->template->write("msg", "<div class=msg>".sizeof($all_id)." Industry has been updated.</div>");
					}
					
					$ascdesc = substr($order,0,1) == "a" ? "ASC" : "DESC";
					$orderby = substr($order,2);
					
					
					$json = CORE_URL."get_industries.php?tx_id=$tx_id&ndata=$ndata&page=$page&order=$orderby&ascdesc=$ascdesc&id=".$_SESSION["userid"];
					//die($json);
					$data = $this->Madmin->get_data($json);
					
					$data["page"] = $page;
					$data["order"] = $order;
					$data["next_order"] = $ascdesc == "ASC" ? "d" : "a";

					$data["form_submit"] = base_url()."admin/master/industry/manage/$page/$order";
					//echo "<pre>"; print_r($data); echo "</pre>";
					
					$this->template->write("title", "Manage Industry");
					$this->template->write_view("content", "admin/industry_manage", $data, TRUE);
				}
				else
					$this->template->write("msg", $this->Madmin->check_access($_SESSION["userlevel"], $useraccess)); 
				
				$this->template->write_view("content", "admin/industry_manage", $data, TRUE);
				break;
			}
			
			case "add" :
			{
				$useraccess = array("superadmin", "admin");
				if ($this->Madmin->check_access($_SESSION["userlevel"], $useraccess) == "1")
				{			
					$arr = array(
						"industry_id" => "",
						"industry_title" => "",
						"description" => "",
						"date_add" => "",
						"date_update" => "",
						"status" => "",
						"country_id" => ""
					);
					$arr["msg"] = "";
					$arr['form_action'] = base_url()."admin/master/industry/insert";
					$this->template->write("title", "Add Industry");
					$this->template->write_view("content", "admin/industry_edit", $arr, TRUE);
				}
				else
					$this->template->write("msg", $this->Madmin->check_access($_SESSION["userlevel"], $useraccess)); 
				break;
			
			}
			
			case "insert" :
			{
			
				$tx_id = $this->Madmin->get_uuid(current_url());
				$edu_title = $this->input->post('edu_title');
				$arr = array(
						"industry_id" => "",
						"industry_title" => "",
						"description" => "",
						"status" => "",
						"date_add" => "",
						"date_update" => "",
						"country_id" => ""
					);
				$arr['form_action'] = base_url()."admin/master/industry/insert";
				$this->form_validation->set_rules('title', 'Industry title', 'required');
				if ($this->form_validation->run() == FALSE)
				{
					$arr = array(
						"industry_id" => "",
						"industry_title" => "",
						"description" => "",
						"date_add" => "",
						"date_update" => "",
						"status" => "",
						"country_id" => ""
					);
					$arr['form_action'] = base_url()."admin/master/industry/insert";
					$this->template->write("header", "<script src=\"".base_url()."js/jquery.js\"></script>");

					$this->template->write("title", "Add Industry");
					$this->template->write_view("content", "admin/industry_edit", $arr, TRUE);
				}
				else
				{
					$tx_id = $this->Madmin->get_uuid(current_url());
					$title = urlencode($this->input->post("title"));
					$desc = urlencode($this->input->post("desc"));
					$status = urlencode($this->input->post("status"));
				
					$url = CORE_URL."add_industry.php?tx_id=$tx_id&title=$title&desc=$desc&status=$status&country_id=".$_SESSION["curr_country"];
					$status = file_get_contents($url);
					$status = json_decode($status, true);
					$arr['status'] = $status['status'];
					$arr['msg'] = $status['msg'];
					$this->template->write("header", "
					<script src=\"".base_url()."js/jquery.js\"></script>");

					if ($arr["status"] == "0")
						$this->template->write("msg", "<div class=msg>".$arr["msg"]."</div>");
					else
						$this->template->write("msg", "<div class=msg>1 Industry has been registered.</div>");
						
					$this->template->write("title", "Add Industry");
					$this->template->write_view("content", "admin/industry_edit", $arr, TRUE);
				}
				break;
			
			}
			
			case "edit" :
			{
				$id = $this->uri->segment(5);
				$tx_id = $this->Madmin->get_uuid(current_url());
				$useraccess = array("superadmin", "admin", "company");
				if ($this->Madmin->check_access($_SESSION["userlevel"], $useraccess) == "1")
				{
					$is_ok = 0;
					if (($_SESSION["userlevel"] == "superadmin") || ($_SESSION["userlevel"] == "admin")) 
						$is_ok = 1;
					else
						if ($_SESSION["userlevel"] == "company") { $is_ok = 1; $id_industry = $_SESSION["comp_id"]; }
					
					$arr = file_get_contents(CORE_URL."get_industries_by_industry_id.php?tx_id=$tx_id&id=$id");
					$arr = json_decode($arr, true);
					//die($id);
				
					$arr['form_action'] = base_url()."admin/master/industry/update";
					if ($is_ok == 1)
					{
						//echo "<pre>"; print_r($arr); echo "</pre>";
						$this->template->write("header", "<script src=\"".base_url()."js/jquery.js\"></script>");
						$this->template->write("title", "Edit Industry");
						$this->template->write_view("content", "admin/industry_edit", $arr, TRUE);
					}
					else
						$this->template->write("msg", "You are not authorize to modify the industry.");
				}
				else
					$this->template->write("msg", $this->Madmin->check_access($_SESSION["userlevel"], $useraccess));
				
				break;
			}
			
			case "update" :
			{
				$tx_id = $this->Madmin->get_uuid(current_url());
				$industry_id = $this->input->post('id');
				$industry_title = $this->input->post('title');
				$description = $this->input->post('desc');
				$status = $this->input->post('status');
				$arr = array(
							"industry_id" => $industry_id,
							"industry_title" => $industry_title,
							"description" => $description,
						);
					
				$arr['form_action'] = base_url()."admin/master/industry/update";
				$this->form_validation->set_rules('title', 'Industry name', 'required');
				if ($this->form_validation->run() == FALSE)
				{	
					$this->template->write("header", "<script src=\"".base_url()."js/jquery.js\"></script>");
					$this->template->write("title", "Edit Education");
					$this->template->write_view("content", "admin/industry_edit", $arr, TRUE);
				}
				else
				{
					$url = CORE_URL."update_industries.php?tx_id=$tx_id&industry_id=".urlencode($industry_id)."&industry_title=".urlencode($industry_title)."&description=".urlencode($description)."&status=".urlencode($status);
					$status = file_get_contents($url);
					$status = json_decode($status, true);
					$arr['status'] = $status['status'];
					$arr['msg'] = $status['msg'];
					$this->template->write("header", "<script src=\"".base_url()."js/jquery.js\"></script>");
					if ($arr["status"] == "0")
						$this->template->write("msg", "<div class=msg>".$arr["msg"]."</div>");
					else
						$this->template->write("msg", "<div class=msg>1 Industry has been updated.</div>");
						
					$this->template->write("title", "Edit Industry");
					$this->template->write_view("content", "admin/industry_edit", $arr, TRUE);
				}
				break;
			}
		}
		$this->template->render();
	}

	function location($stat="0", $page=1, $id=0, $order="a_name")
	{
		$tx_id = $this->Madmin->get_uuid(current_url());
		$data["countries"] = CORE_URL."get_countries.php?tx_id=$tx_id";
		$data["countries"] = $this->Madmin->get_data($data["countries"]);
		$data["countries"] = $data["countries"]["results"];
		$header = "
			<script src=\"".base_url()."js/jquery.js\"></script>
			<script src=\"".base_url()."js/jquery.chainedSelects.js\"></script>
			<script type=\"text/javascript\" src=\"http://maps.google.com/maps/api/js?sensor=true\"></script>
			<script type=\"text/javascript\" src=\"http://code.google.com/apis/gears/gears_init.js\"></script>
			<script src=\"".base_url()."js/geolocation2.js\"></script>
			<script>
				$(function()
				{
					$('#city2').chainSelect('#kotamadya2','".base_url()."func/get_location_by_parent_id.php');
					
					
				});
			</script>
			"; 
		
		switch ($stat)
		{
			case "add" :
			{	
				
				
				//echo "<pre>"; print_r($data); echo "</pre>";
				$this->template->write("header", $header);
				$header .= "<script type=\"text/javascript\" src=\"http://maps.google.com/maps/api/js?sensor=true\"></script>
					<script src=\"".base_url()."js/geolocation2.js\"></script>
					<script src=\"".base_url()."js/jquery.js\"></script>
					<script src=\"".base_url()."js/jquery.chainedSelects.js\"></script>
					<script>
						$(document).ready(function(){
							initialize();
						
							$('#country1').chainSelect('#city1','".base_url()."func/get_location_by_parent_id.php');
							$('#country2').chainSelect('#city2','".base_url()."func/get_location_by_parent_id.php');
							$('#city2').chainSelect('#kotamadya2','".base_url()."func/get_location_by_parent_id.php');
							$('#country3').chainSelect('#city3','".base_url()."func/get_location_by_parent_id.php');
							$('#city3').chainSelect('#kotamadya3','".base_url()."func/get_location_by_parent_id.php');
							$('#kotamadya3').chainSelect('#kecamatan3','".base_url()."func/get_location_by_parent_id.php');
							
						});
							function get_location_by_loc_id(id)
							{
								
								$.ajax({
									type: \"GET\",
									url: \"".CORE_URL."get_location_by_loc_id.php\",
									data: \"id=\"+id,
									success: function(result){
										if (id != 0) 
										{
											a = jQuery.parseJSON(result);
											
											$(\"#lat2\").val(a.loc_lat);
											$(\"#lng2\").val(a.loc_lng);
											$(\"#zipcode\").val(a.zipcode);
											setLatLng(a.loc_lat, a.loc_lng);											
										}
									}
								});
								alert('test');
							};
					</script>
				";
				$this->template->write("header", $header);
				$this->template->write_view("content", "admin/location_add", $data, TRUE);
				break;
			}
			
			case "manage" :
			{
				$tx_id = $this->Madmin->get_uuid(current_url());
				if (sizeof($_POST) > 0)
				{
					$del = !is_null($this->input->post("del")) ? ($this->input->post("del")) : "";
					$all_id = !is_null($this->input->post("all_id")) ? ($this->input->post("all_id")) : "";
					$all_id = explode(",",$all_id);
					//echo "<pre>"; print_r($all_id); echo "</pre>";
					
					foreach ($all_id as $a)
					{
						if(!empty($del)){
							if (in_array($a, $del))
								$status = 2;
							else
								$status = 1;
							$json = CORE_URL."update_location.php?tx_id=$tx_id&loc_id=$a&status=$status";
							$json = $this->Madmin->get_data($json);
						}
						else
						{
							$json = CORE_URL."update_location.php?tx_id=$tx_id&loc_id=$a&status=1";
							//die($json);
							$json = $this->Madmin->get_data($json);

						}
					}
					$this->template->write("msg", "<div class=msg>".sizeof($all_id)." location has been updated.</div>");
				}
					
				
				$ascdesc = substr($order,0,1) == "a" ? "ASC" : "DESC";
				$orderby = substr($order,2);
				$ndata = 20;
				$json = CORE_URL."get_location_all.php?tx_id=$tx_id&ndata=$ndata&page=$page&order=$orderby&ascdesc=$ascdesc&id=".$_SESSION["userid"];
				$data = $this->Madmin->get_data($json);
				
				$data["page"] = $page;
				$data["order"] = $order;
				$data["next_order"] = $ascdesc == "ASC" ? "d" : "a";
				//echo "<pre>"; print_r($data); echo "</pre>";
				$data["form_submit"] = base_url()."admin/master/location/manage/$page/$order";
				$this->template->write_view("content", "admin/location_manage", $data, TRUE);
				break;
			}
			
			case "edit" :
			{
				$tx_id = $this->Madmin->get_uuid(current_url());
				if (sizeof($_POST) > 0)
				{
					$json = "";
					$loc_id = !is_null($this->input->post("loc_id")) ? urlencode($this->input->post("loc_id")) : "";
					$loc_type = !is_null($this->input->post("loc_type")) ? urlencode($this->input->post("loc_type")) : "";
					$name = !is_null($_POST["name"]) ? urlencode($_POST["name"]) : "";
					$loc_lat = isset($_POST["lat"]) ? urlencode($_POST["lat"]) : "";
					$loc_lng = isset($_POST["lng"]) ? urlencode($_POST["lng"]) : "";
					$zipcode = isset($_POST["zipcode"]) ? urlencode($_POST["zipcode"]) : "";
					$status = isset($_POST["status"]) ? urlencode($_POST["status"]) : "1";
					
					if (($loc_type == "KOTA") || ($loc_type == "KOTAMADYA") || ($loc_type == "KECAMATAN"))
					{
						$loc_type = $loc_type == "KOTA" ? "PROVINCE" : $loc_type;
						$this->form_validation->set_rules("name", "$loc_type name", 'required');
						if ($this->form_validation->run() == TRUE)
							$json = CORE_URL."update_location.php?loc_id=$loc_id&status=$status&name=$name";
					}
					else
					{
						$this->form_validation->set_rules("name", "$loc_type name", 'required');
						$this->form_validation->set_rules('zipcode', 'Zip Code', 'required');
						$this->form_validation->set_rules('lat', 'Latitude', 'required');
						$this->form_validation->set_rules('lng', 'Longitude', 'required');
						if ($this->form_validation->run() == TRUE)
							$json = CORE_URL."update_location.php?tx_id=$tx_id&loc_id=$loc_id&status=$status&name=$name&loc_lat=$loc_lat&loc_lng=$loc_lng&zipcode=$zipcode&status=$status";
					}
					if ($json != "")
					{
						$json = $this->Madmin->get_data($json);
						if ($json["status"] == "1")
						{
							$loc_type = $loc_type == "KOTA" ? "PROVINCE" : $loc_type;
							$this->template->write("msg", "<div class=msg>1 $loc_type has been updated.</div>");
						}
					}
					//echo "<pre>"; print_r($arr); echo "</pre>";
				}

				$json = CORE_URL."get_location_by_loc_id.php?id=$id";
				$data = $this->Madmin->get_data($json);
				
				$data["countries"] = CORE_URL."get_countries.php";
				$data["countries"] = $this->Madmin->get_data($data["countries"]);
				
				if ($data["loc_type"] == "KELURAHAN")
				{
					$header .= "<script type=\"text/javascript\" src=\"http://maps.google.com/maps/api/js?sensor=true\"></script>
						<script src=\"".base_url()."js/gmap_nogeolocation.js\"></script>
						<script>
							$(document).ready(function(){
								initialize(".$data["loc_lat"].",".$data["loc_lng"].");
							
								$('#country').chainSelect('#city','".base_url()."func/get_location_by_parent_id.php');
								$('#city').chainSelect('#kotamadya','".base_url()."func/get_location_by_parent_id.php');
								$('#kotamadya').chainSelect('#kecamatan','".base_url()."func/get_location_by_parent_id.php');
								$('#kecamatan').chainSelect('#kelurahan','".base_url()."func/get_location_by_parent_id.php');
								
							});
								function get_location_by_loc_id(id)
								{
									
									$.ajax({
										type: \"GET\",
										url: \"".CORE_URL."get_location_by_loc_id.php\",
										data: \"id=\"+id,
										success: function(result){
											if (id != 0) 
											{
												a = jQuery.parseJSON(result);
												
												$(\"#lat2\").val(a.loc_lat);
												$(\"#lng2\").val(a.loc_lng);
												$(\"#zipcode\").val(a.zipcode);
												setLatLng(a.loc_lat, a.loc_lng);
											}
										}
									});
									
								};
						</script>
					";
			
					$this->template->write("header", $header);
					$data["kelurahan"] = $data["loc_id"];
					$data["kecamatan"] = $data["parent_id"];
					$json = CORE_URL."get_location_by_loc_id.php?id=".$data["parent_id"];
					$json = $this->Madmin->get_data($json);
					$data["kotamadya"] = $json["parent_id"];
					$json = CORE_URL."get_location_by_loc_id.php?id=".$data["kotamadya"];
					$json = $this->Madmin->get_data($json);
					$data["province"] = $json["parent_id"];

					$json = CORE_URL."get_location_by_parent_id.php?id=0&country_id=".$_SESSION["curr_country"];
					$data["provinces"] = $this->Madmin->get_data($json);
					$json = CORE_URL."get_location_by_parent_id.php?id=".$data["province"];
					$data["kotamadyas"] = $this->Madmin->get_data($json);
					$json = CORE_URL."get_location_by_parent_id.php?id=".$data["kotamadya"];
					$data["kecamatans"] = $this->Madmin->get_data($json);
					$json = CORE_URL."get_location_by_parent_id.php?id=".$data["kecamatan"];
					$data["kelurahans"] = $this->Madmin->get_data($json);
				}
				else if ($data["loc_type"] == "KECAMATAN")
				{
					$header .= "
						<script>
							$(document).ready(function(){
								$('#country').chainSelect('#province','".base_url()."func/get_location_by_parent_id.php');
								$('#province').chainSelect('#kotamadya','".base_url()."func/get_location_by_parent_id.php');
								$('#kotamadya').chainSelect('#kecamatan','".base_url()."func/get_location_by_parent_id.php');
							});
								function get_location_by_loc_id(id)
								{
									
									$.ajax({
										type: \"GET\",
										url: \"".CORE_URL."get_location_by_loc_id.php\",
										data: \"id=\"+id,
										success: function(result){
											if (id != 0) 
											{
												a = jQuery.parseJSON(result);
												
												$(\"#lat2\").val(a.loc_lat);
												$(\"#lng2\").val(a.loc_lng);
												$(\"#zipcode\").val(a.zipcode);
												setLatLng(a.loc_lat, a.loc_lng);
											}
										}
									});
									
								};
						</script>";
					$json = CORE_URL."get_location_by_loc_id.php?id=".$data["parent_id"];
					$json = $this->Madmin->get_data($json);
					$data["province"] = $json["parent_id"];
					$data["kotamadya"] = $data["parent_id"];
					$data["kecamatan"] = $data["loc_id"];
					
					$json = CORE_URL."get_location_by_parent_id.php?id=0&country_id=".$_SESSION["curr_country"];
					$data["provinces"] = $this->Madmin->get_data($json);
					$kotamadyas = CORE_URL."get_location_by_parent_id.php?id=".$data["province"];
					$data["kotamadyas"] = $this->Madmin->get_data($kotamadyas);
					$kecamatans = CORE_URL."get_location_by_parent_id.php?id=".$data["kotamadya"];
					$data["kecamatans"] = $this->Madmin->get_data($kecamatans);
				}
				else if ($data["loc_type"] == "KOTAMADYA")
				{
					$header .= "
						<script>
							$(document).ready(function(){
								$('#country').chainSelect('#province','".base_url()."func/get_location_by_parent_id.php');
								$('#province').chainSelect('#kotamadya','".base_url()."func/get_location_by_parent_id.php');
							});
								function get_location_by_loc_id(id)
								{
									
									$.ajax({
										type: \"GET\",
										url: \"".CORE_URL."get_location_by_loc_id.php\",
										data: \"id=\"+id,
										success: function(result){
											if (id != 0) 
											{
												a = jQuery.parseJSON(result);
												
												$(\"#lat2\").val(a.loc_lat);
												$(\"#lng2\").val(a.loc_lng);
												$(\"#zipcode\").val(a.zipcode);
												setLatLng(a.loc_lat, a.loc_lng);
											}
										}
									});
									
								};
						</script>";
			
					$data["province"] = $data["parent_id"];
					$data["kotamadya"] = $data["loc_id"];
					
					$json = CORE_URL."get_location_by_parent_id.php?id=0&country_id=".$_SESSION["curr_country"];
					$data["provinces"] = $this->Madmin->get_data($json);
					$kotamadyas = CORE_URL."get_location_by_parent_id.php?id=".$data["province"];
					$data["kotamadyas"] = $this->Madmin->get_data($kotamadyas);
				}
				else if ($data["loc_type"] == "KOTA")
				{ $data["province"] = $data["loc_id"]; }
				
				//echo "<pre>"; print_r($data); echo "</pre>";

				$this->template->write("header", $header);
				$this->template->write_view("content", "admin/location_edit", $data, TRUE);
				break;
			}
			
			
			case "update" :
			{
				$tx_id = $this->Madmin->get_uuid(current_url());
				$loc_id = !is_null($this->input->post("loc_id")) ? urlencode($this->input->post("loc_id")) : "";
				$loc_type = !is_null($this->input->post("loc_type")) ? urlencode($this->input->post("loc_type")) : "";
				$name = !is_null($this->input->post("name")) ? urlencode($this->input->post("name")) : "";
				$loc_lat = !is_null($this->input->post("lat")) ? urlencode($this->input->post("lat")) : "";
				$loc_lng = !is_null($this->input->post("lng")) ? urlencode($this->input->post("lng")) : "";
				$zipcode = !is_null($this->input->post("zipcode")) ? ($this->input->post("zipcode")) : "";
				$status = !is_null($this->input->post("status")) ? ($this->input->post("status")) : "1";
				
				$this->form_validation->set_rules("name", "$loc_type name", 'required');
				if (($loc_type == "KOTA") || ($loc_type == "KOTAMADYA") || ($loc_type == "KECAMATAN"))
				{
					if ($this->form_validation->run() == TRUE)
						$json = CORE_URL."update_location.php?loc_id=$loc_id&status=$status&name=$name";
				}
				else
				{
					$this->form_validation->set_rules('zipcode', 'Zip Code', 'required');
					$this->form_validation->set_rules('lat', 'Latitude', 'required');
					$this->form_validation->set_rules('lng', 'Longitude', 'required');
					if ($this->form_validation->run() == TRUE)
						$json = CORE_URL."update_location.php?loc_id=$loc_id&status=$status&name=$name&loc_lat=$$loc_lat&loc_lng=$loc_lng&zipcode=$zipcode&status=$status";
				}
				$json = $this->Madmin->get_data($json);
				
				if ($json["status"] == "1")
					$this->template->write("msg", "<div class=msg>1 $type has been updated.</div>");
				else
					$this->template->write("msg", "<div class=msg>".$json["msg"]."</div>");
				
				$ndata = 20;
				$json = CORE_URL."get_location_all.php?ndata=$ndata&page=$page";
				$data = $this->Madmin->get_data($json);
				$data["form_submit"] = base_url()."admin/master/location/manage/$page";
				//echo "<pre>"; print_r($arr); echo "</pre>";
				$this->template->write_view("content", "admin/location_edit", $data, TRUE);
				break;
			}
			
			
			case "set_country" :
			{
				$id = urlencode(strtoupper($this->input->post("id")));
				
				if ($id != "")
				{
					$country = CORE_URL."set_curr_country.php?id=$id";
					$country = $this->Madmin->get_data($country);
					if ($country["status"] == "0")
						$this->template->write("msg", "<div class=msg>".$country["msg"]."</div>");
					else
					{
						$this->template->write("msg", "<div class=msg>Default COUNTRY has been set.</div>");
						$_SESSION["curr_country"] = $id;
					}
				}
				else
				{ $this->template->write("msg", "<div class=msg>Please choose a country.</div>"); }
				$this->template->write_view("content", "admin/location_add", $data, TRUE);
				break;
			}
			
			case "add_country" :
			{
				$id = urlencode(strtoupper($this->input->post("id")));
				$name = urlencode($this->input->post("name"));
				
				if (($id != "") && ($name != ""))
				{
					$country = file_get_contents(CORE_URL."add_country.php?id=$id&name=$name");
					$country = json_decode($country, true);
					if ($country["status"] == "0")
						$this->template->write("msg", "<div class=msg>".$country["msg"]."</div>");
					else
						$this->template->write("msg", "<div class=msg>1 COUNTRY has been registered.</div>");
				
				}
				else
				{
					$this->template->write("msg", "<div class=msg>Please enter a valid Country ID and Country Name.</div>");
				}
				//echo "<pre>"; print_r($arr); echo "</pre>";
				$this->template->write_view("content", "admin/location_add", $data, TRUE);
				break;
			}
			
			case "add_location" :
			{
				$data["country_id"] = !is_null($this->input->post("country_id")) ? urlencode($this->input->post("country_id")) : "";
				$data["parent_id"] = !is_null($this->input->post("parent_id")) ? urlencode($this->input->post("parent_id")) : "";
				$data["type"] = !is_null($this->input->post("type")) ? urlencode($this->input->post("type")) : "";
				$data["name"] = !is_null($this->input->post("name")) ? urlencode($this->input->post("name")) : "";
				$data["lat"] = !is_null($this->input->post("lat")) ? urlencode($this->input->post("lat")) : "";
				$data["lng"] = !is_null($this->input->post("lng")) ? urlencode($this->input->post("lng")) : "";
				$data["zipcode"] = !is_null($this->input->post("zipcode")) ? ($this->input->post("zipcode")) : "";
				//echo "<pre>"; print_r($_POST); echo "</pre>";
				//if (($data["country_id"] != "") && ($data["name"] != ""))
				{
					if ($data["type"] == "KELURAHAN")
					{
						$this->form_validation->set_rules('lat', 'Google map coordinates', 'required');
						$this->form_validation->set_rules('zipcode', 'Zip code', 'required');
					}
					//if ($this->form_validation->run() == TRUE)
					{
						$loc = CORE_URL."add_location.php?tx_id=$tx_id&country_id=".$data["country_id"]."&parent_id=".$data["parent_id"]."&type=".$data["type"]."&name=".$data["name"]."&lat=".$data["lat"]."&lng=".$data["lng"]."&zipcode=".$data["zipcode"]."'";
						//echo $loc;
						$loc = $this->Madmin->get_data($loc);
						if ($loc["status"] == "0")
							$this->template->write("msg", "<div class=msg>".$loc["msg"]."</div>");
						else
						{	
							$type = ($data["type"] == "KOTA") ? "PROVINCE" : $data["type"];
							//$type = ($type == "KOTA") ? "PROVINCE" : $data["type"];														
							$this->template->write("msg", "<div class=msg>1 $type has been registered.</div>");
						}
					}
				}
				/*
				else
				{
					$this->template->write("msg", "<div class=msg>Please enter a valid Name.</div>");
				}
				*/
				$header .= "<script type=\"text/javascript\" src=\"http://maps.google.com/maps/api/js?sensor=true\"></script>
					<script src=\"".base_url()."js/geolocation2.js\"></script>
					<script src=\"".base_url()."js/jquery.js\"></script>
					<script src=\"".base_url()."js/jquery.chainedSelects.js\"></script>
					<script>
						$(document).ready(function(){
							initialize();
						
							$('#country1').chainSelect('#city1','".base_url()."func/get_location_by_parent_id.php');
							$('#country2').chainSelect('#city2','".base_url()."func/get_location_by_parent_id.php');
							$('#city2').chainSelect('#kotamadya2','".base_url()."func/get_location_by_parent_id.php');
							$('#country3').chainSelect('#city3','".base_url()."func/get_location_by_parent_id.php');
							$('#city3').chainSelect('#kotamadya3','".base_url()."func/get_location_by_parent_id.php');
							$('#kotamadya3').chainSelect('#kecamatan3','".base_url()."func/get_location_by_parent_id.php');
							
						});
							function get_location_by_loc_id(id)
							{
								
								$.ajax({
									type: \"GET\",
									url: \"".CORE_URL."get_location_by_loc_id.php\",
									data: \"id=\"+id,
									success: function(result){
										if (id != 0) 
										{
											a = jQuery.parseJSON(result);
											
											$(\"#lat2\").val(a.loc_lat);
											$(\"#lng2\").val(a.loc_lng);
											$(\"#zipcode\").val(a.zipcode);
											setLatLng(a.loc_lat, a.loc_lng);
										}
									}
								});
								
							};
					</script>
				";
				$this->template->write("header", $header);
				$this->template->write_view("content", "admin/location_add", $data, TRUE);
				break;
			}
			
			//add ali
			case "import" :
			{			
				
				
				$config['upload_path'] = './upload/';
				$config['allowed_types'] = 'csv';
				$config['max_size']	= '100';
				
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				$json = "";
				if ( ! $this->upload->do_upload())
				{
					$data = array('error' => $this->upload->display_errors());
					
				}
				else
				{
					$tp=$this->upload->data();
					
					$open_file = "./upload/".$tp['file_name'];
					$handle = fopen($open_file, "r");
					if ($handle !== FALSE) {
						while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
							$c = count($data);
							for ($x=0;$x<$c;$x++)
							{	
								$data[$x] = str_replace('"','',$data[$x]);
								$array[] = explode(";",$data[$x]);
							}
						}
						fclose($handle);
					}
					
					//echo "<PRE>"; print_r($array); echo "</PRE>";
					 foreach ($array as $b) {
						 $json = CORE_URL."add_location_import.php?tx_id=$tx_id&loc_id=".$b[0]."&country_id=".$b[2]."&parent_id=".$b[3]."&type=".$b[1]."&name=".$b[4]."&lat=".$b[5]."&lng=".$b[6]."&zipcode=".$b[8];
						$data = $this->Madmin->get_data($json);
					 }
					
					//die($json);
				}
				
				$tx_id = $this->Madmin->get_uuid(current_url());
				$data["countries"] = CORE_URL."get_countries.php?tx_id=$tx_id";
				$data["countries"] = $this->Madmin->get_data($data["countries"]);
				$data["countries"] = $data["countries"]["results"];
				
				if($json) 
					$this->template->write("msg", "<div class=msg>Import Done.</div>");
				
				$this->template->write_view("content", "admin/location_add", $data, TRUE);
				break;
			}
				
			//end add
			
			case "update_country" :
			{
				$id = !is_null($this->input->post("id")) ? urlencode(strtoupper($this->input->post("id"))) : "";
				$del = !is_null($this->input->post("del")) ? urlencode($this->input->post("del")) : "";
				$new_id = !is_null($this->input->post("new_id")) ? urlencode($this->input->post("new_id")) : "";
				$country_name = !is_null($this->input->post("country_name")) ? urlencode($this->input->post("country_name")) : "";
				
				if ($del == "1")
				{
					$json = CORE_URL."update_country.php?id=$id&status=2";
					$json = $this->Madmin->get_data($json);
					if ($json["status"] == "0")
						$this->template->write("msg", "<div class=msg>".$json["msg"]."</div>");
					else
						$this->template->write("msg", "<div class=msg>1 COUNTRY has been updated.</div>");
				}
				else
				{
					if (($new_id != "") && ($country_name != ""))
					{
						$country = file_get_contents(CORE_URL."update_country.php?tx_id=$tx_id&id=$id&new_id=$new_id&country_name=$country_name");
						//echo $country;
						$country = json_decode($country, true);
						if ($country["status"] == "0")
							$this->template->write("msg", "<div class=msg>".$country["msg"]."</div>");
						else
							$this->template->write("msg", "<div class=msg>1 COUNTRY has been updated.</div>");
					}
					else
					{
						$this->template->write("msg", "<div class=msg>Please enter a valid Country ID and Country Name.</div>");
					}
				}
				//echo "<pre>"; print_r($arr); echo "</pre>";
				$countries = CORE_URL."get_countries.php";
				$countries = $this->Madmin->get_data($countries);
				$this->template->write_view("content", "admin/location_edit", $data, TRUE);
				break;
			}

			
		}
		
		$this->template->render();
	}

	
	function jobmatching()
	{
		$data = "";
		$this->template->write_view("content", "admin/jobmatching_manage", $data, TRUE);
	}
	
	function custom_err_msg($str)
	{
		if ($str == '0')
		{ $this->form_validation->set_message('custom_err_msg', 'Please choose a valid %s.'); return FALSE; }
		else
			return TRUE;
	}
	


}
?>