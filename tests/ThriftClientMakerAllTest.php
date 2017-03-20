<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2017/3/20
 * Time: 15:10
 */

namespace xltxlm\thrift\tests;


use PHPUnit\Framework\TestCase;
use xltxlm\thrift\App\Base;
use xltxlm\thrift\ThriftClientMakerAll;

class ThriftClientMakerAllTest extends TestCase
{

    public function test()
    {
        (new ThriftClientMakerAll(Base::class))
            ->__invoke();
    }
}