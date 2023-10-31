<?php
namespace MyApp;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use React\Stream\ReadableResourceStream as Stream;

class Servidorsocket implements MessageComponentInterface
{
    protected $clients;
    protected $connection = array();
    protected $shell = array();
    protected $stream = array();
    protected $conectado = array();

    const COLS = 80;
    const ROWS = 24;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        $this->connection[$conn->resourceId] = null;
        $this->shell[$conn->resourceId] = null;
        $this->stream[$conn->resourceId] = null;
        $this->conectado[$conn->resourceId] = null;
        //$conn->send("Wait Connection ");
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);
        switch (key($data)) {
	         case 'data':
	             if (is_resource($this->shell[$from->resourceId])) {
                  fwrite($this->shell[$from->resourceId], $data['data']['data']);
                } else {
                  //$from->send(mb_convert_encoding("\n\rWaiting New Connection","UTF-8"));
                  $this->conectado[$from->resourceId] = false;
                  $this->shell[$from->resourceId] = null;
                  $from->close();
                }
                break;
            case 'resize':
                // Use lastest build https://github.com/php/pecl-networking-ssh2 for ssh2_shell_resize() function
                if (function_exists('ssh2_shell_resize')) {
                    ssh2_shell_resize($this->shell[$from->resourceId], $data['resize']['cols'], $data['resize']['rows']);
                }
                break;
            case 'auth':
                if ($this->connectSSH(
                        $data['auth']['server'],
                        $data['auth']['port'],
                        $data['auth']['user'],
                        $data['auth']['password'],
                        $data['auth']['key'],
                        $from)
                    ) {

                } else {
                    $from->send(mb_convert_encoding("Erro, non podo conectar co host.\n\r- Revisa o nome do host e as credenciais de acceso\n\n\r", "UTF-8"));
                    $from->close();
                }
                break;
        }
    }

    public function connectSSH($server, $port, $user, $password, $key, $from)
    {
        if (!is_numeric($port)) return;
        $this->connection[$from->resourceId] = ssh2_connect($server, $port);
        $auth = false;
        if ($key) {
            //convert from data-url
            $key_data=file_get_contents($key);

            $pem = tmpfile();
            $pem_file = stream_get_meta_data($pem)['uri'];
            file_put_contents($pem_file,$key_data);

            //convert key to rsa
            shell_exec('ssh-keygen -p -N "" -m pem -f ' . $pem_file);

            $pub = tmpfile();
            $pub_file = stream_get_meta_data($pub)['uri'];

            //extract public key
            shell_exec("ssh-keygen -m pem -y -f $pem_file > $pub_file");

            $auth = ssh2_auth_pubkey_file(
                $this->connection[$from->resourceId],
                $user,
                $pub_file,
                $pem_file,
                'secret'
            );
	} else {
		if (is_resource($this->connection[$from->resourceId])) {
            		$auth = ssh2_auth_password(
                		$this->connection[$from->resourceId],
                		$user,
                		$password
			);
		} else {
			$auth=false;
		}
        }

        if ($auth) {
            //$conn->send("Authentication Successful!\n");
            $this->shell[$from->resourceId] = ssh2_shell($this->connection[$from->resourceId], 'xterm', null, self::COLS, self::ROWS, SSH2_TERM_UNIT_CHARS);
            sleep(1);
            $this->conectado[$from->resourceId] = true;

            $this->stream[$from->resourceId] = new Stream($this->shell[$from->resourceId]);
            $this->stream[$from->resourceId]->on('data', function ($data) use ($from) {
                  $from->send(mb_convert_encoding($data, "UTF-8"));
            });
            
            
	    $this->stream[$from->resourceId]->on('close', function () use ($from) {
		$from->send("\033[2J");
           	$from->send("\033[H");
                $from->close();
            });
            
            return true;
        } else {
            return false;
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->conectado[$conn->resourceId] = false;
        $this->clients->detach($conn);

        // Gracefully closes terminal, if it exists
        if (isset($this->shell[$conn->resourceId]) && is_resource($this->shell[$conn->resourceId])) {
            fclose($this->shell[$conn->resourceId]);
            $this->stream[$conn->resourceId]->close();
            $this->shell[$conn->resourceId] = null;
        }
        // Prevent memory leak 
        unset($this->connection[$conn->resourceId]);
        unset($this->shell[$conn->resourceId]);
        unset($this->stream[$conn->resourceId]);
	     unset($this->conectado[$conn->resourceId]);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }
}


