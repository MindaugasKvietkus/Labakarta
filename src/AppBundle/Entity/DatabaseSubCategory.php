<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="sub_categories")
 */
class DatabaseSubCategory
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */

    public $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\DatabaseCategory", inversedBy="sub_categories")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="CASCADE")
     */

    public $category_id;

    /**
     * @ORM\Column(type="string", name="category", length=100)
     */

    public $category;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\DatabaseOffertolearn", mappedBy="sub_category_id")
     */

    public $sub_category;

    public function __construct()
    {
        return $this->sub_category = new ArrayCollection();
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
     * Set categoryId
     *
     * @param integer $categoryId
     *
     * @return DatabaseSubCategory
     */
    public function setCategoryId($categoryId)
    {
        $this->category_id = $categoryId;

        return $this;
    }

    /**
     * Get categoryId
     *
     * @return integer
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * Set category
     *
     * @param string $category
     *
     * @return DatabaseSubCategory
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
     * Add subCategory
     *
     * @param \AppBundle\Entity\DatabaseOffertolearn $subCategory
     *
     * @return DatabaseSubCategory
     */
    public function addSubCategory(\AppBundle\Entity\DatabaseOffertolearn $subCategory)
    {
        $this->sub_category[] = $subCategory;

        return $this;
    }

    /**
     * Remove subCategory
     *
     * @param \AppBundle\Entity\DatabaseOffertolearn $subCategory
     */
    public function removeSubCategory(\AppBundle\Entity\DatabaseOffertolearn $subCategory)
    {
        $this->sub_category->removeElement($subCategory);
    }

    /**
     * Get subCategory
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubCategory()
    {
        return $this->sub_category;
    }
}
