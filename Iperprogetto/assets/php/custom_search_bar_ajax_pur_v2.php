<?php

function custom_search_bar() {
	
	$search_term = $_POST['search_term'];

	global $wpdb;
	$query = "SELECT * FROM hfu_pur_content WHERE paragraphs_content LIKE '%".$search_term."%'";
	$results = $wpdb->get_results($query);
	
		if ($results) {
			echo json_encode($results);
		
		} else {
			$output = 'Nessun risultato trovato.';
		}
	
		
	wp_die();

}
add_action("wp_ajax_custom_search", "custom_search_bar");




function search_bar_live_search(){
?>
<head>
	<style>
		.on_hover_results{
			background-color:#f5f5f5;
		}

		
	</style>
</head>
<body>
<!--
	<div id="ex" class="serach_bar_iperpro_wrapper">
		<input type="text" id="search_bar_iperpro" name="searchTerm" placeholder="Cerca nel P.U.R.">
		<div id="content_live_search"></div>
	</div>
-->


	<script>
		
		var ajaxTimeout;
		var clickTimeout;

		jQuery(document).ready(function($) {

			$(document).on("input", '#search_bar_iperpro', function() {
				
				let searchTerm = $(this).val();

				if (searchTerm.length >= 3) {

					clearTimeout(ajaxTimeout);
					ajaxTimeout = setTimeout(function() {
						$.ajax({
							url: '/wp-admin/admin-ajax.php',
							method: 'POST',
							data: {
								action: 'custom_search',
								search_term: searchTerm
							},
							success: function (response) {

								const paragraphs = JSON.parse(response)
								console.log(paragraphs);

								let responseHtml = '';

								if (paragraphs) {

									paragraphs.map(p => {
										
										const indice =  p.paragraphs_content.indexOf(searchTerm);
										
										let inizio = Math.max(0, indice - 35); // Estrae 40 caratteri prima della parola chiave
										let fine = Math.min(p.paragraphs_content.length, indice + 35); // Estrae 40 caratteri dopo la parola chiave
										
										/*if((inizio.length - fine.length) != 0 ){
											
											if(inizio.length < 35){
												let inizio_lenght = inizio.length;
												inizio = Math.max(0, indice - (35 - inizio_lenght));
											}
											if(fine.length < 35){
													let fine_lenght = fine.length;
													fine = Math.max(0, indice - (35 - fine_lenght))
												}
										}
										else
										{

										}*/
										
										const testoRidotto = p.paragraphs_content.slice(inizio, fine) + '...';										
										const highlighted = testoRidotto.replace(searchTerm, `<strong>${searchTerm}</strong>`);




										//const content = p.paragraphs_content.slice(0, 80) + '...'
										//console.log(p)
										responseHtml += '<div class="click_for_open"><p style="margin:10px; padding:10px;" data-id="'+p.id+'" data-region="'+p.region+'" data-chapter="'+p.chapter+'"  data-subchapter="'+p.subchapter+'" data-head_paragraphs="'+p.head_paragraphs+'"><strong>' + p.region+ ': ' + p.chapter + '.' + p.subchapter + '.' + p.head_paragraphs + ')</strong> ' + highlighted + '</p></div>'
									})




									//console.log(responseHtml);

								}

								$('#content_live_search').addClass('search_bar_iperpro_results');
								$('#content_live_search').removeClass('d-none');
								$('#content_live_search').html(responseHtml);



							},
							error: function (xhr, status, error) {
								alert('Errore nella richiesta');
							}
						});
					}, 1000);

				}

				else 
				{
					if($('#content_live_search').hasClass('d-none') == false)
					{
						$('#content_live_search').removeClass('search_bar_iperpro_results');
						$('#content_live_search').addClass('d-none');
						$('#content_live_search').html('');
					}
				}

			});
			
			
			$(document).on("click", function(event) {
				if (!$(event.target).closest('#content_live_search').length) {
					if($('#content_live_search').hasClass('d-none') == false)
					{
						$('#content_live_search').removeClass('search_bar_iperpro_results');
						$('#content_live_search').addClass('d-none');
						$('#content_live_search').html('');
					}
				}
			});


			//dove le idee cagano soldi
			$(document).on("click",".click_for_open p", function(event) {
				
				$("body").append("<div id='overlay'><div id='spinner'></div></div>");

				
				if (!$(event.target).closest('#content_live_search').length) {
					if($('#content_live_search').hasClass('d-none') == false)
					{
						$('#content_live_search').removeClass('search_bar_iperpro_results');
						$('#content_live_search').addClass('d-none');
						$('#content_live_search').html('');
					}
				}
				
				let id = $(this).attr("data-id");
				let region = $(this).attr("data-region");
				let chapter = $(this).attr("data-chapter");
				let subchapter = $(this).attr("data-subchapter");
				let head_paragraphs = $(this).attr("data-head_paragraphs");
				console.log(id,region, chapter, subchapter, head_paragraphs);
				
				clearTimeout(clickTimeout);
				clickTimeout = setTimeout(function() {
					
					$("#regioni").val(region).click();
					$("#regioni").val(region).change();



					var checkChapter = setInterval(function() {
						if ($(".chapter_selector").length) {
							clearInterval(checkChapter); // rimuovi l'intervallo
							// esegui altre azioni in base all'elemento generato
							console.log("aspetto che si generi chapter");
							//$(".chapter_selector").val(chapter).click();
							$(".chapter_selector").val(chapter).change();
						}
					}, 100); // controlla ogni 100 millisecondi

					var checkChapter2 = setInterval(function() {
						if ($(".subchapter_selector").length) {
							clearInterval(checkChapter2); // rimuovi l'intervallo
							// esegui altre azioni in base all'elemento generato
							console.log("aspetto che si generi subchapter");
							//$(".subchapter_selector").val(subchapter).click();
							$(".subchapter_selector").val(subchapter).change();
							
						}
					}, 100); // controlla ogni 100 millisecondi

					var checkChapter3 = setInterval(function() {
						if ($(".paragraphs_selector[data-paragraphs='"+head_paragraphs+"']").length) {
							clearInterval(checkChapter3); // rimuovi l'intervallo
							console.log("selezione pulsante");
							$('.paragraphs_selector[data-paragraphs="'+head_paragraphs+'"]').trigger("click");
							/*if($('.paragraphs_selector[data-paragraphs="'+head_paragraphs+'"]').hasClass("bg_blue") == false){
								$('.paragraphs_selector[data-paragraphs="'+head_paragraphs+'"]').click();
								console.log("tasto cliccato");
							}*/

						}
					}, 100); // controlla ogni 100 millisecondi

					//$("#subchapter").val(subchapter).change();
					//$("#button_head_paragraphs").val(head_paragraphs).click();
				},100);
				
				
				//$('html, body').animate({scrollTop: $("#content").offset().top}, 1000);
				$('html, body').animate({scrollTop: $("#content").offset().top}, 1000, function() {
					$("#overlay").remove();
				});
				

			});



			
			$(document).on( "mouseenter", ".click_for_open", function() {
				/*let id = $(this).attr("data-id");
				let region = $(this).attr("data-region");
				let chapter = $(this).attr("data-chapter");
				console.log(id,region, chapter);*/
				//console.log("sopra risultato");
				$(this).addClass('on_hover_results');	
			});
			
			$(document).on( "mouseleave", ".click_for_open", function() {
				//console.log("fuori risultato");
				$(this).removeClass('on_hover_results');	
			});

		});

	</script>
</body>
<?php
	
	
	
}
add_shortcode('live_search_bar_paragraphs_content', 'search_bar_live_search');