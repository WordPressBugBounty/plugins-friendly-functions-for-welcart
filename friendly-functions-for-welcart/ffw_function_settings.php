<?php
/*
	Template Name: 設定画面
*/

if(!defined('ABSPATH')) exit;

/* Welcart有効確認 */
include_once(ABSPATH . 'wp-admin/includes/plugin.php');
if (is_plugin_active('usc-e-shop/usc-e-shop.php')):

/* 設定情報 */
//取得
$friendlyFunctionsForWelcartData = get_option('friendlyFunctionsForWelcartData');
//サポート機能
$ffwDataNameArr = array('couponDiscountsCode','couponDiscountsPrice','couponDiscountsUnit','purchaseDiscountsA1','purchaseDiscountsB1','purchaseDiscountsA2','purchaseDiscountsB2','purchaseDiscountsA3','purchaseDiscountsB3','shippingDiscountsA','shippingDiscountsB','conversionTag','cartButtonText','itemGpExpMark','changeLinkCartPrebutton','changeAfterLogin','changeAfterLogout','changeOrderEmail','removeOrderEmailMemberNo','pdfSignImage','removeOGP','amount12months','cartButtonMaintenance','changeDisplayAdminItem','soldoutAlertMessage');
foreach ($ffwDataNameArr as $key => $val){
	if(!empty($_POST['submit_settings_data_1'])){
		if(!empty($_REQUEST[$val])){
			if($val == 'conversionTag'){
				$friendlyFunctionsForWelcartData[$val] = htmlspecialchars($_REQUEST[$val], ENT_QUOTES, "UTF-8");
			}else{
				$friendlyFunctionsForWelcartData[$val] = sanitize_text_field($_REQUEST[$val]);
			}
		}else{
			unset($friendlyFunctionsForWelcartData[$val]);
		}
	}
	if(!empty($friendlyFunctionsForWelcartData[$val])){
		$$val = $friendlyFunctionsForWelcartData[$val]; //可変変数
	}else{
		$$val = '';
	}
}
//ユーザビリティ
$ffwDataNameArr = array('shippingDiscountsMessage','postagePrivilegeMessage','displayFurigana','displayFaxNum','displayDiscountForCart','cartButtonEntryNum','quantityEntryNum','customOrderTypeDate','formErrorCheck','preventExpiration','onThanksgivingPage','extendSearch','cssInlineOutput');
foreach ($ffwDataNameArr as $key => $val){
	if(!empty($_POST['submit_settings_data_2'])){
		if(!empty($_REQUEST[$val])){
			$friendlyFunctionsForWelcartData[$val] = sanitize_text_field($_REQUEST[$val]);
		}else{
			unset($friendlyFunctionsForWelcartData[$val]);
		}
	}
	if(!empty($friendlyFunctionsForWelcartData[$val])){
		$$val = $friendlyFunctionsForWelcartData[$val]; //可変変数
	}else{
		$$val = '';
	}
}

//DB更新
update_option('friendlyFunctionsForWelcartData',$friendlyFunctionsForWelcartData);

//設定保存時のメッセージ
if(isset($_POST['submit_settings'])){
	$saveMessage = '<div class="saveMessage updated"><p>'.sprintf(esc_html__('%s saved.', MAINICHI_WEB_THIS_PLUGIN_NAME), $_POST['submit_settings']).'</p></div>'; // ～を保存しました。
}
?>

<section class="ffwSettingsSection">
	<?php
	if(!empty($saveMessage)){
		echo $saveMessage;
	}
	?>

	<input id="settingsTab1" type="radio" name="tab_settings" checked><label for="settingsTab1"><span class="tabLabel"><?php esc_html_e('Support Functions Settings', MAINICHI_WEB_THIS_PLUGIN_NAME); //サポート機能 ?></span></label>
	<input id="settingsTab2" type="radio" name="tab_settings"><label for="settingsTab2"><span class="tabLabel"><?php esc_html_e('Usability Settings', MAINICHI_WEB_THIS_PLUGIN_NAME); //ユーザビリティ ?></span></label>
	<input id="settingsTab3" type="radio" name="tab_settings"><label for="settingsTab3"><span class="tabLabel"><?php esc_html_e('Other Plugins and Themes', MAINICHI_WEB_THIS_PLUGIN_NAME); //その他のプラグイン・テーマ ?></span></label>

	<div id="settings1" class="tab_content">
		<form class="settingsForm1" method="post" action="admin.php?page=ffw_function_settings">
			<div class="ffwGrayBackground">
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><label for="couponDiscounts"><?php esc_html_e('Coupon Discount', MAINICHI_WEB_THIS_PLUGIN_NAME); //クーポン割引 ?></label></th>
						<td>
							<span class="flex flexStartWrap flexCenterColumn marginBottom8"><?php esc_html_e('Coupon Code:', MAINICHI_WEB_THIS_PLUGIN_NAME); //クーポンコード： ?><input name="couponDiscountsCode" type="text" value="<?php echo $couponDiscountsCode; ?>" class="regular-text ffwSmallTextInput"></span>
							<span class="flex flexStartWrap flexCenterColumn">
								<?php esc_html_e('Discount Amount:', MAINICHI_WEB_THIS_PLUGIN_NAME); //割引額： ?><input name="couponDiscountsPrice" type="number" value="<?php echo $couponDiscountsPrice; ?>" class="regular-text ffwSmallTextInput">
								<select name="couponDiscountsUnit">
									<option value="price"<?php if(empty($couponDiscountsUnit) || $couponDiscountsUnit == 'price'){echo ' selected';} ?>><?php esc_html_e('$', MAINICHI_WEB_THIS_PLUGIN_NAME); //円 ?></option>
									<option value="percent"<?php if(!empty($couponDiscountsUnit) && $couponDiscountsUnit == 'percent'){echo ' selected';} ?>><?php esc_html_e('%', MAINICHI_WEB_THIS_PLUGIN_NAME); //％ ?></option>
								</select>
							</span>
							<p class="description"><?php esc_html_e('The "Coupon Code" input field will be displayed on the "Shipping and Payment" page, and the set amount will be discounted from the purchase amount when the set coupon code is entered. Please note that this offer cannot be used in conjunction with the "Campaign" or "Purchase Discount" or "Shipping Discount".', MAINICHI_WEB_THIS_PLUGIN_NAME); //「発送・支払方法」ページに「クーポンコード」入力欄を表示し、設定したクーポンコードを入力した場合に購入額から設定した額を割り引きます。「キャンペーン」「購入点数割引」「送料割引」とは併用できないのでご注意ください。 ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="purchaseDiscounts"><?php esc_html_e('Purchase Discount', MAINICHI_WEB_THIS_PLUGIN_NAME); //購入点数割引 ?></label></th>
						<td>
							<div class="purchaseDiscountsWrap">
								<div class="purchaseDiscountsAWrap"><?php echo sprintf(__('%s units over', MAINICHI_WEB_THIS_PLUGIN_NAME), '<input name="purchaseDiscountsA1" class="purchaseDiscountsA" type="number" value="'.$purchaseDiscountsA1.'" class="regular-text ffwSmallTextInput">'); //個以上の購入で ?></div>
								<div class="purchaseDiscountsBWrap"><?php echo sprintf(__('$%s discount', MAINICHI_WEB_THIS_PLUGIN_NAME), '<input name="purchaseDiscountsB1" class="purchaseDiscountsB" type="number" value="'.$purchaseDiscountsB1.'" class="regular-text ffwSmallTextInput">'); //円割引 ?></div>
							</div>
							<div class="purchaseDiscountsWrap">
								<div class="purchaseDiscountsAWrap"><?php echo sprintf(__('%s units over', MAINICHI_WEB_THIS_PLUGIN_NAME), '<input name="purchaseDiscountsA2" class="purchaseDiscountsA" type="number" value="'.$purchaseDiscountsA2.'" class="regular-text ffwSmallTextInput">'); //個以上の購入で ?></div>
								<div class="purchaseDiscountsBWrap"><?php echo sprintf(__('$%s discount', MAINICHI_WEB_THIS_PLUGIN_NAME), '<input name="purchaseDiscountsB2" class="purchaseDiscountsB" type="number" value="'.$purchaseDiscountsB2.'" class="regular-text ffwSmallTextInput">'); //円割引 ?></div>
							</div>
							<div class="purchaseDiscountsWrap">
								<div class="purchaseDiscountsAWrap"><?php echo sprintf(__('%s units over', MAINICHI_WEB_THIS_PLUGIN_NAME), '<input name="purchaseDiscountsA3" class="purchaseDiscountsA" type="number" value="'.$purchaseDiscountsA3.'" class="regular-text ffwSmallTextInput">'); //個以上の購入で ?></div>
								<div class="purchaseDiscountsBWrap"><?php echo sprintf(__('$%s discount', MAINICHI_WEB_THIS_PLUGIN_NAME), '<input name="purchaseDiscountsB3" class="purchaseDiscountsB" type="number" value="'.$purchaseDiscountsB3.'" class="regular-text ffwSmallTextInput">'); //円割引 ?></div>
							</div>
							<p class="description"><?php esc_html_e('Discount any amount by the total number of products purchased. A maximum of three settings can be made. Please note that this offer cannot be used in conjunction with the "Campaign" or "Coupon Discount" or "Shipping Discount".', MAINICHI_WEB_THIS_PLUGIN_NAME); //商品の合計購入点数で任意の額を割り引きます。最大で3つの設定が可能です。「キャンペーン」「クーポン割引」「送料割引」とは併用できないのでご注意ください。 ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="shippingDiscounts"><?php esc_html_e('Shipping Discount', MAINICHI_WEB_THIS_PLUGIN_NAME); //送料割引 ?></label></th>
						<td>
							<div class="shippingDiscountsAWrap"><?php echo sprintf(__('$%s over', MAINICHI_WEB_THIS_PLUGIN_NAME), '<input name="shippingDiscountsA" type="number" value="'.$shippingDiscountsA.'" class="regular-text ffwSmallTextInput">'); //円以上の購入で ?></div>
							<div class="shippingDiscountsBWrap"><?php echo sprintf(__('$%s discount', MAINICHI_WEB_THIS_PLUGIN_NAME), '<input name="shippingDiscountsB" type="number" value="'.$shippingDiscountsB.'" class="regular-text ffwSmallTextInput">'); //円割引 ?></div>
							<p class="description"><?php esc_html_e('If you have set up the "Free Shipping Terms" in Welcart, the discount will be applied to your order. Please note that this offer cannot be used in conjunction with the "Campaign" or "Coupon Discount" or "Purchase Discount".', MAINICHI_WEB_THIS_PLUGIN_NAME); //指定の購入額以上で、送料から任意の額を割り引きます。Welcartの「送料無料条件」を設定している場合、合わせて適用されます。「キャンペーン」「クーポン割引」「購入点数割引」とは併用できないのでご注意ください。 ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="conversionTag"><?php esc_html_e('Conversion tag installation', MAINICHI_WEB_THIS_PLUGIN_NAME); //コンバージョンタグ設置 ?></label></th>
						<td>
							<textarea name="conversionTag" class="code"><?php echo stripslashes(htmlspecialchars_decode($conversionTag, ENT_QUOTES)); ?></textarea>
							<p class="description">
								<?php
								esc_html_e('Outputs the code set for the Thanksgiving page (purchase completion screen). Please use it to place a conversion tag.', MAINICHI_WEB_THIS_PLUGIN_NAME); //サンクスページ（購入完了画面）に設定したコードを出力します。コンバージョンタグの設置等にご利用ください。
								echo sprintf(__('HTML tags can be used. In addition, order information such as "order number" and "total amount" can be output. For more details, please click <a href="%s" target="_blank" rel="noopener">here</a>.', MAINICHI_WEB_THIS_PLUGIN_NAME), 'https://mainichi-web.com/friendly-functions-for-welcart-conversion-tag/'); //HTMLタグが使用可能です。また、「注文番号」や「合計金額」等の注文情報を出力できます。詳しくはこちらをご覧ください。
								?>
							</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="cartButtonText"><?php esc_html_e('Change the text of the "cart button"', MAINICHI_WEB_THIS_PLUGIN_NAME); //「カートボタン」のテキスト変更 ?></label></th>
						<td>
							<input name="cartButtonText" type="text" value="<?php echo $cartButtonText; ?>" class="regular-text" placeholder="<?php esc_html_e('Add to Shopping Cart', 'usces'); //カートへ入れる ?>">
							<p class="description"><?php
								esc_html_e('You can change the text of the "Cart Button". ', MAINICHI_WEB_THIS_PLUGIN_NAME); //「カートボタン」のテキストを変更できます。
								$textChangerUrl = 'https://ja.wordpress.org/plugins/text-changer-for-welcart/';
								echo sprintf(__('If you want to change the text in the cart/member page, you can also try <a href="%s" target="_blank" rel="noopener">this plugin</a>.', MAINICHI_WEB_THIS_PLUGIN_NAME), esc_url($textChangerUrl)); //カート・メンバーページ内のテキストを変更したい方はこちらのプラグインもお試しください。
								?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="itemGpExpMark"><?php esc_html_e('Changed the image of the "Business Pack"', MAINICHI_WEB_THIS_PLUGIN_NAME); //「業務パック」の画像を変更 ?></label></th>
						<td>
							<?php friendly_functions_for_welcart_generate_upload_image_tag('itemGpExpMark', $itemGpExpMark); ?>
							<p class="description"><?php esc_html_e('You can change the image of the "Business Pack" displayed on the cart page.', MAINICHI_WEB_THIS_PLUGIN_NAME); //カートページに表示される「業務パック」の画像を変更できます。 ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="changeLinkCartPrebutton"><?php esc_html_e('Change "Continue Shopping" link', MAINICHI_WEB_THIS_PLUGIN_NAME); //「買い物を続ける」のリンク先変更 ?></label></th>
						<td>
							<input name="changeLinkCartPrebutton" type="url" value="<?php echo $changeLinkCartPrebutton; ?>" class="regular-text">
							<p class="description"><?php esc_html_e('You can specify the link to "Continue Shopping". If you want to change it, please enter the URL including "https://".', MAINICHI_WEB_THIS_PLUGIN_NAME); //「買い物を続ける」のリンク先を指定できます。変更する場合は「https://」を含めてURLをご入力ください。 ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="changeAfterLogin"><?php esc_html_e('Change transition page after login', MAINICHI_WEB_THIS_PLUGIN_NAME); //ログイン後の遷移ページ変更 ?></label></th>
						<td>
							<input name="changeAfterLogin" type="url" value="<?php echo $changeAfterLogin; ?>" class="regular-text">
							<p class="description"><?php esc_html_e('You can specify the page to go to after the user login. If you want to change it, please enter the URL including "https://".', MAINICHI_WEB_THIS_PLUGIN_NAME); //ユーザーがログインした後に移動するページを指定できます。変更する場合は「https://」を含めてURLをご入力ください。 ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="changeAfterLogout"><?php esc_html_e('Change transition page after logout', MAINICHI_WEB_THIS_PLUGIN_NAME); //ログアウト後の遷移ページ変更 ?></label></th>
						<td>
							<input name="changeAfterLogout" type="url" value="<?php echo $changeAfterLogout; ?>" class="regular-text">
							<p class="description"><?php esc_html_e('You can specify the page to go to after the user logout. If you want to change it, please enter the URL including "https://".', MAINICHI_WEB_THIS_PLUGIN_NAME); //ユーザーがログアウトした後に移動するページを指定できます。変更する場合は「https://」を含めてURLをご入力ください。 ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="changeOrderEmail"><?php esc_html_e('Change the return address of the order e-mail to the purchaser\'s address.', MAINICHI_WEB_THIS_PLUGIN_NAME); //受注メールの返信先を購入者のアドレスに変更 ?></label></th>
						<td>
							<select name="changeOrderEmail">
								<option value="yes"<?php if($changeOrderEmail == 'yes'){echo ' selected';} ?>><?php esc_html_e('Change', MAINICHI_WEB_THIS_PLUGIN_NAME); //変更する ?></option>
								<option value="no"<?php if($changeOrderEmail == 'no' || empty($changeOrderEmail)){echo ' selected';} ?>><?php esc_html_e('No change', MAINICHI_WEB_THIS_PLUGIN_NAME); //変更しない ?></option>
							</select>
							<p class="description"><?php esc_html_e('Change the reply address of the order email to the purchaser\'s email address to make replying smoother.', MAINICHI_WEB_THIS_PLUGIN_NAME); //受注メールの返信先を購入者のメールアドレスに変更することで返信がスムーズになります。 ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="removeOrderEmailMemberNo"><?php esc_html_e('Deletion of "Membership Number" from order email', MAINICHI_WEB_THIS_PLUGIN_NAME); //受注メールから「会員No」を削除 ?></label></th>
						<td>
							<select name="removeOrderEmailMemberNo">
								<option value="yes"<?php if($removeOrderEmailMemberNo == 'yes'){echo ' selected';} ?>><?php esc_html_e('Remove', MAINICHI_WEB_THIS_PLUGIN_NAME); //削除する ?></option>
								<option value="no"<?php if($removeOrderEmailMemberNo == 'no' || empty($removeOrderEmailMemberNo)){echo ' selected';} ?>><?php esc_html_e('No remove', MAINICHI_WEB_THIS_PLUGIN_NAME); //削除しない ?></option>
							</select>
							<p class="description"><?php esc_html_e('Delete the "Membership Number" description from the order email automatically sent when an order is placed.', MAINICHI_WEB_THIS_PLUGIN_NAME); //注文時に自動送信される受注メールから「会員No」の記述を削除します。 ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="pdfSignImage"><?php esc_html_e('Display images on estimates, delivery slips, receipts, and invoices', MAINICHI_WEB_THIS_PLUGIN_NAME); //見積書・納品書・領収書・請求書に画像を表示 ?></label></th>
						<td>
							<?php friendly_functions_for_welcart_generate_upload_image_tag('pdfSignImage', $pdfSignImage); ?>
							<p class="description"><?php esc_html_e('Display any image on a quotation, delivery note, receipt, or invoice. You can use this feature if you want to put your seal, logo, etc. on it.', MAINICHI_WEB_THIS_PLUGIN_NAME); //見積書・納品書・領収書・請求書に任意の画像を表示します。印鑑やロゴ等を載せてい場合にご利用いただけます。 ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="removeOGP"><?php esc_html_e('Remove OGP property output by Welcart', MAINICHI_WEB_THIS_PLUGIN_NAME); //Welcartが出力するOGPプロパティ削除 ?></label></th>
						<td>
							<select name="removeOGP">
								<option value="yes"<?php if($removeOGP == 'yes'){echo ' selected';} ?>><?php esc_html_e('Remove', MAINICHI_WEB_THIS_PLUGIN_NAME); //削除する ?></option>
								<option value="no"<?php if($removeOGP == 'no' || empty($removeOGP)){echo ' selected';} ?>><?php esc_html_e('No remove', MAINICHI_WEB_THIS_PLUGIN_NAME); //削除しない ?></option>
							</select>
							<p class="description"><?php esc_html_e('Disables the OGP property output by Welcart. Please use this option when OGP is duplicated by themes or other plugins.', MAINICHI_WEB_THIS_PLUGIN_NAME); //Welcartが出力するOGPプロパティを無効化します。テーマや他のプラグインによりOGPが重複した場合等にご利用ください。 ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="amount12months"><?php esc_html_e('Displays the number and amount of orders received over a 12-month period', MAINICHI_WEB_THIS_PLUGIN_NAME); //12ヶ月間の受注数・金額を表示 ?></label></th>
						<td>
							<select name="amount12months">
								<option value="yes"<?php if($amount12months == 'yes'){echo ' selected';} ?>><?php esc_html_e('Show', MAINICHI_WEB_THIS_PLUGIN_NAME); //表示する ?></option>
								<option value="no"<?php if($amount12months == 'no' || empty($amount12months)){echo ' selected';} ?>><?php esc_html_e('Hide', MAINICHI_WEB_THIS_PLUGIN_NAME); //表示しない ?></option>
							</select>
							<p class="description"><?php esc_html_e('"Welcart Shop > Home" displays the number of orders, the amount of money, and the average unit price per customer for the last 12 months, as well as the number of orders, the amount of money, the average unit price per customer, and the comparison with the same month last year.', MAINICHI_WEB_THIS_PLUGIN_NAME); //「Welcart Shop ＞ ホーム」に直近12ヶ月の受注数・金額・平均客単価、昨年同月の受注数・金額・平均客単価、昨対比を表示します。 ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="cartButtonMaintenance"><?php esc_html_e('Suspend use of cart button', MAINICHI_WEB_THIS_PLUGIN_NAME); //カートボタンを一時利用停止 ?></label></th>
						<td>
							<select name="cartButtonMaintenance">
								<option value="yes"<?php if($cartButtonMaintenance == 'yes'){echo ' selected';} ?>><?php esc_html_e('Stop', MAINICHI_WEB_THIS_PLUGIN_NAME); //停止する ?></option>
								<option value="no"<?php if($cartButtonMaintenance == 'no' || empty($cartButtonMaintenance)){echo ' selected';} ?>><?php esc_html_e('No stop', MAINICHI_WEB_THIS_PLUGIN_NAME); //停止しない ?></option>
							</select>
							<p class="description"><?php esc_html_e('The cart button will not be clickable, and the message "Under maintenance" will be displayed. Please use this function during maintenance.', MAINICHI_WEB_THIS_PLUGIN_NAME); //カートボタンをクリックできなくし、「只今メンテナンス中です」と表示します。メンテナンス時等にご利用ください。 ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="changeDisplayAdminItem"><?php esc_html_e('Change the number of products displayed in the admin panel', MAINICHI_WEB_THIS_PLUGIN_NAME); //商品マスターの商品表示数を変更 ?></label></th>
						<td>
							<input name="changeDisplayAdminItem" type="number" class="marginRight8" value="<?php echo $changeDisplayAdminItem; ?>" class="regular-text" min="1" max="1000" placeholder="30"><?php esc_html_e('items', MAINICHI_WEB_THIS_PLUGIN_NAME); //件 ?>
							<p class="description"><?php esc_html_e('Change the number of products to be displayed in "Welcart Shop > Product Master".', MAINICHI_WEB_THIS_PLUGIN_NAME); //「Welcart Shop ＞ 商品マスター」で表示する商品数を変更します。 ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="soldoutAlertMessage"><?php esc_html_e('Send out-of-stock alerts', MAINICHI_WEB_THIS_PLUGIN_NAME); //在庫切れアラート送信 ?></label></th>
						<td>
							<select name="soldoutAlertMessage">
								<option value="no"<?php if($soldoutAlertMessage == 'no' || empty($soldoutAlertMessage)){echo ' selected';} ?>><?php esc_html_e('Do not send', MAINICHI_WEB_THIS_PLUGIN_NAME); //送信しない ?></option>
								<option value="yes"<?php if($soldoutAlertMessage == 'yes'){echo ' selected';} ?>><?php esc_html_e('Send', MAINICHI_WEB_THIS_PLUGIN_NAME); //送信する ?></option>
							</select>
							<p class="description"><?php esc_html_e('Upon completion of the order, you will be notified by e-mail of products whose stock quantity is "0".', MAINICHI_WEB_THIS_PLUGIN_NAME); //受注完了時、在庫数が「0」になった商品をメールにてお知らせいたします。 ?></p>
						</td>
					</tr>
				</table>
			</div>
			<input type="hidden" name="submit_settings" id="submit_settings" value="<?php esc_html_e('Support function settings', MAINICHI_WEB_THIS_PLUGIN_NAME); //サポート機能の設定 ?>">
			<p class="submit">
				<input type="submit" name="submit_settings_data_1" id="submit_settings_data_1" class="button-primary" value="<?php esc_html_e('Save the settings', MAINICHI_WEB_THIS_PLUGIN_NAME); //設定を保存 ?>">
			</p>
		</form>
	</div>

	<div id="settings2" class="tab_content">
		<form class="settingsForm2" method="post" action="admin.php?page=ffw_function_settings">
			<div class="ffwGrayBackground">
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><label for="shippingDiscountsMessage"><?php esc_html_e('Show the amount up to the shipping discount', MAINICHI_WEB_THIS_PLUGIN_NAME); //送料割引までの金額を表示 ?></label></th>
						<td>
							<select name="shippingDiscountsMessage">
								<option value="yes"<?php if($shippingDiscountsMessage == 'yes'){echo ' selected';} ?>><?php esc_html_e('Show', MAINICHI_WEB_THIS_PLUGIN_NAME); //表示する ?></option>
								<option value="no"<?php if($shippingDiscountsMessage == 'no' || empty($shippingDiscountsMessage)){echo ' selected';} ?>><?php esc_html_e('Hide', MAINICHI_WEB_THIS_PLUGIN_NAME); //表示しない ?></option>
							</select>
							<p class="description"><?php esc_html_e('Show the amount up to the shipping discount on the cart page. This is available if you have set up a "Shipping Discount".', MAINICHI_WEB_THIS_PLUGIN_NAME); //カートページに送料割引までの金額を表示します。「送料割引」を設定している場合にご利用いただけます。 ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="postagePrivilegeMessage"><?php esc_html_e('Show the amount up to free shipping', MAINICHI_WEB_THIS_PLUGIN_NAME); //送料無料までの金額を表示 ?></label></th>
						<td>
							<select name="postagePrivilegeMessage">
								<option value="yes"<?php if($postagePrivilegeMessage == 'yes'){echo ' selected';} ?>><?php esc_html_e('Show', MAINICHI_WEB_THIS_PLUGIN_NAME); //表示する ?></option>
								<option value="no"<?php if($postagePrivilegeMessage == 'no' || empty($postagePrivilegeMessage)){echo ' selected';} ?>><?php esc_html_e('Hide', MAINICHI_WEB_THIS_PLUGIN_NAME); //表示しない ?></option>
							</select>
							<p class="description"><?php esc_html_e('Show the amount up to free shipping on the cart page, which is available if you have set the "Conditions for free shipping" in Welcart.', MAINICHI_WEB_THIS_PLUGIN_NAME); //カートページに送料無料までの金額を表示します。Welcartの「送料無料条件」を設定している場合にご利用いただけます。 ?></p>
						</td>
					</tr>
					<?php if(get_locale() == 'ja'): ?>
					<tr valign="top">
						<th scope="row"><label for="displayFurigana"><?php esc_html_e('Show/hide "Furigana"', MAINICHI_WEB_THIS_PLUGIN_NAME); //「フリガナ」の表示・非表示 ?></label></th>
						<td>
							<select name="displayFurigana">
								<option value="yes"<?php if($displayFurigana == 'yes' || empty($displayFurigana)){echo ' selected';} ?>><?php esc_html_e('Show', MAINICHI_WEB_THIS_PLUGIN_NAME); //表示する ?></option>
								<option value="no"<?php if($displayFurigana == 'no'){echo ' selected';} ?>><?php esc_html_e('Hide', MAINICHI_WEB_THIS_PLUGIN_NAME); //表示しない ?></option>
							</select>
							<p class="description"><?php esc_html_e('You can delete and hide the "Furigana" field from the input form.', MAINICHI_WEB_THIS_PLUGIN_NAME); //入力フォームから「フリガナ」の項目を削除し非表示にできます。 ?></p>
						</td>
					</tr>
					<?php endif; ?>
					<tr valign="top">
						<th scope="row"><label for="displayFaxNum"><?php esc_html_e('Show/hide "FAX Number"', MAINICHI_WEB_THIS_PLUGIN_NAME); //「FAX番号」の表示・非表示 ?></label></th>
						<td>
							<select name="displayFaxNum">
								<option value="yes"<?php if($displayFaxNum == 'yes' || empty($displayFaxNum)){echo ' selected';} ?>><?php esc_html_e('Show', MAINICHI_WEB_THIS_PLUGIN_NAME); //表示する ?></option>
								<option value="no"<?php if($displayFaxNum == 'no'){echo ' selected';} ?>><?php esc_html_e('Hide', MAINICHI_WEB_THIS_PLUGIN_NAME); //表示しない ?></option>
							</select>
							<p class="description"><?php esc_html_e('You can delete and hide the "FAX Number" field from the input form.', MAINICHI_WEB_THIS_PLUGIN_NAME); //入力フォームから「FAX番号」の項目を削除し非表示にできます。 ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="displayDiscountForCart"><?php esc_html_e('Show/hide "Discount Amount" on cart page', MAINICHI_WEB_THIS_PLUGIN_NAME); //カートページに「割引額」の表示・非表示 ?></label></th>
						<td>
							<select name="displayDiscountForCart">
								<option value="yes"<?php if($displayDiscountForCart == 'yes'){echo ' selected';} ?>><?php esc_html_e('Show', MAINICHI_WEB_THIS_PLUGIN_NAME); //表示する ?></option>
								<option value="no"<?php if($displayDiscountForCart == 'no' || empty($displayDiscountForCart)){echo ' selected';} ?>><?php esc_html_e('Hide', MAINICHI_WEB_THIS_PLUGIN_NAME); //表示しない ?></option>
							</select>
							<p class="description"><?php esc_html_e('You can display a discount amount such as "Campaign Discount" on the cart page.', MAINICHI_WEB_THIS_PLUGIN_NAME); //カートページに「キャンペーン割引」等の割引額を表示できます。 ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="cartButtonEntryNum"><?php esc_html_e('Change cart button quantity entry to "number"', MAINICHI_WEB_THIS_PLUGIN_NAME); //カートボタン数量入力を「number」に変更 ?></label></th>
						<td>
							<select name="cartButtonEntryNum">
								<option value="yes"<?php if($cartButtonEntryNum == 'yes'){echo ' selected';} ?>><?php esc_html_e('Change', MAINICHI_WEB_THIS_PLUGIN_NAME); //変更する ?></option>
								<option value="no"<?php if($cartButtonEntryNum == 'no' || empty($cartButtonEntryNum)){echo ' selected';} ?>><?php esc_html_e('No change', MAINICHI_WEB_THIS_PLUGIN_NAME); //変更しない ?></option>
							</select>
							<p class="description"><?php esc_html_e('The type of quantity entry in the cart button will be set to "number" so that the quantity can be changed by cursor operation.', MAINICHI_WEB_THIS_PLUGIN_NAME); //カートボタンの数量入力のタイプを「number」にし、カーソル操作で数量の変更ができるようになります。 ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="quantityEntryNum"><?php esc_html_e('Change cart page quantity entry to "number"', MAINICHI_WEB_THIS_PLUGIN_NAME); //カートページ数量入力を「number」に変更 ?></label></th>
						<td>
							<select name="quantityEntryNum">
								<option value="yes"<?php if($quantityEntryNum == 'yes'){echo ' selected';} ?>><?php esc_html_e('Change', MAINICHI_WEB_THIS_PLUGIN_NAME); //変更する ?></option>
								<option value="no"<?php if($quantityEntryNum == 'no' || empty($quantityEntryNum)){echo ' selected';} ?>><?php esc_html_e('No change', MAINICHI_WEB_THIS_PLUGIN_NAME); //変更しない ?></option>
							</select>
							<p class="description"><?php esc_html_e('The type of quantity entry on the cart page will be set to "number" so that the quantity can be changed by cursor operation.', MAINICHI_WEB_THIS_PLUGIN_NAME); //カートページの数量入力のタイプを「number」にし、カーソル操作で数量の変更ができるようになります。 ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="customOrderTypeDate"><?php esc_html_e('Change the input type of the custom field to "date"', MAINICHI_WEB_THIS_PLUGIN_NAME); //カスタム・フィールドの入力タイプを「date」に変更 ?></label></th>
						<td>
							<select name="customOrderTypeDate">
								<option value="yes"<?php if($customOrderTypeDate == 'yes'){echo ' selected';} ?>><?php esc_html_e('Change', MAINICHI_WEB_THIS_PLUGIN_NAME); //変更する ?></option>
								<option value="no"<?php if($customOrderTypeDate == 'no' || empty($customOrderTypeDate)){echo ' selected';} ?>><?php esc_html_e('No change', MAINICHI_WEB_THIS_PLUGIN_NAME); //変更しない ?></option>
							</select>
							<p class="description"><?php esc_html_e('Change the input type to "date" for the "Custom Order Field" and "Custom Customer Field" and "Custom Delivery Field" that can be added in "Welcart Shop > Cart Page Settings". Make sure the "Field Key" of the input field you want to apply the change to contains the string "date" and the input type is "Text".', MAINICHI_WEB_THIS_PLUGIN_NAME); //「Welcart Shop ＞ カートページ設定」で追加できる「カスタム・オーダーフィールド」「カスタム・カスタマーフィールド」「カスタム・デリバリーフィールド」の入力タイプを「date」に変更します。変更を適用したい入力フィールドの「フィールドキー」に「date」の文字列を含ませ、入力タイプは「テキスト」にしてください。 ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="formErrorCheck"><?php esc_html_e('Check before transitioning to the input form', MAINICHI_WEB_THIS_PLUGIN_NAME); //入力フォームの遷移前チェック ?></label></th>
						<td>
							<select name="formErrorCheck">
								<option value="yes"<?php if($formErrorCheck == 'yes'){echo ' selected';} ?>><?php esc_html_e('Use', MAINICHI_WEB_THIS_PLUGIN_NAME); //利用する ?></option>
								<option value="no"<?php if($formErrorCheck == 'no' || empty($formErrorCheck)){echo ' selected';} ?>><?php esc_html_e('No use', MAINICHI_WEB_THIS_PLUGIN_NAME); //利用しない ?></option>
							</select>
							<p class="description"><?php esc_html_e('In the input form of the cart and member page, the validation check is performed before the page transition to ensure that there are no omissions when clicking "Submit".', MAINICHI_WEB_THIS_PLUGIN_NAME); //カート・メンバーページの入力フォームで、「送信」クリック時に入力漏れがないかページ遷移前にバリデーションチェックします。 ?></p>
						</td>
					</tr>
					<?php
					// ※特定の条件・環境下で会員編集時の本人確認メールが届かない報告があり、機能停止（ver 1.2.4~）
					if(false):
					?>
					<tr valign="top">
						<th scope="row"><label for="preventExpiration"><?php esc_html_e('Prevent documents from expiring by using "Back"', MAINICHI_WEB_THIS_PLUGIN_NAME); //「戻る」によるドキュメント有効切れ防止 ?></label></th>
						<td>
							<select name="preventExpiration">
								<option value="yes"<?php if($preventExpiration == 'yes'){echo ' selected';} ?>><?php esc_html_e('Use', MAINICHI_WEB_THIS_PLUGIN_NAME); //利用する ?></option>
								<option value="no"<?php if($preventExpiration == 'no' || empty($preventExpiration)){echo ' selected';} ?>><?php esc_html_e('No use', MAINICHI_WEB_THIS_PLUGIN_NAME); //利用しない ?></option>
							</select>
							<p class="description"><?php esc_html_e('This prevents the document from expiring due to "Back", thus preventing the form from being re-filled. This may not be applicable in some environments.', MAINICHI_WEB_THIS_PLUGIN_NAME); //「戻る」によるドキュメント有効切れを防ぐことで、フォームの再入力を防ぎます。環境によっては適用されない場合があります。 ?></p>
						</td>
					</tr>
					<?php endif; ?>
					<tr valign="top">
						<th scope="row"><label for="onThanksgivingPage"><?php esc_html_e('Show order number and order date', MAINICHI_WEB_THIS_PLUGIN_NAME); //注文番号・注文日時を表示 ?></label></th>
						<td>
							<select name="onThanksgivingPage">
								<option value="yes"<?php if($onThanksgivingPage == 'yes'){echo ' selected';} ?>><?php esc_html_e('Show', MAINICHI_WEB_THIS_PLUGIN_NAME); //表示する ?></option>
								<option value="no"<?php if($onThanksgivingPage == 'no' || empty($onThanksgivingPage)){echo ' selected';} ?>><?php esc_html_e('Hide', MAINICHI_WEB_THIS_PLUGIN_NAME); //表示しない ?></option>
							</select>
							<p class="description"><?php esc_html_e('The order number and order date will be displayed on the Thank You page (purchase completion screen).', MAINICHI_WEB_THIS_PLUGIN_NAME); //サンクスページ（購入完了画面）に注文番号・注文日時を表示します。 ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="extendSearch"><?php esc_html_e('Expanded search results', MAINICHI_WEB_THIS_PLUGIN_NAME); //検索結果の拡張 ?></label></th>
						<td>
							<select name="extendSearch">
								<option value="yes"<?php if($extendSearch == 'yes'){echo ' selected';} ?>><?php esc_html_e('Use', MAINICHI_WEB_THIS_PLUGIN_NAME); //利用する ?></option>
								<option value="no"<?php if($extendSearch == 'no' || empty($extendSearch)){echo ' selected';} ?>><?php esc_html_e('No use', MAINICHI_WEB_THIS_PLUGIN_NAME); //利用しない ?></option>
							</select>
							<p class="description"><?php esc_html_e('It also includes custom field values in the search target.', MAINICHI_WEB_THIS_PLUGIN_NAME); //検索対象にカスタムフィールドの値も含めます。また、「ひらがな」と「カタカナ」・「全角英数」と「半角英数」を区別せず検索します。 ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="cssInlineOutput"><?php esc_html_e('Inline output of Welcart\'s CSS files', MAINICHI_WEB_THIS_PLUGIN_NAME); //WelcartのCSSファイルをインライン出力 ?></label></th>
						<td>
							<select name="cssInlineOutput">
								<option value="yes"<?php if($cssInlineOutput == 'yes'){echo ' selected';} ?>><?php esc_html_e('Use', MAINICHI_WEB_THIS_PLUGIN_NAME); //利用する ?></option>
								<option value="no"<?php if($cssInlineOutput == 'no' || empty($cssInlineOutput)){echo ' selected';} ?>><?php esc_html_e('No use', MAINICHI_WEB_THIS_PLUGIN_NAME); //利用しない ?></option>
							</select>
							<p class="description"><?php esc_html_e('Output "usces_default.css" and "usces_cart.css" inline in "head" to reduce the number of network communication.', MAINICHI_WEB_THIS_PLUGIN_NAME); //「usces_default.css」と「usces_cart.css」を「head」内にインラインで出力し、ネットワーク通信回数を削減します。 ?></p>
						</td>
					</tr>
				</table>
			</div>
			<input type="hidden" name="submit_settings" id="submit_settings" value="<?php esc_html_e('Usability settings', MAINICHI_WEB_THIS_PLUGIN_NAME); //ユーザビリティの設定 ?>">
			<p class="submit">
				<input type="submit" name="submit_settings_data_2" id="submit_settings_data_2" class="button-primary" value="<?php esc_html_e('Save the settings', MAINICHI_WEB_THIS_PLUGIN_NAME); //設定を保存 ?>">
			</p>
		</form>
	</div>

	<div id="settings3" class="tab_content">
		<div class="ffwGrayBackground overflowHidden marginBottom16">
			<h2 class="marginBottom24"><?php esc_html_e('Plugins', MAINICHI_WEB_THIS_PLUGIN_NAME); //プラグイン ?></h2>
			<div class="flex flexStartWrap colWrap">
				<?php mainichi_web_other_plugins_contents('echo'); ?>
			</div>
		</div>
		<div class="ffwGrayBackground overflowHidden marginBottom16">
			<h2 class="marginBottom24"><?php esc_html_e('Themes', MAINICHI_WEB_THIS_PLUGIN_NAME); //テーマ ?></h2>
			<div class="flex flexStartWrap colWrap">
				<?php mainichi_web_other_theme_contents('echo'); ?>
			</div>
		</div>
		<div class="ffwGrayBackground overflowHidden">
			<h2 class="marginBottom24"><?php esc_html_e('Service', MAINICHI_WEB_THIS_PLUGIN_NAME); //サービス ?></h2>
			<div class="flex flexStartWrap colWrap">
				<?php mainichi_web_other_service_contents('echo'); ?>
			</div>
		</div>
	</div>

</section>

<script>
	/* 設定保存メッセージフェードアウト */
	jQuery(function($){
		setTimeout(function(){
			$(".saveMessage").fadeOut("600");
		},3000);
	});
</script>
<?php else: //Welcartが無効の場合 ?>
<section class="ffwSettingsSection">
	<div class="error"><p><?php echo sprintf(__('To use "Friendly Functions for Welcart", you need to add "<a href="%s" target="_blank" rel="noopener">Welcart e-Commerce</a>" should be enabled.', MAINICHI_WEB_THIS_PLUGIN_NAME), home_url('/wp-admin/plugin-install.php?tab=plugin-information&plugin=usc-e-shop')); //「Friendly Functions for Welcart」を利用するには「Welcart e-Commerce」を有効化してください。 ?></p></div>
</section>
<?php endif; ?>