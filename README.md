Установка плагина Robokassa для WooCommerce
- Скачайте репозиторий в папку /wp-content/plugins/woocommerce_robokassa
- Активируйте плагин в настройках WordPress /wp-admin/plugins.php
- Настройте параметры подключения /wp-admin/admin.php?page=main_settings_rb.php

Настройка на стороне Robokassa
- Алгоритм расчета хеша – MD5
- Result Url – http(s)://your-domain.ru/?robokassa=result
- Success Url – http(s)://your-domain.ru/?robokassa=success
- Fail Url – http(s)://your-domain.ru/?robokassa=fail
- Метод отсылки данных по Result Url, Success Url и fail Url  – POST