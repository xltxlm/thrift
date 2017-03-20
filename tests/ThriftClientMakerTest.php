<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2017/3/20
 * Time: 11:00
 */

namespace xltxlm\thrift\tests;


use PHPUnit\Framework\TestCase;
use tutorial\CalculatorClient;
use xltxlm\thrift\App\Base;
use xltxlm\thrift\ThriftClientMaker;

class ThriftClientMakerTest extends TestCase
{

    public function test()
    {
        (new ThriftClientMaker(Base::class))
            ->setClassName(CalculatorClient::class)
            ->__invoke();
    }
}