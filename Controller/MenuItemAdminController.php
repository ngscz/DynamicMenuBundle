<?php

namespace SymfonyContrib\Bundle\DynamicMenuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use SymfonyContrib\Bundle\DynamicMenuBundle\Entity\MenuItem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Gedmo\Tree\TreeListener;

class MenuItemAdminController extends Controller
{
    public function indexAction()
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $repo = $doctrine->getRepository('DynamicMenuBundle:MenuItem');

        /*
        // create some dummy data
        $menu = new MenuItem();
        $menu->setName('test');

        $home = new MenuItem();
        $home->setName('home')->setUri('/')->setParent($menu);

        $about = new MenuItem();
        $about->setName('about')->setUri('/about')->setParent($home);

        $em->persist($menu);
        $em->persist($home);
        $em->persist($about);
        $em->flush();
        */

        /*
        // test reordering
        $home = $repo->findOneBy(['name' => 'home']);
        $home->setLeft(2);
        $about = $repo->findOneBy(['name' => 'about']);
        $about->setLeft(4);
        $contact = $repo->findOneBy(['name' => 'contact']);
        $contact->setLeft(5);
        $shop = $repo->findOneBy(['name' => 'shop']);
        $shop->setLeft(8);
        //$em->persist($home);
        $em->flush();
        */

        $repo->recover();
        $em->flush();

        // get the data back out
        $root = $repo->find('test');

        return $this->render('DynamicMenuBundle:Admin/Item:index.html.twig', [
            'root' => $root
        ]);
    }

    /**
     * Lists menu items for admin management view.
     *
     * @param Request $request
     * @param string $menu
     * @return Response
     */
    public function listAction(Request $request, $menu = 'test')
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        foreach ($em->getEventManager()->getListeners() as $listeners) {
            foreach ($listeners as $listener) {
                if ($listener instanceof TreeListener) {
                    $em->getEventManager()->removeEventListener(['onFlush'], $listener);
                    break 2;
                }
            }
        }

        $repo  = $doctrine->getRepository('DynamicMenuBundle:MenuItem');
        $root  = $repo->findOneBy(['name' => $menu]);
        $items = $repo->getNodesHierarchyQuery($root)->getResult();
        $uri   = $request->getRequestUri();
        $form  = $this->createForm('menu_reorder_form', ['items' => $items], ['cancel_url' => $uri]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();

            return $this->redirect($uri);
        }

        return $this->render('DynamicMenuBundle:Admin/Item:list.html.twig', [
            'menu' => $root,
            'items' => $items,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Add/edit form callback view.
     *
     * @param Request $request
     * @param string $menu
     * @param null|string $name
     * @return RedirectResponse|Response
     */
    public function formAction(Request $request, $menu, $name = null)
    {
        $em = $this->getDoctrine()->getManager();

        if ($name) {
            $dql = "SELECT i
                    FROM DynamicMenuBundle:MenuItem i
                    WHERE i.name = :name";
            $item = $em->createQuery($dql)
                ->setParameter('name', $name)
                ->getSingleResult();
        } else {
            $item = new MenuItem();
            $dql = "SELECT i
                    FROM DynamicMenuBundle:MenuItem i
                    WHERE i.name = :name";
            $root = $em->createQuery($dql)
                ->setParameter('name', $menu)
                ->getSingleResult();
            $item->setMenu($root);
        }

        $formId = 'menu_item_form';
        $form = $this->createForm($formId, $item, [
            'cancel_url' => $this->generateUrl('dynamic_menu_admin_menu_item_list', ['menu' => $menu]),
        ]);

        // Default parent to menu root when creating.
        if (!$name) {
            $form->get('parent')->setData($root);
        }

        $form->handleRequest($request);

        if ($form->isValid()) {
            // Menu root is in sync when moving item to another menu.
            $changedMenu = false;
            if ($item->getParent()->getMenu()->getName() !== $menu) {
                $item->setMenu($item->getParent()->getMenu());
                $changedMenu = true;
                $menu = $item->getParent()->getMenu()->getName();
            }

            // Save data.
            $em->persist($item);
            $em->flush($item);

            // Item is in new menu and old menu too when moved.
            // @todo: Fix this bug and remove this.
            if ($changedMenu) {
                $em->getRepository('DynamicMenuBundle:MenuItem')->recover();
                $em->flush();
            }

            // Set a success message.
            $msg = ($name ? 'Updated ' : 'Created ') . $item->getName();
            $this->get('session')->getFlashBag()->add('success', $msg);

            // Redirect to menu item list
            return $this->redirect($this->generateUrl('dynamic_menu_admin_menu_item_list', ['menu' => $menu]));
        }

        // Render the wealth profile add/edit form.
        return $this->render('DynamicMenuBundle:Admin/Item:form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete a menu item with confirmation.
     *
     * @param string $menu ID of the menu.
     * @param string $name ID of the menu item.
     * @return Response
     */
    public function deleteAction($menu, $name)
    {
        $item = $this->getDoctrine()
            ->getRepository('DynamicMenuBundle:MenuItem')
            ->find($name);

        $options = [
            'message' => 'Are you sure you want to <strong>DELETE "' . $item->getLabel() . '"</strong>?',
            'warning' => 'This can not be undone!',
            'confirmButtonText' => 'Delete',
            'cancelLinkText' => 'Cancel',
            'confirmAction' => [$this, 'menuItemDelete'],
            'confirmActionArgs' => [
                'menu' => $menu,
                'item' => $item,
            ],
            'cancelUrl' => $this->generateUrl('dynamic_menu_admin_menu_item_list', ['menu' => $menu]),
        ];

        return $this->forward('ConfirmBundle:Confirm:confirm', ['options' => $options]);
    }

    /**
     * Delete confirmation callback.
     *
     * @param array $args
     * @return RedirectResponse
     */
    public function menuItemDelete(array $args)
    {
        $item = $args['item'];
        $em = $this->getDoctrine()->getManager();
        $em->remove($item);
        $em->flush($item);

        $msg = 'Deleted ' . $item->getName();
        $this->get('session')->getFlashBag()->add('success', $msg);

        return $this->redirect($this->generateUrl('dynamic_menu_admin_menu_item_list', ['menu' => $args['menu']]));
    }
}
