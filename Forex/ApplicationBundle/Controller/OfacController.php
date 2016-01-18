<?php

namespace Forex\ApplicationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Forex\ApplicationBundle\Entity\Ofac;
use Forex\ApplicationBundle\Form\Type\OfacType;
use Forex\ApplicationBundle\Form\Type\OfacFilterType;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Ofac controller.
 *
 * @Route("/ofac")
 */
class OfacController extends Controller
{

	/**
	 * Lists all Ofac entities.
	 *
	 * @Route("/", name="ofac")
	 * @Method("GET")
	 * @Template()
	 */
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		$form = $this->createForm(new OfacFilterType());
		if (!is_null($response = $this->saveFilter($form, 'ofac', 'ofac'))) {
			return $response;
		}
		$qb = $em->getRepository('ForexApplicationBundle:Ofac')->createQueryBuilder('o');
		$paginator = $this->filter($form, $qb, 'ofac');

		return array(
			'form' => $form->createView(),
			'paginator' => $paginator,
		);
	}


	/**
	 * Creates a new Ofac entity.
	 *
	 * @Route("/import", name="ofac_import")
	 */
	public function importAction()
	{

		$this->get('orders.service')->updateOfac();

		return $this->redirect($this->generateUrl('ofac'));
	}

	/**
	 * Finds and displays a Ofac entity.
	 *
	 * @Route("/{id}/show", name="ofac_show", requirements={"id"="\d+"})
	 * @Method("GET")
	 * @Template()
	 */
	public function showAction(Ofac $ofac)
	{
		$deleteForm = $this->createDeleteForm($ofac->getId(), 'ofac_delete');

		return array(
			'ofac' => $ofac,
			'delete_form' => $deleteForm->createView(),
		);
	}

	/**
	 * Displays a form to create a new Ofac entity.
	 *
	 * @Route("/new", name="ofac_new")
	 * @Method("GET")
	 * @Template()
	 */
	public function newAction()
	{
		$ofac = new Ofac();
		$form = $this->createForm(new OfacType(), $ofac);

		return array(
			'ofac' => $ofac,
			'form' => $form->createView(),
		);
	}

	/**
	 * Creates a new Ofac entity.
	 *
	 * @Route("/create", name="ofac_create")
	 * @Method("POST")
	 * @Template("ForexApplicationBundle:Ofac:new.html.twig")
	 */
	public function createAction(Request $request)
	{
		$ofac = new Ofac();
		$form = $this->createForm(new OfacType(), $ofac);
		if ($form->handleRequest($request)->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($ofac);
			$em->flush();

			return $this->redirect($this->generateUrl('ofac_show', array('id' => $ofac->getId())));
		}

		return array(
			'ofac' => $ofac,
			'form' => $form->createView(),
		);
	}

	/**
	 * Displays a form to edit an existing Ofac entity.
	 *
	 * @Route("/{id}/edit", name="ofac_edit", requirements={"id"="\d+"})
	 * @Method("GET")
	 * @Template()
	 */
	public function editAction(Ofac $ofac)
	{
		$editForm = $this->createForm(new OfacType(), $ofac, array(
			'action' => $this->generateUrl('ofac_update', array('id' => $ofac->getid())),
			'method' => 'POST',
		));
		$deleteForm = $this->createDeleteForm($ofac->getId(), 'ofac_delete');

		return array(
			'ofac' => $ofac,
			'form' => $editForm->createView(),
			'delete_form' => $deleteForm->createView(),
		);
	}

	/**
	 * Edits an existing Ofac entity.
	 *
	 * @Route("/{id}/update", name="ofac_update", requirements={"id"="\d+"})
	 * @Method("POST")
	 * @Template("ForexApplicationBundle:Ofac:edit.html.twig")
	 */
	public function updateAction(Ofac $ofac, Request $request)
	{
		$editForm = $this->createForm(new OfacType(), $ofac, array(
			'action' => $this->generateUrl('ofac_update', array('id' => $ofac->getid())),
			'method' => 'POST',
		));
		if ($editForm->handleRequest($request)->isValid()) {
			$this->getDoctrine()->getManager()->flush();

			return $this->redirect($this->generateUrl('ofac_edit', array('id' => $ofac->getId())));
		}
		$deleteForm = $this->createDeleteForm($ofac->getId(), 'ofac_delete');

		return array(
			'ofac' => $ofac,
			'form' => $editForm->createView(),
			'delete_form' => $deleteForm->createView(),
		);
	}


	/**
	 * Save order.
	 *
	 * @Route("/order/{field}/{type}", name="ofac_sort")
	 */
	public function sortAction($field, $type)
	{
		$this->setOrder('ofac', $field, $type);

		return $this->redirect($this->generateUrl('ofac'));
	}

	/**
	 * @param string $name session name
	 * @param string $field field name
	 * @param string $type sort type ("ASC"/"DESC")
	 */
	protected function setOrder($name, $field, $type = 'ASC')
	{
		$this->getRequest()->getSession()->set('sort.' . $name, array('field' => $field, 'type' => $type));
	}

	/**
	 * @param  string $name
	 * @return array
	 */
	protected function getOrder($name)
	{
		$session = $this->getRequest()->getSession();

		return $session->has('sort.' . $name) ? $session->get('sort.' . $name) : null;
	}

	/**
	 * @param QueryBuilder $qb
	 * @param string $name
	 */
	protected function addQueryBuilderSort(QueryBuilder $qb, $name)
	{
		$alias = current($qb->getDQLPart('from'))->getAlias();
		if (is_array($order = $this->getOrder($name))) {
			$qb->orderBy($alias . '.' . $order['field'], $order['type']);
		}
	}

	/**
	 * Save filters
	 *
	 * @param  FormInterface $form
	 * @param  string $name route/entity name
	 * @param  string $route route name, if different from entity name
	 * @param  array $params possible route parameters
	 * @return Response
	 */
	protected function saveFilter(FormInterface $form, $name, $route = null, array $params = null)
	{
		$request = $this->getRequest();
		$url = $this->generateUrl($route ?: $name, is_null($params) ? array() : $params);
		if ($request->query->has('submit-filter') && $form->handleRequest($request)->isValid()) {
			$request->getSession()->set('filter.' . $name, $request->query->get($form->getName()));

			return $this->redirect($url);
		} elseif ($request->query->has('reset-filter')) {
			$request->getSession()->set('filter.' . $name, null);

			return $this->redirect($url);
		}
	}

	/**
	 * Filter form
	 *
	 * @param  FormInterface $form
	 * @param  QueryBuilder $qb
	 * @param  string $name
	 * @return \Knp\Component\Pager\Pagination\PaginationInterface
	 */
	protected function filter(FormInterface $form, QueryBuilder $qb, $name)
	{
		if (!is_null($values = $this->getFilter($name))) {
			if ($form->submit($values)->isValid()) {
				$this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($form, $qb);
			}
		}

		// possible sorting
		$this->addQueryBuilderSort($qb, $name);
		return $this->get('knp_paginator')->paginate($qb->getQuery(), $this->getRequest()->query->get('page', 1), 20);
	}

	/**
	 * Get filters from session
	 *
	 * @param  string $name
	 * @return array
	 */
	protected function getFilter($name)
	{
		return $this->getRequest()->getSession()->get('filter.' . $name);
	}

	/**
	 * Deletes a Ofac entity.
	 *
	 * @Route("/{id}/delete", name="ofac_delete", requirements={"id"="\d+"})
	 * @Method("DELETE")
	 */
	public function deleteAction(Ofac $ofac, Request $request)
	{
		$form = $this->createDeleteForm($ofac->getId(), 'ofac_delete');
		if ($form->handleRequest($request)->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->remove($ofac);
			$em->flush();
		}

		return $this->redirect($this->generateUrl('ofac'));
	}

	/**
	 * Create Delete form
	 *
	 * @param integer $id
	 * @param string $route
	 * @return \Symfony\Component\Form\Form
	 */
	protected function createDeleteForm($id, $route)
	{
		return $this->createFormBuilder(null, array('attr' => array('id' => 'delete')))
			->setAction($this->generateUrl($route, array('id' => $id)))
			->setMethod('DELETE')
			->getForm();
	}

}
