<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Repository\RkeyRepository;
use AppBundle\Form\Type\RkeyType;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RkeyController
 * @package AppBundle\Controller
 *
 * @RouteResource("Key")
 */
class RkeyController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Gets an individual Rkey
     *
     * @param int $id
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     */
    public function getAction($id)
    {
        $key = $this->getRkeyRepository()->createFindOneByIdQuery($id)->getOneOrNullResult();
        if ($key === null) {
            return new Response(sprintf('Dont exist key with id %s', $id));
        }
        return $key;
    }

    /**
     * Gets a collection of Rkeys
     *
     * @return array
     *
     */
    public function cgetAction()
    {
        return $this->getRkeyRepository()->createFindAllQuery()->getResult();
    }

    /**
     * @param Request $request
     * @return View|\Symfony\Component\Form\Form
     *
     */
    public function postAction(Request $request)
    {
        $form = $this->createForm(RkeyType::class, null, [
            'csrf_protection' => false,
        ]);

        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        $rkey = $form->getData();
        $em = $this->getDoctrine()->getManager();
        $em->persist($rkey);
        $em->flush();

        $routeOptions = [
            'id' => $rkey->getId(),

            '_format' => $request->get('_format'),
        ];

        return $this->routeRedirectView('', $routeOptions, Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param int     $id
     * @return View|\Symfony\Component\Form\Form
     *
     */
    public function putAction(Request $request, $id)
    {

        $rkey = $this->getRkeyRepository()->find($id);

        if ($rkey === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(RkeyType::class, $rkey, [
            'csrf_protection' => false,]);

        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $routeOptions = [
            'id' => $rkey->getId(),
            '_format' => $request->get('_format'),
        ];

        return $this->routeRedirectView('', $routeOptions, Response::HTTP_OK);
    }


    /**
     * @param Request $request
     * @param int     $id
     * @return View|\Symfony\Component\Form\Form
     *
     */
    public function patchAction(Request $request, $id)
    {
        $rkey = $this->getRkeyRepository()->find($id);

        if ($rkey === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(RkeyType::class, $rkey, [
            'csrf_protection' => false,
        ]);

        $form->submit($request->request->all(), false);

        if (!$form->isValid()) {
            return $form;
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $routeOptions = [
            'id' => $rkey->getId(),
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
        $key = $this->getRkeyRepository()->deleteQuery($id)->getResult();
        if ($key == 0) {
            return new Response(sprintf('This id %s doesnt exist', $id));
        }
        return new Response(sprintf('Deleted user #%s', $id));
    }

    /**
     * @return RkeyRepository
     */
    private function getRkeyRepository()
    {
        return $this->get('crv.doctrine_entity_repository.rkey');
    }
}
