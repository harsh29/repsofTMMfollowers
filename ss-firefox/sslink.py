#! /usr/bin/python

import base64
import sys
import re
import os

if_nohup = 0
link = None

def printHelp():
	print(
"""usage: %s [[option], ...] [ss link(format: 'ss://(base64)')]

options:
  -b          run at backend
""" % (sys.argv[0]))

for arg in sys.argv[1:]:
	if arg == "-b":
		if_nohup = 1
	else:
		if not link:
			link = arg
		else:
			print("unknown argument '%s'" % arg)
			printHelp()
			exit(1)

if not link:
	print("no link given")
	printHelp()
	exit(1)

reg = re.match("ss://([a-zA-Z0-9+/=]+)", link)

if not reg:
	print("'%s' is not a legal shadowsocks link" % link)
	exit(1)

bas = reg.group(1)
dec = base64.b64decode(bas)
reg = re.match("(.*)", str(dec))
dec = reg.group(1)

print("%s >> %s" % (bas, dec))

reg = re.match("([a-zA-Z0-9-]+):(.*)@(.*):([0-9]+)", str(dec))

method = reg.group(1)
passwd = reg.group(2)
hostnm = reg.group(3)
port = reg.group(4)

print("%s, %s, %s, %s" % (method, passwd, hostnm, port))

output = \
"""{
	"server": "%s",
	"server_port": %s,
	"local_address": "127.0.0.1",
	"local_port": 1080,
	"password": "%s",
	"timeout": 300,
	"method": "%s",
	"fast_open": false,
	"workers": 1
}""" % (hostnm, port, passwd, method)

output = \
"""-s "%s" -p %s -k "%s" -m "%s" -b "127.0.0.1" -l 1080""" % (hostnm, port, passwd, method)

print("argument: " + output)
print(str(if_nohup))

if if_nohup:
	os.system("nohup sslocal %s 2>/dev/null &" % output)
else:
	os.system("sslocal %s" % output)
