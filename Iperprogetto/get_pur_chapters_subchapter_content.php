<?php

function process_selected_region() {
  
  
	global $wpdb;
	
	$selected_region = $_GET["selected_region"];
    
	//query per leggere i dati dalla tabella
    $results = $wpdb->get_results("SELECT * FROM hfu_pur_chapters where region='$selected_region'", OBJECT);
   
	// Codifica dei risultati in formato JSON
	if($results > 0){
		echo json_encode($results);
	}

	else{
		// Output in formato JSON
		header('Content-Type: application/json');
		echo "nulla";
	}
	
  wp_die();
	
}

function process_selected_chapter(){
	
	global $wpdb;
	
	$selected_region = $_GET["selected_region"];
	$selected_chapter = $_GET["selected_chapter"];
    
	//query per leggere i dati dalla tabella
    $results = $wpdb->get_results("SELECT subchapter, title From hfu_pur_subchapter where region ='$selected_region' AND hfu_pur_subchapter.chapter ='$selected_chapter'", OBJECT);
   
	// Codifica dei risultati in formato JSON
	if($results > 0){
		//header('Content-Type: application/json');
		echo json_encode($results);
	}

	else{
		// Output in formato JSON
		header('Content-Type: application/json');
		echo "nulla";
	}
	
  wp_die();
	
	
	
}

function process_selected_content(){
	
	global $wpdb;
	
	$selected_region = $_GET["selected_region"];
	$selected_chapter = $_GET["selected_chapter"];
	$selected_subchapter = $_GET["selected_subchapter"];
    
	//query per leggere i dati dalla tabella
    $results = $wpdb->get_results("SELECT chapter, subchapter, `head_paragraphs`,`paragraphs_content`, keywords FROM `hfu_pur_content` where `region`='$selected_region' AND `chapter`='$selected_chapter' AND `subchapter`='$selected_subchapter' ", OBJECT);
   
	// Codifica dei risultati in formato JSON
	if($results > 0){
		header('Content-Type: application/json');
		echo json_encode($results);
	}

	else{
		// Output in formato JSON
		header('Content-Type: application/json');
		echo "nulla";
	}
	
  wp_die();
	
}

function process_selected_voice(){
	
	global $wpdb;
	
	$selected_region = $_GET["selected_region"];
	$selected_chapter = $_GET["selected_chapter"];
	$selected_subchapter = $_GET["selected_subchapter"];
    

	
	$results = $wpdb->get_results("SELECT hfu_pur_voice.id, hfu_pur_voice.region, hfu_pur_content.paragraphs_content, hfu_pur_voice.chapter, hfu_pur_voice.subchapter, hfu_pur_voice.paragraphs, hfu_pur_voice.voice_id, hfu_pur_voice.text_content, hfu_pur_voice.unit, hfu_pur_voice.price, hfu_pur_voice.vat FROM hfu_pur_voice INNER JOIN hfu_pur_content ON hfu_pur_voice.region = hfu_pur_content.region AND hfu_pur_voice.chapter = hfu_pur_content.chapter AND hfu_pur_voice.subchapter = hfu_pur_content.subchapter AND hfu_pur_voice.paragraphs = hfu_pur_content.head_paragraphs WHERE hfu_pur_voice.region='$selected_region' AND hfu_pur_voice.chapter = '$selected_chapter' AND hfu_pur_voice.subchapter = '$selected_subchapter' GROUP BY hfu_pur_voice.region, hfu_pur_voice.chapter, hfu_pur_voice.subchapter, hfu_pur_voice.paragraphs, hfu_pur_voice.voice_id, hfu_pur_voice.text_content, hfu_pur_voice.unit, hfu_pur_voice.price, hfu_pur_voice.vat ORDER BY `hfu_pur_voice`.`chapter` ASC");
	
/*
 * Per far visualizzare correttamente il pur è necassario che la join sia unita indicando i parametri capitolo, sottocapitolo e parragrafo. Inoltre è necessario inserire le voci, anche vuote ma che ci siano, per ogni paragrafo al fine di far collassare correttamente e visualizzare il contenuto. Esempio attuale = 1.1.11 non collassa perché nella tabella hfu_pur_voice non esiste una voce per il paragrafo 11 anche se quest'ultimo è contenuto in hfu_pur_content. Al momento per far funzinare il pur hfu_pur_voice e hfu_pur_content dipendono l'uno dall'altra.
 * 
 * 
 * */
   
	// Codifica dei risultati in formato JSON
	if($results > 0){
				header('Content-Type: application/json');

		echo json_encode($results);
	}

	else{
		// Output in formato JSON
		header('Content-Type: application/json');
		echo "nulla";
	}
	
  wp_die();
	
}

function process_selected_voice_2(){
	
	global $wpdb;
	
	$selected_region = $_GET["selected_region"];
	$selected_chapter = $_GET["selected_chapter"];
	$selected_subchapter = $_GET["selected_subchapter"];
	$selected_paragraphs = $_GET["selected_paragraphs"];
    

	
	$results = $wpdb->get_results("SELECT hfu_pur_voice.id, hfu_pur_voice.region, hfu_pur_content.paragraphs_content, hfu_pur_voice.chapter, hfu_pur_voice.subchapter, hfu_pur_voice.paragraphs, hfu_pur_voice.voice_id, hfu_pur_voice.text_content, hfu_pur_voice.unit, hfu_pur_voice.price, hfu_pur_voice.vat FROM hfu_pur_voice INNER JOIN hfu_pur_content ON hfu_pur_voice.region = hfu_pur_content.region AND hfu_pur_voice.chapter = hfu_pur_content.chapter AND hfu_pur_voice.subchapter = hfu_pur_content.subchapter AND hfu_pur_voice.paragraphs = hfu_pur_content.head_paragraphs WHERE hfu_pur_voice.region='$selected_region' AND hfu_pur_voice.chapter = '$selected_chapter' AND hfu_pur_voice.subchapter = '$selected_subchapter' AND hfu_pur_voice.paragraphs = '$selected_paragraphs'  GROUP BY hfu_pur_voice.region, hfu_pur_voice.chapter, hfu_pur_voice.subchapter, hfu_pur_voice.paragraphs, hfu_pur_voice.voice_id, hfu_pur_voice.text_content, hfu_pur_voice.unit, hfu_pur_voice.price, hfu_pur_voice.vat ORDER BY `hfu_pur_voice`.`chapter` ASC");
	
/*
 * Per far visualizzare correttamente il pur è necassario che la join sia unita indicando i parametri capitolo, sottocapitolo e parragrafo. Inoltre è necessario inserire le voci, anche vuote ma che ci siano, per ogni paragrafo al fine di far collassare correttamente e visualizzare il contenuto. Esempio attuale = 1.1.11 non collassa perché nella tabella hfu_pur_voice non esiste una voce per il paragrafo 11 anche se quest'ultimo è contenuto in hfu_pur_content. Al momento per far funzinare il pur hfu_pur_voice e hfu_pur_content dipendono l'uno dall'altra.
 * 
 * 
 * */
   
	// Codifica dei risultati in formato JSON
	if($results > 0){
				header('Content-Type: application/json');

		echo json_encode($results);
	}

	else{
		// Output in formato JSON
		header('Content-Type: application/json');
		echo "nulla";
	}
	
  wp_die();
	
}

function save_voice_data_user(){
	
	global $wpdb;
	
	$id_ai_voice = $_POST["id_ai_voice"];
	$id_user = $_POST["id_user"];

	//query per leggere i dati dalla tabella
   	$results = $wpdb->get_results("INSERT INTO `hfu_users_note`(`user_id`, `id_ai_voice`) VALUES ('$id_user', '$id_ai_voice') ");
	
	
	//echo $results;
	
	

		
  wp_die();
	
	
	
}





add_action("wp_ajax_process_selected_region", "process_selected_region");

add_action("wp_ajax_process_selected_chapter", "process_selected_chapter");

add_action("wp_ajax_process_selected_content", "process_selected_content");

add_action("wp_ajax_process_selected_voice", "process_selected_voice");

add_action("wp_ajax_process_selected_voice_2", "process_selected_voice_2");

add_action("wp_ajax_save_voice_data_user", "save_voice_data_user");