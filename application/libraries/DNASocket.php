<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use Workerman\Worker;
use Workerman\WebServer;
use Workerman\Autoloader;
use PHPSocketIO\SocketIO;
// composer autoload
require_once join(DIRECTORY_SEPARATOR, array(__DIR__, "DNASocket", "autoload.php"));

class DNASocket{
    var $io;
    public function __construct()
    {
        $this->io = new SocketIO(2022);
        $this->io->on('connection', function($socket){
            echo "connection";
            $socket->addedUser = false;
            // when the client emits 'new message', this listens and executes
            $socket->on('new message', function ($data)use($socket){
                // we tell the client to execute 'new message'
                $socket->broadcast->emit('new message', array(
                    'username'=> $socket->username,
                    'message'=> $data
                ));
            });
        });
        Worker::runAll();
    }
}