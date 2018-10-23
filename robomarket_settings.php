<div class="content_holder">
    <p class="big_title_rb">Настройки экспорта в РобоМаркет</p>

    <form method="post" action="options.php">
        <?php wp_nonce_field('update-options'); ?>
        <table class="form-table">
            <p>Укажите в настройке "URL процессинга" следующий адрес: <code id="ResultURL"><?php echo site_url('/?robomarket'); ?></code><button class="btn btn-default btn-clipboard" data-clipboard-target="#ResultURL" onclick="event.preventDefault();" style="padding: 4px;">Скопировать</button></p>
            <p>Выберите тип запроса "JSON", тип хеша "MD5" и не забудьте поставить галочку напротив "Использовать N заказа в магазине как InvId в ROBOKASSA"</p>
            <p>Секретная фраза РобоМаркета: <input type="password" name="robomarket_secret" value="<?php echo get_option('robomarket_secret') ?>"></p>
            <p>Вам необходимо загрузить автоматически сгенерированный каталог вашего магазина в Личном Кабинете на сайте Робокассы, в разделе "Панель Robomarket"</p>
            <img src="/wp-content/plugins/wp_robokassa_1.2.30_DEBUG/images/robokassa_help.png" style="border: 2px solid; margin-bottom: 15px;">
        </table>

        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="robomarket_secret"/>

        <p class="submit">
            <a href="<?php echo admin_url('/admin.php?page=YMLGenerator'); ?>" target="_blank" class="button-secondary">Экспортировать</a>
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
        </p>
    </form>
</div>