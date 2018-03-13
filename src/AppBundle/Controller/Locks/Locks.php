<?php
namespace AppBundle\Controller\Locks;

class Locks{
    public function qwerty($username, $pass){
        //$broker_ip=$this->getParameter('broker_ip');
        //$broker_port=$this->getParameter('broker_port');
        //$broker_name=$this->getParameter('broker_name');
        //$broker_pass=$this->getParameter('broker_pass');
        $client = new \Mosquitto\Client('aaaaaa');
        $client->onConnect([$this,'connect']);
        $client->onDisconnect([$this,'disconnect']);
        $client->onPublish([$this,'publish']);
        $client->setCredentials('grabber', 'toor');
        $client->connect("163.172.90.25", 9002);
        $arr = array('lock_name'=>$username, 'lock_pass'=>$pass); 
        $arr=json_encode($arr);
        $mid = $client->publish('new-locks', $arr);
        //$client->disconnect();
        unset($client);
    }
    public function connect($r) {
        echo "I got code {$r}\n";
    }

    public function publish() {
        global $client;

        //$client->disconnect();
    }

    public function disconnect() {

    }
}
