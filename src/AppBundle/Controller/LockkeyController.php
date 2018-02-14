<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Lockkey;
use AppBundle\Entity\Repository\LockRepository;
use AppBundle\Entity\Repository\LockkeyRepository;
use AppBundle\Form\Type\LockkeyType;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class KeyController
 * @package AppBundle\Controller
 *
 * @RouteResource("Lock")
 */
class LockkeyController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Gets an individual key
     *
     * @param int $lock
     * @param int $id
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     */
    public function getAvailablekeysAction($lock, $id)
    {
        $key = $this->getLockKeyRepository()->findlockKeyQuery($lock,$id)->getOneOrNullResult();
        if ($key === null) {
            return new View("Dont exist key with id $id for lock $lock");
        }
        return $key;
    }

    /**
     * Gets a collection of keys for locks
     *
     * @return array
     *
     */
    public function cgetAvailablekeysAction($lock){

        return $this->getLockKeyRepository()->findLockQuery($lock)->getResult();
    }


    /**
     * @param Request $request
     * @return View|\Symfony\Component\Form\Form
     *
     */
    public function postAvailablekeysAction(Request $request, $lock)
    {
        $lockk=$this->getLockRepository()->createFindOneByIdQuery($lock)->getOneOrNullResult();
        if ($lockk === null) {
        return new View('Doesnt exist', Response::HTTP_NOT_FOUND);
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
        $em->flush();

        $routeOptions = [
            'id' => $key->getId(),

            '_format' => $request->get('_format'),
        ];

        $this->routeRedirectView('', $routeOptions, Response::HTTP_CREATED);
        $id=$lockkey->getId();
        return $this->getLockkeyRepository()->findIdQuery($id)->getOneOrNullResult();
    }

    /**
     * @param Request $request
     * @param int     $id
     * @param int     $lock
     * @return View|\Symfony\Component\Form\Form
     *
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
     * @param Request $request
     * @param int     $id
     * @param int     $lock
     * @return View|\Symfony\Component\Form\Form
     *
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
     * @param int $id
     * @param int $lock
     * @return View
     *
     */
    public function deleteAvailablekeysAction($lock, $id)
    {
        $key = $this->getLockKeyRepository()->deleteLockKeyQuery($lock, $id)->getResult();
        if ($key== 0) {
            return new View("This id $id doesnt exist"); }
        return new View("Deleted user $id");
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
