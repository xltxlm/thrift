cd %~dp0
del /S /Q ThriftClient
del /S /Q ThriftServer
mkdir ThriftClient
mkdir ThriftServer
thrift-0.10.0.exe -out ThriftClient  -r   --gen php:psr4     tutorial.thrift
thrift-0.10.0.exe -out ThriftServer  -r   --gen php:server      tutorial.thrift