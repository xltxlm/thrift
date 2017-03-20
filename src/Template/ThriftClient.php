<?php /** @var \xltxlm\thrift\ThriftClientMaker $this */ ?>
<<?='?'?>php

namespace <?=$this->getReflectionClass()->getNamespaceName()?>;

use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TSocket;
use Thrift\Transport\THttpClient;
use Thrift\Transport\TBufferedTransport;
use Thrift\Exception\TException;
use <?=$this->getReflectionClass()->getName()?>;

class <?=$this->getReflectionClass()->getShortName().ucfirst($this->getMethodName()->getName())?>

{
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

    /** @var TBinaryProtocol */
    protected $transport;
    /** @var <?=$this->getReflectionClass()->getShortName()?> */
    protected $client;

<?php foreach ($this->getParameter() as $name=>$type) {?>
    /** @var <?=$type?> */
    protected $<?=$name?>;

    /**
     * @return <?=$type?>
     */
    public function get<?=ucfirst($name)?>()
    {
        return $this-><?=$name?>;
    }

    /**
     * @param <?=$type?> $<?=$name?>

     * @return <?=$this->getReflectionClass()->getShortName().$this->getMethodName()->getName()?>
     */
    public function set<?=ucfirst($name)?>($<?=$name?>)
    {
        $this-><?=$name?> = $<?=$name?>;
        return $this;
    }
<?php } ?>

    /**
     * constructor.
     */
    public function __construct( string $uri )
    {
        $parse_url = parse_url($uri);
        if (strpos($uri, 'http') !== false) {
            $socket = new THttpClient($parse_url['host'], $parse_url['port'] ?: 80, $parse_url['path'].'?'.$parse_url['query']);
        } else {
            $socket = new TSocket($uri);
        }
        $this->transport = new TBufferedTransport($socket, 1024, 1024);
        $protocol = new TBinaryProtocol($this->transport);
        $this->client = new <?=$this->getReflectionClass()->getShortName()?>($protocol);
        $this->transport->open();
    }


    /**
     * <?php if($this->getReturn()){?>@return <?=$this->getReturn()?><?php } ?>

    */
    public function __invoke()  {
        <?php
        $Parameter = $this->getParameter();
        array_walk($Parameter,function (&$item, $key){
            $item = '$this->get'.ucfirst($key).'()';
        })?>
        return call_user_func_array( [$this->client,'<?=$this->getMethodName()->getName()?>'], [<?=join(',',array_values($Parameter))?>]);
    }

    public function __destruct()
    {
        $this->transport->close();
    }
}

