<?php

namespace kuaigeng\pushconfig\Siteroot;


use xltxlm\helper\Ctroller\LoadClass;
use xltxlm\thrift\App\Base;

eval('include "/var/www/html/vendor/autoload.php";');

(new LoadClass(Base::class))
    ->setUrlPath($_GET['c'] ?: 'Index/Pushconfig')
    ->__invoke();