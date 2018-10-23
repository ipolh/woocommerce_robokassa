<?php

/**
 * Класс выбора типа оплаты на стороне Робокассы
 */
class WC_WP_robokassa extends WC_Payment_Gateway {

    /**
     * @var string
     */
    public $long_name;

    /**
     * @var int | float
     */
    public $commission;

    public function __construct() {


	    $this->title = get_option('RobokassaOrderPageTitle', 'Робокасса');
	    $this->description = get_option('RobokassaOrderPageDescription');

        $this->init_form_fields();
        $this->init_settings();

        $this->method_description = $this->long_name.'<br>Больше настроек в <a href="'.admin_url('/admin.php?page=main_rb').'">панели плагина</a>';

        add_action('woocommerce_api_wc_'.$this->id, array($this, 'check_ipn'));
        add_action('woocommerce_receipt_'.$this->id, array($this, 'receipt_page'));
    }

    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title' => 'Включить/Выключить',
                'type' => 'checkbox',
                'label' => $this->long_name,
                'default' => 'yes',
            ),
        );
    }

    public function receipt_page($order) {
        echo '<p>Спасибо за ваш заказ, пожалуйста, нажмите ниже на кнопку, чтобы заплатить.</p>';

        createFormWC($order, $this->id, $this->commission);
    }

    /**
     * По идее - выполняем процесс оплаты и получаем результат
     *
     * @param int $order_id
     *
     * @return array
     */
    public function process_payment($order_id) {
        return array(
            'result' => 'success',
            'redirect' => add_query_arg('order-pay', $order_id, add_query_arg('key', wc_get_order($order_id)->order_key, get_permalink(woocommerce_get_page_id('pay')))),
        );
    }

}
