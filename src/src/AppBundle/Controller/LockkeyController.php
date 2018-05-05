<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Lockkey;
use AppBundle\Entity\Key;
use AppBundle\Entity\Repository\LockkeyRepository;
use AppBundle\Form\Type\LockkeyType;
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
 * @RouteResource("Lock")
 */
class LockkeyController extends FOSRestController implements ClassResourceInterface
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
     *     output="AppBundle\Entity\Lockkey",
     *     statusCodes={
     *         200 = "Returned when successful",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function getAvailablekeysAction($lock, $id)
    {
        $key = $this->getLockKeyRepository()->findLockKeyQuery($lock,$id)->getOneOrNullResult();
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
     *     output="AppBundle\Entity\Lockkey",
     *     statusCodes={
     *         200 = "Returned when successful",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function cgetAvailablekeysAction($lock){

        return $this->getLockKeyRepository()->findLockQuery($lock)->getResult();
    }

//    public function cgetAvailablekeysAction($lock, Request $request){
//
//        $queryBuilder = $this->getLockKeyRepository()->searchQuery($lock);
//        if ($request->query->getAlnum('filter')) {
//            $queryBuilder->join("lk.lock", "l")
//                ->join("lk.key", "k")
//                ->andwhere('l.lock_name LIKE :tag OR k.tag LIKE :tag OR k.description LIKE :tag')
////                ->orwhere('l.lock_name LIKE :tag')
////                ->orwhere('k.tag LIKE :tag')
////                ->orwhere('k.description LIKE :tag')
//                //->andwhere('lk.lock = :lock')
//                ->setParameter('lock', $lock)
//                ->setParameter('tag', '%' . $request->query->getAlnum('filter') . '%');
//
//        }
//        return $queryBuilder->getQuery()->getResult();
//    }
   

    /**
     * Add a new lock key relationship
     * @param Request $request
     * @return View|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *     output="AppBundle\Entity\Lockkey",
     *     statusCodes={
     *         201 = "Returned when a new lock key relationship has been successful created",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function postAvailablekeysAction(Request $request, $lock)
    {
        $requestLock=$this->getLockRepository()->createFindOneByIdQuery($lock)->getOneOrNullResult();
        if ($requestLock === null) {
        return new View("Doesnt exist lock $lock", Response::HTTP_NOT_FOUND);
        }
        $em = $this->get('doctrine')->getManager();
        $locks=$em->getRepository('AppBundle:Lock')->findOneById($lock);
        $em->flush();

        $lockkey=new Lockkey();
        $lockkey->setLock($locks);

        $form = $this->createForm(LockkeyType::class, $lockkey, [
            'csrf_protection' => false,
        ]);

        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        $key = $form->getData();
        $em = $this->getDoctrine()->getManager();
        $em->persist($key);

        $routeOptions = [
            'id' => $key->getId(),

            '_format' => $request->get('_format'),
        ];

        $this->routeRedirectView('', $routeOptions, Response::HTTP_CREATED);

        $id=$lockkey->getLock();
        $ids=$lockkey->getKey();

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

        $lock=$em->getRepository('AppBundle:Lock')->findOneById($id);
        $name_lock=$lock->getLockName();

        $keys=$em->getRepository('AppBundle:Key')->findOneById($ids);
        $tag_key=$keys->getTag();


        $lock_key= $name_lock.':'.$tag_key;
        $predisClient->set($lock_key, 1);
        $em->flush();

        $id=$lockkey->getId();
        return $this->getLockkeyRepository()->findIdQuery($id)->getOneOrNullResult();

    }

    /**
     * Totally update 
     * @param Request $request
     * @param int     $id
     * @param int     $lock
     * @return View|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *     input="AppBundle\Form\Type\LockkeyType",
     *     output="AppBundle\Entity\Lockkey",
     *     statusCodes={
     *         204 = "Returned when an existing lock key relationship has been successful updated",
     *         400 = "Return when errors",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function putAvailablekeysAction(Request $request, $lock, $id)
    {

        $key = $this->getLockKeyRepository()->findOneBy(array('key'=>$id, 'lock'=>$lock));

        if ($key === null) {
            return new View("Doesnt exist", Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(LockkeyType::class, $key, [
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
        return $this->getLockkeyRepository()->findIdQuery($id)->getOneOrNullResult();
    }


    /**
     * Update
     * @param Request $request
     * @param int     $id
     * @param int     $lock
     * @return View|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *     input="AppBundle\Form\Type\LockkeyType",
     *     output="AppBundle\Entity\Lockkey",
     *     statusCodes={
     *         204 = "Returned when an existing lock key relationship has been successful updated",
     *         400 = "Return when errors",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function patchAvailablekeysAction(Request $request, $lock, $id)
    {
        /**
         * @var $key key
         */
        $key = $this->getLockKeyRepository()->findOneBy(array('key'=>$id, 'lock'=>$lock));
        if ($key === null) {
            return new View("Doesnt exist", Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm(LockkeyType::class, $key, [
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
        return $this->getLockkeyRepository()->findIdQuery($id)->getOneOrNullResult();
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
    public function deleteAvailablekeysAction($lock, $id)
    {
        $key = $this->getLockKeyRepository()->findOnlyLockKeyQuery($lock, $id)->getOneOrNullResult();
         if ($key=== null) {
            return new View("This id $id doesnt exist", Response::HTTP_NOT_FOUND); }
        // return new View("Deleted key $id for lock $lock");
        
        $em = $this->get('doctrine')->getManager();
        $locks=$em->getRepository('AppBundle:Lock')->findOneById($lock);
        $kkey=$em->getRepository('AppBundle:Key')->findOneById($id);
        $em->flush();

        $lockkey=new Lockkey();
        $lockkey->setLock($locks);
        $lockkey->setKey($kkey);

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

        $id=$lockkey->getLock();
        $ids=$lockkey->getKey();


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
     * @return lockkeyRepository
     */
    private function getLockKeyRepository()
    {
        return $this->get('crv.doctrine_entity_repository.lockkey');
    }
    private function getLockRepository()
    {
        return $this->get('crv.doctrine_entity_repository.lock');
    }

}
