<?php
/**
 * Object representing a single item in a menu.
 */

namespace SymfonyContrib\Bundle\DynamicMenuBundle\Entity;

use SymfonyContrib\Bundle\DynamicMenuBundle\Model\NestedSetNodeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Knp\Menu\ItemInterface;
use Knp\Menu\MenuItem as KnpMenuItem;

class MenuItem extends KnpMenuItem implements NestedSetNodeInterface
{
    /**
     * Machine name to refer to menu item. Unique.
     *
     * @var string
     */
    protected $name;

    /**
     * Link text to be shown to the user.
     *
     * @var string
     */
    protected $label;

    /**
     * Static URI for the link.
     *
     * @var string
     */
    protected $uri;

    /**
     * Name of a Symfony route to get URI from.
     *
     * @var string
     */
    protected $route;

    /**
     * Arguments to pass to Symfony route.
     *
     * @var array
     */
    protected $routeParameters;

    /**
     * Options to pass to the menu builder.
     *
     * @var array
     */
    protected $config;

    /**
     * Tree attribute.
     *
     * @var int
     */
    protected $left;

    /**
     * Tree attribute.
     *
     * @var int
     */
    protected $right;

    /**
     * Tree attribute.
     *
     * @var ItemInterface
     */
    protected $menu;

    /**
     * Tree attribute.
     *
     * @var int
     */
    protected $level;

    /**
     * Status of menu item.
     *
     * @var bool
     */
    protected $enabled = true;

    /**
     * Is menu item shown to user?
     *
     * @var bool
     */
    protected $display = true;

    /**
     * Are children expanded in tree with parent?
     *
     * @var bool
     */
    protected $displayChildren = true;

    /**
     * Date when content was created.
     *
     * @var \DateTime
     */
    protected $created;

    /**
     * Date when content was updated.
     *
     * @var \DateTime
     */
    protected $updated;

    /**
     * Self reference to parent menu item.
     *
     * @var MenuItem|null
     */
    protected $parent;

    /**
     * Children of this menu item.
     *
     * @var ArrayCollection
     */
    protected $children;


    public function __construct()
    {
        $this->created  = new \DateTime();
        $this->children = new ArrayCollection();
    }

    /**
     * Doctrine lifecycle callback.
     *
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args = null)
    {
        if (!$args->hasChangedField('updated')) {
            $this->updated = new \DateTime();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function setChildren(array $children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * {@inheritDoc}
     */
    public function setChildrenAttributes(array $childrenAttributes)
    {
        $this->childrenAttributes = $childrenAttributes;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getChildrenAttributes()
    {
        return $this->childrenAttributes;
    }

    /**
     * {@inheritDoc}
     */
    public function getChildrenAttribute($name, $default = null)
    {
        if (isset($this->childrenAttributes[$name])) {
            return $this->childrenAttributes[$name];
        }

        return $default;
    }

    /**
     * {@inheritDoc}
     */
    public function setChildrenAttribute($name, $value)
    {
        $this->childrenAttributes[$name] = $value;

        return $this;
    }

    /**
     * @param array $config
     *
     * @return ItemInterface
     */
    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param \DateTime $created
     * @return MenuItem
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @see isDisplayed()
     * @return bool
     */
    public function isDisplay()
    {
        return $this->isDisplayed();
    }

    /**
     * {@inheritDoc}
     */
    public function isDisplayed()
    {
        return $this->display;
    }

    /**
     * {@inheritDoc}
     */
    public function setDisplay($bool)
    {
        $this->display = (bool)$bool;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setDisplayChildren($displayChildren)
    {
        $this->displayChildren = $displayChildren;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getDisplayChildren()
    {
        return $this->displayChildren;
    }

    /**
     * {@inheritDoc}
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param string $id
     *
     * @return ItemInterface
     */
    public function setId($id)
    {
        $this->setName($id);

        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return $this->label ?: $this->name;
    }

    /**
     * Returns the label prepended by level to show hierarchy.
     *
     * @param string $levelCharacter
     *
     * @return string
     */
    public function getHierarchyLabel($levelCharacter = '--')
    {
        return $this->level ? str_repeat($levelCharacter, $this->level) . ' ' . $this->getLabel() : '<' . $this->getLabel() . '>';
    }

    /**
     * {@inheritDoc}
     */
    public function setLabelAttributes(array $labelAttributes)
    {
        $this->labelAttributes = $labelAttributes;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getLabelAttributes()
    {
        return $this->labelAttributes;
    }

    /**
     * {@inheritDoc}
     */
    public function getLabelAttribute($name, $default = null)
    {
        if (isset($this->labelAttributes[$name])) {
            return $this->labelAttributes[$name];
        }

        return $default;
    }

    /**
     * {@inheritDoc}
     */
    public function setLabelAttribute($name, $value)
    {
        $this->labelAttributes[$name] = $value;

        return $this;
    }

    /**
     * @param int $left
     */
    public function setLeft($left)
    {
        $this->left = $left;
    }

    /**
     * @return int
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * @param int $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * {@inheritDoc}
     */
    public function setLinkAttributes(array $linkAttributes)
    {
        $this->linkAttributes = $linkAttributes;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getLinkAttributes()
    {
        return $this->linkAttributes;
    }

    /**
     * {@inheritDoc}
     */
    public function getLinkAttribute($name, $default = null)
    {
        if (isset($this->linkAttributes[$name])) {
            return $this->linkAttributes[$name];
        }

        return $default;
    }

    /**
     * {@inheritDoc}
     */
    public function setLinkAttribute($name, $value)
    {
        $this->linkAttributes[$name] = $value;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setName($name)
    {
        $this->name = $name;

        $this->label = $this->label ?: $name;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function setParent(ItemInterface $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param int $right
     */
    public function setRight($right)
    {
        $this->right = $right;
    }

    /**
     * @return int
     */
    public function getRight()
    {
        return $this->right;
    }

    /**
     * @param ItemInterface $menu
     */
    public function setMenu($menu)
    {
        $this->menu = $menu;
    }

    /**
     * @return ItemInterface
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * @param string $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param array $routeParameters
     */
    public function setRouteParameters($routeParameters)
    {
        $this->routeParameters = $routeParameters;
    }

    /**
     * @return array
     */
    public function getRouteParameters()
    {
        return $this->routeParameters;
    }

    /**
     * @param \DateTime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }


    /**
     * {@inheritDoc}
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * {@inheritDoc}
     */
    public function getAttribute($name, $default = null)
    {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }

        return $default;
    }

    /**
     * {@inheritDoc}
     */
    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setExtras(array $extras)
    {
        $this->extras = $extras;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getExtras()
    {
        return $this->extras;
    }

    /**
     * {@inheritDoc}
     */
    public function getExtra($name, $default = null)
    {
        if (isset($this->extras[$name])) {
            return $this->extras[$name];
        }

        return $default;
    }

    /**
     * {@inheritDoc}
     */
    public function setExtra($name, $value)
    {
        $this->extras[$name] = $value;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function actsLikeLast()
    {
        // root items are never "marked" as last
        if ($this->isRoot()) {
            return false;
        }

        // A menu acts like last only if it is displayed
        if (!$this->isDisplayed()) {
            return false;
        }

        // if we're last and visible, we're last, period.
        if ($this->isLast()) {
            return true;
        }

        $children = array_reverse($this->getParent()->getChildren()->toArray());
        foreach ($children as $child) {
            // loop until we find a visible menu. If its this menu, we're first
            if ($child->isDisplayed()) {
                return $child->getName() === $this->getName();
            }
        }

        return false;
    }

}
