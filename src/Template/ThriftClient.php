<?php /** @var \xltxlm\thrift\ThriftClientMaker $this */ ?>
<<?='?'?>php

namespace <?=$this->getReflectionClass()->getNamespaceName()?>\Client;

use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TSocket;
use Thrift\Transport\THttpClient;
use Thrift\Transport\TBufferedTransport;
use Thrift\Exception\TException;
use \xltxlm\thrift\Config\ThriftConfig;
use <?=$this->getReflectionClass()->getName()?>;

/**
* Thrift 客户端实现
*/
class <?=ucfirst($this->getMethodName()->getName())?>

{
    /** @var TBinaryProtocol */
    protected $transport;
    /** @var <?=$this->getReflectionClass()->getShortName()?> */
    protected $client;

<?php foreach ($this->getParameter() as $name=>$type) {
    $typename=ucfirst(basename(strtr('/'.$type,['\\'=>'/'])));
    ?>
    /** @var <?=$type?>Model */
    protected $<?=$typename?>Model;

    /**
     * @return <?=$type?>Model
     */
    public function get<?=$typename?>Model()
    {
        return $this-><?=$typename?>Model;
    }

    /**
     * @param <?=$type?>Model $<?=$typename?>

     * @return $this
     */
    public function set<?=$typename?>Model($<?=$typename?>)
    {
        $this-><?=$typename?>Model = $<?=$typename?>;
        return $this;
    }
<?php } ?>

    /**
     * constructor.
     */
    public function __construct( ThriftConfig $ThriftConfig )
    {
        if ($ThriftConfig->getType() == ThriftConfig::HTTP) {
            $socket = new THttpClient($ThriftConfig->getHost(), $ThriftConfig->getPort(), '?c=<?=strtr($this->getReflectionClass()->getShortName(),['Client'=>''])?>/<?=$this->getReflectionClass()->getShortName().ucfirst($this->getMethodName()->getName())?>');
        } else {
            $socket = new TSocket($ThriftConfig->getHost(), $ThriftConfig->getPort());
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
            $typename=ucfirst(basename(strtr('/'.$item,['\\'=>'/'])));
            $item = '$this->get'.$typename.'Model()->__toArray()';
        })?>
        return call_user_func_array( [$this->client,'<?=$this->getMethodName()->getName()?>'], [<?=join(',',array_values($Parameter))?>]);
    }

    public function __destruct()
    {
        $this->transport->close();
    }
}

