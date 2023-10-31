         var URLWSOCKET=null;

         Terminal.applyAddon(attach);  // Apply the `attach` addon
         Terminal.applyAddon(fit);  //Apply the `fit` addon

         var TCOLS=120;
         var TROWS=40;
         var tname;
         var resizeInterval;
         var wSocket=null;
         var term=null;

         var wssh_server="";
         var wssh_user="";
         var wssh_pass="";
         var wssh_port=22;
         var wssh_keyfile="";

         function makeTerminal() {
            var terminal=new Terminal({"cols":TCOLS,"rows":TROWS});

            terminal.on('data', function (data) {
               if (wSocket!=null) {
                  var dataSend = {"data":{"data":data}};
                  wSocket.send(JSON.stringify(dataSend));
                  //Xtermjs with attach dont print zero, so i force. Need to fix it :(
                  if (data=="0"){
                     term.write(data);
                  }
               }
            })

            terminal.onResize(function (evt) {
               if (wSocket!=null)  wSocket.send(JSON.stringify({"resize": {"rows": evt.rows,"cols": evt.cols }}));
            })

            return terminal;
         }

         function openSocket() {
            if (URLWSOCKET==null) {
                  URLWSOCKET="ws:"+location.hostname+":8080";
            }
            var socket = new WebSocket(URLWSOCKET);

            socket.onopen = function (event) {
               term.attach(wSocket,false,false);
               term.fit();
            };

            socket.onclose = function(event) {
               wSocket=openSocket();
               if ((wSocket!=null) && (wssh_server!="")) term.write("Acceso SSH a "+wssh_server+": Pulsa Conectar\n\r");
	         }

            socket.onerror = function (event){
               term.detach(wSocket);
               term.write("Error en Conexión");
            }
            return socket;
        }
        function ConnectServer() {
            clearConsole();
            if (wSocket==null) return;
            var dataSend = {"auth": {
                            "server": wssh_server,
                            "port": wssh_port,
                            "user": wssh_user,
                            "password": wssh_pass,
                            "key": false
                            }
                          };
	         var key=wssh_keyfile;

		      if (key!="") {
			      dataSend['auth']['key']=key;
		      }
            wSocket.send(JSON.stringify(dataSend));
            term.fit();
            term.focus();
        }

        //Execute resize with a timeout
        window.onresize = function() {
          clearTimeout(resizeInterval);
          resizeInterval = setTimeout(resize, 400);
        }

        // Recalculates the terminal Columns / Rows and sends new size to SSH server + xtermjs
        function resize() {
          if (term) {
            term.fit()
          }
        }

        function clearConsole() {
           term.write("\033[2J");
           term.write("\033[H");
        }

        function showTerminal(nameid) {
           tname=nameid;
           term=makeTerminal();
           if (wSocket==null) wSocket=openSocket();
           if (wSocket!=null) {
              term.open(document.getElementById(nameid));
              if (wssh_server!="") term.write("Acceso SSH a "+wssh_server+": Pulsa Conectar\n\r");
           }
        }

        function setServer(sname,pnum,uname,pwd,privkey) {
            clearConsole();
            wssh_server=sname;
            wssh_port=pnum;
            wssh_user=uname;
            wssh_pass=pwd;
            if (privkey !== undefined) wssh_keyfile=privkey;
            else                       wssh_keyfile="";
            if (wSocket!=null) {
               wSocket.close();
               wSocket=null;
            }
        }

        function setSSHWebSocket(host,port) {
            URLWSOCKET="ws:"+host+":"+port;
            if (wSocket!=null) {
               wSocket.close();
               wSocket=null;
            } else wSocket=openSocket();
        }
/*
	function setServerAndConnect(sname, pnum, uname, pwd, privkey) {
		clearConsole();
		wssh_server = sname;
	  	wssh_port = pnum;
	 	wssh_user = uname;
	 	wssh_pass = pwd;
	  	if (privkey !== undefined) wssh_keyfile = privkey;
	  	else wssh_keyfile = "";
	  	if (wSocket != null) {
	    	wSocket.close();
	    	wSocket = null;
	  	}
		if (wSocket == null) return;
	  		var dataSend = {
	   			 "auth": {
	      				"server": wssh_server,
	      				"port": wssh_port,
	      				"user": wssh_user,
	      				"password": wssh_pass,
	      				"key": false
	    		}
	  	};
	  	var key = wssh_keyfile;
		if (key != "") {
	    		dataSend['auth']['key'] = key;
	  	}
	  	wSocket.send(JSON.stringify(dataSend));
	  	term.fit();
	  	term.focus();
	}


	function prueba (sname,pnum,uname,pwd,privkey) {
		setServer(sname,pnum,uname,pwd,privkey);
		ConnectServer();
	}
*/
