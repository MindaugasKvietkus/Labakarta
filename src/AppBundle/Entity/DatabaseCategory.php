<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="categories")
 */

class DatabaseCategory{

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */

    public $id;

    /**
     * @ORM\Column(type="string", name="category", length=100)
     */

    public $category;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\DatabaseSubCategory", mappedBy="category_id")
     */

    public $sub_categories;

    public function __construct()
    {
        $this->sub_categories = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set category
     *
     * @param string $category
     *
     * @return DatabaseCategory
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add category
     *
     * @param \AppBundle\Entity\DatabaseSubCategory $category
     *
     * @return DatabaseCategory
     */
    public function addCategory(\AppBundle\Entity\DatabaseSubCategory $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \AppBundle\Entity\DatabaseSubCategory $category
     */
    public function removeCategory(\AppBundle\Entity\DatabaseSubCategory $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }
}
