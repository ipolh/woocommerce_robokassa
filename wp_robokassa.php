<?php

/*
  Plugin Name: Робокасса WooCommerce

  Description: Данный плагин добавляет на Ваш сайт метод оплаты Робокасса для WooCommerce
  Plugin URI: /wp-admin/admin.php?page=main_settings_rb.php
  Author: Робокасса
  Version: 1.2.30
*/

define('DEBUG_STATUS', false);

if (DEBUG_STATUS) {
    error_reporting(E_ALL);
    ini_set('display_errors', 'on');
} else {
    error_reporting(0);
    ini_set('display_errors', 'off');
}

spl_autoload_register(function ($className) {
    $classFile = __DIR__ . "/classes/$className.php";

    if (file_exists($classFile)) {
        require_once $classFile;
    }
});

add_action('admin_menu', 'initMenu'); // Хук для добавления страниц плагина в админку
add_action('plugins_loaded', 'initWC'); // Хук инициализации плагина робокассы
add_action('parse_request', 'wp_robokassa_checkPayment'); // Хук парсера запросов
add_action('parse_request', 'robomarketRequest'); // Хук парсера запросов RoboMarket
add_action('woocommerce_order_status_completed', 'smsWhenCompleted'); // Хук статуса заказа = "Выполнен"
add_filter('cron_schedules', 'labelsCron'); // Добавляем CRON-период в 30 минут
add_action('robokassaCRON1', 'getCurrLabels'); // Хук для CRONа. Обновление доступных способов оплаты.

if (!wp_next_scheduled('robokassaCRON1')) {
    wp_schedule_event(time(), 'halfHour', 'robokassaCRON1');
}

register_activation_hook(__FILE__, 'wp_robokassa_activate'); //Хук при активации плагина. Дефолтовые настройки и таблица в БД для СМС.

/**
 * @param string $str
 */
function DEBUG($str) {
    $file = __DIR__ . '/data/robokassa_DEBUG.txt';

    if (DEBUG_STATUS) {
        $time = time();
        $DEBUGFile = fopen($file, 'a+');
        fwrite($DEBUGFile, date('d.m.Y H:i:s', $time + 10800)." ($time) : $str\r\n");
        fclose($DEBUGFile);
    }
}

/**
 * @param mixed  $order_id
 * @param string $debug
 *
 * @return void
 */
function smsWhenCompleted($order_id, $debug = '') {
    //Отправка СМС-2 если необходимо
    $mrhLogin = get_option('MerchantLogin');
    DEBUG("mrh_login = $mrhLogin \r\n");

    if (get_option('test_onoff') == 'true') {
        $pass1 = get_option('testshoppass1');
        $pass2 = get_option('testshoppass2');
    } else {
        $pass1 = get_option('shoppass1');
        $pass2 = get_option('shoppass2');
    }

    $debug .= "pass1 = $pass1 \r\n";
    $debug .= "pass2 = $pass2 \r\n";

    if (get_option('sms2_enabled') == 'on') {
        $debug .= "Условие СМС-2 верно! \r\n";

        $order = wc_get_order($order_id);

        $phone = $order->billing_phone;
        $debug .= "phone = $phone \r\n";

        $message = get_option('sms2_text');
        $debug .= "message = $message \r\n";

        $translit = (get_option('sms_translit') == 'on');
        $debug .= "translit = $translit \r\n";
        $debug .= "order_id = $order_id \r\n";

        $roboDataBase = new RoboDataBase(mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME));
        $robokassa = new RobokassaPayAPI($mrhLogin, get_option('shoppass1'), get_option('shoppass2'));

        $sms = new RobokassaSms($roboDataBase, $robokassa, $phone, $message, $translit, $order_id, 2);
        $sms->send();
    }

    if (DEBUG_STATUS) {
        $filePath = __DIR__ . '/data/robokassa_DEBUG.txt';

        $date = date('H:i:s', strtotime('+3 hours'));
        $file = fopen($_SERVER['DOCUMENT_ROOT'].$filePath, 'a+');

        fwrite($file, "NEW SMS-2 RECORD ($date) \r\n");
        fwrite($file, "$debug \r\n");
        fclose($file);
    }
}

/**
 * @param string $debug
 *
 * @return void
 */
function wp_robokassa_activate($debug) {
    $file = __DIR__ . '/data/robokassa_DEBUG.txt';

    $time = time();

    $debug .= date('H:i:s', strtotime('+3 hours', $time))." (Timestamp: $time) Starting plugin activation... \r\n";

    $dbPrefix = getDbPrefix();

    $roboDataBase = new RoboDataBase(mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME));

    $roboDataBase->query("CREATE TABLE IF NOT EXISTS `{$dbPrefix}sms_stats` (`sms_id` int(10) unsigned NOT NULL AUTO_INCREMENT, `order_id` int(11) NOT NULL, `type` int(1) NOT NULL, `status` int(11) NOT NULL DEFAULT '0', `number` varchar(11) NOT NULL, `text` text NOT NULL, `send_time` datetime DEFAULT NULL, `response` text, `reply` text, PRIMARY KEY (`sms_id`), KEY `order_id` (`order_id`), KEY `status` (`status`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");
    $roboDataBase->query("CREATE TABLE IF NOT EXISTS `{$dbPrefix}robomarket_orders` (`post_id` int(11) NOT NULL COMMENT 'Id поста, он же id заказа', `other_id` int(11) NOT NULL COMMENT 'Id на стороне робомаркета', PRIMARY KEY (`post_id`,`other_id`), UNIQUE KEY `other_id` (`other_id`), UNIQUE KEY `post_id` (`post_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

    $debug .= "Database connected successful! \r\n";

    add_option('wc_robokassa_enabled', 'no');
    add_option('test_onoff', 'false');
    add_option('type_commission', 'true');
    add_option('tax', 'none');
    add_option('sno', 'fckoff');
    add_option('who_commission', 'shop');
    add_option('paytype', 'false');
    add_option('SuccessURL', 'wc_success');
    add_option('FailURL', 'wc_checkout');

    $debug .= "Default options seted successful! \r\n";

    if (DEBUG_STATUS) {
        $file = fopen($_SERVER['DOCUMENT_ROOT'].$file, 'a++');
        fwrite($file, "$debug \r\n");
        fclose($file);
    }
}

/**
 * @param $schedules
 *
 * @return mixed
 */
function labelsCron($schedules) {
    $schedules['halfHour'] = array(
        'interval' => 30 * MINUTE_IN_SECONDS, // каждые 30 минут
        'display' => __('Half hour'),
    );

    return $schedules;
}

/**
 * @param string $returned
 *
 * @return void
 */
function cronLog($returned = 'success') {
    $file = __DIR__ . '/data/CRONLog/log.txt';

    if (DEBUG_STATUS) {
        $cronTestFile = fopen($_SERVER['DOCUMENT_ROOT'].$file, 'a+');

        fwrite($cronTestFile, date('d.m.Y H:i:s')." Worked succesfull! \r\n");
        fwrite($cronTestFile, "Returned => $returned \r\n\r\n");
        fclose($cronTestFile);
    }
}

/**
 * @return void
 */
function getCurrLabels() {
    cronLog('Labels Loaded!');
}

/**
 * @return void
 */
function initMenu() {
    add_submenu_page('woocommerce', 'Настройки Робокассы', 'Настройки Робокассы', 8, 'main_settings_rb.php', 'main_settings');
    add_submenu_page('main_settings_rb.php', 'Основные настройки', 'Основные настройки', 8, 'main_rb', 'main_settings');
    add_submenu_page('main_settings_rb.php', 'Настройки СМС', 'Настройки СМС', 8, 'sms_rb', 'sms_settings');
    add_submenu_page('main_settings_rb.php', 'РобоМаркет', 'РобоМаркет', 8, 'robomarket_rb', 'robomarket_settings');
    add_submenu_page('main_settings_rb.php', 'Генерировать YML', 'Генерировать YML', 8, 'YMLGenerator', 'yml_generator');
}

/**
 * @param string $name
 * @param mixed  $order_id
 *
 * @return string
 */
function get_success_fail_url($name, $order_id) {
    $order = new WC_Order($order_id);

    switch ($name) {
        case 'wc_success':
            return $order->get_checkout_order_received_url();
        case 'wc_checkout':
            return $order->get_view_order_url();
        case 'wc_payment':
            return $order->get_checkout_payment_url();
        default:
            return get_page_link(get_option($name));
    }
}

/**
 * @return void
 */
function wp_robokassa_checkPayment() {
    if (isset($_REQUEST['robokassa'])) {
        $mrhLogin = get_option('MerchantLogin');

        if (get_option('test_onoff') == 'true') {
            $pass1 = get_option('testshoppass1');
            $pass2 = get_option('testshoppass2');
        } else {
            $pass1 = get_option('shoppass1');
            $pass2 = get_option('shoppass2');
        }

        $returner = '';

        if ($_REQUEST['robokassa'] === 'result') {
            $OutSum_confirm = $_REQUEST['OutSum'];
            $InvId_confirm = $_REQUEST['InvId'];
            $sign = $_REQUEST['SignatureValue'];

            $str = "$OutSum_confirm:$InvId_confirm:$pass2";

            $crc_confirm = strtoupper(md5($str));

            if ($crc_confirm == $sign) {
                $robokassa = new RobokassaPayAPI($mrhLogin, $pass1, $pass2);

                if ($robokassa->reCheck($_REQUEST['InvId'])) {
                    $order = new WC_Order($_REQUEST['InvId']);
                    $order->add_order_note('Заказ успешно оплачен!');
                    $order->payment_complete();

                    WC()->cart->empty_cart();

                    $returner = 'OK'.$_REQUEST['InvId'];

                    // Отправка СМС-1 если необходимо
                    if (get_option('sms1_enabled') == 'on') {
                        $phone = $order->billing_phone;
                        $message = get_option('sms1_text');
                        $translit = (get_option('sms_translit') == 'on');
                        $order_id = $_REQUEST['InvId'];

                        $dataBase = new RoboDataBase(mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME));
                        $robokassa = new RobokassaPayAPI($mrhLogin, get_option('shoppass1'), get_option('shoppass2'));

                        $sms = new RobokassaSms($dataBase, $robokassa, $phone, $message, $translit, $order_id, 1);
                        $sms->send();
                    }
                } else {
                    $order = new WC_Order($_REQUEST['InvId']);
                    $order->add_order_note('Заказ отменен!');
                    $order->update_status('failed');

                    $returner = 'NOT 2 OK'.$_REQUEST['InvId'];
                }
            } else {
                $order = new WC_Order($_REQUEST['InvId']);
                $order->add_order_note('Bad CRC');
                $order->update_status('failed');

                $returner = 'NOT 1 OK'.$_REQUEST['InvId'];
            }
        }

        if ($_REQUEST['robokassa'] == 'success') {
            header('Location:'.get_success_fail_url(get_option('SuccessURL'), $_REQUEST['InvId']));
            die;
        }

        if ($_REQUEST['robokassa'] == 'fail') {
            header('Location:'.get_success_fail_url(get_option('FailURL'), $_REQUEST['InvId']));
            die;
        }

        echo $returner;
        die;
    }
}

/**
 * Хеширование пароля
 *
 * @param string $document
 * @param string $secret
 *
 * @return string
 */
function getRobomarketHeaderHash($document, $secret) {
    return strtoupper(md5($document.$secret));
}

/**
 * Обработка запросов, приходящих из робомаркета
 *
 * @return void
 */
function robomarketRequest() {
    if (isset($_REQUEST['robomarket'])) {
        $requestBody = file_get_contents('php://input');

        $robomarketSecret = get_option('robomarket_secret');
        $headerRequest = getRobomarketHeaderHash($requestBody, $robomarketSecret);

        $headers = getallheaders();

        $roboSignature = isset($headers['Robosignature']) ? $headers['Robosignature'] : null;

        if ($roboSignature !== $headerRequest) {
            DEBUG($requestBody);
            DEBUG($robomarketSecret);
            DEBUG("Header hash wrong!!! Calc Hash: $headerRequest Got Hash: $roboSignature");

            die('Header hash wrong!!!');
        }

        header('Content-type: application/json');

        // Запрос на резервацию товара в Робомаркете, сбор всех данных, поступивших из запроса,
        // создание заказа, добавление в него всех выбранных продуктов, отправка запроса в Робокассу,
        // в конце - ответ от Робокассы.

        $mainResponse = '';

        $request = json_decode($requestBody, true);

        if (isset($request['Robomarket']['ReservationRequest'])) {
            $reservationRequest = $request['Robomarket']['ReservationRequest'];

            $totalCost = $reservationRequest['TotalCost'];

            if ($totalCost !== 0) {
                $items = $reservationRequest['Items'];

                if (!empty($items) && is_array($items)) {
                    $customer = $reservationRequest['Customer'];

                    $lastItem = end($items);

                    $delivery = $lastItem['Delivery'];

                    $deliveryCity = 'Не указано';
                    $deliveryAddress = 'Не указано';
                    $deliveryAddress1 = 'Не указано';

                    if (isset($delivery['City'])) {
                        $deliveryCity = $delivery['City'];
                    }

                    if (isset($delivery['Address'])) {
                        $deliveryAddress = $delivery['Address'];
                    }

                    if (isset($delivery['City']) && isset($delivery['Address'])) {
                        $deliveryAddress1 = $delivery['City'].' '.$delivery['Address'];
                    }

                    $orderId = $reservationRequest['OrderId'];

                    $order = wc_create_order();

                    if ($order instanceof WC_Order) {
                        foreach ($items as $item) {
                            $invId = $item['OfferId'];

                            $product = wc_get_product($invId);

                            $quantity = $item['Quantity'];

                            if ($product->get_stock_quantity() > $quantity || $product->get_stock_status() == 'instock') {
                                $order->add_product($product, $quantity);
                            } else {
                                $mainResponse = json_encode(array(
                                    'Robomarket' => array(
                                        'ReservationFailure' => array(
                                            'OrderId' => $reservationRequest['product_id'],
                                            'Error' => array(
                                                'ErrorCode' => 'NotEnoughGoodsInStock',
                                            ),
                                        ),
                                    ),
                                ));
                                $order->add_order_note('[RoboMarket]Резервация не удалось');
                                $order->update_status('failed');
                            }
                        }

                        list($customerFirstName, $customerLastName) = explode(' ', $customer['Name']);

                        $order->set_address(array(
                            'first_name' => $customerFirstName,
                            'last_name' => $customerLastName,
                            'email' => $customer['Email'],
                            'phone' => $customer['Phone'],
                            'address_1' => $deliveryAddress1,
                            'address_2' => $deliveryAddress,
                            'city' => $deliveryCity,
                        ), 'billing');

                        $order->calculate_totals();

                        $reservationTime = strtotime($reservationRequest['MinPaymentDue'].' +1 hour');

                        if ($mainResponse == '') {
                            $order->add_order_note('[RoboMarket]Заказ зарезервирован');
                            $order->save();

                            saveRobomarketOrder($order, $orderId);

                            $mainResponse = json_encode(array(
                                'Robomarket' => array(
                                    'ReservationSuccess' => array(
                                        'PaymentDue' => date('c', $reservationTime),
                                        'OrderId' => $orderId,
                                        'InvoiceId' => $order->get_id(),
                                    ),
                                ),
                            ));
                        }
                    }
                }
            }
        }

        // Поиск заказа по id, запрос на оплату заказа и изменение его статуса,
        // в конце - ответ от Робокассы, подтверждающий оплату.

        if (isset($request['Robomarket']['PurchaseRequest'])) {
            $purchaseRequest = $request['Robomarket']['PurchaseRequest'];

            $orderId = $purchaseRequest['OrderId'];

            $order = loadRobomarketOrder($orderId);

            if (!empty($order)) {
                if ('completed' !== $order->get_status()) {
                    /** @var WC_Order_Item_Product $item */
                    foreach ($order->get_items() as $item) {
                        $product = $item->get_product();
                        $product->set_stock_quantity($product->get_stock_quantity() - $item->get_quantity());
                        $product->save();
                    }

                    $order->add_order_note('[RoboMarket]Заказ оплачен');
                    $order->update_status('completed');
                    $order->payment_complete();

                    $mainResponse = json_encode(array(
                        'Robomarket' => array(
                            'PurchaseResponse' => array(
                                'OrderId' => $orderId,
                                'Error' => array(
                                    'ErrorCode' => 'Ok',
                                ),
                            ),
                        ),
                    ));
                }
            }
        }

        // Запрос на отмену уже имеющегося заказа и изменение его статуса,
        // в конце - Робокасса присылает ответ о том, что заказ отменен.

        if (isset($request['Robomarket']['CancellationRequest'])) {
            $cancellationRequest = $request['Robomarket']['CancellationRequest'];

            $invId = $cancellationRequest['InvoiceId'];
            $orderId = $cancellationRequest['OrderId'];

            $order = new WC_Order($invId);
            $order->add_order_note('[RoboMarket]Заказ отменен');
            $order->update_status('failed');

            $mainResponse = json_encode(array(
                'Robomarket' => array(
                    'CancellationResponse' => array(
                        'OrderId' => $orderId,
                        'Error' => array(
                            'ErrorCode' => 'Ok',
                        ),
                    ),
                ),
            ));
        }

        // Запрос, посылаемый при просроченной оплате, если по итогом запроса
        // приходит подтверждение, происходит переход на запрос об оплате.

        if (isset($request['Robomarket']['YaReservationRequest'])) {
            $yaReservationRequest = $request['Robomarket']['YaReservationRequest'];

            $items = $yaReservationRequest['Items'];

            $order = wc_get_order();

            foreach ($items as $item) {
                $product = wc_get_product($item['OfferId']);

                $quantity = $item['Quantity'];

                if ($product->get_stock_quantity() > $quantity || $product->get_stock_status() == 'instock') {
                    $order->add_product($product, $quantity);
                } else {
                    $mainResponse = json_encode(array(
                        'Robomarket' => array(
                            'ReservationFailure' => array(
                                'OrderId' => $yaReservationRequest['product_id'],
                                'Error' => array(
                                    'ErrorCode' => 'NotEnoughGoodsInStock',
                                ),
                            ),
                        ),
                    ));
                    $order->add_order_note('[RoboMarket]Резервация не удалось');
                    $order->update_status('failed');
                }
            }

            $order->set_address(array(// Здесь наверное что-то должно быть, но Егорушка малолетний долбоклюй
            ), 'billing');

            $order->calculate_totals();

            if ($mainResponse == '') {
                $order->add_order_note('[RoboMarket]Заказ зарезервирован');
                $mainResponse = json_encode(array(
                    'Robomarket' => array(
                        'ReservationSuccess' => array(
                            'OrderId' => $request['OrderId'],
                        ),
                    ),
                ));
            }
        }

        $headerResponse = getRobomarketHeaderHash($mainResponse, $robomarketSecret);

        header('RoboSignature: '.$headerResponse);

        DEBUG('RoboMarket request: '.$requestBody);
        DEBUG('RoboMarket request hash: '.$headerRequest);
        DEBUG('Main hash: '.$roboSignature);
        DEBUG('RoboMarket response: '.$mainResponse);
        DEBUG('Robomarket secret: '.$robomarketSecret);
        DEBUG('RoboMarket response hash: '.$headerResponse);
        DEBUG('Request Headers = {');

        foreach (getallheaders() as $key => $value) {
            DEBUG("\t$key => $value");
        }

        DEBUG('}');

        echo $mainResponse;

        die();
    }
}

/**
 * Формирование формы, перенаправляющей пользователя на сайт робокассы
 *
 * Включает в себя подготовку данных и рендеринг самой формы
 *
 * @param mixed $order_id - вукомерс настолько гейский, что любое значение валидным считает
 * @param       $label
 * @param int   $commission
 *
 * @return void
 */
function createFormWC($order_id, $label, $commission = 0) {

    $mrhLogin = get_option('MerchantLogin');

    if (get_option('test_onoff') == 'true') {
        $pass1 = get_option('testshoppass1');
        $pass2 = get_option('testshoppass2');
    } else {
        $pass1 = get_option('shoppass1');
        $pass2 = get_option('shoppass2');
    }

    $rb = new RobokassaPayAPI($mrhLogin, $pass1, $pass2);

    $order = wc_get_order($order_id);

    $sno = get_option('sno');
    $tax = get_option('tax');

    $receipt = array();

    if ($sno != 'fckoff') {
        $receipt['sno'] = $sno;
    }

    global $woocommerce;
    $cart = $woocommerce->cart->get_cart();

    foreach ($cart as $item) {
        $product = wc_get_product($item['product_id']);

        $current['name'] = $product->get_title();
        $current['quantity'] = (float) $item['quantity'];
        $current['sum'] = $item['line_total'];

        if (isset($receipt['sno']) && ($receipt['sno'] == 'osn')) {
            $current['tax'] = $tax;
        } else {
            $current['tax'] = 'none';
        }

        $receipt['items'][] = $current;
    }

    if((double) $order->get_shipping_total() > 0)
    {
	    $current['name'] = 'Доставка';
	    $current['quantity'] = 1;
	    $current['sum'] = (double) \sprintf("%01.2f", $order->get_shipping_total());

	    if (isset($receipt['sno']) && ($receipt['sno'] == 'osn')) {
		    $current['tax'] = $tax;
	    } else {
		    $current['tax'] = 'none';
	    }

	    $receipt['items'][] = $current;
    }

    $order_total = floatval($order->get_total());

    if (get_option('paytype') == 'true') {
        if (get_option('who_commission') == 'shop') {
            DEBUG("who_commisson = shop");

            $commission = $commission / 100;
            DEBUG("commission = $commission");

            $incSum = number_format($order_total * (1 + (0 * $commission)), 2, '.', '');
            DEBUG("incSum = $incSum");

            $commission = $rb->getCommission($label, $incSum) / 100;
            DEBUG("commission = $commission");

            $sum = $rb->getCommissionSum($label, $incSum);
            DEBUG("sum = $sum");
        } elseif (get_option('who_commission') == 'both') {
            $aCommission = get_option('size_commission') / 100;
            DEBUG("who_commisson = both");
            DEBUG("aCommission = $aCommission");

            $commission = $commission / 100;
            DEBUG("commission = $commission");

            $incSum = number_format($order_total * (1 + ($aCommission * $commission)), 2, '.', '');
            DEBUG("incSum = $incSum");

            $commission = $rb->getCommission($label, $incSum) / 100;
            DEBUG("commission = $commission");

            $sum = $rb->getCommissionSum($label, $incSum);
            DEBUG("sum = $sum");
        } else {
            DEBUG("who_commission = client");
            $sum = number_format($order_total, 2, '.', '');
            DEBUG("sum = $sum");
        }
    } else {
        DEBUG("paytype = false");
        $sum = number_format($order_total, 2, '.', '');
        DEBUG("sum = $sum");
    }

    $invDesc = implode(', ', array_map(function(WC_Order_Item_Product $item) {
        return $item->get_name();
    }, $order->get_items()));

    if (iconv_strlen($invDesc) > 100) {
        $invDesc = "Заказ номер $order_id";
    }

    $receiptForForm = (get_option('type_commission') == 'false') ? $receipt : array();

    echo $rb->createForm($sum, $order_id, $invDesc, get_option('test_onoff'), $label, $receiptForForm);
}

/**
 * Начало оформления заказа
 *
 * @return void
 */
function initWC() {
    if (!defined('ABSPATH')) {
        exit;
    }

    if (!class_exists('WC_Payment_Gateway')) {
        return;
    }

    require 'labelsClasses.php';

    add_filter('woocommerce_payment_gateways', 'add_WC_WP_robokassa_class');
}

/**
 * Создает связь между заказом в woocommerce и заказом в робомаркете
 *
 * @param WC_Order $order
 * @param int      $otherId
 *
 * @return void
 *
 * @throws Exception
 */
function saveRobomarketOrder(WC_Order $order, $otherId) {
    $roboDataBase = new RoboDataBase(mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME));

    $dbPrefix = getDbPrefix();
    $postId = $order->get_id();

    if (0 == mysqli_num_rows($roboDataBase->query("SELECT * FROM `{$dbPrefix}robomarket_orders` WHERE `post_id` = '$postId' AND `other_id` = $otherId"))) {
        if (false === $roboDataBase->query("INSERT INTO `{$dbPrefix}robomarket_orders` (`post_id`, `other_id`) VALUES ('$postId', '$otherId')")) {
            throw new Exception('Не удалось сохранить информацию о заказе, полученную из робомаркета');
        }
    }
}

/**
 * Возвращает объект заказа по id в робомаркете
 *
 * @param int $otherId
 *
 * @return WC_Order | null
 */
function loadRobomarketOrder($otherId) {
    $roboDataBase = new RoboDataBase(mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME));

    $dbPrefix = getDbPrefix();

    $result = $roboDataBase->query("SELECT * FROM `{$dbPrefix}robomarket_orders` WHERE `other_id` = $otherId");

    if (!empty($result)) {
        $robomarketOrder = $result->fetch_assoc();

        $order = wc_get_order($robomarketOrder['post_id']);

        if ($order instanceof WC_Order) {
            return $order;
        }
    }

    return null;
}

/**
 * @return void
 */
function main_settings() {
    $_GET['li'] = 'main';
    include 'menu_rb.php';
    include 'main_settings_rb.php';
}

/**
 * @return void
 */
function sms_settings() {
    $_GET['li'] = 'sms';
    include 'menu_rb.php';
    include 'sms_settings_rb.php';
}

/**
 * @return void
 */
function robomarket_settings() {
    $_GET['li'] = 'robomarket';
    include 'menu_rb.php';
    include 'robomarket_settings.php';
}

/**
 * @return void
 */
function yml_generator() {
    $_GET['li'] = 'robomarket';
    include 'menu_rb.php';
    include 'YMLGenerator.php';
    generateYML();
}

/**
 * Возвращает префикс таблиц в базе данных
 *
 * @return string
 *
 * @throws Exception
 */
function getDbPrefix() {
    global $wpdb;

    if ($wpdb instanceof wpdb) {
        return $wpdb->prefix;
    }

    throw new Exception('Объект типа "wpdb" не найден в глобальном пространстве имен по имени "$wpdb"');
}

if (!function_exists('getallheaders')) {

    /**
     * Возвращает заголовки http-запроса
     *
     * Не во всех окружениях эта функция есть, а для работы модуля она необходима
     *
     * @return array
     */
    function getallheaders() {
        static $headers = null;

        if (null === $headers) {
            $headers = array();

            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) == 'HTTP_') {
                    $headers[str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($name, 5)))))] = $value;
                }
            }
        }

        return $headers;
    }
}