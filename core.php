<?php
class bcc_currency_widget_class extends WP_Widget {
	/**
	* Initialize the wiki widget
	**/
	public function __construct() {
		// This is where we add the style and script
        add_action( 'load-widgets.php', array(&$this, 'bcc_load_color_picker') );
		$widget_options = array( 
			'classname' => 'bcc_currency_widget',
			'description' => 'Display currency converter on your sidebar.',
		);
		parent::__construct( 'bcc_currency_widget', 'Currency Converter Widget', $widget_options );
	}

	/**
	* wiki widget load scripts
	* Method @bcc_load_color_picker
	*/
    function bcc_load_color_picker() {    
        wp_enqueue_style( 'wp-color-picker' );        
        wp_enqueue_script( 'wp-color-picker' );    
    }

	/**
	* wiki widget front-end
	* Method @widget
	*/
	public function widget( $args, $instance ) {
		$title = (isset($instance[ 'title' ])) ? apply_filters( 'widget_title', $instance[ 'title' ] ) : "";
		$width = (isset($instance[ 'width' ])) ? $instance[ 'width' ] : "";
		$height = (isset($instance[ 'height' ])) ? $instance[ 'height' ] : "";
		$font_color = (isset($instance[ 'font_color' ])) ? str_replace("#", "", $instance[ 'font_color' ]) : "";
		$style = (isset($instance[ 'style' ])) ? str_replace("#", "", $instance[ 'style' ]) : "";
		$from = (isset($instance[ 'from' ])) ? $instance[ 'from' ] : "";
		$to = (isset($instance[ 'to' ])) ? $instance[ 'to' ] : "";
		$size = (isset($instance[ 'size' ])) ? $instance[ 'size' ] : "";
		$lang = (isset($instance[ 'lang' ])) ? $instance[ 'lang' ] : "";
		$widget = $args['before_widget'] . $args['before_title'] . $title . $args['after_title'];
		$uniq_id = uniqid();
		
		$atts = array(
			'w' => ($width != "") ? $width : 170,
			'h' => ($height != "") ? $height : 280,
			's' => ($style != "") ? $style : 'D51D29',
			'fc' => ($font_color != "") ? $font_color : 'FFFFFF',
			'f' => ($from != "") ? $from :'USD',
			't' => ($to != "") ? $to : 'EUR',
			'type' => ($size != "") ? $size : 'fix',
			'lang' => ($lang != "") ? $lang : '',
		);

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
                        ypFrame.class = "iframe";
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
		/*============================*/

		$widget .= ob_get_clean();
		echo $widget .= $args['after_widget'];
	}

	/**
	* wiki widget form
	* Method @form
	*/
	public function form( $instance ) {
		?>
		<h2><?php echo _e("Widget Parameters"); ?></h2>
		<?php
		$uniq_id = uniqid();
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$width = ! empty( $instance['width'] ) ? $instance['width'] : '';
		$height = ! empty( $instance['height'] ) ? $instance['height'] : '';
		$font_color = ! empty( $instance['font_color'] ) ? $instance['font_color'] : '';
		$style = ! empty( $instance['style'] ) ? $instance['style'] : '';
		$from = ! empty( $instance['from'] ) ? $instance['from'] : '';
		$to = ! empty( $instance['to'] ) ? $instance['to'] : '';
		$size = ! empty( $instance['size'] ) ? $instance['size'] : '';
		$lang = ! empty( $instance['lang'] ) ? $instance['lang'] : '';
		$from_val = esc_attr( $from );
		$to_val = esc_attr( $to );
		$country_arr = array('error'=>'ERROR-#116: Either bcc-countries.php file is missing Or not present on the include path.');
		if(file_exists(plugin_dir_path(__FILE__) . 'bcc-countries.php')) {
			$country_arr = include('bcc-countries.php');
		}
		$language_arr = array('error'=>'ERROR on line#120 Either bcc-languages.php file is missing or not present on the include path.');
		if(file_exists(plugin_dir_path(__FILE__) . 'bcc-languages.php')) {
			$language_arr = include('bcc-languages.php');
		}
		?>
		<?php if ( !is_customize_preview() ) : ?>
			<p class="note"><?php echo _e("NOTE: Please click on \"Save\" button to select color and/or display a preview."); ?></p>
		<?php endif; ?>
		<table>
			<tr>
				<td>
					<label for="<?php echo $this->get_field_id( 'title' ); ?>" class="currency-bcc-widget-label"><?php echo _e("Title"); ?>:</label>
				</td>
				<td>
					<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo ($title != "") ? esc_attr( $title ) : ''; ?>" style="max-width: 160px;" />
				</td>
			</tr>
			<tr>
				<td>
					<label for="<?php echo $this->get_field_id( 'size' ); ?>" class="currency-bcc-widget-label"><?php echo _e("Type"); ?>:</label>
				</td>
    			<td>
    				<select name="<?php echo $this->get_field_name( 'size' ); ?>" id="<?php echo $this->get_field_id( 'size' ); ?>" class="size-<?php echo $uniq_id; ?>" style="max-width: 160px; min-width: 160px;">
    					<option value="auto" <?php echo ($size == 'auto') ? 'selected' : ''; ?>><?php echo _e("Auto"); ?></option>
    					<option value="fix" <?php echo ($size == 'fix') ? 'selected' : ((!$size)?'selected':''); ?>><?php echo _e("170x280"); ?></option>
    					<option value="custom" <?php echo ($size == 'custom') ? 'selected' : ''; ?>><?php echo _e("Custom"); ?></option>
    				</select>
    			</td>
    		</tr>
			<tr id="width-section-<?php echo $this->get_field_id( 'width' ); ?>" class="width-section-<?php echo $uniq_id; ?>" <?php echo ($size != 'custom') ? "style='display: none;'" : "" ; ?>>
				<td>
					<label for="<?php echo $this->get_field_id( 'width' ); ?>" class="currency-bcc-widget-label"><?php echo _e("Width"); ?>:</label>
				</td>
				<td>
					<input type="text" class="width" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" value="<?php echo ($width != '') ? esc_attr( $width ) : '170'; ?>" />
				</td>
			</tr>
			<tr id="height-section-<?php echo $this->get_field_id( 'height' ); ?>" class="height-section-<?php echo $uniq_id; ?>" <?php echo ($size != 'custom') ? "style='display: none;'" : "" ; ?>>
				<td>
					<label for="<?php echo $this->get_field_id( 'height' ); ?>" class="currency-bcc-widget-label"><?php echo _e("Height"); ?>:</label>
				</td>
				<td>
					<input type="text" class="height" id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" value="<?php echo ($height != '') ? esc_attr( $height ) : '280'; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<label for="<?php echo $this->get_field_id( 'font_color' ); ?>" class="currency-bcc-widget-label"><?php echo _e("Font Color"); ?>:</label>
				</td>
				<td>
					<input type="text" id="<?php echo $this->get_field_id( 'font_color' ); ?>" class="font_color-<?php echo $uniq_id; ?>" name="<?php echo $this->get_field_name( 'font_color' ); ?>" value="<?php echo ($font_color != '') ? esc_attr( $font_color ) : '#FFFFFF'; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<label for="<?php echo $this->get_field_id( 'style' ); ?>" class="currency-bcc-widget-label"><?php echo _e("Style"); ?>:</label>
				</td>
				<td>
					<input type="text" id="<?php echo $this->get_field_id( 'style' ); ?>" class="style-<?php echo $uniq_id; ?>" name="<?php echo $this->get_field_name( 'style' ); ?>" value="<?php echo ($style != '') ? esc_attr( $style ) : '#D51D29'; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<label for="<?php echo $this->get_field_id( 'from' ); ?>" class="currency-bcc-widget-label"><?php echo _e("From"); ?>:</label>
				</td>
				<td>
					<select class="from" id="<?php echo $this->get_field_id( 'from' ); ?>" name="<?php echo $this->get_field_name( 'from' ); ?>" style="max-width: 160px;">
						<?php foreach ($country_arr as $key => $value) : ?>
							<option value="<?php echo $key; ?>" 
								<?php echo ($from_val != "" && $from_val == $key) ? 'selected="selected"' : ('USD' == $key) ? 'selected="selected"' : ""; ?>>
								<?php echo $value; ?>
							</option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<label for="<?php echo $this->get_field_id( 'to' ); ?>" class="currency-bcc-widget-label"><?php echo _e("To"); ?>:</label>
				</td>
				<td>
					<select class="to" id="<?php echo $this->get_field_id( 'to' ); ?>" name="<?php echo $this->get_field_name( 'to' ); ?>" style="max-width: 160px;">
						<?php foreach ($country_arr as $key => $value) : ?>
							<option value="<?php echo $key; ?>"
								<?php echo ($to_val != "" && $to_val == $key) ? 'selected="selected"' : ('EUR' == $key) ? 'selected="selected"' : ""; ?>>
								<?php echo $value; ?>
							</option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
				<tr>
					<td>
						<label for="<?php echo $this->get_field_id( 'lang' ); ?>" class="currency-bcc-widget-label"><?php echo _e("Language"); ?>:</label>
					</td>
					<td>
						<select id="<?php echo $this->get_field_id( 'lang' ); ?>" name="<?php echo $this->get_field_name( 'lang' ); ?>" class="currency-bcc-widget-input lang" style="max-width: 160px; min-width: 160px;">
							<option value="-1"><?php echo _e("English (UK)"); ?></option>
							<?php foreach ($language_arr as $key => $value) : ?>
								<option value="<?php echo $key; ?>"
									<?php echo ($key == $lang) ? 'selected' : ''; ?>>
									<?php echo $value; ?>
								</option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
		</table>
		<?php if ( is_customize_preview() ) : ?>
			<script type="text/javascript">
				jQuery.noConflict();
				jQuery(document).ready(function($) {
					var s = '#'+jQuery('.style-<?php echo $uniq_id; ?>').attr('id');
					var fc = '#'+jQuery('.font_color-<?php echo $uniq_id; ?>').attr('id');
					if (jQuery('.style-<?php echo $uniq_id; ?>').attr('id').replace('widget-bcc_currency_widget-', "").replace('-style',"") != '__i__') {
						jQuery(s).wpColorPicker({
			                change: _.throttle( function () { $(this).trigger('change'); }, 1000, {leading: false} ),
			                width : 150
			            });
					}

					if (jQuery('.font_color-<?php echo $uniq_id; ?>').attr('id').replace('widget-bcc_currency_widget-', "").replace('-font_color',"") != '__i__') {
						jQuery(fc).wpColorPicker({
			                change: _.throttle( function () { $(this).trigger('change'); }, 1000, {leading: false} ),
			                width : 150
			            });
					}
					
				});
				jQuery('.size-<?php echo $uniq_id; ?>').change(function() {
					if (jQuery('.size-<?php echo $uniq_id; ?>').val() == 'custom') {
						jQuery('.width-section-<?php echo $uniq_id; ?>').show();
						jQuery('.height-section-<?php echo $uniq_id; ?>').show();
					} else {
						jQuery('.width-section-<?php echo $uniq_id; ?>').hide();
						jQuery('.height-section-<?php echo $uniq_id; ?>').hide();
					}
				});
			</script>
		<?php else: ?>
			<div id="currency-bcc-<?php echo $uniq_id; ?>" class="currency-bcc">
				<a href="https://www.currency.wiki/" style="display: none;">currency converter</a>
			</div>
			<script type='text/javascript'>
				jQuery.noConflict();
	            jQuery(document).ready(function($) {
	            	$('#<?php echo $this->get_field_id( "style" ); ?>, #<?php echo $this->get_field_id( 'font_color' ); ?>').wpColorPicker({
	            		width : 150
	            	});
		        });

	            var ypFrame = document.createElement("IFRAME");
				function widgetTrigger(ypFrame) {
					var width = jQuery('#<?php echo $this->get_field_id( 'width' ); ?>').val();
					var height = jQuery('#<?php echo $this->get_field_id( 'height' ); ?>').val();
					var font_color = jQuery('#<?php echo $this->get_field_id( 'font_color' ); ?>').val();
					var style = jQuery('#<?php echo $this->get_field_id( 'style' ); ?>').val();
					var from = jQuery('#<?php echo $this->get_field_id( 'from' ); ?>').val();
					var to = jQuery('#<?php echo $this->get_field_id( 'to' ); ?>').val();
					var type = jQuery('#<?php echo $this->get_field_id( 'size' ); ?>').val();
					var lang = jQuery('#<?php echo $this->get_field_id( 'lang' ); ?>').val();
					var langg = (lang != '-1' && typeof lang != 'undefined') ? '&lang='+lang : "";

					var uniqID = "<?php echo $uniq_id; ?>";
					var yp='';
					ypFrame.id = uniqID;
                                        ypFrame.class = "iframe";
					ypFrame.name = uniqID;
					ypFrame.style = "border:0!important;min-width:170px;min-height:300px";
					ypFrame.width = (type == 'custom') ? width+"px" : ((type == 'fix') ? "170px" : "100%");
					ypFrame.height = (type == 'custom') ? height+"px" : ((type == 'fix') ? "300px" : "300px");
					document.getElementById("currency-bcc-"+uniqID).appendChild(ypFrame);
					var ypElem = document.getElementById(uniqID).parentNode.childNodes;
					var l = false;
					var width = (type == 'custom') ? width : ((type == 'fix') ? 170 : 0);
					var height = (type == 'custom') ? height : ((type == 'fix') ? 300 : 300);
					
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
								f:from,
								t:to,
								c:style.replace("#", ""),
								fc:font_color.replace("#", "")
							});
							l=true;
							break;
						}
					}
					if (l && yp) {
						var url = "https://www.currency.wiki/widget/w.php?wd=1&tm="+uniqID+langg;
						url = url.replace(/\"/g, "");
						ypFrame.setAttribute("src", url);
						var w = window.frames[uniqID];
						ypFrame.onload = function() {
							w.postMessage({"t": yp}, "*");
						}
						//ypTmp.parentNode.removeChild(ypTmp);
					}
					else {
						console.log('Something went wrong, please try later.');
					}
				}
				widgetTrigger(ypFrame);

				jQuery('#<?php echo $this->get_field_id( 'size' ); ?>').change(function() {
					if (jQuery('#<?php echo $this->get_field_id( 'size' ); ?>').val() == 'custom') {
						jQuery('#width-section-<?php echo $this->get_field_id( 'width' ); ?>').show();
						jQuery('#height-section-<?php echo $this->get_field_id( 'height' ); ?>').show();
					} else {
						jQuery('#width-section-<?php echo $this->get_field_id( 'width' ); ?>').hide();
						jQuery('#height-section-<?php echo $this->get_field_id( 'height' ); ?>').hide();
					}
				});

	        </script>
		<style>
	        .currency-bcc iframe {border:none; outline: none;}
	    </style>
		<?php endif; ?>
		<style type="text/css">
			/*.wp-picker-input-wrap {
			    display: none !important;
			}*/
			p.note {
				background: yellow;
    			padding: 4px;
			}
		</style>
		<?php
	}

	/**
	* Save the YP widget instances
	* Method @update
	*/
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
		$instance[ 'width' ] = strip_tags( $new_instance[ 'width' ] );
		$instance[ 'height' ] = strip_tags( $new_instance[ 'height' ] );
		$instance[ 'font_color' ] = strip_tags( $new_instance[ 'font_color' ] );
		$instance[ 'style' ] = strip_tags( $new_instance[ 'style' ] );
		$instance[ 'from' ] = strip_tags( $new_instance[ 'from' ] );
		$instance[ 'to' ] = strip_tags( $new_instance[ 'to' ] );
		$instance[ 'size' ] = strip_tags( $new_instance[ 'size' ] );
		$instance[ 'lang' ] = strip_tags( $new_instance[ 'lang' ] );
		return $instance;
	}
}
function register_bcc_currency_widget() { 
	register_widget( 'bcc_currency_widget_class' );
}
add_action( 'widgets_init', 'register_bcc_currency_widget' );