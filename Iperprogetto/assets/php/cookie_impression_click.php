<?php

function post_impression()
{
	
	global $wpdb;
	
	$postId = $_POST['postId'];
	$impression_value = $_POST['impression_value'];
			
	$wpdb->insert( 'hfu_tracking_data', array(
		'type' => 'impression',
		'post_id' => $postId,
		'counter' => $impression_value,
		'data' => current_time('mysql')

	));
	
	wp_die();

}
add_action("wp_ajax_post_impression", "post_impression");

function post_click()
{
	
	global $wpdb;
	
	$postId = $_POST['postId'];
	$click_value = $_POST['click_value'];
				
	$wpdb->insert( 'hfu_tracking_data', array(
		'type' => 'click',
		'post_id' => $postId,
		'counter' => $click_value,
		'data' => current_time('mysql')
		));

	wp_die();

}
add_action("wp_ajax_post_click", "post_click");

function wpb_hook_javascript() {
	if ( ! is_admin() ) 
	{
  	?>
		<script type="text/javascript">
			
			jQuery(document).ready(function($) {
			
				function checkDOMChanges() {
			  var observer = new MutationObserver(function(mutations) {
				mutations.forEach(function(mutation) {
				  if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
					init();
				  }
				});
			  });
			  observer.observe(document.body, { childList: true, subtree: true });
			}

			  	var delay = 3000; // tempo di permanenza minimo in millisecondi
			  	var viewedProducts = [];

			  	function trackImpression(postId) {
				if (!viewedProducts.includes(postId)) {
				  // qui puoi inserire la tua chiamata per il conteggio dell'impressione
				  console.log('Impressione conteggiata per il prodotto ' + postId);
				  //
					$.ajax({
						url: "/wp-admin/admin-ajax.php",
						type: "POST",
						data: {
							action: "post_impression",
							postId: postId,
							impression_value: 1
						},
						success: function(response) {
							//console.log(response);
						}

					});
				    
					viewedProducts.push(postId);
				  	setCookie('viewed_products', JSON.stringify(viewedProducts), 30);
				}
			  }

			  	function setCookie(name, value, days) {
				var expires = '';
				if (days) {
				  var date = new Date();
				  date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
				  expires = '; expires=' + date.toUTCString();
				}
				document.cookie = name + '=' + encodeURIComponent(value) + expires + '; path=/';
			  }

			  	function getCookie(name) {
				var nameEQ = name + '=';
				var ca = document.cookie.split(';');
				for (var i = 0; i < ca.length; i++) {
				  var c = ca[i];
				  while (c.charAt(0) == ' ') c = c.substring(1, c.length);
				  if (c.indexOf(nameEQ) == 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
				}
				return null;
			  }

			  	function init() {
				  
				var viewedProductsCookie = getCookie('viewed_products');
				if (viewedProductsCookie) {
				  viewedProducts = JSON.parse(viewedProductsCookie);
				}
				  
				$(document).on('mouseenter','.etheme-product-grid-item', function() {
					var $this = $(this);
					var postId = $this.closest('.etheme-product-grid-item').attr('class').match(/post-(\d+)/);
					if (postId) {
					  postId = postId[1];
					//console.log(postId);
					  setTimeout(function() {
						trackImpression(postId);
					  }, delay);
					}
				  });
				  
				 
				 $(document).on('click','.etheme-product-grid-item', function(){
					var $this = $(this);
					var postId = $this.closest('.etheme-product-grid-item').attr('class').match(/post-(\d+)/);
					if (postId) {
					  postId = postId[1];
					  // qui puoi inserire la tua chiamata per il conteggio del click
					  //console.log('Click conteggiato per il prodotto ' + postId);
						
						$.ajax({
						url: "/wp-admin/admin-ajax.php",
						type: "POST",
						data: {
							action: "post_click",
							postId: postId,
							click_value: 1
						},
						success: function(response) {
							//console.log(response);
						}

					});
						
					}
				  });
			  }

				init();
				
			//checkDOMChanges();

			
			/*
			$(document).on('click', function(event) {
			  var elemento = event.target;
			  var tipo_evento = event.type;
			  var testo = '';
			  var id_elemento= elemento.getAttribute('id');


			  // Ottieni il testo dell'elemento cliccato
			  if (elemento.nodeName === 'BUTTON' || 
				  elemento.nodeName === 'A' || 
				   elemento.nodeName === 'P'
				 ) 
			  {
				testo = elemento.textContent.trim();

			  }


			  // Invia i dati dell'evento al server
			  var dati_evento = {
				tipo_evento: tipo_evento,
				testo: testo,
				ID: id_elemento,
				data: new Date().toISOString()
			  };
				
			console.log('dati:', dati_evento);
			
	


			  /*$.ajax({
				url: '/traccia_evento',
				type: 'POST',
				contentType: 'application/json',
				data: JSON.stringify(dati_evento)
			  });   
			});
			*/
				
			});
		</script>
	<?php
	}
	
}
add_action('wp_head', 'wpb_hook_javascript');

function test_cookie()
{
	// Recupera il gruppo di campi ACF con ID 5906
    $group = acf_get_field_group(5906);
    // Recupera tutti i campi personalizzati definiti nel gruppo
    $fields = acf_get_fields($group['ID']);

    // Aggiunge ogni campo come opzione di mappatura nell'importatore di Woocommerce
    foreach ( $fields as $field ) {
      echo  $options[ '_' . $field['name'] ] = $field['label'];
    }

    //return $options;



}
add_shortcode('get_cookie_test', 'test_cookie');