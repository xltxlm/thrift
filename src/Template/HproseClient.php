<?php /** @var \xltxlm\thrift\ThriftClientMaker $this */ ?>
<<?='?'?>php
namespace <?=$this->getReflectionClass()->getNamespaceName()?>\Hprose;

use Hprose\Socket\Client;
/**
* Hprose 客户端实现
*/
class <?=ucfirst($this->getMethodName()->getName())?>

{
    /** @var string 服务器的地址 */
    protected $serverhost="";

  /**
     * @return string
     */
    public function getServerhost(): string
    {
        return $this->serverhost;
    }

    /**
     * @param string $serverhost
     * @return $this
     */
    public function setServerhost(string $serverhost): <?=ucfirst($this->getMethodName()->getName())?>
    {
        $this->serverhost = $serverhost;
        return $this;
    }


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

    public function __invoke()
    {
        <?php
        $Parameter = $this->getParameter();
        array_walk($Parameter,function (&$item, $key){
            $typename=ucfirst(basename(strtr('/'.$item,['\\'=>'/'])));
            $item = '$this->get'.$typename.'Model()->__toArray()';
        })?>
        $client = new Client($this->getServerhost(),false);
        return call_user_func_array( [$client,'<?=$this->getMethodName()->getName()?>'], [<?=join(',',array_values($Parameter))?>]);
    }


}