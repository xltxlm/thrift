<?php /** @var \xltxlm\thrift\ThriftClientMaker $this */ ?>
<<?='?'?>php
namespace <?=$this::$rootNamespce?>\<?=strtr($this->getReflectionClass()->getShortName(),['Client'=>""])?>;

use Thrift\ClassLoader\ThriftClassLoader;
use Thrift\Transport\TBufferedTransport;
use xltxlm\helper\Ctroller\LoadClass;
use <?=strtr($this->getReflectionClass()->getName(),['Client'=>""])?>Processor;
use Thrift\Transport\TPhpStream;
use Thrift\Protocol\TBinaryProtocol;
use xltxlm\helper\Ctroller\Unit\RunInvoke;
<?php
array_walk($this->getParameter(),function ($item, $key){
    echo "use $item;\n";
})
?>
/**
* 服务端代码实现
*/
class <?=ucfirst(strtr($this->getReflectionClass()->getShortName(),['Client'=>""])).ucfirst($this->getMethodName()->getName())?>

{
    use RunInvoke;

    <?php
    $Parameter = $this->getParameter();
    array_walk($Parameter,function (&$item, $key){
        $item = array_pop(explode("\\",$item)).' $'.ucfirst($key);
    })?>

    /**
     * @return <?=$this->getReturn()?>

    */
    public function <?=$this->getMethodName()->getName()?>(<?=join(',',$Parameter)?>)
    {

        //TODO: ...完成真实代码
    }

    //
    public function getLoadCLass()
    {
        $loader = new ThriftClassLoader();
        $loader->registerDefinition('<?=$this->getReflectionClass()->getNamespaceName()?>', LoadClass::$rootDir.'/../');
        $loader->register();

        header('Content-Type', 'application/x-thrift');
        $handler = new self();
        $processor = new <?=strtr($this->getReflectionClass()->getShortName(),['Client'=>""])?>Processor($handler);
        $transport = new TBufferedTransport(new TPhpStream(TPhpStream::MODE_R | TPhpStream::MODE_W));
        $protocol = new TBinaryProtocol($transport, true, true);
        $transport->open();
        $processor->process($protocol, $protocol);
        $transport->close();
    }
}

