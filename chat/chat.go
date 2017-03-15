package chat

type MessageListener func(Message)

type MessageBroker struct {
	messages  map[TopicName]MessageBuffer
	listeners map[TopicName]map[SessionKey]MessageListener
	newBuf func()MessageBuffer
}

func (m MessageBroker) addListener(t TopicName, k SessionKey, l MessageListener) {
	m.listeners[t][k] = l
}

func (m MessageBroker) removeListener(t TopicName, k SessionKey) {
	delete(m.listeners[t], k)
}

func (m MessageBroker) sendMessage(t TopicName, ms Message) {
	buf, ok := m.messages[t]
	if !ok {
		buf = m.newBuf()
		m.messages[t] = buf
	}
	buf.addMessage(ms)
	for _, v := range m.listeners[t] {
		v(ms) // trigger listener
	}
}
