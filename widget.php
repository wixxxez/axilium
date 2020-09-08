<?php
require_once __DIR__ . '/vendor/autoload.php';

use WayForPay\SDK\Collection\ProductCollection;
use WayForPay\SDK\Credential\AccountSecretTestCredential;
use WayForPay\SDK\Credential\AccountSecretCredential;
use WayForPay\SDK\Domain\Client;
use WayForPay\SDK\Domain\Product;
use WayForPay\SDK\Wizard\PurchaseWizard;

// Use test credential or yours
//$credential = new AccountSecretTestCredential();

function create_widget($array) { 

$credential = new AccountSecretCredential('axilium_best', 'bc2fc534bcccc1ededc54b723ef28ddffe954e11');
$widget = PurchaseWizard::get($credential)
    ->setOrderReference($array['order'])
    ->setAmount($array['amount'])
    ->setCurrency('UAH')
    ->setOrderDate(new \DateTime())
    ->setMerchantDomainName('http://axilium.best')
    ->setClient(new Client(
        $array['firstname'],
        $array['lastname'],
        $array['emailtrans'],
        $array['phonenumber'],
        'UA'
    ))
    ->setProducts(new ProductCollection(array(
        new Product($array['fulln'],$array['amount'], 1)
    )))
    ->setReturnUrl('')
    ->setServiceUrl('http://axilium.best/curl.php')
    ->getForm()
    ->getWidget();
    

return $widget;
}
