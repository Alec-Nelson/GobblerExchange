package chat

type MessageListener func(Message)

type MessageBroker struct {
	messages  map[TopicName]MessageBuffer
	listeners map[TopicName]map[SessionKey]MessageListener
	NewBuf    func() MessageBuffer
}

func NewBroker(nb func() MessageBuffer) MessageBroker {
	return MessageBroker{
		messages:  make(map[TopicName]MessageBuffer),
		listeners: make(map[TopicName]map[SessionKey]MessageListener),
		NewBuf:    nb,
	}
}

func (m MessageBroker) addListener(t TopicName, k SessionKey, l MessageListener) {
	lists, ok := m.listeners[t]
	if !ok {
		lists = make(map[SessionKey]MessageListener)
		m.listeners[t] = lists
	}
	lists[k] = l
}

func (m MessageBroker) removeListener(t TopicName, k SessionKey) {
	delete(m.listeners[t], k)
}

func (m MessageBroker) getMessages(t TopicName) MessageBuffer {
	buf, ok := m.messages[t]
	if !ok {
		buf = m.NewBuf()
		m.messages[t] = buf
	}
	return buf
}

func (m MessageBroker) sendMessage(t TopicName, ms Message) {
	buf, ok := m.messages[t]
	if !ok {
		buf = m.NewBuf()
		m.messages[t] = buf
	}

	buf = m.applySpecialActions(t, ms, buf)

	buf.addMessage(ms)
	for _, v := range m.listeners[t] {
		v(ms) // trigger listener
	}

}
