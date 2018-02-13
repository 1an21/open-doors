<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Lock;
use AppBundle\Entity\Repository\LockRepository;
use AppBundle\Form\Type\LockType;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     */
    public function cgetAction()
    {
        return $this->getLockRepository()->createFindAllQuery()->getResult();
    }

    /**
     * @param Request $request
     * @return View|\Symfony\Component\Form\Form
     *
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

        /**
         * @var $Lock Lock
         */
        $lock = $form->getData();

        $em = $this->getDoctrine()->getManager();
        $em->persist($lock);
        $em->flush();

        $routeOptions = [
            'id' => $lock->getId(),
            '_format' => $request->get('_format'),
        ];

        return $this->routeRedirectView('', $routeOptions, Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param int     $id
     * @return View|\Symfony\Component\Form\Form
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

        return $this->routeRedirectView('', $routeOptions, Response::HTTP_NO_CONTENT);
    }


    /**
     * @param Request $request
     * @param int     $id
     * @return View|\Symfony\Component\Form\Form
     *
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

        return $this->routeRedirectView('', $routeOptions, Response::HTTP_NO_CONTENT);
    }


    /**
     * @param int $id
     * @return View
     *
     */
    public function deleteAction($id)
    {
        $lock = $this->getLockRepository()->deleteQuery($id)->getResult();
        if ($lock == 0) {
            return new Response(sprintf('This id %s doesnt exist', $id));
        }

        return new Response(sprintf('Deleted lock #%s', $id));

        return new Response(sprintf('Deleted user #%s', $id));

    }

    /**
     * @return LockRepository
     */
    private function getLockRepository()
    {
        return $this->get('crv.doctrine_entity_repository.lock');
    }
}
