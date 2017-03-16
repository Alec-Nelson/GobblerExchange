package main
import "github.com/gdcolella/GobblerExchange/chat/chat_server"

func main() {
	svr := chat.NewServer(chat.NewBroker(chat.MakeMemoryBuffer(20000)))
	svr.StartServer(80)
}
