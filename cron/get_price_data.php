<?php
require_once(dirname(__DIR__, 1) . '\vendor\autoload.php');
require dirname(__DIR__, 1) . '/lib/Prices.class.php';
$priceObj = new Prices();
$priceObj->curl('http://apilayer.net/api/live?access_key=e5adcce0a8be7b2cd79a13f7bbf78a1b', 'currency', 'currencies-' . date('Ymd') . '.data');
$priceObj->curl('https://www.alko.fi/INTERSHOP/static/WFS/Alko-OnlineShop-Site/-/Alko-OnlineShop/fi_FI/Alkon%20Hinnasto%20Tekstitiedostona/alkon-hinnasto-tekstitiedostona.xlsx', 'prices', 'alko-prices-' . date('Ymd') . '.xlsx', true);
