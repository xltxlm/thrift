<?php /** @var \xltxlm\thrift\ThriftModelMaker $this */ ?>
<<?="?"?>php

namespace <?=$this->getReflectionClass()->getNamespaceName()?>;

class  <?=$this->getReflectionClass()->getShortName()?>Model extends <?=$this->getReflectionClass()->getShortName()?>

{

<?php foreach ($this->getProperties() as $name=>$type) {?>
    /** @var <?=$type?> */
    public $<?=$name?>;

    /**
     * @return <?=$type?>
     */
    public function get<?=ucfirst($name)?>()
    {
        return $this-><?=$name?>;
    }

    /**
     * @param <?=$type?> $<?=$name?>

     * @return <?=$this->getReflectionClass()->getShortName()?>Model
     */
    public function set<?=ucfirst($name)?>($<?=$name?>)
    {
        $this-><?=$name?> = $<?=$name?>;
        return $this;
    }
<?php } ?>

}
