<?php

function pur_menu(){

	global $wpdb;





?>

<head>

	<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css' rel='stylesheet'>

	<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js'></script>


	<style>
		.elementor-section.elementor-section-boxed > .elementor-container {
			max-width: 1175px !important;
		}
		
		.select_pur_iperpro {
			background-image: url('/wp-content/uploads/2023/03/Untitled-design-1.png');
			background-position: right;
			background-size: 1500px;
			border: none;
			border-radius: 15px;
			width: 570px;
		}
		



		.button_up {
			border: none;
			border-radius: 24px;
			color: black;
			background-color: #F5F5F5;
			padding: 10px 10px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 16px;
			margin: 10px 10px;
			cursor: pointer;
		}

		.button_up:hover {
			background-color: #1E3B88;
			color: white;
		}

		.button_coll {
			border: none;
			color: black;
			background-color: #F5F5F5;
			padding: 10px 10px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 16px;
			margin: 5px 5px;
			cursor: pointer;

		}

		.button_coll:hover {
			background-color: #1E3B88;
			color: white;

		}

		.bg_blue {
			background-color: #1E3B88 !important;
			color: white !important;
		}

		.collapse {}

		.div_ {

			margin-left: 25px;
		}

		a {
			color: black
		}

		a:hover {
			color: white;
		}

		.button_up {
			border: none;
			border-radius: 24px;
			color: black;
			background-color: #F5F5F5;
			padding: 10px 10px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 16px;
			margin: 4px 2px;
			cursor: pointer;
		}

		.button_up:hover {
			background-color: #1E3B88;
			color: white;
		}

		.button_coll {
			border: none;
			color: black;
			background-color: #F5F5F5;
			padding: 10px 10px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 16px;
			margin: 4px 2px;
			cursor: pointer;
		}

		.button_coll:hover {
			background-color: #1E3B88;
			color: white;
		}

		.button_coll:active {
			background-color: #1E3B88;
			color: white;

		}

		.pur-select {
			margin-top: 10px;
		}

		.div_ {

			margin-left: 25px;
		}

		.btn-blue {
			padding: 10px 5px;
			color: white;
			border: none;
			border-radius: 15px;
			background:#1E3B88; 
		}

		#style-1::-webkit-scrollbar-track {
			-webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0);
			border-radius: 10px;
			background-color: rgba(0, 0, 0, 0.0);
		}

		#style-1::-webkit-scrollbar {
			width: 12px;
			background-color: rgba(0, 0, 0, 0.0);
		}

		#style-1::-webkit-scrollbar-thumb {
			border-radius: 10px;
			-webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.0);
			background-color: #d4d4d4;
		}
		
		
	</style>

	<head>
	</head>


	<body>
		<?php 
	//$results_chapter = $wpdb->get_results("SELECT * FROM hfu_pur_chapters");
	//$results_subchapter = $wpdb->get_results("SELECT * FROM hfu_pur_subchapters");
		?>

		<div class="container">
			<div class="row">
				<div class="col-sm select_pur_iperpro_container">
					<select class="col-sm select_pur_iperpro" name="region" id="regioni">
						<option value="" disabled selected>Seleziona il P.U.R. della Regione</option>
						<option value="Sicilia">Sicilia</option>
					</select>
				</div>
				<div id="chapter_sel" class="pur-select"></div>
				<div id="sub_chapter_sel" class="pur-select"></div>
				<div id="content" class="pur-select"></div>

			</div>
		</div>

		<script>
			jQuery(document).ready(function($) {

				$(document).on("change", "select.select_pur_iperpro", function() {
					$(this).css("background-image", "url('/wp-content/uploads/2023/03/Untitled-design.png')");
				})

				$(document).on("click", "button.button_up", function() {

					let dataAttr = $(this).attr('data-bs-target');

					$('button[data-bs-target^=#paragrafo]').each(function(button) {

						$(this).removeClass('bg_blue')
					})

					$(this).addClass('bg_blue');
					//	let button = $(this)

					$("div[id^='paragrafo']").each(function() {
						var divId = $(this).attr('id');
						if (divId != dataAttr) {

							$(this).removeClass("show");
						}


					});

				});

				$("#regioni").change(function() {

					$('#voice').empty();
					$('#content').empty();
					$('#sub_chapter_sel').empty();
					$('#chapter_sel').empty();

					var selected_region = $(this).val();
					$.ajax({
						url: "/wp-admin/admin-ajax.php",
						type: "GET",
						data: {
							action: "process_selected_region",
							selected_region: selected_region
						},
						success: function(response) {
							let data = JSON.parse(response);
							let html =
								'<div><select class="select_pur_iperpro chapter_selector" name="chapter_sel_" id="chapter" ><option class="option-style" disabled selected>Seleziona capitolo</option>';
							for (let i = 0; i < data.length; i++) {
								let name_chapter = data[i].name_chapter.charAt(0).toUpperCase() + data[i].name_chapter.slice(1).toLowerCase();
								if (data[i].region == selected_region) {
									html += "<option value='" + data[i].chapter + "'>" + data[i]
										.chapter + ". " + name_chapter + "</option>";
								}
							}
							html += '</select></div>';
							$("#chapter_sel").html(html);
						}
					});
				});

				$(document).on('change', '#chapter', function() {
					$('#voice').empty();
					$('#content').empty();
					$('#sub_chapter_sel').empty();

					var selected_chapter = $(this).val();
					var selected_region = $('#regioni').val();
					$("div#voice").empty();
					$("div.buttons_up_container").remove();


					$.ajax({
						url: "/wp-admin/admin-ajax.php",
						type: "GET",
						data: {
							action: "process_selected_chapter",
							selected_region: selected_region,
							selected_chapter: selected_chapter
						},
						success: function(response) {
							let data = JSON.parse(response);
							let html =
								'<div><select class="select_pur_iperpro subchapter_selector" name="sub_chapter_sel_" id="subchapter"><option disabled selected>Seleziona sottocapitolo</option>';
							for (let i = 0; i < data.length; i++) {
								let title = data[i].title.charAt(0).toUpperCase() + data[i].title.slice(1).toLowerCase();
								html += "<option value='" + data[i].subchapter + "'>" + data[i]
									.subchapter + '. ' + title + "</option>";
							}
							html += '</select></div>';
							$("#sub_chapter_sel").html(html);
						}
					});
				});




				$(document).on('change', '#subchapter', function() {

					$('#voice').empty();
					$('#content').empty();

					let selected_subchapter = $(this).val();
					let selected_chapter = $('#chapter').val();
					let selected_region = $('#regioni').val();
					let selected_paragraph;
					let data;


					$.ajax({
						url: "/wp-admin/admin-ajax.php",
						type: "GET",
						data: {
							action: "process_selected_content",
							selected_region: selected_region,
							selected_chapter: selected_chapter,
							selected_subchapter: selected_subchapter
						},
						success: function(response) {

							let coversion = JSON.stringify(response);
							data = JSON.parse(coversion);

							let html = '<div><select class="select_pur_iperpro paragraphs_selector" name="paragraphs_sel_" id="paragraphs"><option disabled selected>Seleziona paragrafo</option>';

							for (let i = 0; i < data.length; i++) {

								let content = data[i].paragraphs_content.replace(/(<([^>]+)>)/ig, '');

								html += '<option id="access_css" value="' + data[i].head_paragraphs + '">' + data[i].head_paragraphs + '. ' + content.slice(0,80) + '...</option>';
								
							

							}




							html += '</select></div></div>';
							$("#content").html(html);

						}

					});

				}); //chiusura #subchapter

				$(document).on( "change", "#paragraphs", function(){



					let selected_region = $('#regioni').val();
					let selected_chapter = $('#chapter').val();
					let selected_subchapter = $('#subchapter').val();
					let selected_paragraphs = $(this).val();

					console.log(selected_region, selected_chapter, selected_subchapter, selected_paragraphs);



					$.ajax({
						url: "/wp-admin/admin-ajax.php",
						type: "GET",
						data: {
							action: "process_selected_voice_2",
							selected_region: selected_region,
							selected_chapter: selected_chapter,
							selected_subchapter: selected_subchapter,
							selected_paragraphs: selected_paragraphs

						},
						success: function(response) {

							let conversion = JSON.stringify(response);
							let data2 = JSON.parse(conversion);

							let html2 = '<div class="row"><div class="col">';

							let count = 0;
							let prec;
							let prec_par;

							for (let i = 0; i < data2.length; i++) {

								if (data2[i].paragraphs_content != prec) {

									if (data2[i].paragraphs != prec_par) {

										html2 += '</div></div><br></div></div>';

									}

									html2 += '<div id="paragrafo' + data2[i].paragraphs +
										'" class=""><div class="div_"><div class="row"><div class="col"><br><h2 style="color: #1D3C6E;">' +
										data2[i].chapter + '.' + data2[i].subchapter +
										') Paragrafo n.' + data2[i].paragraphs +
										'</h2><p style="text-align:justify;">' + data2[i]
										.paragraphs_content + '</p></div></div><br>';
									html2 +=
										'<div><div data-bs-spy="scroll" id="style-1" data-bs-target="#navbar-example2" data-bs-offset="0"class="scrollspy-example" tabindex="0" style="min-height:100px; max-height:500px; overflow-y: scroll;  overflow-x: hidden; background-color:#dbe3ec;padding:15px;border:solid;border-radius:15px;border-width:0px;border-color:#636270;">';

									prec = data2[i].paragraphs_content;
									prec_par = data2[i].paragraphs;
								}

								let test = parseFloat(data2[i].price.replace(',','.',).replace('€',''),2) * parseFloat(data2[i].vat.replace(',','.',).replace('%',''),2)/100.00;

								html2 += '<div class="row"><div class="col-5"><p style="text-align: justify;"><strong>' + data2[i].voice_id + ')</strong> ' + data2[i].text_content + '</p></div><div class="col text-center align-self-end p-0 mb-2"><p>' + data2[i].unit + '</p></div><div class="col text-center align-self-end p-0 mb-2"><p><strong>€ ' + data2[i].price + '</strong></p></div><div class="col text-center align-self-end p-0 mb-2"><p>' + data2[i].vat + '    <strong>(€ '+test.toFixed(2)+')</strong></p></div><div class="col text-center align-self-end p-0"><p><button id="save-note-btn" data-value-voice-id="' + data2[i].id + '" class="btn-blue" >Salva su note</button></p></div></div>';	
							}

							html2 += '</div></div>'
							$("#voice").html(html2);

						} //chiusure response success
					}); //Chiusura seconda ajax */


				});



				$(document).on( 'click', '#save-note-btn', function () {

					let id_ai_voice = $(this).attr('data-value-voice-id');
					let id_user = <?php echo get_current_user_id(); ?>;

					$.ajax({
						url: '/wp-admin/admin-ajax.php',
						type: "POST",
						data: {
							action: "save_voice_data_user",
							id_ai_voice: id_ai_voice,
							id_user: id_user
						},
						success: function(response) {

							alert("Nota salvata con successo!");
						}
					});
				});

			});
		</script>



		<?php
}

add_shortcode('pur', 'pur_menu');