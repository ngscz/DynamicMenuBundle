<?php

namespace SymfonyContrib\Bundle\DynamicMenuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use SymfonyContrib\Bundle\DynamicMenuBundle\Entity\MenuItem;
use Gedmo\Tree\TreeListener;

class MenuAdminController extends Controller
{
    /**
     * Lists menu items for admin management view.
     *
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository('DynamicMenuBundle:MenuItem');
        $roots = $repo->getRootNodes('label');

        return $this->render('DynamicMenuBundle:Admin/Menu:list.html.twig', [
            'menus' => $roots,
        ]);
    }

    /**
     * Add/edit form callback view.
     *
     * @param Request $request
     * @param string $menu
     * @return RedirectResponse|Response
     */
    public function formAction(Request $request, $menu = null)
    {
        $em = $this->getDoctrine()->getManager();

        if ($menu) {
            $dql = "SELECT i
                    FROM DynamicMenuBundle:MenuItem i
                    WHERE i.name = :name
                        AND i.level = 0";
            $item = $em->createQuery($dql)
                ->setParameter('name', $menu)
                ->getSingleResult();
        } else {
            $item = new MenuItem();
        }

        $formId = 'menu_form';
        $form = $this->createForm($formId, $item);

        $form->handleRequest($request);

        if ($form->isValid()) {
            // Save data.
            $em->persist($item);
            $em->flush($item);

            // Menu roots are part of themselves.
            $item->setMenu($item);
            $em->flush($item);

            // Set a success message.
            $msg = ($menu ? 'Updated ' : 'Created ') . $item->getLabel();
            $this->get('session')->getFlashBag()->add('success', $msg);

            // Redirect to menu item list
            return $this->redirect($this->generateUrl('dynamic_menu_admin_menu_list'));
        }

        // Render the wealth profile add/edit form.
        return $this->render('DynamicMenuBundle:Admin/Menu:form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Delete a menu item with confirmation.
     *
     * @param string $menu ID of the menu item.
     * @return Response
     */
    public function deleteAction($menu)
    {
        $item = $this->getDoctrine()
            ->getRepository('DynamicMenuBundle:MenuItem')
            ->find($menu);

        $options = [
            'message' => 'Are you sure you want to <strong>DELETE "' . $item->getLabel() . '"</strong>?',
            'warning' => 'This can not be undone!',
            'confirmButtonText' => 'Delete',
            'cancelLinkText' => 'Cancel',
            'confirmAction' => [$this, 'menuDelete'],
            'confirmActionArgs' => [
                'item' => $item,
            ],
            'cancelUrl' => $this->generateUrl('dynamic_menu_admin_menu_list'),
        ];

        return $this->forward('ConfirmBundle:Confirm:confirm', ['options' => $options]);
    }

    /**
     * Delete confirmation callback.
     *
     * @param array $args
     * @return RedirectResponse
     */
    public function menuDelete(array $args)
    {
        $item = $args['item'];
        $em = $this->getDoctrine()->getManager();
        $em->remove($item);
        $em->flush($item);

        $msg = 'Deleted ' . $item->getLabel();
        $this->get('session')->getFlashBag()->add('success', $msg);

        return $this->redirect($this->generateUrl('dynamic_menu_admin_menu_list'));
    }
}
