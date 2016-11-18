<?php

namespace AppBundle\Entity;

use  Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="offer_to_learn")
 */

class DatabaseOffertolearn{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */

    public $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\DatabaseUserVariables", inversedBy="offer_to_learn")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */

    public $user_id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\DatabaseSubCategory", inversedBy="sub_category")
     * @ORM\JoinColumn(name="sub_category_id", referencedColumnName="id", onDelete="CASCADE")
     */

    public $sub_category_id;

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
     * Set userId
     *
     * @param \AppBundle\Entity\DatabaseUserVariables $userId
     *
     * @return DatabaseOffertolearn
     */
    public function setUserId(\AppBundle\Entity\DatabaseUserVariables $userId = null)
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return \AppBundle\Entity\DatabaseUserVariables
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set subCategoryId
     *
     * @param \AppBundle\Entity\DatabaseSubCategory $subCategoryId
     *
     * @return DatabaseOffertolearn
     */
    public function setSubCategoryId(\AppBundle\Entity\DatabaseSubCategory $subCategoryId = null)
    {
        $this->sub_category_id = $subCategoryId;

        return $this;
    }

    /**
     * Get subCategoryId
     *
     * @return \AppBundle\Entity\DatabaseSubCategory
     */
    public function getSubCategoryId()
    {
        return $this->sub_category_id;
    }
}
