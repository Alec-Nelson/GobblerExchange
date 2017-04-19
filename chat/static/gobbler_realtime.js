gobbler_realtime = function(key, username){
    var mykey = key;
    var myuname = username;
    var HOST = "ws://"+window.location.host+"/socket?session="+key;

    
    socket = null;
    var _onMessage = function(msg){ console.log("No handler for "+msg); };
    var _onMessageDispatch = function(message){
	console.log(message);
	_onMessage(JSON.parse(message.data))
    };
    
    var _connectSocket = function(topic){
	if(socket != null){
	    socket.close();
	}
	var socketurl = HOST+"&topic="+topic;
	socket = new WebSocket(socketurl);
	socket.onmessage = _onMessageDispatch;
    }

    var _setOnMessage = function(messageFunc){
	_onMessage = messageFunc
    }

    var sendMessage = function(message){
	console.log(message);
	socket.send(JSON.stringify(message));
	console.log("SENT");
    }

    var sendMessageContent = function(content){
	message = {
	    Content: content,
	    SenderName: myuname
	}
	sendMessage(message);
    }

    return {
	sendMessage: sendMessage,
	setOnMessage: _setOnMessage,
	joinTopic: _connectSocket,
	sendMessageContent: sendMessageContent,
	currentName: myuname
    }
}
