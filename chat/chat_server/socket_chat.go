package chat

import "github.com/gorilla/websocket"
import "log"
import "net/http"

type WebsocketHandler struct {
	sessions SessionHandler
	messages MessageBroker
}

func serializeMessage(m Message) string {
	return m.Content
}

func (w *WebsocketHandler) handle(t TopicName, k SessionKey, c *websocket.Conn) {
	log.Print("Connecting to ", t)
	w.messages.addListener(t, k,
		func(ms Message) {
			if c.WriteJSON(ms) != nil {
				log.Print("Disconnecting")
				w.messages.removeListener(t,k)
			}
		})
	go func() {
		m := Message{}
		for {
			err := c.ReadJSON(m)
			if err != nil {
				return
			}
			log.Print(m)
			w.messages.sendMessage(t, m)
		}
	}()
}

func (wh *WebsocketHandler)  DelegateConnection(w http.ResponseWriter, r* http.Request, responseHeader http.Header, t TopicName, k SessionKey) {
	upg := websocket.Upgrader{}
	conn, err := upg.Upgrade(w,r,responseHeader)
	if err != nil {
		log.Print(err)
		return
	}
	wh.handle(t,k,conn)
}
