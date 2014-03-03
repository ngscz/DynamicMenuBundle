<?php
/**
 * Custom menu provider to pull menus out of Doctrine.
 */

namespace SymfonyContrib\Bundle\DynamicMenuBundle\Provider;

use Knp\Menu\Provider\MenuProviderInterface;
use Doctrine\Bundle\DoctrineBundle\Registry;

class DynamicMenuProvider implements MenuProviderInterface
{
    /**
     * @var Registry
     */
    public $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function get($name, array $options = array())
    {
        $menu = $this->doctrine
            ->getRepository('DynamicMenuBundle:MenuItem')
            ->find($name);

        if (!$menu) {
            throw new \InvalidArgumentException(sprintf('The menu "%s" is not defined.', $name));
        }

        return $menu;
    }

    public function has($name, array $options = [])
    {
        return (bool)$this->doctrine
            ->getRepository('DynamicMenuBundle:MenuItem')
            ->find($name);
    }
}
