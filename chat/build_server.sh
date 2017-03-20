#!/bin/sh

# Cross compile binary for the server OS
GOOS="linux" go build
scp ./chat root@104.236.205.162:~/GobblerExchange/chat/server_bin
