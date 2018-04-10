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
     * Gets an individual key for individual lock
     *
     * @param int $lock
     * @param int $id
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @ApiDoc(
     *     output="AppBundle\Entity\Entrance",
     *     statusCodes={
     *         200 = "Returned when successful",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function getAction($id)
    {
        $key = $this->getEntranceRepository()->findEntranceQuery($id)->getOneOrNullResult();
        if ($key === null) {
            return new View("Dont exist key with id $id for lock $lock");
        }
        
        return $key;
    }

    /**
     * Gets a collection of keys for locks )
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
        if ($request->query->getAlnum('filter')) {
            $queryBuilder
                ->join("en.lock", "l")
                ->join("en.key", "k")
                ->where('l.lock_name LIKE :tag OR k.tag LIKE :tag OR en.result LIKE :tag' )
                ->setParameter('tag', '%' . $request->query->getAlnum('filter') . '%');
        }
        return $queryBuilder->getQuery()->getResult();
        // return $this->getEntranceRepository()->findLockQuery()->getResult();
    }



    /**
     * Add a new lock key relationship
     * @param Request $request
     * @return View|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *     output="AppBundle\Entity\Entrance",
     *     statusCodes={
     *         201 = "Returned when a new lock key relationship has been successful created",
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
        $id_lock=$lock->getId();

        $key=$em->getRepository('AppBundle:Key')->findOneBy(array('tag' => $fieldtag));
        $id_key=$key->getId();
        
        $em->flush();
        $entrance=new Entrance();
        $entrance->setLock($lock);
        $entrance->setKey($key);
        $entrance->setResult($fieldresult);
        $entrance->setTime($fieldtime);
        $em->persist($entrance);
        $em->flush();
        
        return new View("OK", Response::HTTP_OK);
    }

    /**
     * Totally update 
     * @param Request $request
     * @param int     $id
     * @param int     $lock
     * @return View|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *     input="AppBundle\Form\Type\EntranceType",
     *     output="AppBundle\Entity\Entrance",
     *     statusCodes={
     *         204 = "Returned when an existing lock key relationship has been successful updated",
     *         400 = "Return when errors",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function putAction(Request $request, $id)
    {

        $key = $this->getEntranceRepository()->findOneBy(array('key'=>$id, 'lock'=>$lock));

        if ($key === null) {
            return new View("Doesnt exist", Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(EntranceType::class, $key, [
            'csrf_protection' => false,]);

        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $routeOptions = [
            'id' => $key->getId(),
            '_format' => $request->get('_format'),
        ];

        $this->routeRedirectView('', $routeOptions, Response::HTTP_OK);
        $id=$key->getId();
        return $this->getEntranceRepository()->findIdQuery($id)->getOneOrNullResult();
    }


    /**
     * Update
     * @param Request $request
     * @param int     $id
     * @param int     $lock
     * @return View|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *     input="AppBundle\Form\Type\EntranceType",
     *     output="AppBundle\Entity\Entrance",
     *     statusCodes={
     *         204 = "Returned when an existing lock key relationship has been successful updated",
     *         400 = "Return when errors",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function patchAction(Request $request, $id)
    {
        /**
         * @var $key key
         */
        $key = $this->getEntranceRepository()->findOneBy(array('key'=>$id, 'lock'=>$lock));
        if ($key === null) {
            return new View("Doesnt exist", Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm(EntranceType::class, $key, [
            'csrf_protection' => false,
        ]);
        $form->submit($request->request->all(), false);
        if (!$form->isValid()) {
            return $form;
        }
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $routeOptions = [
            'id' => $key->getId(),
            '_format' => $request->get('_format'),
        ];
        $this->routeRedirectView('', $routeOptions, Response::HTTP_OK);
        $id=$key->getId();
        return $this->getEntranceRepository()->findIdQuery($id)->getOneOrNullResult();
    }


    /**
     * Delete a lock key relationship
     * @param int $id
     * @param int $lock
     * @return View
     *
     * @ApiDoc(
     *     statusCodes={
     *         204 = "Returned when an existing lock key relationship has been successful deleted",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function deleteAction( $id)
    {
        $key = $this->getEntranceRepository()->findEntranceQuery($lock, $id)->getOneOrNullResult();
         if ($key=== null) {
            return new View("This id $id doesnt exist", Response::HTTP_NOT_FOUND); }
        // return new View("Deleted key $id for lock $lock");
        
        $em = $this->get('doctrine')->getManager();
        $locks=$em->getRepository('AppBundle:Lock')->findOneById($lock);
        $kkey=$em->getRepository('AppBundle:Key')->findOneById($id);
        $em->flush();

        $Entrance=new Entrance();
        $Entrance->setLock($locks);
        $Entrance->setKey($kkey);

        $redis_ip=$this->container->getParameter('redis_ip');
        $redis_ports=$this->container->getParameter('redis_ports');
        $redis_schemes=$this->container->getParameter('redis_schemes');

        try {
            $predisClient = new Client(array(
            'scheme'   => $redis_schemes,
            'host'     => $redis_ip,
            'port'     => $redis_ports
        ));
        }
        catch (Exception $e){
            die($e->getMessage());
        }    

        $id=$Entrance->getLock();
        $ids=$Entrance->getKey();


        $lockss=$em->getRepository('AppBundle:Lock')->findOneById($id);
        $name_lock=$lockss->getLockName();

        $keys=$em->getRepository('AppBundle:Key')->findOneById($ids);
        $tag_key=$keys->getTag();


        $lock_key= $name_lock.':'.$tag_key;
        $predisClient->del($lock_key);
        
        $em->remove($key);
        $em->flush();
        return new View("Deleted key ");
    }

    /**
     * @return EntranceRepository
     */
    private function getEntranceRepository()
    {
        return $this->get('crv.doctrine_entity_repository.entrance');
    }
    private function getLockRepository()
    {
        return $this->get('crv.doctrine_entity_repository.lock');
    }
    private function getLockKeyRepository()
    {
        return $this->get('crv.doctrine_entity_repository.lockkey');
    }

}
