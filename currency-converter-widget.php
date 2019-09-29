<?php
/**
Plugin Name: Currency Converter Widget
Plugin URI: https://www.currencyconverterplugin.com
Description: Super light currency converter widget (Bitcoin included) by Currency.Wiki that offers Easy-To-Select colors to match your website theme with a preview function and not to mention 7 languages to select from - Spanish, Italian, French, Dutch, Hindi, Ukrainian, Russian and more with updates.
Author: Currency Wiki
Author URI: http://bit.ly/2RGFrbp
Version: 2.0.5
*/

/**
 * Adds a new top-level menu to the bottom of the WordPress administration menu.
 */
 if( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

 if(file_exists(plugin_dir_path(__FILE__) . 'bcc-widget.php')) {
	include('bcc-widget.php');
}

function bcc_create_menu_page() {
 
    add_menu_page('Currency Tool', 'Currency Tool', 'administrator', 'currency-bcc', 'bcc_menu_page_display', '');
 
} // end bcc_create_menu_page
add_action('admin_menu', 'bcc_create_menu_page');
 
/**
 * Renders the basic display of the menu page for the theme.
 */
function bcc_menu_page_display() {
    ob_start();
    wp_enqueue_style( "bcc_style", plugins_url( 'css/admin/bcc.css', __FILE__ ) );
	wp_enqueue_style( 'wp-color-picker' );        
    wp_enqueue_script( 'wp-color-picker' );
	$country_arr = array('error'=>'ERROR on line#28: Either bcc-countries.php file is missing or not present on the include path.');
	if(file_exists(plugin_dir_path(__FILE__) . 'bcc-countries.php')) {
		$country_arr = include('bcc-countries.php');
	}

	$language_arr = array('error'=>'ERROR on line#32 Either bcc-languages.php file is missing or not present on the include path.');
	if(file_exists(plugin_dir_path(__FILE__) . 'bcc-languages.php')) {
		$language_arr = include('bcc-languages.php');
	}
	wp_register_script( 'bcc_script', plugins_url( 'js/admin/bcc.js', __FILE__ ) );
	$uniq_id = uniqid();
	?>
	<div class="wrap">
    	<h1 class="wp-heading-inline"><?php _e("Widget Parameters"); ?></h1>
    	<hr class="wp-header-end">
    	<div class="col-md-6">
	    	<table>
	    		<tr>
	    			<td colspan="2">
	    				<div class="size input-block">
						    <label for="size-auto"><input type="radio" name="size" id="size-auto" value="auto"><?php echo _e("Auto"); ?></label>
						    <label for="size-fix"><input type="radio" name="size" id="size-fix" value="fix" checked="checked"><?php echo _e("170x280"); ?></label>
						    <label for="size-custom"><input type="radio" name="size" id="size-custom" value="custom"><?php echo _e("Custom"); ?></label>
						</div>
	    			</td>
	    		</tr>
				<tr id="width-section" style="display: none;">
					<td>
						<label for="width" class="currency-bcc-widget-label bcc-width"><?php echo _e("Width"); ?>:</label>
					</td>
					<td>
						<input type="text" class="currency-bcc-widget-input" id="width" name="width" value="170" />
					</td>
				</tr>
				<tr id="height-section" style="display: none;">
					<td>
						<label for="height>" class="currency-bcc-widget-label bcc-height"><?php echo _e("Height"); ?>:</label>
					</td>
					<td>
						<input type="text" class="currency-bcc-widget-input" id="height" name="height>" value="280" />
					</td>
				</tr>
				<tr>
					<td>
						<label for="font_color" class="currency-bcc-widget-label"><?php echo _e("Font Color"); ?>:</label>
					</td>
					<td>
						<input type="text" class="currency-bcc-widget-input bcc-font-color" id="font_color" name="font_color" value="#FFFFFF" />
					</td>
				</tr>
				<tr>
					<td>
						<label for="style" class="currency-bcc-widget-label"><?php echo _e("Style"); ?>:</label>
					</td>
					<td>
						<input type="text" class="currency-bcc-widget-input bcc-style" id="style" name="style" value="#D51D29" />
					</td>
				</tr>
				<tr>
					<td>
						<label for="from" class="currency-bcc-widget-label"><?php echo _e("From"); ?>:</label>
					</td>
					<td>
						<select name="from" class="currency-bcc-widget-input bcc-from" id="from">
							<?php foreach ($country_arr as $key => $value) : ?>
								<option value="<?php echo $key; ?>"
								<?php echo ("USD" == $key) ? 'selected="selected"' : ""; ?>>
									<?php echo $value; ?>
								</option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<label for="to" class="currency-bcc-widget-label"><?php echo _e("To"); ?>:</label>
					</td>
					<td>
						<select name="to" class="currency-bcc-widget-input bcc-to" id="to">
							<?php foreach ($country_arr as $key => $value) : ?>
								<option value="<?php echo $key; ?>" 
									<?php echo ("EUR" == $key) ? 'selected="selected"' : ""; ?>>
									<?php echo $value; ?>
								</option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<label for="lang" class="currency-bcc-widget-label"><?php echo _e("Language"); ?>:</label>
					</td>
					<td>
						<select name="lang" class="currency-bcc-widget-input bcc-lang" id="lang">
							<option value="-1"><?php echo _e("English (UK)"); ?></option>
							<?php foreach ($language_arr as $key => $value) : ?>
								<option value="<?php echo $key; ?>">
									<?php echo $value; ?>
								</option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: right;">
						<button class="currency-bcc-widget-input-preview button button-bcc-widget">
							<?php _e("Preview"); ?>
						</button>
					</td>
				</tr>
			</table>
		</div>
		<div class="col-md-6">
			<div id="currency-bcc-<?php echo $uniq_id; ?>">
				<a href="https://www.currency.wiki/" style="display: none;">currency converter</a>
			</div>
		</div>
		<div class="col-md-12">
			<input type="text" id="shortcode-input" value='[currency_bcc w="170" h="280" s="D51D29" fc="FFFFFF" f="USD" t="EUR" type="fix"]' readonly />
		</div>
	</div>
	<?php

	$arguments = array(
		'time' => time(),
		'uniqID' => $uniq_id
	);

	wp_localize_script( 'bcc_script', 'bcc', $arguments );
	wp_enqueue_script( 'bcc_script' );
	echo ob_get_clean();
} // end bcc_menu_page_display

function currency_bcc_shortcode( $atts ) {
    $atts = array_change_key_case((array)$atts, CASE_LOWER);

	$atts = shortcode_atts( array(
		'w' => 170,
		'h' => 280,
		's' => 'D51D29',
		'fc' => 'FFFFFF',
		'f' => 'USD',
		't' => 'EUR',
		'type' => 'fix',
		'lang' => '',
	), $atts, 'currency_bcc' );

	$uniq_id = uniqid();
	ob_start();
	?>
	<div id="currency-bcc-<?php echo $uniq_id; ?>" class="currency-bcc">
		<a href="https://www.currency.wiki/" style="display: none;">currency converter</a>
	</div>

	<script type="text/javascript">
		jQuery.noConflict();
		var ypFrame = document.createElement("IFRAME");
		function widgetTrigger(ypFrame, type, lang) {
			var langg = (lang != '-1' && typeof lang != 'undefined') ? '&lang='+lang : "";
			var uniqID = '<?php echo $uniq_id; ?>';
			var yp='';
			ypFrame.id = uniqID;
			ypFrame.name = uniqID;
			ypFrame.style = "border:0!important;min-width:170px;min-height:300px";
			ypFrame.width = (type == 'custom') ? "<?php echo $atts['w']; ?>px" : ((type == 'fix') ? "170px" : "100%");
			ypFrame.height = (type == 'custom') ? "<?php echo $atts['h']; ?>px" : ((type == 'fix') ? "300px" : "300px");
			document.getElementById("currency-bcc-"+uniqID).appendChild(ypFrame);
			var ypElem = document.getElementById(uniqID).parentNode.childNodes;
			var l = false;
			var width = (type == 'custom') ? '<?php echo $atts['w']; ?>' : ((type == 'fix') ? 170 : 0);
			var height = (type == 'custom') ? '<?php echo $atts['h']; ?>' : ((type == 'fix') ? 300 : 300);
			for(var i=0;i < ypElem.length;i++) {
				if (ypElem[i].nodeType == 1 
					&& ypElem[i].nodeName == "A" 
					&& ypElem[i].href == "https://www.currency.wiki/" 
					&& !(ypElem[i].rel 
					&& (ypElem[i].rel.indexOf('nofollow') + 1))) {
					var ypTmp = ypElem[i];
					yp=JSON.stringify({
						w:width,
						h:height,
						nodeType:ypElem[i].nodeType,
						nodeName:ypElem[i].nodeName,
						href :ypElem[i].href,
						rel:ypElem[i].rel,
						cd:uniqID,
						f:'<?php echo $atts['f']; ?>',
						t:'<?php echo $atts['t']; ?>',
						c:'<?php echo $atts['s']; ?>',
						fc:'<?php echo $atts['fc']; ?>'
					});
					l=true;
					break;
				}
			}
			if (l && yp) {
				var url = "https://www.currency.wiki/widget/w.php?wd=1&tm="+<?php echo time(); ?>+langg;
				url = url.replace(/\"/g, "");
				ypFrame.setAttribute("src", url);
				var w = window.frames[uniqID];
				ypFrame.onload = function() {
					w.postMessage({"t": yp}, "*");
				}
				ypTmp.parentNode.removeChild(ypTmp);
			}
			else {
				console.log('Something went wrong, please try later.');
			}
		}
		widgetTrigger(ypFrame, '<?php echo $atts['type']; ?>', '<?php echo $atts['lang']; ?>');
	</script>
	<style>
        .currency-bcc iframe {border:none; outline: none;}
    </style>
	<?php

	$html = ob_get_clean();

	return $html;
}
add_shortcode( 'currency_bcc', 'currency_bcc_shortcode' );
