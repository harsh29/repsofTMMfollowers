#! /usr/bin/python

# this script is used for config shadowsocks in firefox 2.0+

import sys
from marionette import Marionette

args = sys.argv[1:]

if_open = 1

try:
	args.index("close")
	if_open = 0
except: pass
finally: pass

client = Marionette("localhost", port = 2828)
client.start_session()

client.set_prefs({
	"network.proxy.type": if_open,
	"network.proxy.socks": "127.0.0.1",
	"network.proxy.socks_port": 1080
})