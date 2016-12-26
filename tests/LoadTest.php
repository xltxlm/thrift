<?php
use PHPUnit\Framework\TestCase;
use Thrift\Transport\TSocket;

/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2016/12/26
 * Time: 18:16
 */
class LoadTest extends TestCase
{

    public function test1()
    {
        $socket = new TSocket("118.178.121.54", "9090");
    }
}