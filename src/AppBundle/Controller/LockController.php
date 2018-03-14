<?php

namespace AppBundle\Controller;

use AppBundle\Controller\BrokerController;
use AppBundle\Entity\Lock;
use AppBundle\Entity\Repository\LockRepository;
use AppBundle\Form\Type\LockType;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
/**
 * Class LockController
 * @package AppBundle\Controller
 *
 * @RouteResource("Lock")
 */
class LockController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Gets an individual Lock
     *
     * @param int $id
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @ApiDoc(
     *     output="AppBundle\Entity\Lock",
     *     statusCodes={
     *         200 = "Returned when successful",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function getAction($id)
    {
        $lock = $this->getLockRepository()->createFindOneByIdQuery($id)->getOneOrNullResult();
        if ($lock === null) {
            return new Response(sprintf('Dont exist lock with id %s', $id));
        }
        return $lock;
    }

    /**
     * Gets a collection of Locks
     *
     * @return array
     *
     * @ApiDoc(
     *     output="AppBundle\Entity\Lock",
     *     statusCodes={
     *         200 = "Returned when successful",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function cgetAction()
    {
        return $this->getLockRepository()->createFindAllQuery()->getResult();
    }

    /**
     * Add a new lock
     * @param Request $request
     * @return View|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *     output="AppBundle\Entity\Lock",
     *     statusCodes={
     *         201 = "Returned when a new lock has been successful created",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function postAction(Request $request)
    {

        $form = $this->createForm(LockType::class, null, [
            'csrf_protection' => false,
        ]);

        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        $lock = $form->getData();

        $em = $this->getDoctrine()->getManager();
        $em->persist($lock);
        $em->flush();

        $routeOptions = [
            'id' => $lock->getId(),
            '_format' => $request->get('_format'),
        ];

        $this->routeRedirectView('', $routeOptions, Response::HTTP_CREATED);
        $id=$lock->getId();
        return $this->getLockRepository()->createFindOneByIdQuery($id)->getOneOrNullResult();



    }

    /**
     * @Route("/locks/try")
     * @Method ({"POST"})
     * @ApiDoc(
     *     output="AppBundle\Entity\Lock",
     *     statusCodes={
     *         201 = "Returned when a new lock has been successful sent to broker",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function postTryAction(Request $request){
        
        $form = $this->createForm(LockType::class, null, [
            'csrf_protection' => false,
        ]);

        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $form;
        }
        $data=$form->getData();
        $name=$request->request->get('lock_name');
        $pass=$request->request->get('lock_pass');
        
        $lock= new Lock();
        $lock->setLockName($name);
        $l_name=$lock->getLockName();

        $lock->setLockPass(base64_encode($pass));
        $l_pass=$lock->getLockPass();
        
        $mqtt= new BrokerController();
        $broker_ip=$this->getParameter('broker_ip');
        $broker_port=$this->getParameter('broker_port');
        $broker_name=$this->getParameter('broker_name');
        $broker_pass=$this->getParameter('broker_pass');
        $broker_client_name=$this->getParameter('broker_client_name');
        $topic_name=$this->getParameter('topic_name');
        $mqtt->pushMqtt($l_name, $l_pass, $broker_ip, $broker_port, $broker_name,$broker_pass, $broker_client_name, $topic_name);

        
        return $data;
    }

    /**
     * @Route("/locks/config")
     * @Method ({"GET"})
     * @ApiDoc(
     *     output="AppBundle\Entity\Lock",
     *     statusCodes={
     *         204 = "Returned when a config successfully created",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function getConfigAction($lock){

        $em = $this->get('doctrine')->getManager();
        $lock=$em->getRepository('AppBundle:Lock')->findOneById($lock);
        $name_lock=$lock->getLockName();
        $pass_lock=$lock->getLockPass();

        $broker_ip=$this->getParameter('broker_ip');
        $broker_port=$this->getParameter('broker_port');

        $template="mqtt_template.h";
        $newfile="mqtt.h";
        $data = file_get_contents($template);
        $data = str_replace('lock',"$name_lock",$data);
        $data = str_replace('toor',"$pass_lock",$data);
        $data = str_replace('Somelock',"$name_lock",$data);
        $data = str_replace('163.172.90.25',"$broker_ip",$data);
        $data = str_replace('9002',"$broker_port",$data);
        file_put_contents($newfile,$data);
        header ("Content-Type: application/octet-stream");
        header ("Accept-Ranges: bytes");
        header ("Content-Length: ".filesize($newfile));
        header ("Content-Disposition: attachment; filename=".$newfile);  
        return $data;
    }

    
    /**
     * Totally update lock
     * @param Request $request
     * @param int     $id
     * @return View|\Symfony\Component\Form\Form
     * @ApiDoc(
     *     input="AppBundle\Form\Type\LockType",
     *     output="AppBundle\Entity\Lock",
     *     statusCodes={
     *         204 = "Returned when an existing lock has been successful updated",
     *         400 = "Return when errors",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function putAction(Request $request, $id)
    {
        /**
         * @var $lock lock
         */
        $lock = $this->getLockRepository()->find($id);

        if ($lock === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(LockType::class, $lock, [
            'csrf_protection' => false,
        ]);

        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $routeOptions = [
            'id' => $lock->getId(),
            '_format' => $request->get('_format'),
        ];

        $this->routeRedirectView('', $routeOptions, Response::HTTP_NO_CONTENT);
        $id=$lock->getId();
        return $this->getLockRepository()->createFindOneByIdQuery($id)->getOneOrNullResult();
    }


    /**
     * Update lock
     * @param Request $request
     * @param int     $id
     * @return View|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *     input="AppBundle\Form\Type\LockType",
     *     output="AppBundle\Entity\Lock",
     *     statusCodes={
     *         204 = "Returned when an existing lock has been successful updated",
     *         400 = "Return when errors",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function patchAction(Request $request, $id)
    {
        /**
         * @var $lock Lock
         */
        $lock = $this->getLockRepository()->find($id);

        if ($lock === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(LockType::class, $lock, [
            'csrf_protection' => false,
        ]);

        $form->submit($request->request->all(), false);

        if (!$form->isValid()) {
            return $form;
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $routeOptions = [
            'id' => $lock->getId(),
            '_format' => $request->get('_format'),
        ];

        $this->routeRedirectView('', $routeOptions, Response::HTTP_NO_CONTENT);
        $id=$lock->getId();
        return $this->getLockRepository()->createFindOneByIdQuery($id)->getOneOrNullResult();
    }


    /**
     * Delete a lock
     * @param int $id
     * @return View
     *
     * @ApiDoc(
     *     statusCodes={
     *         204 = "Returned when an existing lock has been successful deleted",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function deleteAction($id)
    {
        $lock = $this->getLockRepository()->deleteQuery($id)->getResult();
        if ($lock == 0) {
            return new View("This id  $id doesnt exist");
        }
        return new View("Deleted lock $id");
    }

    /**
     * @return LockRepository
     */
    private function getLockRepository()
    {
        return $this->get('crv.doctrine_entity_repository.lock');
    }

}
