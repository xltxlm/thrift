<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2017/3/20
 * Time: 15:05
 */

namespace xltxlm\thrift;

use Composer\Autoload\ClassLoader;
use xltxlm\helper\Ctroller\LoadClassRegister;
use xltxlm\helper\Hclass\ClassNameFromFile;

/**
 * 根据 thrift 文件,一套全部做下来
 * Class ThriftClientMakerAll
 * @package xltxlm\thrift
 */
class ThriftClientMakerAll
{
    use LoadClassRegister;

    public function __invoke()
    {
        $rootDir = realpath(dirname(dirname(dirname((new \ReflectionClass(ClassLoader::class))->getFileName()))).'/Thrift');
        //查找网站的根目录
        $Directory = new \RecursiveDirectoryIterator($rootDir);
        $Iterator = new \RecursiveIteratorIterator($Directory);
        /** @var \SplFileInfo $item */
        foreach ($Iterator as $item) {
            if (strpos($item->getFilename(), '.thrift') === false) {
                continue;
            }
            chdir($rootDir);
            //生成客户端
            echo shell_exec("thrift -out ../  -r   --gen php:psr4   ".$item->getFilename());
            //生成服务端
            echo shell_exec("thrift -out ../  -r   --gen php:server   ".$item->getFilename());
        }

        $Directory = new \RecursiveDirectoryIterator($rootDir);
        $Iterator = new \RecursiveIteratorIterator($Directory);
        /** @var \SplFileInfo $item */
        foreach ($Iterator as $item) {
            if (strpos($item->getFilename(), 'Client.php') === false) {
                continue;
            }
            $className = (new ClassNameFromFile())
                ->setFilePath($item->getRealPath())
                ->getClassName();
            //生成对应的调用代码
            (new ThriftClientMaker(self::$rootClass))
                ->setClassName($className)
                ->__invoke();
        }
    }
}
