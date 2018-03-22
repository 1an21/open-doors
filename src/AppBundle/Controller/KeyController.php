<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Key;
use AppBundle\Entity\Repository\KeyRepository;
use AppBundle\Form\Type\KeyType;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class KeyController
 * @package AppBundle\Controller
 *
 * @RouteResource("Key")
 */
class KeyController extends FOSRestController implements ClassResourceInterface
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
     *     output="AppBundle\Entity\Key",
     *     statusCodes={
     *         200 = "Returned when successful",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function getAction($id)
    {
        $key = $this->getKeyRepository()->findKeyQuery($id)->getOneOrNullResult();
        if ($key === null) {
            return new Response(sprintf('Dont exist key with id %s', $id),Response::HTTP_NOT_FOUND);
        }
        return $key;
    }

    /**
     * Gets a collection of keys (for use filter(tag and descr): /keys?filter= )
     *
     * @return array
     *
     * @ApiDoc(
     *     output="AppBundle\Entity\Key",
     *     statusCodes={
     *         200 = "Returned when successful",
     *         404 = "Return when not found"
     *     }
     * )
     */

    public function cgetAction(Request $request)
    {
//        return $this->getKeyRepository()->findAllKeyQuery()->getResult();
        $queryBuilder = $this->getKeyRepository()->searchQuery();
        if ($request->query->getAlnum('filter')) {
            $queryBuilder->where('k.tag LIKE :tag')
                ->orwhere('k.description LIKE :tag')
                ->setParameter('tag', '%' . $request->query->getAlnum('filter') . '%');
        }
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Add a new key (require a role KEY_ADDER)
     * @param Request $request
     * @return View|\Symfony\Component\Form\Form
     * @Security("has_role('ROLE_KEY_ADDER')")
     * @ApiDoc(
     *     output="AppBundle\Entity\Key",
     *     statusCodes={
     *         201 = "Returned when a new key has been successful created",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function postAction(Request $request)
    {

        $form = $this->createForm(KeyType::class, null, [
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
        $id=$key->getId();
        return $this->getKeyRepository()->findKeyQuery($id)->getOneOrNullResult();
    }

    /**
     * Totally update a key
     * @param Request $request
     * @param int     $id
     * @return View|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *     input="AppBundle\Form\Type\KeyType",
     *     output="AppBundle\Entity\Key",
     *     statusCodes={
     *         204 = "Returned when an existing key has been successful updated",
     *         400 = "Return when errors",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function putAction(Request $request, $id)
    {

        $key = $this->getKeyRepository()->find($id);

        if ($key === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(KeyType::class, $key, [
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
        return $this->getKeyRepository()->findKeyQuery($id)->getOneOrNullResult();
    }


    /**
     * Update a key
     * @param Request $request
     * @param int     $id
     * @return View|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *     input="AppBundle\Form\Type\KeyType",
     *     output="AppBundle\Entity\Key",
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
        $key = $this->getKeyRepository()->find($id);
        if ($key === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm(KeyType::class, $key, [
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
        return $this->getKeyRepository()->findKeyQuery($id)->getOneOrNullResult();
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
        $key = $this->getKeyRepository()->deleteKeyQuery($id)->getResult();
        if ($key == 0) {
            return new View("This id $id doesnt exist", Response::HTTP_NOT_FOUND);
        }
        return new View("Deleted user $id");
    }

    /**
     * @return keyRepository
     */
    private function getKeyRepository()
    {
        return $this->get('crv.doctrine_entity_repository.key');
    }
}
