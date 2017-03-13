<?php

namespace tutorial\php;

error_reporting(E_ALL);

require_once __DIR__.'/../vendor/autoload.php';

/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements. See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership. The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied. See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */

use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TSocket;
use Thrift\Transport\THttpClient;
use Thrift\Transport\TBufferedTransport;
use Thrift\Exception\TException;
use tutorial\CalculatorClient;
use tutorial\InvalidOperation;
use tutorial\Operation;
use tutorial\Work;

try {
//    if (array_search('--http', $argv)) {
//        $socket = new THttpClient('localhost', 8080, '/PhpServer.php');
//    } else {
//        $socket = new TSocket('localhost', 9090);
//    }
    $socket = new THttpClient('localhost', 80, '/PhpServer.php');
    $transport = new TBufferedTransport($socket, 1024, 1024);
    $protocol = new TBinaryProtocol($transport);
    $client = new CalculatorClient($protocol);

    $transport->open();

    $client->ping();
    print "ping()\n";

    //普通的计算
    $sum = $client->add(11, 1);
    print "11+1=$sum\n";

    //参数可以传递整个对象
    $work = new Work();
//    $work->op = Operation::DIVIDE;
//    $work->num1 = 10;
//    $work->num2 = 0;
    $work->setOp(Operation::DIVIDE)
        ->setNum1(10)
        ->setNum2(0);

    try {
        $client->calculate(1, $work);
        print "Whoa! We can divide by zero?\n";
    } catch (InvalidOperation $io) {
        print "InvalidOperation: $io->why\n";
    }

    $work->op = Operation::SUBTRACT;
    $work->num1 = 15;
    $work->num2 = 10;
    $diff = $client->calculate(1, $work);
    print "15-10=$diff\n";

    $log = $client->getStruct(1);
    print "Log: $log->value\n";

    $transport->close();

} catch (TException $tx) {
    print 'TException: '.$tx->getMessage()."\n";
}
