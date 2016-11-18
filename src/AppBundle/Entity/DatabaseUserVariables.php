<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */

class DatabaseUserVariables implements UserInterface, \Serializable
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */

    public $id;

    /**
     * @ORM\Column(type="string", name="email", length=100)
     */

    public $email;

    /**
     * @ORM\Column(type="string", name="name_surname", length=100)
     */


    public $name_surname;

    /**
     * @ORM\Column(type="string", name="password", length=100)
     */

    public $password;

    /**
     * @ORM\Column(type="string", name="image", length=255)
     */

    public $image;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\DatabaseOffertolearn", mappedBy="user_id")
     */

    public $offer_to_learn;

    public function __construct()
    {
        return $this->offer_to_learn = new ArrayCollection();
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
     * Set email
     *
     * @param string $email
     *
     * @return UserDatabaseVariables
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set nameSurname
     *
     * @param string $nameSurname
     *
     * @return UserDatabaseVariables
     */
    public function setNameSurname($nameSurname)
    {
        $this->name_surname = $nameSurname;

        return $this;
    }

    /**
     * Get nameSurname
     *
     * @return string
     */
    public function getNameSurname()
    {
        return $this->name_surname;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return UserDatabaseVariables
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function eraseCredentials(){

    }

    public  function getUsername (){

        return $this->name_surname;

    }

    public function getRoles(){

        return array('ROLE_USER');

    }

    public function getSalt(){

        return null;

    }

    public function serialize(){

        return serialize(array(
           $this->id,
            $this->email,
            $this->password,

            // $this->salt
        ));

    }

    public function unserialize($serialized){

        list(
            $this->id,
            $this->email,
            $this->password
            ) = unserialize($serialized);

    }

    /**
     * Add offerToLearn
     *
     * @param \AppBundle\Entity\DatabaseOffertolearn $offerToLearn
     *
     * @return DatabaseUserVariables
     */
    public function addOfferToLearn(\AppBundle\Entity\DatabaseOffertolearn $offerToLearn)
    {
        $this->offer_to_learn[] = $offerToLearn;

        return $this;
    }

    /**
     * Remove offerToLearn
     *
     * @param \AppBundle\Entity\DatabaseOffertolearn $offerToLearn
     */
    public function removeOfferToLearn(\AppBundle\Entity\DatabaseOffertolearn $offerToLearn)
    {
        $this->offer_to_learn->removeElement($offerToLearn);
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return DatabaseUserVariables
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }
}
