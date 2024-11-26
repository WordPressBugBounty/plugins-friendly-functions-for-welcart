<?php
/* Template Name: 機能 */

if (!defined('ABSPATH')) exit;

/* Welcart有効確認 */
include_once(ABSPATH . 'wp-admin/includes/plugin.php');
if (is_plugin_active('usc-e-shop/usc-e-shop.php')) {

	/* 設定情報 */
	$friendlyFunctionsForWelcartData = get_option('friendlyFunctionsForWelcartData');
	if(!empty($friendlyFunctionsForWelcartData)){
		foreach ($friendlyFunctionsForWelcartData as $key => $val) {
			$$key = esc_html($val);
		}
	}

	/* Welcartが吐き出すOGPプロパティーを削除 */
	if(!empty($removeOGP) && $removeOGP == 'yes'){
		remove_action('wp_head', 'usces_action_ogp_meta');
	}

	/* 受注メールの返信先を購入者のアドレスに変更 */
	if(!empty($changeOrderEmail) && $changeOrderEmail == 'yes'){
		function friendly_functions_for_welcart_order_return_path_change($order_para, $entry, $data)
		{
			$order_para['customer_mailaddress'] = $entry['customer']['mailaddress1'];	
			$order_para['customer_name'] = $entry['customer']['name1'].' '.$entry['customer']['name2'];			

			return $order_para;
		}
		add_filter('usces_send_ordermail_para_to_manager', 'friendly_functions_for_welcart_order_return_path_change', 10, 3);

		//ver 2.9.11以降対応用
		function friendly_functions_for_welcart_order_return_path_change_new($phpmailer)
		{
			global $usces;

			if(
				$usces->mail_para['to_name'] == __( 'An order email', 'usces' ) && 
				array_key_exists('customer_mailaddress', $usces->mail_para)
			){
				$customerName = '';
				if(array_key_exists('customer_name', $usces->mail_para)){
					$customerName = sprintf(esc_html__('Dear %s', MAINICHI_WEB_THIS_PLUGIN_NAME), $usces->mail_para['customer_name']); // 様

				}
				$phpmailer->ClearReplyTos();
				$phpmailer->addReplyTo($usces->mail_para['customer_mailaddress'], $customerName);
			}
		}
		add_action('usces_filter_phpmailer_init', 'friendly_functions_for_welcart_order_return_path_change_new', 100, 1);
	}

	/* 購入額による送料割引 */
	if(!empty($shippingDiscountsA) && !empty($shippingDiscountsB)){
		function friendly_functions_for_welcart_filter_set_cart_fees_shipping_charge($shipping_charge, $carts, $entries)
		{
			$friendlyFunctionsForWelcartData = get_option('friendlyFunctionsForWelcartData');
			if($entries['order']['total_items_price'] >= mb_convert_kana($friendlyFunctionsForWelcartData['shippingDiscountsA'], 'n')){
				$shipping_charge -= mb_convert_kana($friendlyFunctionsForWelcartData['shippingDiscountsB'], 'n');
			}
			return $shipping_charge;
		}
		add_filter('usces_filter_set_cart_fees_shipping_charge', 'friendly_functions_for_welcart_filter_set_cart_fees_shipping_charge', 10, 3);
	}

	/* 「FAX番号」の非表示 */
	if((!empty($displayFaxNum) && $displayFaxNum == 'no') || (!empty($removeOrderEmailMemberNo) && $removeOrderEmailMemberNo == 'yes')){
		//購入者宛
		function friendly_functions_for_welcart_send_ordermail_para_to_customer( $confirm_para, $entry, $data ) {
			$friendlyFunctionsForWelcartData = get_option('friendlyFunctionsForWelcartData');
			//会員No.削除
			$removeOrderEmailMemberNo = NULL;
			if(array_key_exists('removeOrderEmailMemberNo', $friendlyFunctionsForWelcartData)){
				$removeOrderEmailMemberNo = esc_html($friendlyFunctionsForWelcartData['removeOrderEmailMemberNo']);
			}
			if(!empty($removeOrderEmailMemberNo) && $removeOrderEmailMemberNo == 'yes'){
				$confirm_para['message'] = preg_replace('/^会員No : .*\n/um','',$confirm_para['message']);
				$confirm_para['message'] = preg_replace('/^Membership Number : .*\n/um','',$confirm_para['message']);
			}
			//FAX番号削除
			$displayFaxNum = NULL;
			if(array_key_exists('displayFaxNum', $friendlyFunctionsForWelcartData)){
				$displayFaxNum = esc_html($friendlyFunctionsForWelcartData['displayFaxNum']);
			}
			if(!empty($displayFaxNum) && $displayFaxNum == 'no'){
				$confirm_para['message'] = preg_replace('/^FAX番号 : .*\n/um','',$confirm_para['message']);
				$confirm_para['message'] = preg_replace('/^Fax Number : .*\n/um','',$confirm_para['message']);
			}
			return $confirm_para;
		}
		add_filter( 'usces_send_ordermail_para_to_customer',  'friendly_functions_for_welcart_send_ordermail_para_to_customer', 10, 3 );
		//管理者宛
		function friendly_functions_for_welcart_send_ordermail_para_to_manager( $bcc_para, $entry, $data ) {
			$friendlyFunctionsForWelcartData = get_option('friendlyFunctionsForWelcartData');
			//会員No.削除
			$removeOrderEmailMemberNo = NULL;
			if(array_key_exists('removeOrderEmailMemberNo', $friendlyFunctionsForWelcartData)){
				$removeOrderEmailMemberNo = esc_html($friendlyFunctionsForWelcartData['removeOrderEmailMemberNo']);
			}
			if(!empty($removeOrderEmailMemberNo) && $removeOrderEmailMemberNo == 'yes'){
				$bcc_para['message'] = preg_replace('/^会員No : .*\n/um','',$bcc_para['message']);
				$bcc_para['message'] = preg_replace('/^Membership Number : .*\n/um','',$bcc_para['message']);
			}
			//FAX番号削除
			$displayFaxNum = NULL;
			if(array_key_exists('displayFaxNum', $friendlyFunctionsForWelcartData)){
				$displayFaxNum = esc_html($friendlyFunctionsForWelcartData['displayFaxNum']);
			}
			if(!empty($displayFaxNum) && $displayFaxNum == 'no'){
				$bcc_para['message'] = preg_replace('/^FAX番号 : .*\n/um','',$bcc_para['message']);
				$bcc_para['message'] = preg_replace('/^Fax Number : .*\n/um','',$bcc_para['message']);
			}
			return $bcc_para;
		}
		add_filter( 'usces_send_ordermail_para_to_manager',  'friendly_functions_for_welcart_send_ordermail_para_to_manager', 10, 3 );
	}
	//FAX番号項目非表示CSS
	if(!empty($displayFaxNum) && $displayFaxNum == 'no'){
		$friendlyFunctionsForWelcartHideFaxNumCss = '#fax_row,#memberinfo #fax_row,.fax-row{display:none;}';
	}else{
		$friendlyFunctionsForWelcartHideFaxNumCss = '';
	}
	update_option('friendlyFunctionsForWelcartHideFaxNumCss',$friendlyFunctionsForWelcartHideFaxNumCss);

	/* 「フリガナ」の非表示 */
	if(!empty($displayFurigana) && $displayFurigana == 'no'){
		function friendly_functions_for_welcart_furigana_customer($furigana_customer, $type, $values ){
			$furigana_customer = '';
			return $furigana_customer;
		}
		add_filter('usces_filter_furigana_form', 'friendly_functions_for_welcart_furigana_customer', 10, 3);
		function friendly_functions_for_welcart_furigana_confirm_customer($furigana_customer, $type, $values){
			$furigana_customer = '';
			return $furigana_customer;
		}
		add_filter('usces_filter_furigana_confirm_customer', 'friendly_functions_for_welcart_furigana_confirm_customer', 10, 3);
		function friendly_functions_for_welcart_furigana_confirm_delivery($furigana_delivery, $type, $values){
			$furigana_delivery = '';
			return $furigana_delivery;
		}
		add_filter('usces_filter_furigana_confirm_delivery', 'friendly_functions_for_welcart_furigana_confirm_delivery', 10, 3);
	}

	/* カートページに送料割引までの金額を表示 */
	if(!empty($shippingDiscountsMessage) && $shippingDiscountsMessage == 'yes'){
		//表示テキスト関数
		function friendly_functions_for_welcart_shipping_discounts_message(){
			global $usces;
			$friendlyFunctionsForWelcartData = get_option('friendlyFunctionsForWelcartData');
			if(!empty($friendlyFunctionsForWelcartData['shippingDiscountsA'])){
				$shippingDiscountsA = mb_convert_kana($friendlyFunctionsForWelcartData['shippingDiscountsA'], 'n');
			}
			if(!empty($friendlyFunctionsForWelcartData['shippingDiscountsB'])){
				$shippingDiscountsB = mb_convert_kana($friendlyFunctionsForWelcartData['shippingDiscountsB'], 'n');
			}
			$totalPrice = esc_html($usces->get_total_price());
			if(!empty($shippingDiscountsA) && !empty($shippingDiscountsB)){
				$num = ($shippingDiscountsA - $totalPrice);
				$postagePrivilegeStyle = 'display: table; margin: 8px 0 8px auto; border: #999 1px solid; padding: 4px 12px; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;';
				if(0 < $num){
					return '<div id="postagePrivilege" style="'.$postagePrivilegeStyle.'">'.sprintf(__('Spend $%1$s more and get $%2$s off shipping', MAINICHI_WEB_THIS_PLUGIN_NAME), number_format($num), $shippingDiscountsB).'</div>'; //あと～円のお買い上げで送料～円OFF
				}elseif($shippingDiscountsA < $totalPrice){
					return '<div id="postagePrivilege" style="'.$postagePrivilegeStyle.'">'.sprintf(__('Purchase over $%1$s so $%2$s off shipping', MAINICHI_WEB_THIS_PLUGIN_NAME), number_format($shippingDiscountsA), $shippingDiscountsB).'</div>'; //～円以上のお買い上げのため送料～円OFF
				}
			}
		}
		function friendly_functions_for_welcart_shipping_discounts_message_filter($header){
			return $header.apply_filters('ffw_filter_shipping_discounts_message', friendly_functions_for_welcart_shipping_discounts_message());
		}
		add_action('usces_filter_cart_page_header', 'friendly_functions_for_welcart_shipping_discounts_message_filter');
		function friendly_functions_for_welcart_shipping_discounts_message_action(){
			echo apply_filters('ffw_filter_shipping_discounts_message', friendly_functions_for_welcart_shipping_discounts_message());
		}
		add_action('usces_action_cart_page_header', 'friendly_functions_for_welcart_shipping_discounts_message_action');
	}

	/* カートページに送料無料までの金額を表示 */
	if(!empty($postagePrivilegeMessage) && $postagePrivilegeMessage == 'yes'){
		//表示テキスト関数
		function friendly_functions_for_welcart_postage_privilege_message(){
			global $usces;
			$usces_options = $usces->options;
			$postagePrivilege = esc_html($usces_options['postage_privilege']);
			$totalPrice = esc_html($usces->get_total_price());
			if(!empty($postagePrivilege)){
				$num = ($postagePrivilege - $totalPrice);
				$postagePrivilegeStyle = 'display: table; margin: 8px 0 8px auto; border: #999 1px solid; padding: 4px 12px; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;';
				if(0 < $num){
					return '<div id="postagePrivilege" style="'.$postagePrivilegeStyle.'">'.sprintf(__('Free shipping with purchase of $%s more', MAINICHI_WEB_THIS_PLUGIN_NAME), number_format($num)).'</div>'; //あと～円のお買い上げで送料無料
				}elseif($postagePrivilege < $totalPrice){
					return '<div id="postagePrivilege" style="'.$postagePrivilegeStyle.'">'.sprintf(__('Free shipping since the purchase is over $%s', MAINICHI_WEB_THIS_PLUGIN_NAME), number_format($postagePrivilege)).'</div>'; //～円以上のお買い上げのため送料無料
				}
			}
		}
		function friendly_functions_for_welcart_postage_privilege_message_filter($header){
			return $header.apply_filters('ffw_filter_postage_privilege_message',friendly_functions_for_welcart_postage_privilege_message());
		}
		add_action('usces_filter_cart_page_header', 'friendly_functions_for_welcart_postage_privilege_message_filter');
		function friendly_functions_for_welcart_postage_privilege_message_action(){
			echo apply_filters('ffw_filter_postage_privilege_message',friendly_functions_for_welcart_postage_privilege_message());
		}
		add_action('usces_action_cart_page_header', 'friendly_functions_for_welcart_postage_privilege_message_action');
	}

	/* 数量入力を「number」にする */
	if((!empty($cartButtonEntryNum) && $cartButtonEntryNum == 'yes') || (!empty($quantityEntryNum) && $quantityEntryNum == 'yes')){
		function friendly_functions_for_welcart_change_quant_input($quant){
			return str_replace('text', 'number', $quant);
		}

		/* カートボタン数量入力を「number」にする */
		if(!empty($cartButtonEntryNum) && $cartButtonEntryNum == 'yes'){
			add_filter( 'usces_filter_the_itemQuant', 'friendly_functions_for_welcart_change_quant_input' );
		}

		/* カートページ数量入力を「number」にする */
		if(!empty($quantityEntryNum) && $quantityEntryNum == 'yes'){
			add_filter('usces_filter_cart_rows_quant', 'friendly_functions_for_welcart_change_quant_input');
		}
	}

	/* 買い物を続けるのリンク先変更 */
	if(!empty($changeLinkCartPrebutton)){
		function friendly_functions_for_welcart_cart_prebutton_redirect(){
			$friendlyFunctionsForWelcartData = get_option('friendlyFunctionsForWelcartData');
			$url = esc_url($friendlyFunctionsForWelcartData['changeLinkCartPrebutton']);
			return ' onclick="location.href=\''.$url.'/\'"';
		}
		add_filter('usces_filter_cart_prebutton', 'friendly_functions_for_welcart_cart_prebutton_redirect');
	}

	/* ログイン後の遷移ページ変更 */
	if(!empty($changeAfterLogin)){
		function friendly_functions_for_welcart_login_redirect(){
			$referer = $_SERVER['HTTP_REFERER'];
			$url_array = parse_url($referer);
			if(preg_match("/usces-cart/", $url_array['path'])){
			}else{
				$friendlyFunctionsForWelcartData = get_option('friendlyFunctionsForWelcartData');
				$url = esc_url($friendlyFunctionsForWelcartData['changeAfterLogin']);
				wp_redirect($url);
				exit;
			}
		}
		add_action('usces_action_after_login', 'friendly_functions_for_welcart_login_redirect');
	}

	/* ログアウト後の遷移ページ変更 */
	if(!empty($changeAfterLogout)){
		function friendly_functions_for_welcart_logout_redirect(){
			$friendlyFunctionsForWelcartData = get_option('friendlyFunctionsForWelcartData');
			$url = esc_url($friendlyFunctionsForWelcartData['changeAfterLogout']);
			wp_redirect($url);
			exit;
		}
		add_action('usces_action_member_logout', 'friendly_functions_for_welcart_logout_redirect');
	}

	if(!empty($conversionTag) || (!empty($onThanksgivingPage) && $onThanksgivingPage == 'yes')){
		function friendly_functions_for_welcart_on_thankspage($tag, $usces_entries, $usces_carts){
			$friendlyFunctionsForWelcartData = get_option('friendlyFunctionsForWelcartData');
			$onThanksgivingPage = $conversionTag = '';
			if(array_key_exists('onThanksgivingPage', $friendlyFunctionsForWelcartData)){
				$onThanksgivingPage = $friendlyFunctionsForWelcartData['onThanksgivingPage'];
			}
			if(array_key_exists('conversionTag', $friendlyFunctionsForWelcartData)){
				$conversionTag = $friendlyFunctionsForWelcartData['conversionTag'];
			}

			$html = '';

			/* サンクスページに注文番号・注文日時・送信先を表示 */
			if(!empty($onThanksgivingPage) && $onThanksgivingPage == 'yes'){
				$orderID = esc_html(usces_get_deco_order_id($usces_entries['order']['ID'])); //注文番号
				$orderDate = date_i18n( 'Y/m/d H:i' ); //受注日時
				$orderHtml = '
							<div id="orderDataBox" style="font-size: 16px; font-weight: bold; margin-top: 40px; margin-right: auto; margin-left: auto; display: table;">
							<div id="orderDate">
							<span>'.esc_html__('Order Date', MAINICHI_WEB_THIS_PLUGIN_NAME).'</span>
							<span style="margin-right: 8px; margin-left: 8px;">'.esc_html__(':', MAINICHI_WEB_THIS_PLUGIN_NAME).'</span>
							<span>'.$orderDate.'</span>
							</div>
							<div id="orderID">
							<span>'.esc_html__('Order Number', MAINICHI_WEB_THIS_PLUGIN_NAME).'</span>
							<span style="margin-right: 8px; margin-left: 8px;">'.esc_html__(':', MAINICHI_WEB_THIS_PLUGIN_NAME).'</span>
							<span>'.$orderID.'</span>
							</div>
							</div>
							'; //受注日時 & 注文番号
				$html .= $orderHtml;
			}

			/* サンクスページにコンバージョンタグ設置 */
			if(!empty($conversionTag)){
				$conversionTagHtml = stripslashes(htmlspecialchars_decode($conversionTag, ENT_QUOTES));

				//購入者のメールアドレス
				$email = esc_html($usces_entries['customer']['mailaddress1']);
				$conversionTagHtml = str_replace('[e-mail]', $email, $conversionTagHtml);
				//購入者の姓
				$familyName = esc_html($usces_entries['customer']['name1']);
				$conversionTagHtml = str_replace('[family-name]', $familyName, $conversionTagHtml);
				//購入者の名
				$firstName = esc_html($usces_entries['customer']['name2']);
				$conversionTagHtml = str_replace('[first-name]', $firstName, $conversionTagHtml);
				//購入者の郵便番号
				$zip = esc_html($usces_entries['customer']['zipcode']);
				$conversionTagHtml = str_replace('[zip-code]', $zip, $conversionTagHtml);
				//購入者の住所
				$address = esc_html($usces_entries['customer']['pref'].$usces_entries['customer']['address1'].$usces_entries['customer']['address2'].' '.$usces_entries['customer']['address3']);
				$conversionTagHtml = str_replace('[address]', $address, $conversionTagHtml);
				//購入者の電話番号
				$tel = esc_html($usces_entries['customer']['tel']);
				$conversionTagHtml = str_replace('[tel]', $tel, $conversionTagHtml);
				//受注日時
				$orderDate = date_i18n( 'Y/m/d H:i' );
				$conversionTagHtml = str_replace('[order-date]', $orderDate, $conversionTagHtml);
				//割引金額
				$discount = esc_html($usces_entries['order']['discount']);
				$conversionTagHtml = str_replace('[discount]', $discount, $conversionTagHtml);
				//送料
				$shippingCharge = esc_html($usces_entries['order']['shipping_charge']);
				$conversionTagHtml = str_replace('[shipping-charge]', $shippingCharge, $conversionTagHtml);
				//代引き手数料
				$codFee = esc_html($usces_entries['order']['cod_fee']);
				$conversionTagHtml = str_replace('[cod-fee]', $codFee, $conversionTagHtml);
				//注文商品合計金額
				$totalItemsPrice = esc_html($usces_entries['order']['total_items_price']);
				$conversionTagHtml = str_replace('[total-items-price]', $totalItemsPrice, $conversionTagHtml);
				//お支払い合計金額
				$totalFullPrice = esc_html($usces_entries['order']['total_full_price']);
				$conversionTagHtml = str_replace('[total-full-price]', $totalFullPrice, $conversionTagHtml);
				//注文番号
				$orderID = esc_html($usces_entries['order']['ID']);
				$conversionTagHtml = str_replace('[order-id]', $orderID, $conversionTagHtml);

				$html .= $conversionTagHtml;
			}
			return $html;
		}
		add_filter('usces_filter_conversion_tracking', 'friendly_functions_for_welcart_on_thankspage', 99, 3);
	}

	/* クーポン機能 */
	if(!empty($couponDiscountsCode) && !empty($couponDiscountsPrice)){
		//クーポンコード入力欄表示
		//入力欄関数
		function friendly_functions_for_welcart_coupon_code_input(){
			return '
					<table class="customer_form">
					<tbody>
					<tr class="customkey_coupon">
					<th scope="row">'.esc_html__('Coupon Code', MAINICHI_WEB_THIS_PLUGIN_NAME).'</th>
					<td colspan="2">
					<input type="text" name="custom_order[coupon]" class="iopt_text" value="">
					<span class="couponText">'.esc_html__('If you have a coupon code, please enter it here.', MAINICHI_WEB_THIS_PLUGIN_NAME).'</span>
					</td>
					</tr>
					</tbody>
					</table>'; //クーポンコード & クーポンコードがある方はこちらにご入力ください。
		}
		function friendly_functions_for_welcart_filter_custom_field_input_filter($html){
			$html .= apply_filters('ffw_filter_coupon_code_input',friendly_functions_for_welcart_coupon_code_input());
			return $html;
		}
		add_filter('usces_filter_delivery_flag', 'friendly_functions_for_welcart_filter_custom_field_input_filter', 10, 1 );
		function friendly_functions_for_welcart_filter_custom_field_input_action(){
			echo apply_filters('ffw_filter_coupon_code_input',friendly_functions_for_welcart_coupon_code_input());
		}
		add_action('usces_action_delivery_flag', 'friendly_functions_for_welcart_filter_custom_field_input_action', 10, 1 );
		//割引設定
		if(!empty($couponDiscountsUnit) && $couponDiscountsUnit == 'price'){ //額で割引
			function friendly_functions_for_welcart_coupon_order_discount($discount, $cart){
				global $usces;
				$friendlyFunctionsForWelcartData = get_option('friendlyFunctionsForWelcartData');
				$entry = $usces->cart->get_entry();
				if(!empty($entry['custom_order']['coupon']) && $entry['custom_order']['coupon'] == $friendlyFunctionsForWelcartData['couponDiscountsCode']){
					$discount = -$friendlyFunctionsForWelcartData['couponDiscountsPrice'];
				}
				return $discount;
			}
			add_filter('usces_order_discount', 'friendly_functions_for_welcart_coupon_order_discount', 10, 2);
		}elseif(!empty($couponDiscountsUnit) && $couponDiscountsUnit == 'percent'){ //%で割引
			function friendly_functions_for_welcart_coupon_order_discount_percent($discount, $cart){
				global $usces;
				$friendlyFunctionsForWelcartData = get_option('friendlyFunctionsForWelcartData');
				$entry = $usces->cart->get_entry();
				$total_items_price = $usces->get_total_price();
				if($entry['custom_order']['coupon'] == $friendlyFunctionsForWelcartData['couponDiscountsCode']){
					$discount = ceil($total_items_price * $friendlyFunctionsForWelcartData['couponDiscountsPrice'] * 0.01 * -1);
				}
				return $discount;
			}
			add_filter('usces_order_discount', 'friendly_functions_for_welcart_coupon_order_discount_percent', 10, 2);
		}
		//「キャンペーン割引」を「クーポン割引」に書き換え
		function friendly_functions_for_welcart_coupon_confirm_discount_label(){
			global $usces;
			$friendlyFunctionsForWelcartData = get_option('friendlyFunctionsForWelcartData');
			$entry = $usces->cart->get_entry();
			if((!empty($entry['custom_order']['coupon']) && $entry['custom_order']['coupon'] == $friendlyFunctionsForWelcartData['couponDiscountsCode']) || (!empty($_REQUEST['order_id']) && $usces->get_order_meta_value('csod_coupon', $_REQUEST['order_id']) == true)){
				$text = esc_html__('Coupon Discount', MAINICHI_WEB_THIS_PLUGIN_NAME); //クーポン割引
			} else {
				$text = __('Campaign disnount', 'usces'); //キャンペーン割引
			}
			return $text;
		}
		add_filter('usces_confirm_discount_label', 'friendly_functions_for_welcart_coupon_confirm_discount_label', 10, 1);
		add_filter('usces_filter_disnount_label', 'friendly_functions_for_welcart_coupon_confirm_discount_label', 10, 1);
		//クーポンコードがエラーの時の表示
		////入力欄関数
		function friendly_functions_for_welcart_coupon_code_error_message(){
			global $usces;
			$friendlyFunctionsForWelcartData = get_option('friendlyFunctionsForWelcartData');
			$entry = $usces->cart->get_entry();
			if($entry['custom_order']['coupon'] == true && $entry['custom_order']['coupon'] != $friendlyFunctionsForWelcartData['couponDiscountsCode']){
				return '<p class="coupon-error" style="color: #f00; display: table; margin: 24px auto 0; text-align: left; line-height: 125%;">'.esc_html__('* The coupon code you entered is invalid. If you wish to reenter the code, please return to the previous screen.', MAINICHI_WEB_THIS_PLUGIN_NAME).'</p>'; //※入力されたクーポンコードは無効です。再入力される場合は前画面に戻って行ってください。
			}
		}
		function friendly_functions_for_welcart_coupon_confirm_page_header_filter($header){
			return $header.apply_filters('ffw_filter_coupon_code_error_message',friendly_functions_for_welcart_coupon_code_error_message());
		}
		add_filter('usces_filter_confirm_page_header', 'friendly_functions_for_welcart_coupon_confirm_page_header_filter', 10, 1);
		function friendly_functions_for_welcart_coupon_confirm_page_header_action(){
			echo apply_filters('ffw_filter_coupon_code_error_message',friendly_functions_for_welcart_coupon_code_error_message());
		}
		add_action('usces_action_confirm_page_header', 'friendly_functions_for_welcart_coupon_confirm_page_header_action', 10, 1);
	}

	/* 発行書類に画像を表示 */
	if(!empty($pdfSignImage)){
		function friendly_functions_for_welcart_filter_pdf_sign(){
			$friendlyFunctionsForWelcartData = get_option('friendlyFunctionsForWelcartData');
			return $friendlyFunctionsForWelcartData['pdfSignImage'];
		}
		add_filter('usces_filter_pdf_estimate_sign', 'friendly_functions_for_welcart_filter_pdf_sign'); //見積書
		add_filter('usces_filter_pdf_invoice_sign', 'friendly_functions_for_welcart_filter_pdf_sign'); //納品書
		add_filter('usces_filter_pdf_receipt_sign', 'friendly_functions_for_welcart_filter_pdf_sign'); //領収書
		add_filter('usces_filter_pdf_bill_sign', 'friendly_functions_for_welcart_filter_pdf_sign'); //請求書
	}

	/* 入力フォームのリアルタイムチェック */
	if(!empty($formErrorCheck) && $formErrorCheck == 'yes'){
		//「head」内にスクリプト出力
		function friendly_functions_for_welcart_validation_engine_script(){
			if(is_page(array('usces-cart','usces-member'))){
?>
<script type="text/javascript">
	jQuery(function(){
		jQuery("#name1, #name2, #zipcode, #customer_pref, #address1, #address2, #tel, #password1, #password2").addClass("validate[required]");
		jQuery("#customer_pref option:first-child").val("");
		jQuery("input[name*='mailaddress1'],input[name*='mailaddress2']").addClass("validate[required,custom[email]]");
		jQuery("form td").css('position', 'relative');
		jQuery(document).ready(function($){
			jQuery("form").validationEngine({
				promptPosition: "topLeft:0"
			});
		});
		jQuery(".back_cart_button, .back_to_customer_button").click(function(){
			jQuery("form").validationEngine('hideAll');
			jQuery("form").validationEngine('detach');
			return true;
		});
	});
</script>
<?php
			}
		}
		add_action('wp_head', 'friendly_functions_for_welcart_validation_engine_script');

		//ファイルの読み込み
		function friendly_functions_for_welcart_enqueue_style_script(){
			if(is_page(array('usces-cart','usces-member'))){
				wp_enqueue_style( 'validationEngine.jquery.css', FRIENDLY_FUNCTIONS_FOR_WELCART__PLUGIN_URL . 'validation-engine-master/css/validationEngine.jquery.css', array(), date("ymdHis", filemtime(FRIENDLY_FUNCTIONS_FOR_WELCART__PLUGIN_DIR . 'validation-engine-master/css/validationEngine.jquery.css')), 'all');
				wp_enqueue_script( 'jquery.validationEngine.js', FRIENDLY_FUNCTIONS_FOR_WELCART__PLUGIN_URL . 'validation-engine-master/js/jquery.validationEngine.js', array(), date("ymdHis", filemtime(FRIENDLY_FUNCTIONS_FOR_WELCART__PLUGIN_DIR . 'validation-engine-master/js/jquery.validationEngine.js')), 'all');
				if(get_locale() == 'ja'){
					wp_enqueue_script( 'jquery.validationEngine-ja.js', FRIENDLY_FUNCTIONS_FOR_WELCART__PLUGIN_URL . 'validation-engine-master/js/languages/jquery.validationEngine-ja.js', array(), date("ymdHis", filemtime(FRIENDLY_FUNCTIONS_FOR_WELCART__PLUGIN_DIR . 'validation-engine-master/js/languages/jquery.validationEngine-ja.js')), 'all');
				}else{
					wp_enqueue_script( 'jquery.validationEngine-en.js', FRIENDLY_FUNCTIONS_FOR_WELCART__PLUGIN_URL . 'validation-engine-master/js/languages/jquery.validationEngine-en.js', array(), date("ymdHis", filemtime(FRIENDLY_FUNCTIONS_FOR_WELCART__PLUGIN_DIR . 'validation-engine-master/js/languages/jquery.validationEngine-en.js')), 'all');
				}
			}
		}
		add_action('wp_enqueue_scripts', 'friendly_functions_for_welcart_enqueue_style_script');
	}

	/* CSSインライン出力 */
	if(!empty($cssInlineOutput) && $cssInlineOutput == 'yes'){
		function friendly_functions_for_welcart_style_queues(){
			global $wp_styles;
			global $wp_styles_array;
			$site_url = site_url('/');

			foreach ($wp_styles->queue as $handle) {
				if(!empty($wp_styles->registered[$handle])){
					$wp_styles_src = $wp_styles->registered[$handle]->src;
					if (!empty($handle) && ($handle == 'theme_cart_css' || $handle == 'usces_cart_css' || $handle == 'usces_default_css')) {
						//外部CSS読み込み キャンセル
						wp_dequeue_style($handle);
						//外部CSSリスト作成
						$wp_styles_uri = '';
						if (false !== strpos($wp_styles_src, '/wp-includes/')) {
							$wp_styles_uri = str_replace('/wp-includes/', ABSPATH . 'wp-includes/', $wp_styles_src);
						}
						if (false !== strpos($wp_styles_src, $site_url)) {
							$wp_styles_uri = str_replace($site_url, ABSPATH, $wp_styles_src);
						}
						if ($wp_styles_uri) {
							$wp_styles_array[] = array('uri' => $wp_styles_uri, 'queue' => $handle);
						}
					}
				}
			}
		}
		add_action('wp_print_styles', 'friendly_functions_for_welcart_style_queues', 999);
	}

	if((!empty($cssInlineOutput) && $cssInlineOutput == 'yes') || (!empty($displayFaxNum) && $displayFaxNum == 'no')){
		function friendly_functions_for_welcart_wp_get_custom_css($css){
			//外部CSSリスト
			global $wp_styles_array;

			$css_data = '';
			$site_url = site_url('/');

			//外部CSSリスト読み込み
			require_once(ABSPATH . 'wp-admin/includes/file.php');
			if (is_array($wp_styles_array)) {
				if (WP_Filesystem()) {
					global $wp_filesystem;
					foreach ($wp_styles_array as $wp_style) {
						$css_name = $wp_style['queue'];
						$css_tmp = $wp_filesystem->get_contents($wp_style['uri']);
						$css_tmp = friendly_functions_for_welcart_css_simple_minify($css_tmp, $css_name);
						$css_data .= '/*' . $css_name . '*/' . $css_tmp . "\n";
					}
				}
			}
			//インラインCSS追加
			$friendlyFunctionsForWelcartHideFaxNumCss = get_option('friendlyFunctionsForWelcartHideFaxNumCss');
			if(!empty($friendlyFunctionsForWelcartHideFaxNumCss)){
				$css_data .= '/*ffw-inline-css*/' . esc_html($friendlyFunctionsForWelcartHideFaxNumCss) . "\n";
			}
			//カスタムCSS追加
			$wp_custom_css = friendly_functions_for_welcart_css_simple_minify($css, 'wp_custom_css');
			if ($wp_custom_css) {
				$css_data .= '/*wp-custom-css*/' . $wp_custom_css . "\n";
			}
			return $css_data;
		}
		add_filter('wp_get_custom_css', 'friendly_functions_for_welcart_wp_get_custom_css');
	}

	/* CSS圧縮 */
	function friendly_functions_for_welcart_css_simple_minify($css, $css_name){
		$site_url = site_url('/');
		if ($css != '') {
			//Character Code削除
			$css = str_replace('@charset "utf-8";', '', $css);
			$css = str_replace('@charset"utf-8";', '', $css);
			//コメント削除
			$css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
			//改行削除
			$css = str_replace(array("\r\n", "\r", "\n", "\t"), '', $css);
			//スペース削除
			$css = str_replace(array('  ', '    ', '    '), '', $css);
			$css = str_replace(': ', ':', $css);
			$css = str_replace(' :', ':', $css);
			$css = str_replace(' }', '}', $css);
			$css = str_replace('} ', '}', $css);
		}
		return $css;
	}

	/* 「戻る」でドキュメント有効切れを防ぐ */
	/* ※特定の条件・環境下で会員編集時の本人確認メールが届かない報告があり、機能停止（ver 1.2.4~）
	 *   今後、コードを見直しつつ、特定のページでのみ機能するようになども検討
	if(!empty($preventExpiration) && $preventExpiration == 'yes'){
		function friendly_functions_for_welcart_add_header_session()
		{
			header("Cache-Control: private, no-store, no-cache, must-revalidate, max-age=0");
			header("Pragma: no-cache");
			header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
			header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); 
		}
		add_action('send_headers', 'friendly_functions_for_welcart_add_header_session');
	}
	*/

	/* 検索結果の拡張 */
	if(!empty($extendSearch) && $extendSearch == 'yes'){
		function friendly_functions_for_welcart_custom_search($search, $wp_query){
			global $wpdb;

			if (is_admin() || !$wp_query->is_search || !isset($wp_query->query_vars)) {
				return $search;
			}

			$search_words = explode(' ', isset($wp_query->query_vars['s']) ? $wp_query->query_vars['s'] : '');
			if (count($search_words) > 0) {
				$search = '';
				$search .= "AND post_type = 'post'";
				foreach ($search_words as $word) {
					if (!empty($word)) {
						$search_word = '%' . esc_sql($word) . '%';
						$search .= " AND (
										{$wpdb->posts}.post_title LIKE '{$search_word}'
									OR {$wpdb->posts}.post_content LIKE '{$search_word}'
									OR {$wpdb->posts}.ID IN (
									SELECT distinct post_id
									FROM {$wpdb->postmeta}
									WHERE meta_value LIKE '{$search_word}'
									)
								) ";
					}
				}
			}
			return $search;
		}
		add_filter('posts_search', 'friendly_functions_for_welcart_custom_search', 10, 2);

		function friendly_functions_for_welcart_the_slug(){
			global $post;
			if (is_home() || is_front_page()) {
				return ['home'];
			} elseif (is_page()) {
				$page_slug = get_page_uri($post->ID);
				return [$page_slug];
			} elseif (is_category()) {
				$categories = get_the_category($post->ID);
				$slugs = [];
				foreach ($categories as $category) {
					$slugs[] = $category->slug;
				}
				return $slugs;
			} else {
				return [];
			}
		}
		add_shortcode('the_slug', 'friendly_functions_for_welcart_the_slug');

		//「ひらがな」と「カタカナ」・「全角英数」と「半角英数」を区別せず検索
		function friendly_functions_for_welcart_change_search_char($where, $obj){
			if (!is_admin() && $obj->is_search && (DB_CHARSET === 'utf8' || DB_CHARSET === 'utf8mb4')) {
				$where = str_replace('.post_title', '.post_title COLLATE '.DB_CHARSET.'_unicode_ci', $where );
				$where = str_replace('.post_content', '.post_content COLLATE '.DB_CHARSET.'_unicode_ci', $where );
			}
			return $where;
		}
		add_filter('posts_where', 'friendly_functions_for_welcart_change_search_char', 10, 2);
	}

	/* カスタム・オーダーフィールドのinputタイプを「date」に書き換える */
	if(!empty($customOrderTypeDate) && $customOrderTypeDate == 'yes'){
		function friendly_functions_for_welcart_filter_custom_field_input( $html, $data, $custom_field, $position ){
			$trArray = explode('</tr>',$html);
			$html = '';

			foreach ($trArray as $key => $val){
				if(strstr($val,'date') == true){
					$val = str_replace('type="text"', 'type="date" style="width: auto;cursor: pointer;"', $val);
				}
				$html .= $val.'</tr>';
			}

			return $html;
		}
		add_filter( 'usces_filter_custom_field_input',  'friendly_functions_for_welcart_filter_custom_field_input', 10, 4 );
	}

	/* 「カートへ入れる」ボタンのテキスト変更 */
	if(!empty($cartButtonText)){
		function friendly_functions_for_welcart_filter_incart_button_label($button_label){
			$friendlyFunctionsForWelcartData = get_option('friendlyFunctionsForWelcartData');
			return esc_html($friendlyFunctionsForWelcartData['cartButtonText']);
		}
		add_filter('usces_filter_incart_button_label',  'friendly_functions_for_welcart_filter_incart_button_label');
	}

	/* 業務パックの画像変更 */
	if(!empty($itemGpExpMark)){
		function friendly_functions_for_welcart_filter_itemGpExp_cart_mark($business_pack_mark) {
			$friendlyFunctionsForWelcartData = get_option('friendlyFunctionsForWelcartData');
			$business_pack_mark = '<img src="'.esc_html($friendlyFunctionsForWelcartData['itemGpExpMark']).'" alt="'.esc_html__('Business package discount', 'usces').'">';
			return $business_pack_mark;
		}
		add_filter( 'usces_filter_itemGpExp_cart_mark',  'friendly_functions_for_welcart_filter_itemGpExp_cart_mark' );

		$url = $_SERVER['REQUEST_URI'];
		if(strstr($url,'/usces-cart/') == true){
			function friendly_functions_for_welcart_filter_itemGpExp_cart_mark_message() {
				$friendlyFunctionsForWelcartData = get_option('friendlyFunctionsForWelcartData');
				ob_start();
?>
<script type="text/javascript">
	var gpImage = document.querySelector(".gp img"); //変更箇所を取得
	gpImage.src = "<?php echo esc_html($friendlyFunctionsForWelcartData['itemGpExpMark']); ?>"; //「src」書き換え
</script>
<?php
				echo friendly_functions_for_welcart_js_simple_minify(ob_get_clean())."\n";
			}
			add_action('wp_footer', 'friendly_functions_for_welcart_filter_itemGpExp_cart_mark_message');
		}
	}

	/* カートボタンを一時利用停止にする */
	if(!empty($cartButtonMaintenance) && $cartButtonMaintenance == 'yes'){
		function friendly_functions_for_welcart_filter_hide_cart_button() {
			$buttonText = apply_filters('ffw_filter_hide_cart_button_text',esc_html__('Under maintenance', MAINICHI_WEB_THIS_PLUGIN_NAME)); //只今メンテナンス中です
			ob_start();
?>
<script type="text/javascript">
	var cartButton = document.querySelectorAll('input[id^="inCart"]'); //変更箇所を取得
	var text = "<?php echo $buttonText; ?>";
	cartButton.forEach(function(value) {
		value.value = text;
		value.disabled = true;
	});
</script>
<style type="text/css">
	input[id^="inCart"] {
		color: #fff !important;
		background: #ccc !important;
		cursor: not-allowed !important;
	}
	input[id^="inCart"]:hover {
		color: #fff !important;
		background: #ccc !important;
		opacity: 1 !important;
		filter: alpha(opacity=100) !important;
		-ms-filter: "alpha(opacity=100)" !important;
		-khtml-opacity: 1 !important;
		-moz-opacity: 1 !important;
	}
</style>
<?php
			echo friendly_functions_for_welcart_js_simple_minify(ob_get_clean())."\n";
		}
		add_action('wp_footer', 'friendly_functions_for_welcart_filter_hide_cart_button');
	}

	/* 商品マスターの表示数変更 */
	if(!empty($changeDisplayAdminItem)){
		function friendly_functions_for_welcart_admin_display_item_number(){
			$friendlyFunctionsForWelcartData = get_option('friendlyFunctionsForWelcartData');
			return $friendlyFunctionsForWelcartData['changeDisplayAdminItem'];
		}
		add_filter( 'usces_filter_itemlist_maxrow', 'friendly_functions_for_welcart_admin_display_item_number' );
	}

	/* 商品の合計購入点数で割引 */
	if((!empty($purchaseDiscountsA1) && !empty($purchaseDiscountsB1)) || (!empty($purchaseDiscountsA2) && !empty($purchaseDiscountsB2)) || (!empty($purchaseDiscountsA3) && !empty($purchaseDiscountsB3))){
		function friendly_functions_for_welcart_order_number_discount($discount, $carts){
			global $usces;
			$total = $usces->get_total_quantity($carts);
			$friendlyFunctionsForWelcartData = get_option('friendlyFunctionsForWelcartData');
			$discountsArray = array();

			if(!empty($friendlyFunctionsForWelcartData['purchaseDiscountsA1']) && !empty($friendlyFunctionsForWelcartData['purchaseDiscountsB1'])){
				$discountsArray[] = array(
					'units'    => $friendlyFunctionsForWelcartData['purchaseDiscountsA1'],
					'discount' => $friendlyFunctionsForWelcartData['purchaseDiscountsB1']
				);
			}
			if(!empty($friendlyFunctionsForWelcartData['purchaseDiscountsA2']) && !empty($friendlyFunctionsForWelcartData['purchaseDiscountsB2'])){
				$discountsArray[] = array(
					'units'    => $friendlyFunctionsForWelcartData['purchaseDiscountsA2'],
					'discount' => $friendlyFunctionsForWelcartData['purchaseDiscountsB2']
				);
			}
			if(!empty($friendlyFunctionsForWelcartData['purchaseDiscountsA3']) && !empty($friendlyFunctionsForWelcartData['purchaseDiscountsB3'])){
				$discountsArray[] = array(
					'units'    => $friendlyFunctionsForWelcartData['purchaseDiscountsA3'],
					'discount' => $friendlyFunctionsForWelcartData['purchaseDiscountsB3']
				);
			}

			if(!empty($discountsArray)){
				$units = array_column($discountsArray, 'units'); //並び替えの基準を取得
				array_multisort($units, SORT_ASC, $discountsArray); //昇順で並び替え

				foreach ($discountsArray as $key => $val){
					if ($total >= $val['units']) {
						$discount = -$val['discount'];
					}
				}
			}

			return $discount;
		}
		add_filter('usces_order_discount', 'friendly_functions_for_welcart_order_number_discount', 10, 3);

		//キャンペーン割引の名前変更
		function friendly_functions_for_welcart_filter_disnount_label($label){
			return esc_html__('Purchase discount', MAINICHI_WEB_THIS_PLUGIN_NAME); //購入点数割引
		}
		add_filter('usces_confirm_discount_label', 'friendly_functions_for_welcart_filter_disnount_label', 10);
		add_filter('usces_filter_disnount_label', 'friendly_functions_for_welcart_filter_disnount_label', 10);
	}

	/* カートページに割引額を表示 */
	if(!empty($displayDiscountForCart) && $displayDiscountForCart == 'yes'){
		$url = $_SERVER['REQUEST_URI'];
		if(strstr($url,'/usces-cart/') == true){
			function friendly_functions_for_welcart_js_code_cart_page_add_discount() {
				if(usces_order_discount('return')){
					ob_start();
?>
<script type="text/javascript">
	var cartTbody = document.querySelector("#wc_cart #cart_table tbody"); //追加箇所を取得
	var cartTfootAmount = document.querySelector("#wc_cart #cart_table tfoot .amount"); //合計金額のセルを取得
	var discountLabel = "<?php echo apply_filters('usces_confirm_discount_label', esc_html__('Campaign disnount', 'usces')); ?>"; //割引名を取得
	var discountNumber = "<?php echo usces_order_discount('return'); ?>"; //割引額を数字で取得
	var discount = "<?php usces_crform(usces_order_discount('return'),true, false); ?>"; //割引額を価格で取得
	var cartAmount = cartTfootAmount.textContent; //割引前の合計金額を取得
	var cumRemovedAmount = cartAmount.replace(/,/g, ''); //カンマ削除
	var yenRemovedAmount = cumRemovedAmount.replace(/¥/g, ''); //\マーク削除
	var currency = cumRemovedAmount.replace(/[0-9]/g, ''); //通貨記号（数字削除）
	var amountAfterDiscount = yenRemovedAmount - discountNumber; //割引額を引く
	amountAfterDiscount = currency + amountAfterDiscount.toLocaleString(); //表示する割引後金額

	cartTbody.insertAdjacentHTML('afterend','<td class="num"></td><td class="thumbnail"></td><td colspan="3" scope="row" class="aright">'+discountLabel+'</td><td class="aright subtotal" style="color:#FF0000">-'+discount+'</td><td class="stock"></td><td class="action"></td>'); //割引の行を追加
	cartTfootAmount.innerHTML = amountAfterDiscount; //合計金額の書き換え
</script>
<?php
					echo friendly_functions_for_welcart_js_simple_minify(ob_get_clean())."\n";
				}
			}
			add_action('wp_footer', 'friendly_functions_for_welcart_js_code_cart_page_add_discount');
		}
	}

	/* 12ヶ月間の受注数・金額を表示 */
	if(!empty($amount12months) && $amount12months == 'yes'){
		function friendly_functions_for_welcart_amount_of_12_months(){
			global $wpdb;
			$dateStr = substr(get_date_from_gmt(gmdate('Y-m-d H:i:s', time())), 0, 10);
			$yearStr = substr($dateStr, 0, 4);
			$monthStr = substr($dateStr, 5, 2) + 1;
			$dayStr = substr($dateStr, 8, 2);
?>
<h4>12ヶ月間の受注数・金額</h4>
<div class="usces_box">
	<table class="dashboard">
		<tr>
			<th><?php esc_html_e('Currency','usces'); //通貨 : 円 ?> : <?php usces_crcode(); ?></th><th><?php esc_html_e('Number of order', MAINICHI_WEB_THIS_PLUGIN_NAME); //受注数 ?></th><th><?php esc_html_e('Amount of order', MAINICHI_WEB_THIS_PLUGIN_NAME); //受注金額 ?></th><th><?php esc_html_e('Average customer spending', MAINICHI_WEB_THIS_PLUGIN_NAME); //平均客単価 ?></th><th><?php esc_html_e('Same month last year / Compared to last year', MAINICHI_WEB_THIS_PLUGIN_NAME); //昨年同月 / 昨対比 ?></th>
		</tr>
		<?php
			$table_name = $wpdb->prefix . 'usces_order';
			$lastYearNumber = array();
			$lastYearAmount = array();

			//昨年12ヶ月分を取得
			for ($num = 0; $num < 12; $num++){
				$startDate = date('Y-m-01 00:00:00', mktime(0, 0, 0, (int)$monthStr+$num, 1, (int)$yearStr-2));
				$endDate = date('Y-m-d 23:59:59', mktime(0, 0, 0, (int)$monthStr+1+$num, 0, (int)$yearStr-2));

				$query = $wpdb->prepare("SELECT COUNT(ID) AS ct FROM $table_name WHERE order_date >= %s AND order_date <= %s AND 0 = LOCATE(%s, order_status) AND 0 = LOCATE(%s, order_status)", $startDate, $endDate, 'cancel', 'estimate');
				$number = $wpdb->get_var($query);

				$query = $wpdb->prepare("SELECT SUM(order_item_total_price) AS price, SUM(order_usedpoint) AS point, SUM(order_discount) AS discount, SUM(order_shipping_charge) AS shipping, SUM(order_cod_fee) AS cod, SUM(order_tax) AS tax
                                 FROM $table_name WHERE order_date >= %s AND order_date <= %s AND 0 = LOCATE(%s, order_status) AND 0 = LOCATE(%s, order_status)", $startDate, $endDate, 'cancel', 'estimate');
				$res = $wpdb->get_row($query, ARRAY_A);
				if( $res !== NULL ){
					$amount = $res['price'] - $res['point'] + $res['discount'] + $res['shipping'] + $res['cod'] + $res['tax'];
					$lastYearNumber[] = (int) $number;
					$lastYearAmount[] = $amount;
				}
			}

			//直近12ヶ月分を取得
			for ($num = 0; $num < 12; $num++){
				if(get_locale() == 'ja'){
					$date = date('Y年m月', mktime(0, 0, 0, (int)$monthStr+$num, 1, (int)$yearStr-1));
				}else{
					$date = date('Y / m', mktime(0, 0, 0, (int)$monthStr+$num, 1, (int)$yearStr-1));
				}
				$startDate = date('Y-m-01 00:00:00', mktime(0, 0, 0, (int)$monthStr+$num, 1, (int)$yearStr-1));
				$endDate = date('Y-m-d 23:59:59', mktime(0, 0, 0, (int)$monthStr+1+$num, 0, (int)$yearStr-1));
				$table_name = $wpdb->prefix . 'usces_order';
				$query = $wpdb->prepare("SELECT COUNT(ID) AS ct FROM $table_name WHERE order_date >= %s AND order_date <= %s AND 0 = LOCATE(%s, order_status) AND 0 = LOCATE(%s, order_status)", $startDate, $endDate, 'cancel', 'estimate');
				$number = $wpdb->get_var($query);

				$query = $wpdb->prepare("SELECT SUM(order_item_total_price) AS price, SUM(order_usedpoint) AS point, SUM(order_discount) AS discount, SUM(order_shipping_charge) AS shipping, SUM(order_cod_fee) AS cod, SUM(order_tax) AS tax
                                 FROM $table_name WHERE order_date >= %s AND order_date <= %s AND 0 = LOCATE(%s, order_status) AND 0 = LOCATE(%s, order_status)", $startDate, $endDate, 'cancel', 'estimate');
				$res = $wpdb->get_row($query, ARRAY_A);
				if( $res !== NULL ){
					/* 直近 */
					//受注金額
					$amount = $res['price'] - $res['point'] + $res['discount'] + $res['shipping'] + $res['cod'] + $res['tax'];
					//受注数
					$number = (int) $number;
					//平均客単価
					if($amount != 0 && $number != 0){
						$averageCustomerSpend = round( $amount / $number );
					}else{
						$averageCustomerSpend = 0;
					}

					//昨年同月
					$lastYearNumberVal = $lastYearNumber[$num];
					$lastYearAmountVal = $lastYearAmount[$num];
					if($lastYearNumberVal != 0 && $lastYearAmountVal != 0){
						$lastYearAverageCustomerSpend = round($lastYearAmountVal / $lastYearNumberVal);
					}else{
						$lastYearAverageCustomerSpend = 0;
					}

					/* 昨対比 */
					//受注数
					if($number != 0 && $lastYearNumberVal != 0){
						$yesteryearComparisonNumber = $number / $lastYearNumberVal;
						$yesteryearComparisonNumber *= 100;
						$yesteryearComparisonNumber = round($yesteryearComparisonNumber,2);
						//SVGアイコン
						if($yesteryearComparisonNumber > 100){
							$icon = friendly_functions_for_welcart_up_arrow('return');
						}elseif($yesteryearComparisonNumber < 100){
							$icon = friendly_functions_for_welcart_down_arrow('return');
						}else{
							$icon = friendly_functions_for_welcart_no_change_arrow('return');
						}
						$yesteryearComparisonNumber .= '％'.$icon;
					}else{
						$yesteryearComparisonNumber = '-';
					}

					//受注金額
					if($amount != 0 && $lastYearAmountVal != 0){
						$yesteryearComparisonAmount = $amount / $lastYearAmountVal;
						$yesteryearComparisonAmount *= 100;
						$yesteryearComparisonAmount = round($yesteryearComparisonAmount,2);
						//SVGアイコン
						if($yesteryearComparisonAmount > 100){
							$icon = friendly_functions_for_welcart_up_arrow('return');
						}elseif($yesteryearComparisonAmount < 100){
							$icon = friendly_functions_for_welcart_down_arrow('return');
						}else{
							$icon = friendly_functions_for_welcart_no_change_arrow('return');
						}
						$yesteryearComparisonAmount .= '％'.$icon;
					}else{
						$yesteryearComparisonAmount = '-';
					}

					//平均客単価
					if($averageCustomerSpend != 0 && $lastYearAverageCustomerSpend != 0){
						$yesteryearComparisonAverageCustomerSpend = $averageCustomerSpend / $lastYearAverageCustomerSpend;
						$yesteryearComparisonAverageCustomerSpend *= 100;
						$yesteryearComparisonAverageCustomerSpend = round($yesteryearComparisonAverageCustomerSpend,2);
						//SVGアイコン
						if($yesteryearComparisonAverageCustomerSpend > 100){
							$icon = friendly_functions_for_welcart_up_arrow('return');
						}elseif($yesteryearComparisonAverageCustomerSpend < 100){
							$icon = friendly_functions_for_welcart_down_arrow('return');
						}else{
							$icon = friendly_functions_for_welcart_no_change_arrow('return');
						}
						$yesteryearComparisonAverageCustomerSpend .= '％'.$icon;
					}else{
						$yesteryearComparisonAverageCustomerSpend = '-';
					}

					$numberBetsReceived = esc_html__('Number of bets received', MAINICHI_WEB_THIS_PLUGIN_NAME); //受注数
					$amountBetsReceived = esc_html__('Amount of bets received', MAINICHI_WEB_THIS_PLUGIN_NAME); //受注金額
					$averagePrice = esc_html__('Average price', MAINICHI_WEB_THIS_PLUGIN_NAME); //平均客単価
					$numberBetsReceivedYesterday = esc_html__('Number of bets received yesterday', MAINICHI_WEB_THIS_PLUGIN_NAME); //昨対受注数
					$amountBetsReceivedYesterday = esc_html__('Amount of bets received yesterday', MAINICHI_WEB_THIS_PLUGIN_NAME); //昨対受注金額
					$averageUnitPricePreviousYear = esc_html__('Average unit price for the previous year', MAINICHI_WEB_THIS_PLUGIN_NAME); //昨対平均客単価

					echo '<tr><td>'.$date.' : </td><td class="bignum">' .$number. '</td><td class="bignum">'.usces_crform( $amount, true, false, 'return' ). '</td><td class="bignum">'.usces_crform( $averageCustomerSpend, true, false, 'return' ). '</td><td class="flex flexBetween"><span class="width30P">'.$numberBetsReceived.'：'.$lastYearNumberVal.'</span><span class="textalignCenter width5P" style="color:#ccc;">|</span><span class="width30P">'.$amountBetsReceived.'：'.usces_crform( $lastYearAmountVal, true, false, 'return' ).'</span><span class="textalignCenter width5P" style="color:#ccc;">|</span><span class="width30P">'.$averagePrice.'：'.usces_crform( $lastYearAverageCustomerSpend, true, false, 'return' ).'</span></td><td class="flex flexBetween"><span class="width30P">'.$numberBetsReceivedYesterday.'：'.$yesteryearComparisonNumber.'</span><span class="textalignCenter width5P" style="color:#ccc;">|</span><span class="width30P">'.$amountBetsReceivedYesterday.'：'.$yesteryearComparisonAmount.'</span><span class="textalignCenter width5P" style="color:#ccc;">|</span><span class="width30P">'.$averageUnitPricePreviousYear.'：'.$yesteryearComparisonAverageCustomerSpend.'</span></td></tr>'."\n";
				}
			}
		?>
	</table>
</div>
<?php
		}
		add_action( 'usces_action_admintop_box2', 'friendly_functions_for_welcart_amount_of_12_months', 10 );
	}

	/* 在庫切れアラート */
	if(!empty($soldoutAlertMessage) && $soldoutAlertMessage == 'yes'){
		function friendly_functions_for_welcart_out_of_stock_alert($args){
			global $usces;
			extract($args);

			$count = 0;
			$doMessage = 'no';
			$subject = esc_html__('[ Out of Stock Notification / ', MAINICHI_WEB_THIS_PLUGIN_NAME); //【在庫切れ通知 / 
			$message  = '----------------------------------------------'."\n";

			$usces_options = $usces->options;

			foreach($cart as $cartrow){
				++$count;

				$post_id = $cartrow['post_id']; //注文の商品ID
				$sku_code = $cartrow['sku']; //注文のSKUコード

				//「注文のSKUコード」の在庫数を取得
				$item_skus = $usces->get_skus($post_id); //商品の全SKU情報を取得
				foreach($item_skus as $sku){
					$search_sku_code  = $sku['code']; //SKUコード
					if($sku_code == $search_sku_code){ //「注文のSKUコード」と「SKUコード」を照らし合わせる
						if($sku['stocknum'] !== ''){
							$stocknum = (int) $sku['stocknum']; //注文後の在庫数
						}else{
							//在庫管理をしていない商品
							$stocknum = '';
						}
					}
				}

				$limitNum = (int) apply_filters('ffw_filter_soldout_alert_limit_number', 0) + 1;

				if($stocknum !== '' && $stocknum < $limitNum){ //在庫数が「0」になった場合
					//タイトル
					if($count > 1){
						$subject .= '・';
					}
					$item_name = esc_html(get_the_title($post_id)); //注文の商品名
					$subject .= $item_name;
					//メール本文
					$item_sku_name = ''; //注文のSKU表示名
					if(function_exists('wel_get_sku')){
						$sku_data = wel_get_sku($post_id, $sku_code); //注文のSKU情報を取得
						$item_sku_name = esc_html($sku_data['name']); //注文のSKU表示名
					}
					$item_url = esc_url(get_permalink($post_id)); //注文の商品URL
					$message .= esc_html__('SKU code : ', MAINICHI_WEB_THIS_PLUGIN_NAME).$sku_code."\n"; //SKUコード：
					$message .= esc_html__('Item name : ', MAINICHI_WEB_THIS_PLUGIN_NAME).$item_name; //商品名：
					if(!empty($item_sku_name)){
						$message .= ' / '.$item_sku_name."\n";
					}else{
						$message .= "\n";
					}
					$message .= esc_html__('Stock : ', MAINICHI_WEB_THIS_PLUGIN_NAME).$stocknum."\n"; //在庫数：
					$message .= esc_html__('Item URL : ', MAINICHI_WEB_THIS_PLUGIN_NAME).$item_url."\n"; //商品URL：
					$message .= '----------------------------------------------'."\n";

					$doMessage = 'yes'; //メールを送るかどうか
				}
			}

			if($doMessage == 'yes'){
				$subject .= esc_html__(' ]', MAINICHI_WEB_THIS_PLUGIN_NAME); //】
				$message .= "\n\n".esc_html__('The above item is now out of stock.', MAINICHI_WEB_THIS_PLUGIN_NAME)."\n"; //上記の商品が在庫切れになりました。

				$to_name = esc_html__('Dear person in charge', MAINICHI_WEB_THIS_PLUGIN_NAME); //ご担当者さま
				$to_address = $usces_options['order_mail'];
				$from_address = $usces_options['sender_mail'];

				//送信内容セット
				$order_para = array(
					'to_name'      => $to_name,
					'to_address'   => $to_address,
					'from_name'    => esc_html(get_option('blogname')),
					'from_address' => $from_address,
					'return_path'  => $from_address,
					'subject'      => trim(urldecode($subject)),
					'message'      => trim(urldecode($message)),
				);

				//送信実行
				$res = usces_send_mail($order_para);
			}

		}
		add_action('usces_action_reg_orderdata', 'friendly_functions_for_welcart_out_of_stock_alert');
	}

} //Welcart有効確認END