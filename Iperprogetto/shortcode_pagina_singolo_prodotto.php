<?php

function add_download_link_product_page() {
	
	$output = []; // Initializing
	$product = wc_get_product();
	
	if ( $product->is_downloadable() ) {
		// Loop through WC_Product_Download objects
		foreach( $product->get_downloads() as $key_download_id => $download ) {

			## Using WC_Product_Download methods (since WooCommerce 3)

			$download_name = $download->get_name(); // File label name
			$download_sub_name = substr($download->get_name(), 0, 10); // File label name
			$download_sub_name = strlen($download->get_name()) >= 10 ? substr($download->get_name(), 0, 10) .'... ' : $download->get_name(); // File label name
			$download_link = $download->get_file(); // File Url
			$download_id   = $download->get_id(); // File Id (same as $key_download_id)
			//$download_type = $download->get_file_type(); // File type
			$download_ext  = $download->get_file_extension(); // File extension

			## Using array properties (backward compatibility with previous WooCommerce versions)

			// $download_name = $download['name']; // File label name
			// $download_link = $download['file']; // File Url
			// $download_id   = $download['id']; // File Id (same as $key_download_id)
			
			$output[$download_id] = '<a title="'.$download_name.'" style="display: block; color: #54595f;" href="'.$download_link.'">'.$download_sub_name.'.'.$download_ext.'</a>';
		}
		// Output example
	} else  {
		$output = ['Nessun file'];
	}

        $links = implode('', $output);

		return '<div>' . $links . '</div>';

}
add_shortcode('add_dowload_link_product_page', 'add_download_link_product_page');

function display_product_data_table() {
	
 	$sku = get_post_meta( get_the_ID(), '_sku', true );
    $categories = get_the_terms( get_the_ID(), 'product_cat' );
    $tags = get_the_terms( get_the_ID(), 'product_tag' );
	$product = wc_get_product( get_the_ID() );
	$attributes = $product->get_attributes();
   	$weight = $product->get_weight();
   	$dimensions = $product->get_dimensions();
    $table_html = '<table class="attrs_product_page">';
    $table_html .= $sku ? '<tr><th><strong>SKU</strong></th><td>' . $sku . '</td></tr>' : '<tr><th><strong>SKU</strong></th><td>Non disponibile</td></tr>';
    $table_html .= '<tr><th><strong>Categorie</strong></th><td>';
    if ( $categories && ! is_wp_error( $categories ) ) {
        $category_names = array();
        foreach ( $categories as $category ) {
            $category_names[] = $category->name;
        }
        $table_html .= implode( ', ', $category_names );
    }
    $table_html .= '</td></tr>';
    $table_html .= '<tr><th><strong>Tag</strong></th><td>';
    if ( $tags && ! is_wp_error( $tags ) ) {
        $tag_names = array();
        foreach ( $tags as $tag ) {
            $tag_names[] = $tag->name;
        }
        $table_html .= implode( ', ', $tag_names );
    } 
	else {
        $table_html .= 'Nessun tag';
    }
    $table_html .= '</td></tr>';
	if ( $attributes ) {
		foreach ( $attributes as $attribute ) {
			$attribute_value = "";
			$attribute_name = wc_attribute_label( $attribute->get_name());
			$options = $attribute->get_options();
			for($i = 0; $i < count($options); $i++){
				$get_name_parms = get_term_by('id', $options[$i], $attribute->get_name() );
				if ($i == count($options) - 1) {
					$attribute_value .= $get_name_parms->name; 
				}else{
					$attribute_value .= $get_name_parms->name .', '; 
				}
			}
			$table_html .= '<tr><th><strong>'.$attribute_name.'</strong></th><td>'.$attribute_value.'</td>';
		}
	} 
    $table_html .= '</td></tr>';
    $table_html .= $weight ? '<tr><th><strong>Peso</strong></th><td>' . $weight . '</td></tr>' : '';
    $table_html .= $dimensions ? '<tr><th><strong>Dimensioni</strong></th><td>' . $dimensions . '</td></tr>' : '';
    $table_html .= '</table>';
    return $table_html;
	
}

add_shortcode('add_attr_table_product_page', 'display_product_data_table');

function campi_di_applicazione(){

	// Recupera il valore del campo personalizzato "nome_campo"
	$campo_personalizzato = get_field('campi_di_applicazione');

	// Mostra il valore del campo personalizzato
	if ($campo_personalizzato) 
	{	
		
		return $campo_personalizzato;
	}else{
		return '<p>Non disponibile</p>';
	}
}
add_shortcode('print_campi_di_applicazione', 'campi_di_applicazione');

function indicazioni_duso(){
	
	// Recupera il valore del campo personalizzato "nome_campo"
	$campo_personalizzato = get_field('indicazioni_duso');
	
	// Mostra il valore del campo personalizzato
	if ($campo_personalizzato) {
		return $campo_personalizzato;
	}else{
		return '<p>Non disponibile</p>';
	}
	
}
add_shortcode('print_indicazioni_duso', 'indicazioni_duso');

function certificazioni_e_marcature(){
	
	// Recupera il valore del campo personalizzato "nome_campo"
	$campo_personalizzato = get_field('certificazioni_e_marcature');

	// Mostra il valore del campo personalizzato
	if ($campo_personalizzato) {
		return $campo_personalizzato;
	}else{
		return '<p>Non disponibile</p>';
	}
	
}
add_shortcode('print_certificazioni_e_marcature', 'certificazioni_e_marcature');

function altre_indicazioni_duso(){
	
	// Recupera il valore del campo personalizzato "nome_campo"
	$campo_personalizzato = get_field('altre_indicazioni_duso');

	// Mostra il valore del campo personalizzato
	if ($campo_personalizzato) {
		return $campo_personalizzato;
	}else{
		return '<p>Non disponibile</p>';
	}
	
}
add_shortcode('print_altre_indicazioni_duso', 'altre_indicazioni_duso');

function voce_di_capitolato(){
	
	// Recupera il valore del campo personalizzato "nome_campo"
	$campo_personalizzato = get_field('voce_di_capitolato');

	// Mostra il valore del campo personalizzato
	if ($campo_personalizzato) {
		return $campo_personalizzato;
	}else{
		return '<p>Non disponibile</p>';
	}
	
}
add_shortcode('print_voce_di_capitolato', 'voce_di_capitolato');

function avvertenze(){
	
	// Recupera il valore del campo personalizzato "nome_campo"
	$campo_personalizzato = get_field('avvertenze');

	// Mostra il valore del campo personalizzato
	if ($campo_personalizzato) {
		return $campo_personalizzato;
	}else{
		return '<p>Non disponibile</p>';
	}
	
}
add_shortcode('print_avvertenze', 'avvertenze');

function video_product(){
	
	// Recupera il valore del campo personalizzato "nome_campo"
	$campo_personalizzato = get_field('video_prodotto');

	// Mostra il valore del campo personalizzato
	if ($campo_personalizzato) {
		return '
		<iframe width="600" height="400" src="'.$campo_personalizzato.'"></iframe>';
		
	}else{
		return '<p>Non disponibile</p>';
	}
	
}
add_shortcode('print_video_product', 'video_product');

function vendor_product(){
	
		
	// Recupera il valore del campo personalizzato "nome_campo"
	$id_user = get_field('vendor_user');
	$meta_values = get_user_meta($id_user);

	$post_id = get_the_ID();
	$title = get_the_title( $post_id );
	echo '<h1 style="font-size:20px; color: rgb(29, 60, 110);">'.esc_html( $title ).'</h1>';


	// Mostra il valore del campo personalizzato
	if ($meta_values) {
		$company_name = $meta_values['billing_company'][0];
		return '<p>Venduto da <a href="https://iperprogetto.it/vetrina/?venditore='. $meta_values['nickname'][0].'" >'.$company_name.'</a></p>';
		
	}else{
		return '<p>Non disponibile</p>';
	}
}
add_shortcode('print_vendor', 'vendor_product');

function logo_vendor(){
	
	$id_user = get_field('vendor_user');
	$meta_values = get_user_meta($id_user);

	if ($meta_values) 
	{
		$logo_venditore = $meta_values['logo_venditore'][0];
		return '<a href="https://iperprogetto.it/vetrina/?venditore='.$meta_values['nickname'][0].'"><img src="'.esc_url(wp_get_attachment_url($logo_venditore)?wp_get_attachment_url($logo_venditore):wc_placeholder_img_src( 'medium')).'" width="150" height="150"></a>';
	}
	
	else
	{
		return '<p>Non disponibile</p>';
	}
}
add_shortcode('print_logo_vendor', 'logo_vendor');

function product_location(){


// Recupera il valore del campo personalizzato "nome_campo"
	$campo_personalizzato = get_field('product_lease');
		
	if ( $campo_personalizzato ) {
		return  '<p>'.$campo_personalizzato[0]->name.'</p>';
	} else {
		return '<p>Non disponibile</p>';
	}

}
add_shortcode('print_product_location', 'product_location');

function product_datasheet(){


	// Recupera il valore del campo personalizzato "nome_campo"
	$pdf_list = get_field('schede_prodotto_pdf');
	//var_dump($pdf_list);
	echo '<br>';
	if($pdf_list){
		foreach($pdf_list as $pdf){

			echo '		
					<div style="display: grid;float:left;margin-right: 15px;border: 1px solid #AAB1BE; border-radius:1px;align-items: center;width: 200px;height: 200px;text-align: -webkit-center; background-color: #f0f0f1;">
						<a href="'.$pdf['url'].'" target="_blank">
							<div class="content">
								<div class="content_img">
									<img src="'.$pdf['icon'].'" width="50" height="50">
								</div>
								<div style="width: 180px;">
									<p style="margin: 0;text-overflow: ellipsis;white-space: nowrap;overflow: hidden;">'.$pdf['title'].'</p>
								</div>
							</div>
						</a>
					</div>

				';
		}
	}else{
		return '<p>Non disponibili</p>';
	}
}
add_shortcode('print_product_datasheet', 'product_datasheet');

function vendor_catalog_product_page(){

	$product_id = get_the_ID(); // Ottieni l'ID della pagina prodotto
	$vendor_user = get_post_meta( $product_id, 'vendor_user', true ); // Recupera il valore del campo meta "vendor_user"
	$vendor_meta = get_user_meta($vendor_user);
	
	if($vendor_meta)
	{
		for($i = 1; $i<5; $i++)
		{
			if(wp_get_attachment_url($vendor_meta['catalog_vendor_'.$i][0]) && wp_get_attachment_url($vendor_meta["catalog_vendor_image_".$i][0])){
				//echo '<a class="vendor_catalog_link_iperpro" href="'. $link_pdf .'" target=_blank><img src="'.$image_catalog.'"></a>';

				return '<div style="display: grid;float:left;margin-right: 15px;margin-bottom: 15px;border: 1px solid #AAB1BE; border-radius:1px;align-items: center;text-align: -webkit-center; background-color: #f0f0f1;">
						<a href="'.wp_get_attachment_url($vendor_meta['catalog_vendor_'.$i][0]).'" target="_blank">
							<div class="content">
								<div class="content_img">
									<img src="'.wp_get_attachment_url($vendor_meta["catalog_vendor_image_".$i][0]).'"  width="150">
								</div>
							</div>
						</a>
					</div>';
			}
			else
			{
				return '<p>Non disponibile</p>';
			}
		}
	}
	else
	{
		return '<p>Non disponibile</p>';
	}
}
add_shortcode('print_catalog_product_page', 'vendor_catalog_product_page');