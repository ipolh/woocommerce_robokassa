<?php

$robokassa = new RobokassaPayAPI(get_option('MerchantLogin'), get_option('shoppass1'), get_option('shoppass2'));

$currLabels = $robokassa->getCurrLabels();

if (empty($currLabels)) {
    if (DEBUG_STATUS) {
        echo 'Не удалось загрузить методы оплаты.';
    }
    die();
}

$labels = __DIR__ . '/labelsClasses.php';

$labelsFile = fopen($labels, 'w');
chmod($labels, 0666);

fwrite($labelsFile, "<?php \n\n");

$method = "class payment_all extends WC_WP_robokassa {\n"
        . "    public function __construct() {\n"
        . "        \$this->id = 'all';\n"
        . "        \$this->method_title = 'Робокасса';\n"
        . "        \$this->long_name = 'Оплата через Робокасса';\n"
        . "        \$this->description = get_option('RobokassaOrderPageDescription', 'Оплатить через Робокасса');\n"
        . "        \$this->commission = 0;\n\n"
        . "        parent::__construct();\n"
        . "    }\n"
        . "}\n";
fwrite($labelsFile, "$method\n");

$array = array();

foreach ($currLabels as $key => $value) {
    $label = $value['Label'];
    $alias = $value['Alias'];
    $name = $value['Name'];

    $method = "class payment_$label extends WC_WP_robokassa {\n"
            . "    public function __construct() {\n"
            . "        \$this->id = '$alias';\n"
            . "        \$this->method_title = '$name (Робокасса)';\n"
            . "        \$this->long_name='Оплата через $name (Робокасса)';\n"
            . "        \$this->title = '$name';\n"
            . "        \$this->description = 'Оплатить через $name (Робокасса). Комиссия: {$value['Commission']}';\n"
            . "        \$this->commission = {$value['Commission']};\n\n"
            . "        parent::__construct();\n"
            . "    }\n"
            . "}\n";

    $array[] = "payment_$label";

    fwrite($labelsFile, "$method\n");
}

$functionStr = "/**
 * @var array \$methods
 *
 * @return array
 */
function add_WC_WP_robokassa_class(\$methods) {
    if (get_option('wc_robokassa_enabled') == 'no') {
        return \$methods;
    }
    if (get_option('paytype') == 'false') {
        \$methods[] = 'payment_all'; // Класс выбора типа оплаты на стороне Робокассы
    } else {\n";

foreach ($array as $value) {
    $functionStr .= "        \$methods[] = '$value';\n";
}

$functionStr .= "    }\n\n";
$functionStr .= "    return \$methods;\n";
$functionStr .= "}\n";

fwrite($labelsFile,  "$functionStr\n");
fclose($labelsFile);
