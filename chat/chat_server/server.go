package chat

import "net/http"
import "fmt"
import "log"


type Server struct {
	sockets  WebsocketHandler
	sessions SessionHandler
	messages MessageBroker
}

func NewServer(m MessageBroker) Server {
	sessions := newSessionHandler()
	sockets := WebsocketHandler{
		sessions: sessions,
		messages: m,
	}
	s := Server{
		sockets:  sockets,
		sessions: sessions,
		messages: m,
	}
	return s
}

func (s Server) socketHandler(w http.ResponseWriter, r *http.Request) {
	topic := TopicName("all")
	query_topic := r.URL.Query()["topic"]
	if len(query_topic) > 1 {
		topic = TopicName(query_topic[0])
	}
	session := r.URL.Query()["session"]
	if len(session) < 1 {
		// MUST have a session.
		http.Error(w, "Must have a session key.", 400)
		return
	}
	sesh := SessionKey(session[0])
	if !s.sessions.validSession(sesh) {
		http.Error(w, "Invalid session key.", 400)
		return
	}

	s.sockets.DelegateConnection(w, r, http.Header{}, topic, sesh)
}

func (s Server) createSession(w http.ResponseWriter, r *http.Request) {
	nameArg := r.URL.Query()["name"]
	if len(nameArg) < 1 {
		http.Error(w, "Session needs a name. ", 400)
		return
	}
	name := nameArg[0]
	topics := r.URL.Query()["topics"]
	tops := []TopicName{}
	for tn := range topics {
		tops = append(tops, TopicName(tn))
	}
	tops = append(tops, TopicName("all"))
	sesh := UserSession{
		DisplayName:     name,
		AvailableTopics: tops,
	}
	key := s.sessions.openSession(sesh)
	fmt.Fprintf(w, "%s", key)
	log.Printf("Opened session for %s", name)
}

func (s Server) StartServer(port int) {
	http.HandleFunc("/socket", s.socketHandler)
	http.HandleFunc("/create_session", s.createSession)
	http.HandleFunc("/chat", s.serveTemplate)
	http.HandleFunc("/debug_login", s.serveLogin)
	log.Printf("Starting on %d", port)
	http.ListenAndServe(fmt.Sprintf(":%d", port), nil)
}
