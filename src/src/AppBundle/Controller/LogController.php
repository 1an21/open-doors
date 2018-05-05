<?php

namespace AppBundle\Controller;


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
     * Logs for relationship lock - key
     *
     * @return array
     *
     * @ApiDoc(
     *     output="AppBundle\Entity\LogLockKey",
     *     statusCodes={
     *         200 = "Returned when successful",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function cgetLockkeyAction(Request $request, $id)
    {
		return $this->getLogRepository()->FindLockKeyQuery($id)->getResult();
    }
    /**
     *
     * Logs for masterkeys
     * @return array
     *
     * @ApiDoc(
     *     output="AppBundle\Entity\LogMasterKey",
     *     statusCodes={
     *         200 = "Returned when successful",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function cgetMasterkeyAction(Request $request, $id)
    {
        return $this->getLogRepository()->FindMasterKeyQuery($id)->getResult();
    }

    /**
     * @return LogRepository
     */
    private function getLogRepository()
    {
        return $this->get('crv.doctrine_entity_repository.log');
    }

}
