package chat

import "github.com/gorilla/websocket"
import "log"

type WebsocketHandler struct {
	sessions SessionHandler
	messages MessageBroker
}

func serializeMessage(m Message) string {
	return m.Content
}

func (w *WebsocketHandler) handle(t TopicName, k SessionKey, c *websocket.Conn) {
	w.messages.addListener(t, k,
		func(ms Message) {
			if c.WriteJSON(ms) != nil {
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
