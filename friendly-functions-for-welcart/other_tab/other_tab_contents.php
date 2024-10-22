<?php
/* Template Name: その他のプラグイン・テーマ タブ */

if(!defined('ABSPATH')) exit;

/* プラグインコンテンツ */
function mainichi_web_other_plugins_contents($output)
{
	ob_start();

	$listArray = array();
	$list = '';

	if(MAINICHI_WEB_THIS_PLUGIN_NAME != 'friendly-functions-for-welcart'){
		$listArray[] = array(
			'image'       => FRIENDLY_FUNCTIONS_FOR_WELCART__PLUGIN_URL . 'other_tab/img/logo-ffw_square_350.png',
			'title'       => 'Friendly Functions for Welcart',
			'description' => esc_html__('This is a plugin that allows you to customize a small part of the WordPress cart plugin, Welcart.', MAINICHI_WEB_THIS_PLUGIN_NAME), //WordPressのカートプラグイン「Welcart」のちょっとした部分をカスタマイズできるプラグインです。
			'link'        => 'https://mainichi-web.com/how-to-friendly-functions-for-welcart/'
		);
	}

	if(MAINICHI_WEB_THIS_PLUGIN_NAME != 'text-changeer-for-welcart'){
		$listArray[] = array(
			'image'       => FRIENDLY_FUNCTIONS_FOR_WELCART__PLUGIN_URL . 'other_tab/img/logo-tcw_square_350.png',
			'title'       => 'Text Changer for Welcart',
			'description' => esc_html__('This is a plugin that allows you to change the text in the cart/member page output by the WordPress cart plugin "Welcart".', MAINICHI_WEB_THIS_PLUGIN_NAME), //WordPressのカートプラグイン「Welcart」が出力するカート・メンバーページ内のテキストを変更できるプラグインです。
			'link'        => 'https://mainichi-web.com/how-to-text-changer-for-welcart/',
		);
	}

	if(MAINICHI_WEB_THIS_PLUGIN_NAME != 'mainichi-shopify-products-connect'){
		$listArray[] = array(
			'image'       => FRIENDLY_FUNCTIONS_FOR_WELCART__PLUGIN_URL . 'other_tab/img/logo-cps_square_350.png',
			'title'       => 'Connect Products with Shopify ',
			'description' => esc_html__('This plugin retrieves all products from Shopify and automatically registers them as a custom post in WordPress.', MAINICHI_WEB_THIS_PLUGIN_NAME), //Shopifyの全商品を取得しWordPressにカスタム投稿として一括自動登録するプラグインです。
			'link'        => 'https://mainichi-web.com/how-to-connect-products-with-shopify/'
		);
	}

	foreach ($listArray as $key => $val){
		$list .= mainichi_web_other_plugins_and_theme_contents_list_item($val['image'],$val['title'],$val['description'],$val['link'],'return');
	}

	echo $list;

	$html = ob_get_clean();

	if($output == 'echo'){
		echo $html;
	}else{
		return $html;
	}
}

/* テーマコンテンツ */
function mainichi_web_other_theme_contents($output)
{
	ob_start();

	$listArray = array();
	$list = '';

	$listArray[] = array(
		'image'       => FRIENDLY_FUNCTIONS_FOR_WELCART__PLUGIN_URL . 'other_tab/img/logo-tfs_square_350.png',
		'title'       => esc_html__('Welcart Theme', MAINICHI_WEB_THIS_PLUGIN_NAME).'「Twentyfour Seven」', //Welcart対応テーマTwentyfour Seven
		'description' => esc_html__('This is a "ready-to-open" online store building theme.', MAINICHI_WEB_THIS_PLUGIN_NAME).'<br>'.esc_html__('It is produced based on Welcart operation experience and sales know-how, and also enhances "speed opening, customizability, SEO, and fast site display".', MAINICHI_WEB_THIS_PLUGIN_NAME), //「すぐオープンできる」オンラインショップ構築テーマです。Welcart運営経験や販売ノウハウを元に製作し、「スピード開設・カスタマイズ性・SEO・サイト高速表示」の強化も図っています。
		'link'        => 'https://mainichi-web.com/twentyfour-seven/'
	);

	foreach ($listArray as $key => $val){
		$list .= mainichi_web_other_plugins_and_theme_contents_list_item($val['image'],$val['title'],$val['description'],$val['link'],'return');
	}

	echo $list;

	$html = ob_get_clean();

	if($output == 'echo'){
		echo $html;
	}else{
		return $html;
	}
}

/* サービスコンテンツ */
function mainichi_web_other_service_contents($output)
{
	ob_start();

	$listArray = array();
	$list = '';

	$listArray[] = array(
		'image'       => FRIENDLY_FUNCTIONS_FOR_WELCART__PLUGIN_URL . 'other_tab/img/logo-pnp_square_350.png',
		'title'       => esc_html__('Push Notification Introduction Pack', MAINICHI_WEB_THIS_PLUGIN_NAME), //プッシュ通知機能導入パック
		'description' => esc_html__('This is an introduction package that "converts" your website into an application and enables "push notifications" to be sent.', MAINICHI_WEB_THIS_PLUGIN_NAME), //運営中のWEBサイトを「アプリ化」し「プッシュ通知」を送信可能する導入パックです。
		'link'        => 'https://mainichi-web.com/push-notification/?campaign=ffw'
	);

	foreach ($listArray as $key => $val){
		$list .= mainichi_web_other_plugins_and_theme_contents_list_item($val['image'],$val['title'],$val['description'],$val['link'],'return');
	}

	echo $list;

	$html = ob_get_clean();

	if($output == 'echo'){
		echo $html;
	}else{
		return $html;
	}
}

/* コンテンツリストアイテム */
function mainichi_web_other_plugins_and_theme_contents_list_item($image,$title,$description,$link,$output)
{
	ob_start();
?>
<div class="contentsListItem col3to1">
	<div class="plugin-card-top flex flexBetween flexCenterColumn">
		<div class="width22_5P"><img src="<?php echo $image; ?>" class="width100P" width="1" height="1" alt="<?php echo $title; ?>" decoding="async" loading="lazy"></div>
		<div class="width72_5P">
			<h3><?php echo $title; ?></h3>
			<p><?php echo $description; ?></p>
			<div class="flex flexEndWrap marginBottom8">
				<a href="<?php echo $link; ?>" class="install-now button" target="_blank" rel="noopener"><?php esc_html_e('Detail page', MAINICHI_WEB_THIS_PLUGIN_NAME); //詳細ページ ?></a></div>
		</div>
	</div>
</div>
<?php
	$html = ob_get_clean();

	if($output == 'echo'){
		echo $html;
	}else{
		return $html;
	}
}
?>