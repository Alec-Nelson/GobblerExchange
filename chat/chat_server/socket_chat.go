package chat

import "github.com/gorilla/websocket"
import "log"
import "net/http"
import "time"

type WebsocketHandler struct {
	sessions SessionHandler
	messages MessageBroker
}

func serializeMessage(m Message) string {
	return m.Content
}

func writeMessage(ms Message, c *websocket.Conn) error {
	return c.WriteJSON(ms)
}

// Catch up a connection with all the messages we currently have
func (w *WebsocketHandler) catchUpConn(t TopicName, c *websocket.Conn) error {
	iter := w.messages.getMessages(t).iter()
	for {
		msg, ok := iter()
		if !ok {
			return nil
		}

		err := writeMessage(*msg, c)
		if err != nil {
			return err
		}
	}
}

func (w *WebsocketHandler) handle(t TopicName, k SessionKey, c *websocket.Conn) {
	log.Print("Connecting to ", t)
	w.catchUpConn(t, c)
	msg_channel := make(chan Message, 5)
	w.messages.addListener(t, k,
		func(ms Message) {
			msg_channel <- ms
		})

	go func() {
		for {
			msg := <-msg_channel
			if writeMessage(msg, c) != nil {
				log.Print("Disconnecting")
				w.messages.removeListener(t, k)
			}
		}
	}()
	go func() {
		m := Message{}
		for {
			err := c.ReadJSON(&m)
			if err != nil {
				log.Print(err.Error())
				return
			}
			if m.SentTime.IsZero() {
				m.SentTime = time.Now()
			}
			log.Print(m)
			w.messages.sendMessage(t, m)
		}
	}()
}

func (wh *WebsocketHandler) DelegateConnection(w http.ResponseWriter, r *http.Request, responseHeader http.Header, t TopicName, k SessionKey) {
	upg := websocket.Upgrader{
		CheckOrigin: func(r *http.Request) bool {
			return true
		}}
	conn, err := upg.Upgrade(w, r, responseHeader)
	if err != nil {
		log.Print(err)
		return
	}
	wh.handle(t, k, conn)
}
