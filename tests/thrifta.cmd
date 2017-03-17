cd %~dp0
rem del /S /Q ThriftClient
rem del /S /Q ThriftServer
mkdir ThriftClient
mkdir ThriftServer
thrift-0.10.0.exe -out ThriftClient  -r   --gen php:psr4     a\tutorial.thrift
thrift-0.10.0.exe -out ThriftServer  -r   --gen php:server      a\tutorial.thrift