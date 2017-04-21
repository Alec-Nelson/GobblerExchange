package chat

import "net/http"
import "log"

// Start a health check satisfying server so GAE knows our application is running.
func StartGAEHealthServer() {
	gaeMux := http.NewServeMux()
	gaeMux.HandleFunc("/_ah/health", healthCheckHandler)
	go func() {
		log.Fatal(http.ListenAndServe(":8080", gaeMux))
	}()
}
