<?php

namespace AppBundle\Listener;

use Gedmo\Loggable\LoggableListener;

class Listener extends LoggableListener{

	protected function prePersistLogEntry($logEntry, $object)
    {  
    	parent::prePersistLogEntry($logEntry, $object);
    	
    	$mas=['key'=>'1'];
    	if($logEntry->getAction()== self::ACTION_REMOVE)
    	$logEntry->setData($mas);

    }

}