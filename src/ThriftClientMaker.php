<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2017/3/20
 * Time: 10:59
 */

namespace xltxlm\thrift;

use Composer\Autoload\ClassLoader;
use xltxlm\helper\Ctroller\LoadClassRegister;
use xltxlm\helper\Hdir\file_put_contents;


/**
 * 根据生成的代码,继续生成面向对象的代码
 * Class ThriftMaker
 * @package xltxlm\thrift
 */
final class ThriftClientMaker
{
    use LoadClassRegister;
    use file_put_contents;

    /** @var string 需要处理的客户端代码 */
    protected $className = "";
    /** @var  \ReflectionClass */
    protected $ReflectionClass;
    /** @var \ReflectionMethod 当前运行的方法名称 */
    protected $methodName = "";
    /** @var array */
    protected $parameter = [];
    /** @var string 函数的返回值 */
    protected $return = "";

    /**
     * @return string
     */
    public function getReturn(): string
    {
        return $this->return;
    }

    /**
     * @param string $return
     * @return ThriftClientMaker
     */
    public function setReturn(string $return): ThriftClientMaker
    {
        $this->return = $return;
        return $this;
    }


    /**
     * @return array
     */
    public function getParameter(): array
    {
        return $this->parameter;
    }

    /**
     * @param array $parameter
     * @return ThriftClientMaker
     */
    public function setParameter(array $parameter): ThriftClientMaker
    {
        $this->parameter = $parameter;
        return $this;
    }


    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string $className
     * @return ThriftClientMaker
     */
    public function setClassName(string $className): ThriftClientMaker
    {
        $this->className = $className;
        return $this;
    }

    /**
     * @return \ReflectionClass
     */
    public function getReflectionClass(): \ReflectionClass
    {
        return $this->ReflectionClass = (new \ReflectionClass($this->getClassName()));
    }

    /**
     * @return \ReflectionMethod
     */
    public function getMethodName(): \ReflectionMethod
    {
        return $this->methodName;
    }

    /**
     * @param \ReflectionMethod $methodName
     * @return ThriftClientMaker
     */
    public function setMethodName(\ReflectionMethod $methodName): ThriftClientMaker
    {
        $this->methodName = $methodName;
        return $this;
    }


    public function __invoke()
    {
        /** @var \ReflectionClass $interface */
        //获取定义类的名称
        foreach ($this->getReflectionClass()->getInterfaces() as $key => $interface) {
            if (strpos($key, 'ServiceIf') === false) {
                break;
            }
        }
        //解析各个函数名字
        foreach ($interface->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {

            if (strpos($method->getShortName(), 'recv_') === 0 || strpos($method->getShortName(), 'send_') === 0 || strpos($method->getShortName(), '__') === 0) {
                continue;
            }

            $docComment = $method->getDocComment();

            //解析参数的名字和类型
            $docCommentOut = [];
            preg_match_all('#@param\s+([^\s]+)\s+\$([^\s]+)\s#iUs', $docComment, $docCommentOut);
            //解析返回值
            $return = [];
            preg_match_all('#@return\s+([^\s]+)\s#iUs', $docComment, $return);
            $this->setReturn((string)$return[1][0]);

            //如果参数的类型是一个类,那么需要针对这个类生成Model
            foreach ($docCommentOut[1] as $item) {
                if (strpos($item, '\\') !== false) {
                    (new ThriftModelMaker())
                        ->setClassName($item)
                        ->__invoke();
                }
            }

            $this->setParameter([]);
            $this->setParameter(array_combine($docCommentOut[2], $docCommentOut[1]));
            $this->setMethodName($method);

            //生成服务端代码
            $rootDir = realpath(dirname(static::$rootDir));
            mkdir($dir = $rootDir.'/App/');
            mkdir($dir = $dir.strtr($this->getReflectionClass()->getShortName(), ["Client" => ""]).'/');
            $filename = $dir.ucfirst(strtr($this->getReflectionClass()->getShortName(), ['Client' => ""])).ucfirst($method->getName()).".php";
            $this->file_put_contents($filename, __DIR__.'/Template/ThriftServer.php');

            //Hprose
            $Hprose = dirname($this->getReflectionClass()->getFileName()).'/Hprose/';
            mkdir($Hprose);
            $filename = $Hprose.ucfirst($method->getName()).".php";
            $this->file_put_contents($filename, __DIR__.'/Template/HproseClient.php');

            //thrift
            $ClientDir = dirname($this->getReflectionClass()->getFileName()).'/Client/';
            mkdir($ClientDir);

            $filename = $ClientDir.ucfirst($method->getName()).".php";
            $this->file_put_contents($filename, __DIR__.'/Template/ThriftClient.php');

        }
    }

}