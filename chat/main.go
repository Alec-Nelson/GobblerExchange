package main

import "github.com/Alec-Nelson/GobblerExchange/chat/chat_server"
import "flag"

var port = flag.Int("port", 8081, "Port no to start the server")

func main() {
	flag.Parse()
	svr := chat.NewServer(chat.NewBroker(chat.MakeMemoryBuffer(20000)))
	chat.StartGAEHealthServer()
	svr.StartServer(*port)
}
