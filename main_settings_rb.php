<div class="content_holder">
    <?php
    if (isset($_REQUEST['settings-updated'])) {
        include 'labelsGenerator.php';
        echo "<script>console.log('Labels Updated!')</script>";
    }
    ?>
    <div align="left"><p class="big_title_rb">Помощь и инструкция по установке</p></div>
    <p>Введите данные API в разделе "Основные настройки".</p>
    <p>В личном кабинете на сайте Робокассы введите следующие URL адреса:</p>
    <table>
        <tr>
            <td>ResultURL:</td>
            <td><code id="ResultURL"><?php echo site_url('/?robokassa=result'); ?></code></td>
            <td>
                <button class="btn btn-default btn-clipboard" data-clipboard-target="#ResultURL"
                        onclick="event.preventDefault();" style="padding: 4px;">Скопировать
                </button>
            </td>
        </tr>
        <tr>
            <td>SuccessURL:</td>
            <td><code id="SuccessURL"><?php echo site_url('/?robokassa=success'); ?></td>
            <td>
                <button class="btn btn-default btn-clipboard" data-clipboard-target="#SuccessURL"
                        onclick="event.preventDefault();" style="padding: 4px;">Скопировать
                </button>
            </td>
        </tr>
        <tr>
            <td>FailURL:</td>
            <td><code id="FailURL"><?php echo site_url('/?robokassa=fail'); ?></code></td>
            <td>
                <button class="btn btn-default btn-clipboard" data-clipboard-target="#FailURL"
                        onclick="event.preventDefault();" style="padding: 4px;">Скопировать
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
                    <input type="radio" id="enabled_on" name="wc_robokassa_enabled" value="yes"
                        <?php echo get_option('wc_robokassa_enabled') == 'yes' ? 'checked="checked"' : ''; ?>>
                    <label for="enabled_on">Стандартная схема</label>

                    <input type="radio" id="enabled_off" name="wc_robokassa_enabled" value="no"
                        <?php echo get_option('wc_robokassa_enabled') == 'no' ? 'checked="checked"' : ''; ?>>
                    <label for="enabled_off">Отключена</label>

                    <?php /*
                    <input type="radio" id="enabled_torobomarket" name="wc_robokassa_enabled" value="torobomarket" <?php echo get_option('wc_robokassa_enabled') == 'torobomarket' ? 'checked="checked"' : ''; ?>>
                    <label for="enabled_torobomarket">Корзина на Робомаркете</label>
                    */ ?>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">Заголовок на странице оформления заказа</th>
                <td>
	                <input type="text" name="RobokassaOrderPageTitle" value="<?php echo get_option('RobokassaOrderPageTitle'); ?>"/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Описание на странице оформления заказа</th>
                <td>
	                <input type="text" name="RobokassaOrderPageDescription" value="<?php echo get_option('RobokassaOrderPageDescription'); ?>"/>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">Логин магазина</th>
                <td><input type="text" name="MerchantLogin" value="<?php echo get_option('MerchantLogin'); ?>"/></td>
            </tr>

            <tr valign="top">
                <th scope="row">Пароль магазина #1</th>
                <td><input type="password" name="shoppass1" value="<?php echo get_option('shoppass1'); ?>"/></td>
            </tr>

            <tr valign="top">
                <th scope="row">Пароль магазина #2</th>
                <td><input type="password" name="shoppass2" value="<?php echo get_option('shoppass2'); ?>"/></td>
            </tr>
        </table>

        <p class="mid_title_rb">Настройки тестового соединения</p>

        <a class="spoiler_links button">Показать/скрыть</a>

        <div class="spoiler_body" style="display: none;">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Включить тестовый режим</th>
                    <td>
                        <input type="radio" id="test_on" name="test_onoff" value="true"
                            <?php echo get_option('test_onoff') == 'true' ? 'checked="checked"' : ''; ?>>
                        <label for="test_on">Включен</label>

                        <input type="radio" id="test_off" name="test_onoff" value="false"
                            <?php echo get_option('test_onoff') == 'false' ? 'checked="checked"' : ''; ?>>
                        <label for="test_off">Выключен</label>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Пароль магазина для тестов #1</th>
                    <td><input type="password" name="testshoppass1" value="<?php echo get_option('testshoppass1'); ?>"/>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Пароль магазина для тестов #2</th>
                    <td><input type="password" name="testshoppass2" value="<?php echo get_option('testshoppass2'); ?>"/>
                    </td>
                </tr>
            </table>
        </div>

        <script type="text/javascript"
                src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"></script>
        <script>
            $(document).ready(function () {
                spoleer();

                $("#size_commission1").mask("99");
            });

            function spoleer() {
                var e1 = document.getElementById("type_fiz");
                var e2 = document.getElementById("who_both");

                if (e1.checked) {
                    document.getElementById("commission").style.display = 'table-row';
                    document.getElementById("sno").style.display = 'none';
                    document.getElementById("tax").style.display = 'none';

                    if (e2.checked) {
                        document.getElementById("size_commission").style.display = 'table-row';
                    } else {
                        document.getElementById("size_commission").style.display = 'none';
                    }
                } else {
                    document.getElementById("commission").style.display = 'none';
                    document.getElementById("sno").style.display = 'table-row';

                    var sno = document.getElementById("sno_select");

                    if (sno.options[sno.selectedIndex].value === 'osn') {
                        document.getElementById("tax").style.display = 'table-row';
                    } else {
                        document.getElementById("tax").style.display = 'none';
                    }
                    document.getElementById("size_commission").style.display = 'none';
                }
            }
        </script>

        <p class="mid_title_rb">Общие настройки</p>

        <a class="spoiler_links button">Показать/скрыть</a>

        <div class="spoiler_body" style="display: none;">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Статус продавца</th>
                    <td>
                        <input type="radio" id="type_fiz" name="type_commission"
                               value="true" <?php echo get_option('type_commission') == 'true' ? 'checked="checked"'
                            : ''; ?> onchange="spoleer();"><label for="type_fiz">Физ. лицо</label>
                        <input type="radio" id="type_ur" name="type_commission"
                               value="false" <?php echo get_option('type_commission') == 'false' ? 'checked="checked"'
                            : ''; ?> onchange="spoleer();"><label for="type_ur">Юр. лицо</label>
                    </td>
                </tr>

                <tr valign="top" id="sno">
                    <th scope="row">Система налогообложения</th>
                    <td>
                        <select id="sno_select" name="sno" onchange="spoleer();">
                            <option value="fckoff" <?php echo((get_option('sno') == 'fckoff') ? ' selected' : ''); ?>>Не
                                передавать
                            </option>
                            <option value="osn" <?php echo((get_option('sno') == 'osn') ? ' selected' : ''); ?>>Общая
                                СН
                            </option>
                            <option value="usn_income" <?php echo((get_option('sno') == 'usn_income') ? ' selected'
                                : ''); ?>>Упрощенная СН (доходы)
                            </option>
                            <option value="usn_income_outcome" <?php echo((get_option('sno') == 'usn_income_outcome')
                                ? ' selected' : ''); ?>>Упрощенная СН (доходы минус расходы)
                            </option>
                            <option value="envd" <?php echo((get_option('sno') == 'envd') ? ' selected' : ''); ?>>Единый
                                налог на вмененный доход
                            </option>
                            <option value="esn" <?php echo((get_option('sno') == 'esn') ? ' selected' : ''); ?>>Единый
                                сельскохозяйственный налог
                            </option>
                            <option value="patent" <?php echo((get_option('sno') == 'patent') ? ' selected' : ''); ?>>
                                Патентная СН
                            </option>
                        </select>
                    </td>
                </tr>

                <tr valign="top" id="tax">
                    <th scope="row">Система налогообложения</th>
                    <td>
                        <select id="tax_select" name="tax" onchange="spoleer();">
                            <option value="none" <?php echo((get_option('tax') == 'none') ? ' selected' : ''); ?>>Не
                                передавать
                            </option>
                            <option value="none" <?php echo((get_option('tax') == 'none') ? ' selected' : ''); ?>>Без
                                НДС
                            </option>
                            <option value="vat0" <?php echo((get_option('tax') == 'vat0') ? ' selected' : ''); ?>>НДС по
                                ставке 0%
                            </option>
                            <option value="vat10" <?php echo((get_option('tax') == 'vat10') ? ' selected' : ''); ?>>НДС
                                чека по ставке 10%
                            </option>
                            <option value="vat18" <?php echo((get_option('tax') == 'vat18') ? ' selected' : ''); ?>>НДС
                                чека по ставке 18%
                            </option>
                            <option value="vat110" <?php echo((get_option('tax') == 'vat110') ? ' selected' : ''); ?>>
                                НДС чека по расчетной ставке 10/110
                            </option>
                            <option value="vat118" <?php echo((get_option('vat118') == 'esn') ? ' selected' : ''); ?>>
                                НДС чека по расчетной ставке 18/118
                            </option>
                        </select>
                    </td>
                </tr>

                <tr valign="top" id="commission">
                    <th scope="row">Кто оплачивает комиссию?</th>
                    <td>
                        <input type="radio" id="who_shop" name="who_commission"
                               value="shop" <?php echo get_option('who_commission') == 'shop' ? 'checked="checked"'
                            : ''; ?> onchange="spoleer();"><label for="who_shop">Магазин</label>
                        <input type="radio" id="who_client" name="who_commission"
                               value="client" <?php echo get_option('who_commission') == 'client' ? 'checked="checked"'
                            : ''; ?> onchange="spoleer();"><label for="who_client">Покупатель</label>
                        <input type="radio" id="who_both" name="who_commission"
                               value="both" <?php echo get_option('who_commission') == 'both' ? 'checked="checked"'
                            : ''; ?> onchange="spoleer();"><label for="who_both">Оба</label>
                    </td>
                </tr>

                <tr valign="top" id="size_commission">
                    <th scope="row">Доля комиссии, оплачиваемой покупателем(%)</th>
                    <td>
                        <input type="text" name="size_commission" id="size_commission1"
                               value="<?php echo get_option('size_commission'); ?>"/>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Выбор способа оплаты</th>
                    <td>
                        <input type="radio" id="robopaytype" name="paytype"
                               value="false" <?php echo get_option('paytype') == 'false' ? 'checked="checked"' : ''; ?>><label
                                for="robopaytype">В Робокассе</label>
                        <input type="radio" id="shoppaytype" name="paytype"
                               value="true" <?php echo get_option('paytype') == 'true' ? 'checked="checked"'
                            : ''; ?>><label for="shoppaytype">В магазине</label>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Страница успеха платежа</th>
                    <td>
                        <select id="SuccessURL" name="SuccessURL">
                            <option value="wc_success" <?php echo((get_option('SuccessURL') == 'wc_success')
                                ? ' selected' : ''); ?>>Страница "Заказ принят" от WooCommerce
                            </option>
                            <option value="wc_checkout" <?php echo((get_option('SuccessURL') == 'wc_checkout')
                                ? ' selected' : ''); ?>>Страница оформления заказа от WooCommerce
                            </option>
                            <?php
                            if (get_pages()) {
                                foreach (get_pages() as $page) {
                                    $selected = ($page->ID == get_option('SuccessURL')) ? ' selected' : '';
                                    echo '<option value="'.$page->ID.'"'.$selected.'>'.$page->post_title.'</option>';
                                }
                            }
                            ?>
                        </select>
                        <br>
                        <span style="line-height: 1;font-weight: normal;font-style: italic;font-size: 12px;">Эту страницу увидит покупатель, когда оплатит заказ<span>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Страница отказа</th>
                    <td>
                        <select id="FailURL" name="FailURL">
                            <option value="wc_checkout" <?php echo((get_option('FailURL') == 'wc_checkout')
                                ? ' selected' : ''); ?>>Страница оформления заказа от WooCommerce
                            </option>
                            <option value="wc_payment" <?php echo((get_option('FailURL') == 'wc_payment') ? ' selected'
                                : ''); ?>>Страница оплаты заказа от WooCommerce
                            </option>
                            <?php
                            if ($pages = get_pages()) {
                                foreach ($pages as $page) {
                                    $selected = ($page->ID == get_option('FailURL')) ? ' selected' : '';
                                    echo '<option value="'.$page->ID.'"'.$selected.'>'.$page->post_title.'</option>';
                                }
                            }
                            ?>
                        </select>
                        <br><span style="line-height: 1;font-weight: normal;font-style: italic;font-size: 12px;">Эту страницу увидит покупатель, если что-то пойдет не так: например, если ему не хватит денег на карте<span>
                    </td>
                </tr>
            </table>
        </div>

        <input type="hidden" name="action" value="update"/>
        <input type="hidden" name="page_options"
               value="RobokassaOrderPageTitle,RobokassaOrderPageDescription,wc_robokassa_enabled,MerchantLogin,shoppass1,shoppass2,test_onoff,testshoppass1,testshoppass2,type_commission,sno,tax,who_commission,size_commission,paytype,SuccessURL,FailURL"/>

        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>"/>
        </p>

    </form>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.spoiler_links').click(function () {
            $(this).next('.spoiler_body').toggle('normal');
            return false;
        });
    });
</script>