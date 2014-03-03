<?php
/**
 *
 */

namespace SymfonyContrib\Bundle\DynamicMenuBundle\Model;

interface NestedSetNodeInterface
{
    /**
     * @param int $left
     */
    public function setLeft($left);

    /**
     * @return int
     */
    public function getLeft();

    /**
     * @param int $level
     */
    public function setLevel($level);

    /**
     * @return int
     */
    public function getLevel();

    /**
     * @param int $right
     */
    public function setRight($right);

    /**
     * @return int
     */
    public function getRight();

    /**
     * @param int $root
     */
    //public function setRoot($root);

    /**
     * @return int
     */
    //public function getRoot();

    /* Needs to be set but collides with the menu ItemInterface method.
    public function setParent(NestedSetNodeInterface $parent);
    */
}
