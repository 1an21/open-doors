<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ExtLogEntries;
use AppBundle\Entity\Repository\LogRepository;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
/**
 * Class LogController
 * @package AppBundle\Controller
 *
 * @RouteResource("Log")
 */
class LogController extends FOSRestController implements ClassResourceInterface
{
   
    /**
     * Gets a collection of Locks (for use filter(lock_name): /locks?filter= )
     *
     * @return array
     *
     * @ApiDoc(
     *     output="AppBundle\Entity\ExtLogEntries",
     *     statusCodes={
     *         200 = "Returned when successful",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function cgetAction(Request $request)
    {
		return $this->getLogRepository()->createFindAllQuery()->getResult(); 
    }

    /**
     * @return LogRepository
     */
    private function getLogRepository()
    {
        return $this->get('crv.doctrine_entity_repository.ExtLogEntries');
    }

}
