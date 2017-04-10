package chat

import "html/template"
import "net/http"

type chatPage struct {
	User UserSession
	Key  SessionKey
}

var templates = template.Must(template.ParseFiles("templates/chat.html", "templates/dbg_login.html"))

func (s Server) serveLogin(w http.ResponseWriter, r *http.Request) {

	t, err := template.ParseFiles("templates/dbg_login.html")
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	err = t.Execute(w, nil)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
	}
}

func (s Server) setupStatic() http.Handler {
	return http.FileServer(http.Dir("static"))
}

func (s Server) serveTemplate(w http.ResponseWriter, r *http.Request) {
	page := chatPage{}

	sessArg := r.URL.Query()["session"]
	if len(sessArg) < 1 {
		http.Error(w, "Must have a session key.", 400)
		return
	}
	sessK := SessionKey(sessArg[0])

	sess, ok := s.sessions.activeSessions[sessK]
	if !ok {
		http.Error(w, "Invalid session key.", 400)
		return
	}

	page.User = sess
	page.Key = sessK

	t, err := template.ParseFiles("templates/chat.html", "templates/base_templ.html")
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
	}
	err = t.Execute(w, page)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
	}
}

func (s Server) serveWhiteboard(w http.ResponseWriter, r *http.Request) {
	page := chatPage{}

	sessArg := r.URL.Query()["session"]
	if len(sessArg) < 1 {
		http.Error(w, "Must have a session key.", 400)
		return
	}
	sessK := SessionKey(sessArg[0])

	sess, ok := s.sessions.activeSessions[sessK]
	if !ok {
		http.Error(w, "Invalid session key.", 400)
		return
	}

	page.User = sess
	page.Key = sessK

	t, err := template.ParseFiles("templates/whiteboard.html", "templates/base_templ.html")
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
	}
	err = t.Execute(w, page)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
	}
}
