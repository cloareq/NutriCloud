ws = require("nodejs-websocket");

// Dictionaire qui stock tous les clients
// Sera utilisé au moment d'envoyer des web socket aux clients
// Les clefs sont leur username
var clients = new Object();

// Sert à l'initialisation des socket
// Les utilisateurs n'ont pas d'autre raison d'envoyer des sockets autre que l'initialisation 
var server = ws.createServer(function (conn) {
    console.log("+1 New connection (wait for username)");

    // Recois l'unique message qui contient le username
    conn.on("text", function (str) {
		// Parse le json et reccupere le username
		var username = JSON.parse(str)["username"];
		// Stock le username et le client dans le dictionnaire
        clients[username] = conn;
        console.log("+1 New user stored: " + username);
    });

    conn.on("close", function (code, reason) {
        console.log("Connection closed")
    });
}).listen(3000);


// API qui sert à Symfony
// Au lieu s'envoyer des web socket au client (parce que c'est trop galère et que j'ai pas reussi)
// Symfony va envoyer du Json sur la route /web socket de CE serveur (avec le username)
// Cette api (nodejs) va reccupérer la socket pour le username donné et envoyer le json qu'elle vient de recevoir.

var express = require('express');
var app = express();

// Bon j'ai par reussi à reccupérer le username dans ma requete post, je sais pas si j'envoie mal ou la reccupère mal
// bref j'ai mis le username maignan en dur
app.get('/websocket', function (req, res) {
    console.log("--> Send new message:")
    console.log(req.query);
    json_message = '{"sender": "' + req.query["sender"] + '",' +
                   '"notiftype": "'+ req.query["notiftype"] + '",' +
                   '"content": "'+ req.query["content"] + '"}'; 
    clients[req.query["receiver"]].sendText(json_message); // TO DO check si il existe une meth sendJson
    res.send(JSON.stringify({"status": "ok"}));
});

app.listen(3001, function () {
  console.log('API Rest listening on port 3001');
  console.log('Web socket listening on port 3000');
});
