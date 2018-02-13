<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Lockkey;
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
            return new Response(sprintf('Dont exist key with id %s for lock %s', $id , $lock));
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

        $rkey=$this->getLockKeyRepository()->findOneBy(array('lock'=>$lock));

            if ($rkey === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm(LockkeyType::class, $rkey, [
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

        return $this->routeRedirectView('', $routeOptions, Response::HTTP_CREATED);
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
            return new View(null, Response::HTTP_NOT_FOUND);
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

        return $this->routeRedirectView('', $routeOptions, Response::HTTP_OK);
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
            return new View(null, Response::HTTP_NOT_FOUND);
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
        return $this->routeRedirectView('', $routeOptions, Response::HTTP_NO_CONTENT);
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
            return new Response(sprintf('This id %s doesnt exist', $id));
        }
        return new Response(sprintf('Deleted relationship #%s', $id));
    }

    /**
     * @return lockkeyRepository
     */
    private function getLockKeyRepository()
    {
        return $this->get('crv.doctrine_entity_repository.lockkey');
    }
}
