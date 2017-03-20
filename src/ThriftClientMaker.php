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


/**
 * 根据生成的代码,继续生成面向对象的代码
 * Class ThriftMaker
 * @package xltxlm\thrift
 */
final class ThriftClientMaker
{
    use LoadClassRegister;

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
            $rootDir = realpath(dirname(dirname(dirname((new \ReflectionClass(ClassLoader::class))->getFileName()))));
            mkdir($dir = $rootDir.'/App/');
            mkdir($dir = $dir.strtr($this->getReflectionClass()->getShortName(), ["Client" => ""]).'/');
            $filename = $dir.ucfirst(strtr($this->getReflectionClass()->getShortName(), ['Client' => ""])).ucfirst($method->getName()).".php";
            $this->file_put_contents($filename, __DIR__.'/Template/ThriftServer.php');

            $filename = dirname($this->getReflectionClass()->getFileName()).'/'.$this->getReflectionClass()->getShortName().ucfirst($method->getName()).".php";
            $this->file_put_contents($filename, __DIR__.'/Template/ThriftClient.php', true);

        }
    }

    /**
     * 同时写2份文件.临时文件的实时更新
     * @param $classRealFile
     * @param $templatePath
     */
    private function file_put_contents($classRealFile, $templatePath, $orverWrite = false)
    {
        ob_start();
        eval('include $templatePath;');
        $ob_get_clean = ob_get_clean();
        //1:先保证控制层的基准类一定存在
        if (!is_file($classRealFile) || $orverWrite) {
            file_put_contents($classRealFile, $ob_get_clean);
        }
        $dir = dirname($classRealFile).'/temp/';
        mkdir($dir);
        file_put_contents($dir.basename($classRealFile), $ob_get_clean);
    }

}