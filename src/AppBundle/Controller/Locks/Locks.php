<?php
namespace AppBundle\Controller\Locks;

class Locks{
    public function qwerty($username){
        $client = new \Mosquitto\Client('aaaa');
        $client->onConnect([$this,'connect']);
        $client->onDisconnect([$this,'disconnect']);
        $client->onPublish([$this,'publish']);
        $client->setCredentials('grabber', 'toor');
        $client->connect("163.172.90.25", 9002);


        $mid = $client->publish('new-locks', $username);



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