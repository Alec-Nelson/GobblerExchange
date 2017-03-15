package main
import "github.com/gdcolella/GobblerExchange/chat/chat_server"

func main() {
	svr := chat.NewServer(chat.MessageBroker{
		NewBuf: chat.MakeMemoryBuffer(200), 
	})
	svr.StartServer(8080)
}
