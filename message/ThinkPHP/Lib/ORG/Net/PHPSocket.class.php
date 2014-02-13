<?php
# PHPSocket.php -- php sockets
#
# Copyright (C) 2010 Progyan Softwares
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

class PhpSocket {  
	private $socket = NULL;
	private $server = NULL;
	private $port = null;
	private $eol = "\r\n";
	private $debug = true;

	// generic tcp PhpSocket wrapper
	/**
	 * create PhpSocket object
	 * params 
	 * $server - string, ip or domain name to connect to
	 * $port - int, port to make connection to
	 * $eol - string, the end of line string, by default its "\r\n"
	**/
    public function __construct($server, $port, $eol = NULL) {
        $this->socket = NULL;
        $this->server = $server;
		$this->port = $port;

		if ($eol !== NULL) {
			$this->eol = $eol;
		}
	}

	/**
	 * return eol
	 * params 
	 * return end of line
	**/
	public function getEol() {
		return $this->eol;
	}

	/**
	 * connect to socket
	 * params 
	 * return socket reference
	**/
    public function establish() {
        if ($this->socket !== NULL) {
			$this->quicklog("PhpSocket::establish - PhpSocket already created");
			return $this->socket;
		}

		$this->quicklog("PhpSocket::establish - PhpSocket creating");
		//s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
		$s = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

		$server = $this->server;
		$port = $this->port;

		$serverIP = gethostbyname($server);

		$this->quicklog("PhpSocket::establish - server: $server, port: $port, serverIP: $serverIP");
		
		$status = socket_connect($s, $serverIP, $port);

		if ($status === true) {
			$this->quicklog("PhpSocket::establish - PhpSocket created sucessfully");
			$this->socket = $s;
		}
		
        return $this->socket;
	}

	/**
	 * send data to socket
	 * params 
	 * $string - string, data to send to socket
	 * return $sent, length of data sent to socket
	**/
    public function send_data($string) {
		if ($this->socket == NULL) {
			$this->quicklog("PhpSocket::send_data - error in socket");
			return false;
		}
        
		$sent = socket_send($this->socket, $string, strlen($string), MSG_WAITALL);
		$this->quicklog("PhpSocket::send_data - $sent [" . trim($string) . "]");

		// catch sent errors
		/*
		for ($sent = 0; $sent < strlen($string); $sent += $fwrite) {
			$fwrite = fwrite($this->socket, substr($string, $sent));
			if ($fwrite === false) {
				return $sent;
			}
		}*/

		return $sent;
	}
	
	public function send_bytes($string) 
	{
		if ($this->socket == NULL) {
			$this->quicklog("PhpSocket::send_data - error in socket");
			return false;
		}
		$sent = socket_send($this->socket, $string, count($arr), MSG_WAITALL);
		$this->quicklog("PhpSocket::send_data - $sent [" . trim($string) . "]");

		return $sent;
	}

	/**
	 * receive data from socket
	 * params 
	 * $bufsize - int, length of data to recieve from socket
	 * return $buf - string, return data recived from socket
	**/
    public function receive_data($bufsize) {
		$this->quicklog("PhpSocket::receive_data - start,bufsize:" . $bufsize);
		if ($this->socket == NULL) {
			$this->quicklog("PhpSocket::receive_data - error in socket");
			return '';
		}
		$buf = '';
        $retsize = socket_recv($this->socket, $buf, $bufsize, MSG_WAITALL);
//        if ($retsize != $bufsize)
//        {
//        	echo "retsize != bufsize\n";
//        }
//        else {
//        	echo "retsize is ". $retsize;
//        	dump($buf);
//        }
		
		$this->quicklog("PhpSocket::receive_data - end");
		return $buf;
	}

	/**
	 * send data to socket
	 * params 
	 * $buf - string, checks for data sent
	 * return $sent, length of data sent to socket
	**/
    public function send_data_all($buf) {
		$this->quicklog("PhpSocket::send_data_all - start");
		
		if ($this->socket == NULL) {
			$this->quicklog("PhpSocket::send_data_all - error in socket");
			return 0;
		}

        $total = strlen($buf);
        $sent = 0;

        while ($sent < $total) {
            $sent = $sent + $this->send_data(substr($buf, $sent));
		}

		$this->quicklog("PhpSocket::send_data_all - end, sent - " . $sent);

        return $sent;
	}

	/**
	 * send data line to socket, concatenate eol to data
	 * params 
	 * $line - string, checks for data sent
	 * return $sent, length of data sent to socket
	**/
    public function send_data_line($line) {
		if ($this->socket == NULL) {
			$this->quicklog("PhpSocket::send_data_line - error in socket, $line");
			return '';
		}

        $sent = $this->send_data_all($line . $this->eol);
		$this->quicklog("PhpSocket::send_data_line - $sent");
		return $sent;
	}


	/**
	 * read data from socket
	 * params 
	 * $waitSecs - int, wait for data befre returning
	 * return $buf - string, return data recived from socket
	**/
	public function  read($waitSecs = 10) {
		$this->quicklog("PhpSocket::read - start");

		$buf = '';

		if ($this->socket == NULL) {
			break;
		}
		
		$read = array($this->socket);
		$write = array();
		$except = array();
		
		$updated = @socket_select($read, $write, $except, $waitSecs);

		if ($updated > 0){
			$buf = $this->receive_data_line();
		}


		$this->quicklog("PhpSocket::read - end, $buf");

		return $buf;
	}

	/**
	 * recieves one line
	 * params 
	 * return 
	 * $buf - string, returns one line from socket read
	**/
    public function receive_data_line() {
		$this->quicklog("PhpSocket::receive_data_line - started");
		if ($this->socket == NULL) {
			$this->quicklog("PhpSocket::receive_data_line - error in socket");
			return '';
		}

		$cnt = 0;
        $buf = '';

        while (true) {
            $in_byte = $this->receive_data(1);
            if ($in_byte == '') {
                return NULL;
			}

            if ($in_byte == "\r") {
                $cnt = 1;
			}
            elseif ($in_byte == "\n" && $cnt == 1) {
                $cnt = 2;
			}
            else {
                $cnt = 0;
			}

			$buf .= $in_byte;

            if ($cnt == 2 || strrpos($buf, $this->eol) !== false) {
				$this->quicklog("PhpSocket::receive_data_line - data: $buf");
                return $buf;
			}
		}

		
	}

	/**
	 * ccloses socket connection
	 * params 
	**/
    public function close() {
		$this->quicklog("PhpSocket::break_ - start");
		if ($this->socket == NULL) {
			$this->quicklog("PhpSocket::break_ - error in socket");
			return;
		}
		socket_shutdown($this->socket, 2);
        socket_close($this->socket);
        $this->socket = NULL;
		$this->quicklog("PhpSocket::break_ - end");
	}

	/**
	 * log PHPSocket messages
	 * params 
	 * $message - string, debug message
	**/
	private function quicklog($message) {
		if ($this->debug !== true) {
			return;
		}
		//print "$message<br>";
	}
}




