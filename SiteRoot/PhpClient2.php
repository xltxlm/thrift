<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2017/3/20
 * Time: 14:23
 */
//采用新的方法测试调用

use Thrift\abcClientHaha1;
use Thrift\abcClientHaha2;
use Thrift\MyclassModel;
use tutorial\CalculatorClientadd;
use tutorial\CalculatorClientPing;
use tutorial\CalculatorClientzip;

include __DIR__.'/../vendor/autoload.php';


$uri = "http://127.0.0.1:80/index.php?c=abc/AbcHaha1";
echo "<pre>-->";
print_r($uri);
echo "<--@in ".__FILE__." on line ".__LINE__."\n";
$num = (new abcClientHaha1($uri))
    ->__invoke();
echo "<pre>-->";
print_r($num);
echo "<--@in ".__FILE__." on line ".__LINE__."\n";


$uri = "http://127.0.0.1:80/index.php?c=abc/AbcHaha2";

echo "<pre>-->";
print_r($uri);
echo "<--@in ".__FILE__." on line ".__LINE__."\n";
$Myclass = (new MyclassModel())
    ->setNum1(1)
    ->setNum2(2)
    ->setOp('op')
    ->setComment('co');
$num = (new abcClientHaha2($uri))
    ->setMyclass($Myclass)
    ->__invoke();
echo "<pre>-->";
print_r($num);
echo "<--@in ".__FILE__." on line ".__LINE__."\n";


die("\n".date("Y-m-d H:i:s").',@in '.__FILE__.' on line '.__LINE__."\r\n");

$num1 = rand(1, 9099);
$num = (new CalculatorClientadd("http://127.0.0.1:80/PhpServer.php"))
    ->setNum1($num1)
    ->setNum2(21)
    ->__invoke();
echo "<pre>-->";
print_r("$num1+21=".$num);
echo "<--@in ".__FILE__." on line ".__LINE__."\n";

$zip = (new CalculatorClientzip("http://127.0.0.1:80/PhpServer.php"))
    ->__invoke();
echo "<pre>-->";
print_r($zip);
echo "<--@in ".__FILE__." on line ".__LINE__."\n";
