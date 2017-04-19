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
use xltxlm\helper\Hdir\DirTemplate;

/**
 * 根据 thrift 文件,一套全部做下来
 * Class ThriftClientMakerAll
 * @package xltxlm\thrift
 */
class ThriftMakerAll
{
    use LoadClassRegister;

    public function __invoke()
    {
        $rootDir = realpath(dirname(static::$rootDir).'/Thrift');
        $dirname = dirname('/'.strtr(static::$rootNamespce, ['\\' => '/']));
        //查找网站的根目录
        $Directory = new \RecursiveDirectoryIterator($rootDir);
        $Iterator = new \RecursiveIteratorIterator($Directory);
        /** @var \SplFileInfo $item */
        foreach ($Iterator as $item) {
            if (strpos($item->getFilename(), '.thrift') === false) {
                continue;
            }
            chdir($rootDir);
            shell_exec("rm -rf /tmp/thrift/");
            shell_exec("mkdir -p /tmp/thrift/");
            //生成客户端
            $cmd = "thrift -out /tmp/thrift/  -r   --gen php:psr4   ".$item->getFilename();
            echo shell_exec($cmd);
            //生成服务端
            echo shell_exec("thrift -out /tmp/thrift/  -r   --gen php:server   ".$item->getFilename());
            (new DirTemplate)
                ->setFromDir("/tmp/thrift/$dirname/")
                ->setToDir(dirname($rootDir))
                ->__invoke();
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
