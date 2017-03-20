package chat

import "github.com/google/uuid"



type SessionHandler struct {
	activeSessions map[SessionKey]UserSession
	activeTopics map[TopicName]Topic
}

func newSessionHandler() SessionHandler {
	return SessionHandler{
		activeSessions: make(map[SessionKey]UserSession),
		activeTopics: make(map[TopicName]Topic),
	}
}

func (sh SessionHandler) validSession(s SessionKey) bool {
	_, ok := sh.activeSessions[s]
	return ok
}


func (sh SessionHandler) openSession(s UserSession) SessionKey {
	k := SessionKey(uuid.New().String())
	sh.activeSessions[k] = s
	for _, t := range sh.activeTopics {
		t.addUser(s.DisplayName)
	}
	return k
}

func (s SessionHandler) closeSession(sk SessionKey) {
	usr := s.activeSessions[sk]
	delete(s.activeSessions, sk)

	for _, t := range s.activeTopics {
		t.removeUser(usr.DisplayName)
	}
}
