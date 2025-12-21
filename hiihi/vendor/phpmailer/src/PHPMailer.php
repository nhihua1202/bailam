<?php
namespace PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class PHPMailer {
    public $SMTPDebug = 0;
    public $isSMTP_called = false;
    public $SMTPAuth = true;
    public $SMTPSecure;
    public $Host;
    public $Port;
    public $Username;
    public $Password;
    public $From;
    public $FromName;
    public $Subject;
    public $Body;
    protected $to = [];
    public function __construct($exceptions = null) {}
    public function isSMTP(){ $this->isSMTP_called = true; }
    public function setFrom($from, $name=''){ $this->From = $from; $this->FromName = $name; }
    public function addAddress($to){ $this->to[] = $to; }
    public function isHTML($bool) {}
    public function send(){
        if ($this->isSMTP_called) {
            $smtp = new SMTP();
            if (!$smtp->connect($this->Host, $this->Port, 10)) {
                throw new Exception('SMTP connect failed');
            }
            $smtp->mail($this->From ?: $this->Username);
            foreach ($this->to as $rcpt) $smtp->recipient($rcpt);
            $smtp->data("Subject: " . $this->Subject . "\r\n\r\n" . $this->Body);
            $smtp->quit();
            return true;
        } else {
            $to = implode(', ', $this->to);
            $headers = 'From: ' . $this->From;
            return mail($to, $this->Subject, $this->Body, $headers);
        }
    }
}
