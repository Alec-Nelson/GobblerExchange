package chat

func (m MessageBroker) applySpecialActions(t TopicName, ms Message, buf MessageBuffer) MessageBuffer {

	if ms.Content == "!clear" {
		m.messages[t] = m.NewBuf()
		buf = m.messages[t]
	}

	if ms.Content == "!clear_mine" {
		nb := m.NewBuf()
		iter := buf.iter()
		for msg, ok := iter(); ok; msg, ok = iter() {
			if msg.SenderName != ms.SenderName {
				nb.addMessage(*msg)
			}
		}
		m.messages[t] = nb
		buf = nb
	}

	return buf
}
