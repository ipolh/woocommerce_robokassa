<?php
header('Content-Type: application/xml');
header('Content-Description: File Transfer');
header('Content-Disposition: attachment; filename=robomarket'.date("d_m_Y_His").'.yml.xml');
header('Connection: Keep-Alive');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
echo '<?xml version="1.0" encoding="utf-8"?>';
?>
            <yml_catalog date="2017-10-30 11:10">
          <shop>
            <name>Опа говносайт</name>
            <company>Опа говносайт</company>
            <url>http://wp-stage.dymov.tech</url>
            <currencies><currency id="RUB" rate="1"/>
                </currencies>
            <categories><category id="1" parentId="0">Без рубрики</category>
            </categories>
            <cpa>1</cpa>
            <offers>
                      <offer id="8" available="true">
                        <name>Коровья моча в пластиковой бутылочке</name>
                        <description><![CDATA[Для лечения уринотерапией по рецептам Генадия Малахова.]]></description>
                        <url>http://wp-stage.dymov.tech/product/korovya-mocha-v-plastikovoy-butylochke/</url>
                        <price>3</price>
                        <currencyId>RUB</currencyId>
                        <categoryId>0</categoryId><picture></picture>
                      </offer>
                </offers>
          </shop>
        </yml_catalog>
        