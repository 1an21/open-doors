<?php

namespace AppBundle\Controller;

use AppBundle\Entity\MasterKey;
use AppBundle\Entity\Repository\MasterKeyRepository;
use AppBundle\Form\Type\MasterKeyType;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
/**
 * Class MasterKeyController
 * @package AppBundle\Controller
 *
 * @RouteResource("MasterKey")
 */
class MasterKeyController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Gets an individual key
     *
     * @param int $id
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @ApiDoc(
     *     output="AppBundle\Entity\MasterKey",
     *     statusCodes={
     *         200 = "Returned when successful",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function getAction($id)
    {
        $key = $this->getMasterKeyRepository()->findKeyQuery($id)->getOneOrNullResult();
        if ($key === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }
        return $key;
    }

    /**
     * Gets a collection of keys
     *
     * @return array
     *
     * @ApiDoc(
     *     output="AppBundle\Entity\MasterKey",
     *     statusCodes={
     *         200 = "Returned when successful",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function cgetAction()
    {
        return $this->getMasterKeyRepository()->findAllKeyQuery()->getResult();
    }

    /**
     * Add a new key (require a role KEY_ADDER)
     * @param Request $request
     * @return View|\Symfony\Component\Form\Form
     * @Security("has_role('ROLE_KEY_ADDER')")
     * @ApiDoc(
     *     output="AppBundle\Entity\MasterKey",
     *     statusCodes={
     *         201 = "Returned when a new key has been successful created",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function postAction(Request $request)
    {

        $form = $this->createForm(MasterKeyType::class, null, [
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
        return new View("OK", Response::HTTP_OK);
    }

    /**
     * Totally update a key
     * @param Request $request
     * @param int     $id
     * @return View|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *     input="AppBundle\Form\Type\MasterKeyType",
     *     output="AppBundle\Entity\MasterKey",
     *     statusCodes={
     *         204 = "Returned when an existing key has been successful updated",
     *         400 = "Return when errors",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function putAction(Request $request, $id)
    {

        $key = $this->getMasterKeyRepository()->find($id);

        if ($key === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(MasterKeyType::class, $key, [
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
        return $this->getMasterKeyRepository()->findKeyQuery($id)->getOneOrNullResult();
    }


    /**
     * Update a key
     * @param Request $request
     * @param int     $id
     * @return View|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *     input="AppBundle\Form\Type\MasterKeyType",
     *     output="AppBundle\Entity\MasterKey",
     *     statusCodes={
     *         204 = "Returned when an existing key has been successful updated",
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
        $key = $this->getMasterKeyRepository()->find($id);
        if ($key === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm(MasterKeyType::class, $key, [
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
        $this->routeRedirectView('', $routeOptions, Response::HTTP_NO_CONTENT);
        $id=$key->getId();
        return $this->getMasterKeyRepository()->findKeyQuery($id)->getOneOrNullResult();
    }


    /**
     * Delete a key
     * @param int $id
     * @return View
     *
     * @ApiDoc(
     *     statusCodes={
     *         204 = "Returned when an existing key has been successful deleted",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function deleteAction($id)
    {
        $key = $this->getMasterKeyRepository()->deleteKeyQuery($id)->getResult();
        if ($key == 0) {
            return new View("This id $id doesnt exist");
        }
        return new View("Deleted user $id");
    }

    /**
     * @return keyRepository
     */
    private function getMasterKeyRepository()
    {
        return $this->get('crv.doctrine_entity_repository.Masterkey');
    }
}
