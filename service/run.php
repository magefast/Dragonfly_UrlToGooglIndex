<?php
/**
 * Script for add URL to Google Index with console command - php service/run.php
 * add list of URLs to variable $a[]
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$a[] = 'https://magento.local/cat1/cat2/cat3';
$a[] = 'https://magento.local/cat1/cat2/cat4';


require_once __DIR__ . '/../app/bootstrap.php';

use Magento\Framework\App\Bootstrap;

$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();

$api = $objectManager->create('Dragonfly\UrlToGooglIndex\Service\GoogleApi');

foreach ($a as $v) {
    $result = $api->add($v);
    var_dump($v);
    var_dump($result);
    var_dump('-------');
    sleep(1);
}

die('-');
