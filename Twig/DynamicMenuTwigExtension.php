<?php
/**
 *
 */

namespace SymfonyContrib\Bundle\DynamicMenuBundle\Twig;

use Knp\Menu\ItemInterface;
use Twig_Environment;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Knp\Menu\Twig\Helper as KnpHelper;

class DynamicMenuTwigExtension extends \Twig_Extension
{
    /**
     * @var Registry
     */
    public $doctrine;

    /**
     * @var KnpHelper
     */
    public $knpHelper;

    /**
     * @var Twig_Environment
     */
    protected $environment;

    public function __construct(Registry $doctrine, KnpHelper $knpHelper)
    {
        $this->doctrine = $doctrine;
        $this->knpHelper = $knpHelper;
    }

    public function initRuntime(Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('dynamic_menu_render', [$this, 'render'], ['is_safe' => ['html']]),
        ];
    }

    public function render($menu, array $options = [], $renderer = null)
    {
        if (!$menu instanceof ItemInterface && is_string($menu)) {
            $menu = $this->doctrine
                ->getRepository('DynamicMenuBundle:MenuItem')
                ->find($menu);
        } else {
            throw new \Exception('Invalid dynamic menu name.');
        }

        return $this->knpHelper->render($menu, $options, $renderer);
    }

    public function getName()
    {
        return 'dynamic_menu_extension';
    }
}
