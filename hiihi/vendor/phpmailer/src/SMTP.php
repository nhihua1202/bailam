<?php
namespace PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class SMTP {
    public $doDebug = 0;
    protected $socket;
    public function connect($host, $port=25, $timeout=30){
        $this->socket = fsockopen($host, $port, $errno, $errstr, $timeout);
        return (bool)$this->socket;
    }
    public function mail($from){ $this->send('MAIL FROM:<' . $from . '>'); }
    public function recipient($to){ $this->send('RCPT TO:<' . $to . '>'); }
    public function data($data){
        $this->send('DATA');
        $this->send($data);
        $this->send('.');
    }
    public function send($cmd){ if (!$this->socket) return false; fputs($this->socket, $cmd . "\r\n"); return true; }
    public function quit(){ if ($this->socket) { $this->send('QUIT'); fclose($this->socket); } }
}
