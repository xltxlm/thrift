
namespace php Thrift

/**
*
*/
struct Myclass {
  1: i32 num1 = 0,
  2: i32 num2,
  3: optional string op,
  4: optional string comment,
}

service abc
{
    i32 haha1();
    Myclass haha2(1: Myclass Myclass);
    i32 haha3(1: i32 aaaaa);
}

