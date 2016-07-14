inutils4ant
========
:ant: Utilies for Apache Ant

[![Apache License](http://img.shields.io/badge/license-ASL-blue.svg)](https://github.com/brunocvcunha/inutils4ant/blob/master/LICENSE)




Usage Example
--------

Download the [`inutils4ant.xml`](https://raw.githubusercontent.com/brunocvcunha/inutils4ant/master/inutils4ant.xml) file and import to your project:

```xml
  <import file="path//inutils4ant.xml"/>
```


And use the functions:

- <inutils.echo>Message</inutils.echo> to echo with the timestamp.
- <inutils.checksum file="inutils4ant.xml"/> to print the checksum of a file.
- <inutils.upper string="inutils" to="var"/> to create a var with the string uppercase (INUTILS).
- <inutils.remakedir dir="folder" /> to remove and recreate the directory.
- <inutils.assertfileexists file="inutils4ant.xml" /> to assert the existence of a file.


(to be continued...)
