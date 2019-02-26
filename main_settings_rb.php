<?php

	if(!\current_user_can('activate_plugins'))
	{

		echo '<br /><br />
				<div class="error notice">
	                <p>У Вас не хватает прав на настройку компонента</p>
				</div>
			';
		return;
	}

	\wp_enqueue_script(
		'robokassa_payment_admin_main_payment',
		\plugin_dir_url(__FILE__) . 'assets/js/admin-payment.js'
	);
?>

<div class="content_holder">
<?php

	if (isset($_REQUEST['settings-updated']))
	{
        include 'labelsGenerator.php';
    }

    /** @var array $formProperties */
    $formProperties = [
	    'robokassa_payment_wc_robokassa_enabled',
	    'robokassa_payment_MerchantLogin',
	    'robokassa_payment_shoppass1',
	    'robokassa_payment_shoppass2',
	    'robokassa_payment_test_onoff',
	    'robokassa_payment_testshoppass1',
	    'robokassa_payment_testshoppass2',
	    'robokassa_payment_type_commission',
	    'robokassa_payment_sno',
	    'robokassa_payment_tax',
	    'robokassa_payment_who_commission',
	    'robokassa_payment_size_commission',
	    'robokassa_payment_paytype',
	    'robokassa_payment_SuccessURL',
	    'robokassa_payment_FailURL',
	    'robokassa_payment_paymentMethod',
	    'robokassa_payment_paymentObject',
    ];

    require_once __DIR__ . '/labelsClasses.php';

	foreach(robokassa_payment_add_WC_WP_robokassa_class() as $class):
		$method = new $class;
		$formProperties[] = 'RobokassaOrderPageTitle_'.$method->id;
		$formProperties[] = 'RobokassaOrderPageDescription_'.$method->id;
	endforeach;
?>

<div class="main-settings">
    <div align="left"><p class="big_title_rb">Помощь и инструкция по установке</p></div>
    <p>Введите данные API в разделе "Основные настройки".</p>
    <p>В личном кабинете на сайте Робокассы введите следующие URL адреса:</p>
    <table>
        <tr>
            <td>ResultURL:</td>
            <td><code id="ResultURL"><?php echo site_url('/?robokassa=result'); ?></code></td>
            <td>
                <button class="btn btn-default btn-clipboard btn-main" data-clipboard-target="#ResultURL" onclick="event.preventDefault();">
	                Скопировать
                </button>
            </td>
        </tr>
        <tr>
            <td>SuccessURL:</td>
            <td><code id="SuccessURL"><?php echo site_url('/?robokassa=success'); ?></td>
            <td>
                <button class="btn btn-default btn-clipboard btn-main" data-clipboard-target="#SuccessURL" onclick="event.preventDefault();">
	                Скопировать
                </button>
            </td>
        </tr>
        <tr>
            <td>FailURL:</td>
            <td><code id="FailURL"><?php echo site_url('/?robokassa=fail'); ?></code></td>
            <td>
                <button class="btn btn-default btn-clipboard btn-main" data-clipboard-target="#FailURL" onclick="event.preventDefault();">
	                Скопировать
                </button>
            </td>
        </tr>
    </table>

    <p>Метод отсылки данных <code>POST</code></p>
    <p>Алгоритм расчета хеша<code>MD5</code></p>
    <p>После введите логин и пароли магазина в соответсвующие поля ниже</p>
    <p class="big_title_rb">Основные настройки</p>

    <form action="options.php" method="POST">
        <?php wp_nonce_field('update-options'); ?>

        <p class="mid_title_rb">Настройки соединения</p>

        <table class="form-table">
            <tr valign="top">
                <th scope="row">Включить оплату через Робокассу</th>
                <td>
                    <input type="radio" id="enabled_on" name="robokassa_payment_wc_robokassa_enabled" value="yes"
                        <?php echo get_option('robokassa_payment_wc_robokassa_enabled') == 'yes' ? 'checked="checked"' : ''; ?>>
                    <label for="enabled_on">Стандартная схема</label>

                    <input type="radio" id="enabled_off" name="robokassa_payment_wc_robokassa_enabled" value="no"
                        <?php echo get_option('robokassa_payment_wc_robokassa_enabled') == 'no' ? 'checked="checked"' : ''; ?>>
                    <label for="enabled_off">Отключена</label>

                    <?php /*
                    <input type="radio" id="enabled_torobomarket" name="wc_robokassa_enabled" value="torobomarket" <?php echo get_option('wc_robokassa_enabled') == 'torobomarket' ? 'checked="checked"' : ''; ?>>
                    <label for="enabled_torobomarket">Корзина на Робомаркете</label>
                    */ ?>
                </td>
            </tr>

	        <?php if((int) \count(robokassa_payment_add_WC_WP_robokassa_class()) === 1):?>
		        <tr valign="top">
			        <th scope="row">Заголовок на странице оформления заказа</th>
			        <td>
				        <input type="text" name="RobokassaOrderPageTitle_all" value="<?php echo get_option('RobokassaOrderPageTitle_all'); ?>"/>
			        </td>
		        </tr>
		        <tr valign="top">
			        <th scope="row">Описание на странице оформления заказа</th>
			        <td>
				        <input type="text" name="RobokassaOrderPageDescription_all" value="<?php echo get_option('RobokassaOrderPageDescription_all'); ?>"/>
			        </td>
		        </tr>

	        <?php else:?>
		        <?php foreach(robokassa_payment_add_WC_WP_robokassa_class() as $class): $method = new $class;?>

		            <tr>
			            <th colspan="2">
				            <?=$method->title;?>
			            </th>
		            </tr>
		            <tr valign="top">
		                <td scope="row">Заголовок на странице оформления заказа</td>
		                <td>
			                <input type="text" name="RobokassaOrderPageTitle_<?=$method->id;?>" value="<?php echo get_option('RobokassaOrderPageTitle_'.$method->id); ?>"/>
		                </td>
		            </tr>
		            <tr valign="top">
		                <td scope="row">Описание на странице оформления заказа</td>
		                <td>
			                <input type="text" name="RobokassaOrderPageDescription_<?=$method->id;?>" value="<?php echo get_option('RobokassaOrderPageDescription_'.$method->id); ?>"/>
		                </td>
		            </tr>
		        <?php endforeach;?>
	        <?php endif;?>

            <tr valign="top">
                <th scope="row">Логин магазина</th>
                <td><input type="text" name="robokassa_payment_MerchantLogin" value="<?php echo get_option('robokassa_payment_MerchantLogin'); ?>"/></td>
            </tr>

            <tr valign="top">
                <th scope="row">Пароль магазина #1</th>
                <td><input type="password" name="robokassa_payment_shoppass1" value="<?php echo get_option('robokassa_payment_shoppass1'); ?>"/></td>
            </tr>

            <tr valign="top">
                <th scope="row">Пароль магазина #2</th>
                <td><input type="password" name="robokassa_payment_shoppass2" value="<?php echo get_option('robokassa_payment_shoppass2'); ?>"/></td>
            </tr>
        </table>

        <p class="mid_title_rb">Настройки тестового соединения</p>

        <a class="spoiler_links button">Показать/скрыть</a>

        <div class="spoiler_body">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Включить тестовый режим</th>
                    <td>
                        <input type="radio" id="test_on" name="robokassa_payment_test_onoff" value="true"
                            <?php echo get_option('robokassa_payment_test_onoff') == 'true' ? 'checked="checked"' : ''; ?>>
                        <label for="test_on">Включен</label>

                        <input type="radio" id="test_off" name="robokassa_payment_test_onoff" value="false"
                            <?php echo get_option('robokassa_payment_test_onoff') == 'false' ? 'checked="checked"' : ''; ?>>
                        <label for="test_off">Выключен</label>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Пароль магазина для тестов #1</th>
                    <td><input type="password" name="robokassa_payment_testshoppass1" value="<?php echo get_option('robokassa_payment_testshoppass1'); ?>"/>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Пароль магазина для тестов #2</th>
                    <td><input type="password" name="robokassa_payment_testshoppass2" value="<?php echo get_option('robokassa_payment_testshoppass2'); ?>"/>
                    </td>
                </tr>
            </table>
        </div>

        <p class="mid_title_rb">Общие настройки</p>

        <a class="spoiler_links button">Показать/скрыть</a>

        <div class="spoiler_body">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Статус продавца</th>
                    <td>
                        <input type="radio" id="type_fiz" name="robokassa_payment_type_commission"
                               value="true" <?php echo get_option('robokassa_payment_type_commission') == 'true' ? 'checked="checked"'
                            : ''; ?> onchange="spoleer();"><label for="type_fiz">Физ. лицо</label>
                        <input type="radio" id="type_ur" name="robokassa_payment_type_commission"
                               value="false" <?php echo get_option('robokassa_payment_type_commission') == 'false' ? 'checked="checked"'
                            : ''; ?> onchange="spoleer();"><label for="type_ur">Юр. лицо</label>
                    </td>
                </tr>

                <tr valign="top" id="sno">
                    <th scope="row">Система налогообложения</th>
                    <td>
                        <select id="sno_select" name="robokassa_payment_sno" onchange="spoleer();">
                            <option value="fckoff" <?php echo((get_option('robokassa_payment_sno') == 'fckoff') ? ' selected' : ''); ?>>Не
                                передавать
                            </option>
                            <option value="osn" <?php echo((get_option('robokassa_payment_sno') == 'osn') ? ' selected' : ''); ?>>Общая
                                СН
                            </option>
                            <option value="usn_income" <?php echo((get_option('robokassa_payment_sno') == 'usn_income') ? ' selected'
                                : ''); ?>>Упрощенная СН (доходы)
                            </option>
                            <option value="usn_income_outcome" <?php echo((get_option('robokassa_payment_sno') == 'usn_income_outcome')
                                ? ' selected' : ''); ?>>Упрощенная СН (доходы минус расходы)
                            </option>
                            <option value="envd" <?php echo((get_option('robokassa_payment_sno') == 'envd') ? ' selected' : ''); ?>>Единый
                                налог на вмененный доход
                            </option>
                            <option value="esn" <?php echo((get_option('robokassa_payment_sno') == 'esn') ? ' selected' : ''); ?>>Единый
                                сельскохозяйственный налог
                            </option>
                            <option value="patent" <?php echo((get_option('robokassa_payment_sno') == 'patent') ? ' selected' : ''); ?>>
                                Патентная СН
                            </option>
                        </select>
                    </td>
                </tr>

                <tr valign="top" id="payment_method">
                    <th scope="row">Признак способа расчёта</th>
                    <td>
                        <select id="payment_method_select" name="robokassa_payment_paymentMethod" onchange="spoleer();">
	                        <option value="">Не выбрано</option>
	                        <?php foreach(\Robokassa\Payment\Helper::$paymentMethods as $paymentMethod):?>
		                        <option <?php if(\get_option('robokassa_payment_paymentMethod') === $paymentMethod['code']):?> selected="selected"<?php endif;?> value="<?=$paymentMethod['code'];?>"><?=$paymentMethod['title'];?></option>
	                        <?php endforeach;?>
                        </select>
                    </td>
                </tr>
                <tr valign="top" id="payment_object">
                    <th scope="row">Признак предмета расчёта</th>
                    <td>
                        <select id="payment_object_select" name="robokassa_payment_paymentObject" onchange="spoleer();">
	                        <option value="">Не выбрано</option>
	                        <?php foreach(\Robokassa\Payment\Helper::$paymentObjects as $paymentObject):?>
		                        <option <?php if(\get_option('robokassa_payment_paymentObject') === $paymentObject['code']):?> selected="selected"<?php endif;?>value="<?=$paymentObject['code'];?>"><?=$paymentObject['title'];?></option>
	                        <?php endforeach;?>
                        </select>
                    </td>
                </tr>

                <tr valign="top" id="tax">
                    <th scope="row">Система налогообложения</th>
                    <td>
                        <select id="tax_select" name="robokassa_payment_tax" onchange="spoleer();">
                            <option value="none" <?php echo((get_option('robokassa_payment_tax') == 'none') ? ' selected' : ''); ?>>Не передавать</option>
                            <option value="none" <?php echo((get_option('robokassa_payment_tax') == 'none') ? ' selected' : ''); ?>>Без НДС</option>
                            <option value="vat0" <?php echo((get_option('robokassa_payment_tax') == 'vat0') ? ' selected' : ''); ?>>НДС по ставке 0%</option>
                            <option value="vat10" <?php echo((get_option('robokassa_payment_tax') == 'vat10') ? ' selected' : ''); ?>>НДС чека по ставке 10%</option>
                            <option value="vat18" <?php echo((get_option('robokassa_payment_tax') == 'vat20') ? ' selected' : ''); ?>>НДС чека по ставке 20%</option>
                            <option value="vat110" <?php echo((get_option('robokassa_payment_tax') == 'vat110') ? ' selected' : ''); ?>>НДС чека по расчетной ставке 10/110</option>
                            <option value="vat118" <?php echo((get_option('robokassa_payment_tax') == 'vat120') ? ' selected' : ''); ?>>НДС чека по расчетной ставке 20/120</option>
                        </select>
                    </td>
                </tr>

                <tr valign="top" id="commission">
                    <th scope="row">Кто оплачивает комиссию?</th>
                    <td>
                        <input type="radio" id="who_shop" name="robokassa_payment_who_commission"
                               value="shop" <?php echo get_option('robokassa_payment_who_commission') == 'shop' ? 'checked="checked"'
                            : ''; ?> onchange="spoleer();"><label for="who_shop">Магазин</label>
                        <input type="radio" id="who_client" name="robokassa_payment_who_commission"
                               value="client" <?php echo get_option('robokassa_payment_who_commission') == 'client' ? 'checked="checked"'
                            : ''; ?> onchange="spoleer();"><label for="who_client">Покупатель</label>
                        <input type="radio" id="who_both" name="robokassa_payment_who_commission"
                               value="both" <?php echo get_option('robokassa_payment_who_commission') == 'both' ? 'checked="checked"'
                            : ''; ?> onchange="spoleer();"><label for="who_both">Оба</label>
                    </td>
                </tr>

                <tr valign="top" id="size_commission">
                    <th scope="row">Доля комиссии, оплачиваемой покупателем(%)</th>
                    <td>
                        <input type="text" name="robokassa_payment_size_commission" id="size_commission1"
                               value="<?php echo get_option('robokassa_payment_size_commission'); ?>"/>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Выбор способа оплаты</th>
                    <td>
                        <input type="radio" id="robopaytype" name="robokassa_payment_paytype"
                               value="false" <?php echo get_option('robokassa_payment_paytype') == 'false' ? 'checked="checked"' : ''; ?>><label
                                for="robopaytype">В Робокассе</label>
                        <input type="radio" id="shoppaytype" name="robokassa_payment_paytype"
                               value="true" <?php echo get_option('robokassa_payment_paytype') == 'true' ? 'checked="checked"'
                            : ''; ?>><label for="shoppaytype">В магазине</label>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Страница успеха платежа</th>
                    <td>
                        <select id="SuccessURL" name="robokassa_payment_SuccessURL">
                            <option value="wc_success" <?php echo((get_option('robokassa_payment_SuccessURL') == 'wc_success')
                                ? ' selected' : ''); ?>>Страница "Заказ принят" от WooCommerce
                            </option>
                            <option value="wc_checkout" <?php echo((get_option('robokassa_payment_SuccessURL') == 'wc_checkout')
                                ? ' selected' : ''); ?>>Страница оформления заказа от WooCommerce
                            </option>
                            <?php
                            if (get_pages()) {
                                foreach (get_pages() as $page) {
                                    $selected = ($page->ID == get_option('robokassa_payment_SuccessURL')) ? ' selected' : '';
                                    echo '<option value="'.$page->ID.'"'.$selected.'>'.$page->post_title.'</option>';
                                }
                            }
                            ?>
                        </select><br />
                        <span class="text-description">Эту страницу увидит покупатель, когда оплатит заказ<span>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Страница отказа</th>
                    <td>
                        <select id="FailURL" name="robokassa_payment_FailURL">
                            <option value="wc_checkout" <?php echo((get_option('robokassa_payment_FailURL') == 'wc_checkout')
                                ? ' selected' : ''); ?>>Страница оформления заказа от WooCommerce
                            </option>
                            <option value="wc_payment" <?php echo((get_option('robokassa_payment_FailURL') == 'wc_payment') ? ' selected'
                                : ''); ?>>Страница оплаты заказа от WooCommerce
                            </option>
                            <?php
                            if ($pages = get_pages()) {
                                foreach ($pages as $page) {
                                    $selected = ($page->ID == get_option('robokassa_payment_FailURL')) ? ' selected' : '';
                                    echo '<option value="'.$page->ID.'"'.$selected.'>'.$page->post_title.'</option>';
                                }
                            }
                            ?>
                        </select><br />
	                    <span class="text-description">Эту страницу увидит покупатель, если что-то пойдет не так: например, если ему не хватит денег на карте<span>
                    </td>
                </tr>
            </table>
        </div>

        <input type="hidden" name="action" value="update"/>
        <input type="hidden" name="page_options" value="<?=\implode(',', $formProperties);?>"/>

        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>"/>
        </p>

    </form>
</div>