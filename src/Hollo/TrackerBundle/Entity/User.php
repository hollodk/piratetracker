<?php

namespace Hollo\TrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Hollo\TrackerBundle\Entity\UserRepository")
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface, \Serializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @Assert\NotBlank
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @var integer
     *
     * @Assert\NotBlank
     * @ORM\Column(name="rank", type="integer", nullable=true)
     */
    private $rank;

    /**
     * @var string
     *
     * @Assert\NotBlank
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=255, nullable=true)
     */
    private $icon;

    /**
     * @var string
     *
     * @Assert\NotBlank
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var boolean
     *
     * @ORM\Column(name="validated", type="boolean")
     */
    private $validated = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="admin", type="boolean")
     */
    private $admin = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="map_follow", type="boolean")
     */
    private $mapFollow = false;

    /**
     * @var string
     *
     * @ORM\Column(name="profile_image", type="text", nullable=true)
     */
    private $profileImage;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="Position", cascade={"persist"})
     */
    private $position;

    /**
     * @ORM\ManyToOne(targetEntity="Fraction", cascade={"persist"})
     */
    private $fraction;

    /**
     * @ORM\OneToMany(targetEntity="Position", mappedBy="user")
     */
    private $positions;


    public function __toString()
    {
        return $this->getUsername();
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
     * Set name
     *
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
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
     * Set password
     *
     * @param string $password
     * @return User
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

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * Set position
     *
     * @param \Hollo\TrackerBundle\Entity\Position $position
     * @return User
     */
    public function setPosition(\Hollo\TrackerBundle\Entity\Position $position = null)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return \Hollo\TrackerBundle\Entity\Position
     */
    public function getPosition()
    {
        return $this->position;
    }

    public function getRoles()
    {
        $roles = array();
        $roles[] = 'ROLE_USER';

        if ($this->getAdmin()) {
            $roles[] = 'ROLE_ADMIN';
        }

        return $roles;
    }

    public function eraseCredentials()
    {
    }

    public function getSalt()
    {
        return null;
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password
        ));
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password
        ) = unserialize($serialized);
    }

    /**
     * Set fraction
     *
     * @param \Hollo\TrackerBundle\Entity\Fraction $fraction
     * @return User
     */
    public function setFraction(\Hollo\TrackerBundle\Entity\Fraction $fraction = null)
    {
        $this->fraction = $fraction;

        return $this;
    }

    /**
     * Get fraction
     *
     * @return \Hollo\TrackerBundle\Entity\Fraction
     */
    public function getFraction()
    {
        return $this->fraction;
    }

    /**
     * Set rank
     *
     * @param integer $rank
     * @return User
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank
     *
     * @return integer
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Set profileImage
     *
     * @param string $profileImage
     * @return User
     */
    public function setProfileImage($profileImage)
    {
        $this->profileImage = $profileImage;

        return $this;
    }

    /**
     * Get profileImage
     *
     * @return string
     */
    public function getProfileImage()
    {
        return $this->profileImage;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->positions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add positions
     *
     * @param \Hollo\TrackerBundle\Entity\Position $positions
     * @return User
     */
    public function addPosition(\Hollo\TrackerBundle\Entity\Position $positions)
    {
        $this->positions[] = $positions;

        return $this;
    }

    /**
     * Remove positions
     *
     * @param \Hollo\TrackerBundle\Entity\Position $positions
     */
    public function removePosition(\Hollo\TrackerBundle\Entity\Position $positions)
    {
        $this->positions->removeElement($positions);
    }

    /**
     * Get positions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPositions()
    {
        return $this->positions;
    }

    /**
     * Set validated
     *
     * @param boolean $validated
     * @return User
     */
    public function setValidated($validated)
    {
        $this->validated = $validated;

        return $this;
    }

    /**
     * Get validated
     *
     * @return boolean
     */
    public function getValidated()
    {
        return $this->validated;
    }

    /**
     * Set admin
     *
     * @param boolean $admin
     * @return User
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * Get admin
     *
     * @return boolean
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Set icon
     *
     * @param string $icon
     * @return User
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set mapFollow
     *
     * @param boolean $mapFollow
     * @return User
     */
    public function setMapFollow($mapFollow)
    {
        $this->mapFollow = $mapFollow;

        return $this;
    }

    /**
     * Get mapFollow
     *
     * @return boolean
     */
    public function getMapFollow()
    {
        return $this->mapFollow;
    }
}
