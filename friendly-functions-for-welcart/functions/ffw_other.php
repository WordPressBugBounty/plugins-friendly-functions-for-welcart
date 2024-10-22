<?php
/* Template Name: その他 */

if(!defined('ABSPATH')) exit;

/* 画像アップロード用のタグ出力 */
function friendly_functions_for_welcart_generate_upload_image_tag($name, $value){
?>
<input name="<?php echo $name; ?>" type="text" value="<?php echo $value; ?>" class="regular-text" />
<div class="uplodedButtonWrap">
	<input type="button" name="<?php echo $name; ?>_slect" class="cursorPointer" value="<?php esc_html_e('select', MAINICHI_WEB_THIS_PLUGIN_NAME); //選択 ?>" />
	<input type="button" name="<?php echo $name; ?>_clear" class="cursorPointer" value="<?php esc_html_e('clear', MAINICHI_WEB_THIS_PLUGIN_NAME); //クリア ?>" />
	<div id="<?php echo $name; ?>Thumbnail" class="uplodedThumbnail">
		<?php if ($value): ?>
		<img src="<?php echo $value; ?>" alt="<?php esc_html_e('Image being selected', 'textDomain'); //選択中の画像 ?>" class="ffwUploadImg">
		<?php endif; ?>
	</div>
</div>

<script type="text/javascript">
	(function ($) {
		var custom_uploader;
		var titleText = '<?php esc_html_e('Please select an image', MAINICHI_WEB_THIS_PLUGIN_NAME); //画像を選択してください ?>';
		var buttonText = '<?php esc_html_e('Select image', MAINICHI_WEB_THIS_PLUGIN_NAME); //画像の選択 ?>';
		$("input:button[name=<?php echo $name; ?>_slect]").click(function(e) {
			e.preventDefault();
			if (custom_uploader) {
				custom_uploader.open();
				return;
			}
			custom_uploader = wp.media({
				title: titleText,
				library: {
					type: "image"
				},
				button: {
					text: buttonText
				},
				multiple: false
			});
			custom_uploader.on("select", function() {
				var images = custom_uploader.state().get("selection");
				images.each(function(file){
					$("input:text[name=<?php echo $name; ?>]").val("");
					$("#<?php echo $name; ?>Thumbnail").empty();
					$("input:text[name=<?php echo $name; ?>]").val(file.attributes.sizes.full.url);
					$("#<?php echo $name; ?>Thumbnail").append('<img src="'+file.attributes.sizes.full.url+'" class="ffwUploadImg" />');
				});
			});
			custom_uploader.open();
		});
		//クリアボタンを押した時の処理
		$("input:button[name=<?php echo $name; ?>_clear]").click(function() {
			$("input:text[name=<?php echo $name; ?>]").val("");
			$("#<?php echo $name; ?>Thumbnail").empty();
		});
	})(jQuery);
</script>
<?php
																				}

/* アップ矢印 */
function friendly_functions_for_welcart_up_arrow($output)
{
	ob_start();
?>
<svg version="1.1" class="upArrow" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" x="0px"
	 y="0px" width="338.288px" height="338.288px" viewBox="0 0 338.288 338.288" enable-background="new 0 0 338.288 338.288"
	 xml:space="preserve">
	<path d="M309.717,0.619L87.53,0C62.867,0,50.124,30.007,67.799,47.683l52.21,52.209L8.201,211.702
			 c-10.934,10.934-10.934,28.527,0,39.461l78.924,78.925c10.934,10.934,28.528,10.935,39.462,0L238.395,218.28l52.209,52.208
			 c17.676,17.677,47.684,4.933,47.684-19.73l-0.619-222.187C337.588,13.197,325.09,0.7,309.717,0.619z"/>
</svg>
<?php
	$html = ob_get_clean();

	if($output == 'echo'){
		echo $html;
	}else{
		return $html;
	}
}

/* ダウン矢印 */
function friendly_functions_for_welcart_down_arrow($output)
{
	ob_start();
?>
<svg version="1.1" class="downArrow" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" x="0px"
	 y="0px" width="338.288px" height="338.288px" viewBox="0 0 338.288 338.288" enable-background="new 0 0 338.288 338.288"
	 xml:space="preserve">
	<path d="M337.668,309.717l0.619-222.187c0-24.663-30.008-37.407-47.684-19.73l-52.209,52.208L126.587,8.201
			 c-10.934-10.935-28.528-10.934-39.462,0L8.201,87.125c-10.935,10.934-10.935,28.527,0,39.461l111.809,111.81l-52.21,52.209
			 c-17.676,17.676-4.933,47.684,19.731,47.684l222.187-0.619C325.09,337.588,337.588,325.091,337.668,309.717z"/>
</svg>
<?php
	$html = ob_get_clean();

	if($output == 'echo'){
		echo $html;
	}else{
		return $html;
	}
}

/* 変化なしバー */
function friendly_functions_for_welcart_no_change_arrow($output)
{
	ob_start();
?>
<svg version="1.1" class="noChangeArrow" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" x="0px"
	 y="0px" width="398.053px" height="375px" viewBox="0 0 398.053 375" enable-background="new 0 0 398.053 375"
	 xml:space="preserve">
	<path d="M390.366,165.735L233.694,8.188c-17.439-17.439-47.669-5.231-47.669,19.766v73.835l-158.122,0
			 C12.44,101.789,0,114.229,0,129.693l0,111.615c0,15.463,12.44,27.905,27.904,27.904h158.12l0.001,73.834
			 c-0.001,24.998,30.229,37.205,47.669,19.766l156.672-157.547C401.18,194.337,401.179,176.663,390.366,165.735z"/>
</svg>
<?php
	$html = ob_get_clean();

	if($output == 'echo'){
		echo $html;
	}else{
		return $html;
	}
}

/* jsコード圧縮 */
function friendly_functions_for_welcart_js_simple_minify($js) {
	$js = preg_replace('# //.*#', '', $js);
	$js = str_replace("\r", '', $js);
	$js = str_replace("\n", '', $js);
	$js = str_replace("\t", '', $js);
	return $js;
}

/* 管理画面ファイル読み込み */
function friendly_functions_for_welcart_enqueue_admin_style_script()
{
	global $hook_suffix;
	if($hook_suffix == 'settings_page_ffw_function_settings'){
		wp_enqueue_media(); //メディアアップローダのJavaScript API読み込み
	}
	wp_enqueue_style('ffw_admin-style', FRIENDLY_FUNCTIONS_FOR_WELCART__PLUGIN_URL . 'style_admin.css', array(), date("ymdHis", filemtime(FRIENDLY_FUNCTIONS_FOR_WELCART__PLUGIN_DIR . 'style_admin.css')), 'all');
}
add_action('admin_enqueue_scripts', 'friendly_functions_for_welcart_enqueue_admin_style_script');

/* メニュー「設定」に追加 */
function friendly_functions_for_welcart_create_custom_menu_page(){
	require FRIENDLY_FUNCTIONS_FOR_WELCART__PLUGIN_DIR . 'ffw_function_settings.php';
}
function friendly_functions_for_welcart_add_sub_menu() {
	add_submenu_page('options-general.php', esc_html__('Friendly Functions for Welcart', MAINICHI_WEB_THIS_PLUGIN_NAME), esc_html__('Friendly Functions for Welcart', MAINICHI_WEB_THIS_PLUGIN_NAME), 'administrator', 'ffw_function_settings', 'friendly_functions_for_welcart_create_custom_menu_page', 247 );
}
add_action( 'admin_menu', 'friendly_functions_for_welcart_add_sub_menu' );

/* AjaxリクエストURL */
function friendly_functions_for_welcart_add_my_ajaxurl()
{
?><script>
	var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script><?php
}
?>