<?php
namespace AppBundle\Controller;

class BrokerController {
    

    public function pushMqtt($username, $pass, $broker_ip,$broker_port,$broker_name,$broker_pass, $broker_client_name, $topic_name){
        $client = new \Mosquitto\Client($broker_client_name);
        $client->onConnect([$this,'connect']);
        $client->onDisconnect([$this,'disconnect']);
        $client->onPublish([$this,'publish']);
        $client->setCredentials($broker_name, $broker_pass);
        $client->connect($broker_ip, $broker_port);
        $arr = array('lock_name'=>$username, 'lock_pass'=>$pass); 
        $arr=json_encode($arr);
        $mid = $client->publish($topic_name, $arr);
        unset($client);
    }

    public function pushKey($id, $tag, $broker_ip,$broker_port,$broker_name,$broker_pass, $broker_client_name, $topic_name){
        $client = new \Mosquitto\Client($broker_client_name);
        $client->onConnect([$this,'connect']);
        $client->onDisconnect([$this,'disconnect']);
        $client->onPublish([$this,'publish']);
        $client->setCredentials($broker_name, $broker_pass);
        $client->connect($broker_ip, $broker_port);
        $arr = array('id'=>$id, 'tag'=>$tag); 
        $arr=json_encode($arr);
        $mid = $client->publish($topic_name, $arr);
        unset($client);
        return $arr;
    }

    public function connect($r) {
        echo "I got code {$r}\n";
    }

    public function publish() {
        global $client;
    }

    public function disconnect() {

    }
    
}
