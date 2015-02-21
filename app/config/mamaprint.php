<?php

define('DS', DIRECTORY_SEPARATOR);

return array(
    'version' => '0.2.3',
    'adminemail' => 'alexander.sutyagin@gmail.com',
    'supportemail' => 'info@mama-print.ru',
    'attachments_path' => __DIR__ . DS . '..' . DS . 'storage' . DS . 'attachments',
    'galleries_path' => __DIR__ . DS . '..' . DS . 'storage' . DS . 'galleries',
    'tmp_orders_path' => __DIR__ . DS . '..' . DS . 'storage' . DS . 'orders',
    'tmp_catalog_items' => __DIR__ . DS . '..' . DS . 'storage' . DS . 'catalog_items',
    'custom_templates_dir' => __DIR__ . DS . '..' . DS . 'storage' . DS . 'custom_templates',
    'download_link_timeout' => 60,
    'yandex_counter' => '27971118',
);