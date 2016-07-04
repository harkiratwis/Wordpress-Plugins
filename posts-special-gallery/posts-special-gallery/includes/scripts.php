<?php
// Register style sheet.
add_action( 'wp_enqueue_scripts', 'register_plugin_styles' );

/**
 * Register style sheet.
 */
function register_plugin_styles() {
	//JS
	wp_register_script( 'magnific-popup', plugins_url( 'posts-special-gallery/includes/js/jquery.magnific-popup.js' ));
	//CSS
	wp_register_style( 'posts-special-gallery', plugins_url( 'posts-special-gallery/includes/css/special-image-gallery.css' ) );
	wp_enqueue_style( 'posts-special-gallery' );
	wp_enqueue_script( 'magnific-popup' );
}

/**
 * JS
 *
 * @since 1.0
 */
function special_image_gallery_js() {
	global $post;
	?>
	<script>
	jQuery(document).ready(function() {
		jQuery('a.spl-gallery-link').each(function() {
			var _this = jQuery(this),
				eclass = (jQuery(this).data('class') ? jQuery(this).data('class') : ''),
				items = [],
				target = jQuery( _this.attr('href') );
				target.find('.special-gallery-content').each(function() {
					items.push({
						src: jQuery(this) 
					});
				});
			
			_this.on('click', function() {
				jQuery.magnificPopup.open({
					midClick: true,
					mainClass: 'mfp ' + eclass,
					alignTop: true,
					closeBtnInside: true,
					items: items,
					gallery: {
						enabled: true
					},
					closeMarkup: '<button title="%title%" class="mfp-close"></button>',
					callbacks: {
						open: function() {
							jQuery(".lightbox-close").on('click',function(){
								jQuery.magnificPopup.instance.close();
								return false;           
							});
							
							jQuery(".arrow.prev").on('click',function(){
								jQuery.magnificPopup.instance.prev();
								return false;           
							});
							
							jQuery(".arrow.next").on('click',function(){
								jQuery.magnificPopup.instance.next();
								return false;
							});
						}
					}
				});
				return false;
			});
		});
		jQuery('.next,.prev').click(function(){
			var post = "<?php echo $post->ID;?>";
			var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
			jQuery.post(
				ajaxurl, 
				{
					'action':	'ga_send_pageview_new',
					'data':	post
				}, 
				function(response){
					//alert(response);
				}
			);
		});
	});
	
	</script>
<?php }
add_action( 'wp_footer', 'special_image_gallery_js', 20 );
add_action( 'wp_ajax_ga_send_pageview_new', 'ga_send_pageview_new' );
add_action( 'wp_ajax_nopriv_ga_send_pageview_new', 'ga_send_pageview_new' );

//Send Pageview F2unction for Server-Side Google Analytics
function ga_send_pageview_new() {
	$post = $_REQUEST['data'];
	$page = post_permalink($post);
	$title = get_the_title($post);
	$hostname = get_option( 'hostname_gallery' );
	$tid = get_option( 'ga_gallery' );
	$data = array(
		'v' => 1,
		'tid' => $tid, //@TODO: Change this to your Google Analytics Tracking ID.
		'cid' => gaParseCookieFun(),
		't' => 'pageview',
		'dh' => $hostname, //Document Hostname "gearside.com"
		'dp' => $page, //Page "/something"
		'dt' => $title //Title
	);
	gaSendDataFun($data);
	die();
}
function gaSendDataFun($data) {
	$getString = 'https://ssl.google-analytics.com/collect';
	$getString .= '?payload_data&';
	$getString .= http_build_query($data);
	$result = wp_remote_get($getString);
	return $result;
}
//Parse the GA Cookie
function gaParseCookieFun() {
	if (isset($_COOKIE['_ga'])) {
		list($version, $domainDepth, $cid1, $cid2) = explode('.', $_COOKIE["_ga"], 4);
		$contents = array('version' => $version, 'domainDepth' => $domainDepth, 'cid' => $cid1 . '.' . $cid2);
		$cid = $contents['cid'];
	} else {
		$cid = gaGenerateUUIDFun();
	}
	return $cid;
}

//Generate UUID
//Special thanks to stumiller.me for this formula.
function gaGenerateUUIDFun() {
	return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
		mt_rand(0, 0xffff), mt_rand(0, 0xffff),
		mt_rand(0, 0xffff),
		mt_rand(0, 0x0fff) | 0x4000,
		mt_rand(0, 0x3fff) | 0x8000,
		mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
	);
}

function pw_loading_scripts_wrong() {
	$link = plugins_url( 'posts-special-gallery/includes/js/jscolor.js' );
	echo '<script type="text/javascript" src="'.$link.'"></script>';
}
add_action('admin_head', 'pw_loading_scripts_wrong');