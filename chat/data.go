package chat

import "time"

type SessionKey string
type TopicName string

type UserSession struct {
	DisplayName     string
	AvailableTopics []TopicName
}

type Message struct {
	SenderName string
	Content    string
	SentTime   time.Time
}

type Topic struct {
	Name        TopicName
	ActiveUsers map[string]interface{} // Set of user names
	Messages    []Message
}

func (t *Topic) addUser(u string) {
	t.ActiveUsers[u] = nil
}

func (t *Topic) removeUser(u string) {
	delete(t.ActiveUsers, u)
}

type MessageIter func() (*Message, bool)

type MessageBuffer interface {
	iter() MessageIter
	addMessage(Message)
}

type MemoryMessageBuffer struct {
	messages []Message
	maximum  int
}

func (m *MemoryMessageBuffer) addMessage(ms Message) {
	m.messages = append(m.messages, ms)
	if len(m.messages) > m.maximum {
		m.messages = m.messages[m.maximum-len(m.messages):]
	}
}

func (m *MemoryMessageBuffer) iter() MessageIter {
	var i = 0
	return func() (*Message, bool) {
		if i >= len(m.messages) {
			return nil, false
		}
		var result = &m.messages[i]
		i += 1
		return result, true
	}
}
