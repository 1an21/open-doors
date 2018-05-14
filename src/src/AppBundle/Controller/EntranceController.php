<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Entrance;
use AppBundle\Entity\Key;
use AppBundle\Entity\Lockkey;
use AppBundle\Entity\Repository\EntranceRepository;
use AppBundle\Form\Type\EntranceType;
use AppBundle\Form\Type\KeyType;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Predis\Client;

/**
 * Class KeyController
 * @package AppBundle\Controller
 *
 * @RouteResource("Entrance")
 */
class EntranceController extends FOSRestController implements ClassResourceInterface
{

    /**
     * Gets a collection of keys for locks
     *
     * @return array
     *
     * @ApiDoc(
     *     output="AppBundle\Entity\Entrance",
     *     statusCodes={
     *         200 = "Returned when successful",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function cgetAction(Request $request){
    $queryBuilder = $this->getEntranceRepository()->searchQuery();

    if (($request->query->getAlnum('lock'))) {
      if (($request->query->getAlnum('employee'))) {
        $queryBuilder
            ->where('e.surname LIKE :surname or e.name LIKE :surname and l.lock_name LIKE :idlock')
            ->setMaxResults(100)
            ->setParameter('surname', '%' .$request->query->getAlnum('employee').'%')
            ->setParameter('idlock', '%' .$request->query->getAlnum('lock').'%');
      }
      else {
        $queryBuilder
            ->where('l.lock_name LIKE :idlock')
            ->setMaxResults(100)
            ->setParameter('idlock', '%' .$request->query->getAlnum('lock').'%' );
      }
    }
    elseif(($request->query->getAlnum('employee'))) {
      $queryBuilder
          ->where('e.surname LIKE :surname or e.name LIKE :surname')
          ->setMaxResults(100)
          ->setParameter('surname', '%' .$request->query->getAlnum('employee').'%');
    }

    if(($request->query->getInt('datefrom'))){
        if(($request->query->getInt('dateto'))){
        $queryBuilder
            ->where('UNIX_TIMESTAMP(en.time) >= :from and UNIX_TIMESTAMP(en.time) <= :to')
            ->setMaxResults(100)
            ->setParameter('from', $request->query->getInt('datefrom'))
            ->setParameter('to', $request->query->getInt('dateto'));
        }
        else {
        $queryBuilder
            ->where('UNIX_TIMESTAMP(en.time) >= :from')
            ->setMaxResults(100)
            ->setParameter('from', $request->query->getInt('datefrom'));
        }
    }
    elseif(($request->query->getInt('dateto'))) {
        $queryBuilder
            ->where('UNIX_TIMESTAMP(en.time) <= :to')
            ->setMaxResults(100)
            ->setParameter('to', $request->query->getInt('dateto'));
    }

    return $this->get('knp_paginator')->paginate(
        $queryBuilder->getQuery(),
        $request->query->getInt('page', 1),
        $request->query->getInt('limit', 20));
  }



    /**
     * Add logs of entrances from LogEntrancesService
     * @param Request $request
     * @return View|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *     output="AppBundle\Entity\Entrance",
     *     statusCodes={
     *         200 = "Returned when a lock and key are in database",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function postAction(Request $request)
    {
        $em = $this->get('doctrine')->getManager();        
        $fieldtag=$request->request->get('tag');
        $fieldlockname=$request->request->get('lock_name');
        $fieldresult=$request->request->get('result');
        $fieldtime=$request->request->get('time');

        $lock=$em->getRepository('AppBundle:Lock')->findOneBy(array('lock_name' => $fieldlockname));
        $key=$em->getRepository('AppBundle:Key')->findOneBy(array('tag' => $fieldtag));

        if($key==null){
            $key=new Key();
            $key->setTag($fieldtag);
            $key->setDescription("");
            $em->persist($key);
            $em->flush();
            $k_id=$key->getId();
            $k_tag=$key->getTag();

            $mqtt= new BrokerController();
            $broker_ip=$this->getParameter('broker_ip');
            $broker_port=$this->getParameter('broker_port');
            $broker_name=$this->getParameter('broker_name');
            $broker_pass=$this->getParameter('broker_pass');
            $broker_client_name=$this->getParameter('broker_client_name');
            $topic_name=$this->getParameter('topic_key_added');
            $mqtt->pushKey($k_id, $k_tag, $broker_ip, $broker_port, $broker_name,$broker_pass, $broker_client_name, $topic_name);
        }
        if($lock!=null) {
            $entrance = new Entrance();
            $entrance->setLock($lock);
            $entrance->setKey($key);
            $entrance->setResult($fieldresult);
            $entrance->setTime($fieldtime);
            $em->persist($entrance);
            $em->flush();

            return new View("OK", Response::HTTP_OK);
        }

        else return new View("Not found this lock or key", Response::HTTP_NOT_FOUND);
    }

    /**
     * @return EntranceRepository
     */
    private function getEntranceRepository()
    {
        return $this->get('crv.doctrine_entity_repository.entrance');
    }

}
