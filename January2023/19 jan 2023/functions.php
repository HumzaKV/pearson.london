<?php
define('THEME_PATH', get_template_directory());
define('THEME_URL', get_template_directory_uri());

function theme_files() {
	// Theme Files
	wp_register_style( 'theme-style', get_stylesheet_uri(), false, null);
	wp_enqueue_style( 'theme-style');
	wp_register_style( 'theme-styler', get_stylesheet_directory_uri().'/css/responsive.css', false, null);
	wp_enqueue_style( 'theme-styler');
	wp_register_style( 'font-css', get_stylesheet_directory_uri().'/css/fonts.css', false, null);
	wp_enqueue_style( 'font-css');
	
	//Kv Script
	wp_register_script('kv-script', get_template_directory_uri() . '/kv-script.js', array('jquery'), null, true);
	wp_enqueue_script('kv-script');

    // Slick Slider Files
	wp_register_style( 'slick', get_stylesheet_directory_uri().'/slick/slick.css', false, '2.2.1' );
	wp_enqueue_style( 'slick');	
	wp_register_script( 'slick', get_stylesheet_directory_uri().'/slick/slick.js', array( 'jquery' ), '2.2.1', true );
	wp_enqueue_script( 'slick' );


	// Owl Carousel Files
	wp_register_style( 'owl-carousel', get_stylesheet_directory_uri().'/owl-carousel/owl.carousel.min.css', false, '2.2.1' );
	wp_enqueue_style( 'owl-carousel');	
	wp_register_script( 'owl-carousel', get_stylesheet_directory_uri().'/owl-carousel/owl.carousel.min.js', array( 'jquery' ), '2.2.1', true );
	wp_enqueue_script( 'owl-carousel' );
	
	
	// Font Awesome
	wp_register_script( 'fontawesome', '//kit.fontawesome.com/b69272743e.js', true );
	wp_enqueue_script( 'fontawesome' );
}
add_action( 'wp_enqueue_scripts', 'theme_files' );

// Enable Classic Editor
add_filter('use_block_editor_for_post', '__return_false', 10);

// Disables the block editor from managing widgets in the Gutenberg plugin.
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );

// Disables the block editor from managing widgets.
add_filter( 'use_widgets_block_editor', '__return_false' );

// Theme Options
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page(array(
		'page_title' 	=> 'Theme Options',
		'menu_title'	=> 'Theme Options',
		'menu_slug' 	=> 'theme-pptions',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
}


// Owl Carousel
function load_owl_carousel() {
	?>
	<script type="text/javascript">
		jQuery(function($) {	


		});
	</script>
	<?php
}
add_action( 'wp_footer', 'load_owl_carousel', 99 );

// Register Sidebar
add_action( 'widgets_init', 'kv_widgets_init' );
function kv_widgets_init() {
	$sidebar_attr = array(
		'name' 			=> '',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => '</li>',
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => '</h2>',
	);	
	$sidebar_id = 0;
	$gdl_sidebar = array("Blog", "Footer 1", "Footer 2", "Footer 3");
	foreach( $gdl_sidebar as $sidebar_name ){
		$sidebar_attr['name'] = $sidebar_name;
		$sidebar_attr['id'] = 'custom-sidebar' . $sidebar_id++ ;
		register_sidebar($sidebar_attr);
	}
}

// Register Navigation
function register_menu() {
	register_nav_menu('main-menu',__( 'Main Menu' ));
	register_nav_menu('mob-menu',__( 'Mobile Menu' ));
}
add_action( 'init', 'register_menu' );

// Image Crop
function codex_post_size_crop() {
	add_image_size("packages_image", 300, 200, true);
}
add_action("init", "codex_post_size_crop");

// Featured Image Function
add_theme_support( 'post-thumbnails' );

// Woocommerce Support
add_theme_support('woocommerce');
add_theme_support( 'wc-product-gallery-lightbox' );

// Allow SVG Upload
function my_theme_custom_upload_mimes( $existing_mimes ) {
	$existing_mimes['svg'] = 'image/svg+xml';
// Return the array back to the function with our added mime type.
	return $existing_mimes;
}
add_filter( 'mime_types', 'my_theme_custom_upload_mimes' );

function my_custom_mime_types( $mimes ) {

// New allowed mime types.
	$mimes['svg'] = 'image/svg+xml';
	$mimes['svgz'] = 'image/svg+xml';
	$mimes['doc'] = 'application/msword';

// Optional. Remove a mime type.
	unset( $mimes['exe'] );

	return $mimes;
}
add_filter( 'upload_mimes', 'my_custom_mime_types' );


//Custom Post Types
add_action( 'init', 'create_post_type' );
function create_post_type() {
//Services
	register_post_type( 'our_services',
		array(
			'labels' => array(
				'name' => __( 'Services' ),
				'singular_name' => __( 'Services' )
			),
			'supports' => array(
				'title',
				'editor',
				'thumbnail',
				'excerpt',
			),
			'public' => true,
			'has_archive' => true,
			'menu_icon' => 'dashicons-admin-generic',
			'rewrite' => array( 'slug' => 'service' )
		)
	);

//Testimonials
	register_post_type( 'our_testimonials',
		array(
			'labels' => array(
				'name' => __( 'Testimonials' ),
				'singular_name' => __( 'Testimonials' )
			),
			'supports' => array(
				'title',
				'editor',
				'thumbnail'
			),
			'public' => true,
			'has_archive' => true,
			'publicly_queryable'  => false,
			'menu_icon' => 'dashicons-format-quote',
			'rewrite' => array( 'slug' => 'testi' )
		)
	);

//Packages
	register_post_type( 'packages',
		array(
			'labels' => array(
				'name' => __( 'Packages' ),
				'singular_name' => __( 'Packages' )
			),
			'supports' => array(
				'title',
				'editor',
				'thumbnail'
			),
			'public' => true,
			'has_archive' => true,
			'publicly_queryable'  => true,
			'menu_icon' => 'dashicons-list-view',
			'rewrite' => array( 'slug' => 'package' )
		)
	);

//Founders
	register_post_type( 'founders',
		array(
			'labels' => array(
				'name' => __( 'Founders' ),
				'singular_name' => __( 'Founders' )
			),
			'supports' => array(
				'title',
				'editor',
				'thumbnail',
				'excerpt',
			),
			'public' => true,
			'has_archive' => true,
			'publicly_queryable'  => true,
			'menu_icon' => 'dashicons-groups',
			'rewrite' => array( 'slug' => 'founders' )
		)
	);

	flush_rewrite_rules();
}

//Products Carousel Shortcode
add_shortcode('our_services', 'codex_our_services');
function codex_our_services() {
	ob_start();
	wp_reset_postdata();
	?>
	<div class="slick our-services">
		<?php
		$arg = array(
			'post_type' => 'our_services',
			'posts_per_page' => -1,
		);
		$po = new WP_Query($arg);
		?>
		<?php if ($po->have_posts() ): ?>        
			<?php while ( $po->have_posts() ) : ?>
				<?php 
				$po->the_post();
	   //       $product = wc_get_product();
			 // $price = $product->get_price_html();
				?> 		
				<div class="item">
					<div class="featured-img">
						<?php #echo get_the_post_thumbnail(get_the_ID(), ''); ?>
						<?php
							$box_image = get_field('box_image');
							if($box_image){
								echo '<img src="'.esc_url($box_image['url']).'" alt="'.esc_attr($box_image['alt']).'"/>';
							}else{
								echo get_the_post_thumbnail(get_the_ID(), '');
							}
						?>
					</div>
					<div class="service-icon"><img src="<?php the_field('icon'); ?>"></div>
					<h4><?php the_title();?></h4>
					<p><?php echo wp_trim_words( get_the_excerpt(), 9 ); ?></p>
					<span class="price"><?php echo $price; ?></span>
					<a href="<?php the_permalink(); ?>"><i class="fa-solid fa-arrow-right-long"></i></a>
				</div>
			<?php endwhile; ?>
		<?php  endif; ?>
	</div>
	<?php
	wp_reset_postdata();
	return ''.ob_get_clean();	
}


//Product Servcies Page Shortcode
add_shortcode('our_services_full', 'codex_our_services_full');
function codex_our_services_full() {
	ob_start();
	wp_reset_postdata();
	?>
	<div class="our-ser-wrapper">
		<?php $query = new WP_Query( array('post_status' => 'publish', 'post_type' => 'our_services', 'posts_per_page' => -1, 'order'  => 'DESC')); ?>
		<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); #$product = wc_get_product(); $price = $product->get_price_html(); ?>		
			<div class="our-ser-container">
				<div class="featured-img"><?php echo get_the_post_thumbnail(get_the_ID(), ''); ?></div>
				<div class="service-icon"><img src="<?php the_field('icon'); ?>"></div>
				<h4><?php the_title();?></h4>
				<p><?php echo wp_trim_words( get_the_excerpt(), 8 ); ?></p>
				<span class="price"><?php echo $price; ?></span>
				<a href="<?php the_permalink(); ?>"><i class="fa-solid fa-arrow-right-long"></i></a>
			</div>
		<?php endwhile; endif; ?>
	</div>
	<?php
	wp_reset_postdata();
	return ''.ob_get_clean();	
}


// Our Leaders Homepage
#add_shortcode('our_leaders', 'codex_our_leaders');
function codex_our_leaders() {
	ob_start();
	$leaders = get_field('leaders', 'option');
	?>
	<div class="slick our-leaders">
		<?php foreach( $leaders as $leader ) {
			$image = $leader['image'];
			$title = $leader['title'];
			$designation = $leader['designation'];
			?>
			<div class="item">
				<?php echo '<img src="' . $image . '">'; ?>
				<div class="leader-info">
					<?php
					echo '<h4>'.$title.'</h4>';
					echo '<p>'.$designation.'</p>';

					?>
				</div>
			</div>
		<?php } ?>
	</div>
	<?php
	return ''.ob_get_clean();	
}

// Our Leaders Updated
add_shortcode('our_leaders', 'codex_our_leaders_upd');
function codex_our_leaders_upd() {
	ob_start();
	wp_reset_postdata();

	$founder = new WP_Query(
		array(
			'post_type' => 'founders',
			'posts_per_page' => -1,
			'post_status' => 'publish'
		),
	);

	?>
	<div class="slick our-leaders">
		<?php 
			if($founder->have_posts()) : while($founder->have_posts()) : $founder->the_post();
		?>
			<div class="item">
				<a href="<?php echo get_the_permalink(); ?>">
					<?php echo '<img src="' . get_the_post_thumbnail_url() . '">'; ?>
					<div class="leader-info">
						<?php
						echo '<h4>'.get_the_title().'</h4>';
						echo '<p>'.get_field('founder_designation').'</p>';

						?>
					</div>
				</a>
			</div>
		<?php endwhile; endif; ?>
	</div>
	<?php
	wp_reset_postdata();
	return ''.ob_get_clean();	
}


//Latest Blog Homepage Shortcode
add_shortcode('home_blog', 'codex_home_blog');
function codex_home_blog() {
	ob_start();
	wp_reset_postdata();
	?>    
	<div class="hb-wrapper">         
		<?php $query = new WP_Query( array('post_status' => 'publish', 'post_type' => '', 'posts_per_page' => 3, 'order'  => 'DESC')); ?>
		<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>	
			<div class="hb-container">
				<div class="thumbnail"><?php echo get_the_post_thumbnail( get_the_ID(), '' );?></div>
				<div class="date"><?php echo get_the_time('d M Y', $custom_post->ID); ?></div>
				<div class="title"><h4><?php the_title();?></h4></div>
				<div class="more"><a href="<?php the_permalink(); ?>">Read Article</a></div>
			</div>
		<?php endwhile; endif; ?>
	</div>
	<?php
	wp_reset_postdata();
	return ''.ob_get_clean();
}


//Testimonials Homepage
add_shortcode('our_testimonials', 'codex_our_testimonials');
function codex_our_testimonials() {
	ob_start();
	wp_reset_postdata();
	?>
	<div class="slick our-testimonials">
		<?php
		$arg = array(
			'post_type' => 'our_testimonials',
			'posts_per_page' => -1,	
		);
		$po = new WP_Query($arg);
		?>
		<?php if ($po->have_posts() ): ?>        
			<?php while ( $po->have_posts() ) : ?>
				<?php $po->the_post(); ?> 		
				<div class="item">
					<div class="testi-top">
						<div class="image"><?php the_post_thumbnail(get_the_ID(), ''); ?></div>
						<div class="title">
							<h4><?php the_field('name'); ?></h4>
							<p><?php the_field('designation'); ?></p>
						</div>
					</div>
					<div class="testi-bottom">
						<h4><?php the_title(); ?></h4>
						<div class="rating">
							<span class="<?php the_field('star_rating'); ?>"></span>
						</div>
						<p><?php echo get_the_content(); ?></p>
					</div>
				</div>
			<?php endwhile; ?>
		<?php  endif; ?>
	</div>
	<?php
	wp_reset_postdata();
	return ''.ob_get_clean();	
}


//Latest Blog Sidebar Shortcode
add_shortcode('latest_blog', 'codex_latest_blog');
function codex_latest_blog() {
	ob_start();
	wp_reset_postdata();
	?>    
	<div class="lb-wrapper">         
		<?php $query = new WP_Query( array('post_status' => 'publish', 'post_type' => '', 'posts_per_page' => 3, 'order'  => 'DESC')); ?>
		<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>	
			<div class="lb-container">
				<div class="thumbnail"><a href="<?php the_permalink(); ?>"><?php echo get_the_post_thumbnail( get_the_ID(), '' );?></a></div>
				<div class="date"><?php echo get_the_time('d M Y', $custom_post->ID); ?></div>
				<p><?php echo wp_trim_words( get_the_content(), 8 ); ?></p>
			</div>
		<?php endwhile; endif; ?>
	</div>
	<?php
	wp_reset_postdata();
	return ''.ob_get_clean();
}


// Package Shortcode
add_shortcode('our_packages', 'codex_our_packages');
function codex_our_packages() {

	$packages_content = get_field('packages_content', 'option');
	// echo "<pre>";
	// print_r($packages_content);
	// echo "</pre>";
	// die();
	?>
	<script>
		let jq = jQuery;

		jq(document).on("click", ".package-btn", function () {
			let PKGWrap = jq('.packages-wrapper'),
			WPWrap = jq('.wpb_wrapper'),
			PKGCtrl = jq(this).parents('.packages-container'),
			PKGTitle = PKGCtrl.find('.package-content').find('h4 a'),
			PKGDur = PKGCtrl.find('.package-duration'),
			PKGPr = PKGDur.find('span').eq(0);

			jq('#input_3_18').val(PKGTitle.text());
			jq('#input_3_22').val(PKGTitle.text());
			jq('#input_3_22').attr('disabled','disabled');
			jq('#ginput_base_price_3_20').val(PKGPr.text());

		});

	</script>
	<?php 	ob_start(); ?>
	<div class="packages-wrapper">
		<?php foreach( $packages_content as $packages_content ) {
			$title = $packages_content['title'];
			$sub_title = $packages_content['sub_title'];
			$price = $packages_content['price'];
			$duration = $packages_content['duration'];
			$link = $packages_content['link'];
			$description = $packages_content['description'];
			$button_text = $packages_content['button_text'];
			$popup_class = $packages_content['popup_class'];
			?>
			<div class="packages-container">
				<div class="title <?php echo $title; ?>"><?php echo $title; ?></div>
				<div class="package-content">
					<h4><a href="<?php echo $link; ?>"><?php echo $sub_title; ?></a></h4>
					<div class="package-duration">
						<span><?php echo $price; ?></span> <span>/</span> <span><?php echo $duration; ?></span>
					</div>
					<div class="content"><?php echo $description; ?></div>
					<a class="package-btn <?php echo $popup_class; ?>" href="<?php if(!$popup_class){ echo $link; }else { echo 'javascript:;'; } ?>" <?php if($popup_class){ echo "data-popup-open='$popup_class'"; } ?>><?php echo $button_text; ?></a>
				</div>
			</div>
		<?php } ?>
	</div>
	<?php
	return ''.ob_get_clean();	
}
function pkg_data() {
	$parts = parse_url($url);
	// parse_str($parts['query'], $query);
	echo $parts;
	// die;
	?>
	<script>
		let jq = jQuery;
		jq(document).on("load", ".wpb_wrapper .pkg_btn", function () {
			let	WPtitle = jq('.wpb_wrapper').find('.vc_custom_heading') ,
			WPPrice = jq('.package-price .wpb_wrapper h2');		
			jq('#input_3_22').attr('disabled','disabled');
			jq('#input_3_22').val(WPtitle.eq(0).text());
			jq('#input_3_19').val(WPPrice.text());
			
		});
	</script>

<?php
}
add_action( 'wp_footer', 'pkg_data' );

add_filter('gform_field_value', 'populate_fields', 10, 3);
function populate_fields($value, $field, $name)
{
		if ($field['cssClass'] == 'package-name') {
			$values = array(
				'package_name' => the_title(),
			);
		}
	return isset($values[$name]) ? $values[$name] : $value;
}

// add_action( 'gform_after_submission_3', 'post_to_third_party', 10, 2 );
function post_to_third_party( $entry, $form ) {
 
    $endpoint_url = 'https://pay.gocardless.com/';
    print_r($form);
    print_r($entry);
    die();
    // $body = array(
    //     'first_name' => rgar( $entry, '1.3' ),
    //     'last_name' => rgar( $entry, '1.6' ),
    //     'message' => rgar( $entry, '3' ),
    //     );
    // GFCommon::log_debug( 'gform_after_submission: body => ' . print_r( $body, true ) );
 
    // $response = wp_remote_post( $endpoint_url, array( 'body' => $body ) );
    // GFCommon::log_debug( 'gform_after_submission: response => ' . print_r( $response, true ) );
}