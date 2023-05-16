<?php

function update_keyword()
{
	
	global $wpdb;

	
	$id = $_POST["id"];
	$keyword = $_POST["key"];
	
	$table_name ='hfu_pur_content';
		
	$data = array(
		  "keywords" => $keyword
		);

	$where = array(
			'id' => intval($id),
		);
	
	$wpdb->update( $table_name, $data, $where);
	
	wp_die();

}

add_action("wp_ajax_update_keyword", "update_keyword");
