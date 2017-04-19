<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2017/3/20
 * Time: 15:37
 */

namespace xltxlm\thrift;

/**
 * 补全thrift生成的类,不存在gei,set的方法
 * Class ThriftModelMaker
 * @package xltxlm\thrift
 */
class ThriftModelMaker
{
    protected $className = "";

    /** @var  \ReflectionClass */
    protected $ReflectionClass;
    /** @var array 可用参数的名字 */
    protected $Properties = [];

    /**
     * @return \ReflectionClass
     */
    public function getReflectionClass(): \ReflectionClass
    {
        return $this->ReflectionClass;
    }


    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param \ReflectionClass $ReflectionClass
     * @return ThriftModelMaker
     */
    private function setReflectionClass(\ReflectionClass $ReflectionClass): ThriftModelMaker
    {
        $this->ReflectionClass = $ReflectionClass;
        return $this;
    }


    /**
     * @param string $className
     * @return ThriftModelMaker
     */
    public function setClassName(string $className): ThriftModelMaker
    {
        $this->className = $className;
        return $this;
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return $this->Properties;
    }


    public function __invoke()
    {
        $this->setReflectionClass((new \ReflectionClass($this->getClassName())));
        $Properties = $this->getReflectionClass()->getProperties(\ReflectionProperty::IS_PUBLIC);
        foreach ($Properties as $key => $Propertie) {
            if ($Propertie->getName() == '_TSPEC') {
                unset($Properties[$key]);
                continue;
            }
            $PropertieOut = [];
            preg_match('#@var\s+([^\s]+)#', $Propertie->getDocComment(), $PropertieOut);
            $this->Properties[$Propertie->getName()] = $PropertieOut[1];
        }

        $filename = dirname($this->getReflectionClass()->getFileName()).'/'.$this->getReflectionClass()->getShortName()."Model.php";
        $this->file_put_contents($filename, __DIR__.'/Template/ThriftModel.php');
    }

    /**
     * @param $classRealFile
     * @param $templatePath
     */
    private function file_put_contents($classRealFile, $templatePath, $orverWrite = true)
    {
        ob_start();
        eval('include $templatePath;');
        $ob_get_clean = ob_get_clean();
        //1:先保证控制层的基准类一定存在
        if (!is_file($classRealFile) || (file_get_contents($classRealFile) !== $ob_get_clean && $orverWrite)) {
            file_put_contents($classRealFile, $ob_get_clean);
        }
    }
}
