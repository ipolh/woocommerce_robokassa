<?php 

class payment_all extends WC_WP_robokassa {
    public function __construct() {
        $this->id = 'all';
        $this->method_title = 'Робокасса';
        $this->long_name = 'Оплата через Робокасса';
        $this->description = get_option('RobokassaOrderPageDescription', 'Оплатить через Робокасса');
        $this->commission = 0;

        parent::__construct();
    }
}

class payment_Qiwi40PS extends WC_WP_robokassa {
    public function __construct() {
        $this->id = 'QiwiWallet';
        $this->method_title = 'QIWI Кошелек (Робокасса)';
        $this->long_name='Оплата через QIWI Кошелек (Робокасса)';
        $this->title = 'QIWI Кошелек';
        $this->description = 'Оплатить через QIWI Кошелек (Робокасса). Комиссия: 0';
        $this->commission = 0;

        parent::__construct();
    }
}

class payment_YandexMerchantPS3R extends WC_WP_robokassa {
    public function __construct() {
        $this->id = 'YandexMoney';
        $this->method_title = 'Яндекс.Деньги (Робокасса)';
        $this->long_name='Оплата через Яндекс.Деньги (Робокасса)';
        $this->title = 'Яндекс.Деньги';
        $this->description = 'Оплатить через Яндекс.Деньги (Робокасса). Комиссия: 0';
        $this->commission = 0;

        parent::__construct();
    }
}

class payment_WMR20PM extends WC_WP_robokassa {
    public function __construct() {
        $this->id = 'WMR';
        $this->method_title = 'WMR (Робокасса)';
        $this->long_name='Оплата через WMR (Робокасса)';
        $this->title = 'WMR';
        $this->description = 'Оплатить через WMR (Робокасса). Комиссия: 0';
        $this->commission = 0;

        parent::__construct();
    }
}

class payment_W1PaySend extends WC_WP_robokassa {
    public function __construct() {
        $this->id = 'W1';
        $this->method_title = 'RUR Единый кошелек (Робокасса)';
        $this->long_name='Оплата через RUR Единый кошелек (Робокасса)';
        $this->title = 'RUR Единый кошелек';
        $this->description = 'Оплатить через RUR Единый кошелек (Робокасса). Комиссия: 0';
        $this->commission = 0;

        parent::__construct();
    }
}

class payment_BankCardPSR extends WC_WP_robokassa {
    public function __construct() {
        $this->id = 'BankCard';
        $this->method_title = 'Банковская карта (Робокасса)';
        $this->long_name='Оплата через Банковская карта (Робокасса)';
        $this->title = 'Банковская карта';
        $this->description = 'Оплатить через Банковская карта (Робокасса). Комиссия: 0';
        $this->commission = 0;

        parent::__construct();
    }
}

class payment_CardHalvaPSR extends WC_WP_robokassa {
    public function __construct() {
        $this->id = 'BankCardHalva';
        $this->method_title = 'Карта Халва (Робокасса)';
        $this->long_name='Оплата через Карта Халва (Робокасса)';
        $this->title = 'Карта Халва';
        $this->description = 'Оплатить через Карта Халва (Робокасса). Комиссия: 0';
        $this->commission = 0;

        parent::__construct();
    }
}

class payment_ApplePayPSR extends WC_WP_robokassa {
    public function __construct() {
        $this->id = 'ApplePay';
        $this->method_title = 'Apple Pay (Робокасса)';
        $this->long_name='Оплата через Apple Pay (Робокасса)';
        $this->title = 'Apple Pay';
        $this->description = 'Оплатить через Apple Pay (Робокасса). Комиссия: 0';
        $this->commission = 0;

        parent::__construct();
    }
}

class payment_SamsungPayPSR extends WC_WP_robokassa {
    public function __construct() {
        $this->id = 'SamsungPay';
        $this->method_title = 'Samsung Pay (Робокасса)';
        $this->long_name='Оплата через Samsung Pay (Робокасса)';
        $this->title = 'Samsung Pay';
        $this->description = 'Оплатить через Samsung Pay (Робокасса). Комиссия: 0';
        $this->commission = 0;

        parent::__construct();
    }
}

/**
 * @var array $methods
 *
 * @return array
 */
function add_WC_WP_robokassa_class($methods) {
    if (get_option('wc_robokassa_enabled') == 'no') {
        return $methods;
    }
    if (get_option('paytype') == 'false') {
        $methods[] = 'payment_all'; // Класс выбора типа оплаты на стороне Робокассы
    } else {
        $methods[] = 'payment_Qiwi40PS';
        $methods[] = 'payment_YandexMerchantPS3R';
        $methods[] = 'payment_WMR20PM';
        $methods[] = 'payment_W1PaySend';
        $methods[] = 'payment_BankCardPSR';
        $methods[] = 'payment_CardHalvaPSR';
        $methods[] = 'payment_ApplePayPSR';
        $methods[] = 'payment_SamsungPayPSR';
    }

    return $methods;
}

