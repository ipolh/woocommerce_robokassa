<?php 

class payment_all extends WC_WP_robokassa {
    public function __construct() {
        $this->id = 'all';
        $this->method_title = 'Робокасса';
        $this->long_name = 'Оплата через Робокасса';
        $this->commission = 0;

        parent::__construct();
    }
}

class payment_Qiwi50RIBRM extends WC_WP_robokassa {
    public function __construct() {
        $this->id = 'Qiwi50RIBRM';
        $this->method_title = 'QIWI Кошелек (Робокасса)';
        $this->long_name='Оплата через QIWI Кошелек (Робокасса)';
        $this->commission = 0;

        parent::__construct();
    }
}

class payment_YandexMerchantRIBR extends WC_WP_robokassa {
    public function __construct() {
        $this->id = 'YandexMerchantRIBR';
        $this->method_title = 'Яндекс.Деньги (Робокасса)';
        $this->long_name='Оплата через Яндекс.Деньги (Робокасса)';
        $this->commission = 0;

        parent::__construct();
    }
}

class payment_WMR30RM extends WC_WP_robokassa {
    public function __construct() {
        $this->id = 'WMR30RM';
        $this->method_title = 'WMR (Робокасса)';
        $this->long_name='Оплата через WMR (Робокасса)';
        $this->commission = 0;

        parent::__construct();
    }
}

class payment_ElecsnetWalletRIBR extends WC_WP_robokassa {
    public function __construct() {
        $this->id = 'ElecsnetWalletRIBR';
        $this->method_title = 'Кошелек Элекснет (Робокасса)';
        $this->long_name='Оплата через Кошелек Элекснет (Робокасса)';
        $this->commission = 0;

        parent::__construct();
    }
}

class payment_W1RIBR extends WC_WP_robokassa {
    public function __construct() {
        $this->id = 'W1RIBR';
        $this->method_title = 'RUR Единый кошелек (Робокасса)';
        $this->long_name='Оплата через RUR Единый кошелек (Робокасса)';
        $this->commission = 0;

        parent::__construct();
    }
}

class payment_AlfaBankRIBR extends WC_WP_robokassa {
    public function __construct() {
        $this->id = 'AlfaBankRIBR';
        $this->method_title = 'Альфа-Клик (Робокасса)';
        $this->long_name='Оплата через Альфа-Клик (Робокасса)';
        $this->commission = 0;

        parent::__construct();
    }
}

class payment_VTB24RIBR extends WC_WP_robokassa {
    public function __construct() {
        $this->id = 'VTB24RIBR';
        $this->method_title = 'ВТБ (Робокасса)';
        $this->long_name='Оплата через ВТБ (Робокасса)';
        $this->commission = 0;

        parent::__construct();
    }
}

class payment_W1RIBPSBR extends WC_WP_robokassa {
    public function __construct() {
        $this->id = 'W1RIBPSBR';
        $this->method_title = 'RUR Единый кошелек (Робокасса)';
        $this->long_name='Оплата через RUR Единый кошелек (Робокасса)';
        $this->commission = 0;

        parent::__construct();
    }
}

class payment_MINBankRIBR extends WC_WP_robokassa {
    public function __construct() {
        $this->id = 'MINBankRIBR';
        $this->method_title = 'Московский Индустриальный Банк (Робокасса)';
        $this->long_name='Оплата через Московский Индустриальный Банк (Робокасса)';
	    $this->title = get_option('RobokassaOrderPageTitle');
	    $this->description = get_option('RobokassaOrderPageDescription');
        $this->commission = 0;

        parent::__construct();
    }
}

class payment_BSSIntezaRIBR extends WC_WP_robokassa {
    public function __construct() {
        $this->id = 'BSSIntezaRIBR';
        $this->method_title = 'Банк Интеза (Робокасса)';
        $this->long_name='Оплата через Банк Интеза (Робокасса)';
	    $this->title = get_option('RobokassaOrderPageTitle');
	    $this->description = get_option('RobokassaOrderPageDescription');
        $this->commission = 0;

        parent::__construct();
    }
}

class payment_BSSAvtovazbankR extends WC_WP_robokassa {
    public function __construct() {
        $this->id = 'BSSAvtovazbankR';
        $this->method_title = 'Банк АВБ (Робокасса)';
        $this->long_name='Оплата через Банк АВБ (Робокасса)';
	    $this->title = get_option('RobokassaOrderPageTitle');
	    $this->description = get_option('RobokassaOrderPageDescription');
        $this->commission = 0;

        parent::__construct();
    }
}

class payment_FacturaBinBank extends WC_WP_robokassa {
    public function __construct() {
        $this->id = 'FacturaBinBank';
        $this->method_title = 'БИНБАНК (Робокасса)';
        $this->long_name='Оплата через БИНБАНК (Робокасса)';
	    $this->title = get_option('RobokassaOrderPageTitle');
	    $this->description = get_option('RobokassaOrderPageDescription');
        $this->commission = 0;

        parent::__construct();
    }
}

class payment_BSSFederalBankForInnovationAndDevelopmentR extends WC_WP_robokassa {
    public function __construct() {
        $this->id = 'BSSFederalBankForInnovationAndDevelopmentR';
        $this->method_title = 'ФБ Инноваций и Развития (Робокасса)';
        $this->long_name='Оплата через ФБ Инноваций и Развития (Робокасса)';
	    $this->title = get_option('RobokassaOrderPageTitle');
	    $this->description = get_option('RobokassaOrderPageDescription');
        $this->commission = 0;

        parent::__construct();
    }
}

class payment_FacturaSovCom extends WC_WP_robokassa {
    public function __construct() {
        $this->id = 'FacturaSovCom';
        $this->method_title = 'Совкомбанк (Робокасса)';
        $this->long_name='Оплата через Совкомбанк (Робокасса)';
	    $this->title = get_option('RobokassaOrderPageTitle');
	    $this->description = get_option('RobokassaOrderPageDescription');
        $this->commission = 0;

        parent::__construct();
    }
}

class payment_BSSNationalBankTRUSTR extends WC_WP_robokassa {
    public function __construct() {
        $this->id = 'BSSNationalBankTRUSTR';
        $this->method_title = 'Национальный банк ТРАСТ (Робокасса)';
        $this->long_name='Оплата через Национальный банк ТРАСТ (Робокасса)';
	    $this->title = get_option('RobokassaOrderPageTitle');
	    $this->description = get_option('RobokassaOrderPageDescription');
        $this->commission = 0;

        parent::__construct();
    }
}

class payment_BANKOCEAN3R extends WC_WP_robokassa {
    public function __construct() {
        $this->id = 'BANKOCEAN3R';
        $this->method_title = 'Банковская карта (Робокасса)';
        $this->long_name='Оплата через Банковская карта (Робокасса)';
	    $this->title = get_option('RobokassaOrderPageTitle');
	    $this->description = get_option('RobokassaOrderPageDescription');
        $this->commission = 0;

        parent::__construct();
    }
}

class payment_ApplePayRIBR extends WC_WP_robokassa {
    public function __construct() {
        $this->id = 'ApplePayRIBR';
        $this->method_title = 'Apple Pay (Робокасса)';
        $this->long_name='Оплата через Apple Pay (Робокасса)';
        $this->commission = 0;

        parent::__construct();
    }
}

class payment_SamsungPayRIBR extends WC_WP_robokassa {
    public function __construct() {
        $this->id = 'SamsungPayRIBR';
        $this->method_title = 'Samsung Pay (Робокасса)';
        $this->long_name='Оплата через Samsung Pay (Робокасса)';
        $this->commission = 0;

        parent::__construct();
    }
}

class payment_RapidaRIBEurosetR extends WC_WP_robokassa {
    public function __construct() {
        $this->id = 'RapidaRIBEurosetR';
        $this->method_title = 'Евросеть (Робокасса)';
        $this->long_name='Оплата через Евросеть (Робокасса)';
        $this->commission = 0;

        parent::__construct();
    }
}

class payment_RapidaRIBSvyaznoyR extends WC_WP_robokassa {
    public function __construct() {
        $this->id = 'RapidaRIBSvyaznoyR';
        $this->method_title = 'Связной (Робокасса)';
        $this->long_name='Оплата через Связной (Робокасса)';
        $this->commission = 0;

        parent::__construct();
    }
}

class payment_Biocoin extends WC_WP_robokassa {
    public function __construct() {
        $this->id = 'Biocoin';
        $this->method_title = 'BioCoin (Робокасса)';
        $this->long_name='Оплата через BioCoin (Робокасса)';
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
        $methods[] = 'payment_Qiwi50RIBRM';
        $methods[] = 'payment_YandexMerchantRIBR';
        $methods[] = 'payment_WMR30RM';
        $methods[] = 'payment_ElecsnetWalletRIBR';
        $methods[] = 'payment_W1RIBR';
        $methods[] = 'payment_AlfaBankRIBR';
        $methods[] = 'payment_VTB24RIBR';
        $methods[] = 'payment_W1RIBPSBR';
        $methods[] = 'payment_MINBankRIBR';
        $methods[] = 'payment_BSSIntezaRIBR';
        $methods[] = 'payment_BSSAvtovazbankR';
        $methods[] = 'payment_FacturaBinBank';
        $methods[] = 'payment_BSSFederalBankForInnovationAndDevelopmentR';
        $methods[] = 'payment_FacturaSovCom';
        $methods[] = 'payment_BSSNationalBankTRUSTR';
        $methods[] = 'payment_BANKOCEAN3R';
        $methods[] = 'payment_ApplePayRIBR';
        $methods[] = 'payment_SamsungPayRIBR';
        $methods[] = 'payment_RapidaRIBEurosetR';
        $methods[] = 'payment_RapidaRIBSvyaznoyR';
        $methods[] = 'payment_Biocoin';
    }

    return $methods;
}

