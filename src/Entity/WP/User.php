<?php

namespace WonderWp\Entity\WP;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * WonderWp\Entity\WP\User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity
 * @ORM\UniqueEntity({"fields": "email", "message": "Sorry, that email address is already used."})
 * @ORM\UniqueEntity({"fields": "username", "message": "Sorry, that username is already used."})
 * @ORM\UniqueEntity({"fields": "nicename", "message": "Sorry, that nicename is already used."})
 * @ORM\UniqueEntity({"fields": "displayName", "message": "Sorry, that display name has already been taken."})
 * @ORM\HasLifecycleCallbacks
 */
class User
{
    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="ID", type="bigint", length=20)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="user_login", type="string", length=60, unique=true)
     * @Constraints\NotBlank()
     */
    protected $username;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="user_pass", type="string", length=64)
     * @Constraints\NotBlank()
     */
    protected $password;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="user_nicename", type="string", length=64)
     * @Constraints\NotBlank()
     */
    protected $nicename;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="user_email", type="string", length=100)
     * @Constraints\NotBlank()
     * @Constraints\Email()
     */
    protected $email;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="user_url", type="string", length=100)
     * @Constraints\Url()
     */
    protected $url = '';

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="user_registered", type="datetime")
     */
    protected $registeredDate;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="user_activation_key", type="string", length=60)
     */
    protected $activationKey = '';

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="user_status", type="integer", length=11)
     */
    protected $status = 0;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="display_name", type="string", length=250)
     * @Constraints\NotBlank()
     */
    protected $displayName;

    /**
     * {@inheritdoc}
     *
     * @ORM\OneToMany(targetEntity="WonderWp\Entity\WP\UserMeta", mappedBy="user", cascade={"persist"})
     */
    protected $metas;

    /**
     * {@inheritdoc}
     *
     * @ORM\OneToMany(targetEntity="WonderWp\Entity\WP\Post", mappedBy="user")
     */
    protected $posts;

    /**
     * {@inheritdoc}
     *
     * @ORM\OneToMany(targetEntity="WonderWp\Entity\WP\Comment", mappedBy="user")
     */
    protected $comments;

    public function __construct()
    {
        $this->metas = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    /**
     * Get ID
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param $username
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
     * Set nicename
     *
     * @param string $nicename
     * @return User
     */
    public function setNicename($nicename)
    {
        $this->nicename = $nicename;
        return $this;
    }

    /**
     * Get nicename
     *
     * @return string
     */
    public function getNicename()
    {
        return $this->nicename;
    }

    /**
     * Set email
     *
     * @param $email
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
     * Set url
     *
     * @param string $url
     * @return User
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set registeredDate
     *
     * @param $registeredDate
     * @return User
     */
    public function setRegisteredDate($registeredDate)
    {
        $this->registeredDate = $registeredDate;
        return $this;
    }

    /**
     * Get registeredDate
     *
     * @return \DateTime
     */
    public function getRegisteredDate()
    {
        return $this->registeredDate;
    }

    /**
     * Set activationKey
     *
     * @param string $activationKey
     * @return User
     */
    public function setActivationKey($activationKey)
    {
        $this->activationKey = $activationKey;
        return $this;
    }

    /**
     * Get activationKey
     *
     * @return string
     */
    public function getActivationKey()
    {
        return $this->activationKey;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return User
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set displayName
     *
     * @param string $displayName
     * @return User
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
        return $this;
    }

    /**
     * Get displayName
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Add meta
     *
     * @param UserMeta $meta
     * @return User
     */
    public function addMeta(UserMeta $meta)
    {
        $this->metas[] = $meta;

        $meta->setUser($this);
        return $this;
    }

    /**
     * Get metas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMetas()
    {
        return $this->metas;
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return array The user roles
     */
    public function getRoles()
    {
        $roles = array();
        $metas = $this->getMetas()->filter(function (UserMeta $meta) {
            return $meta->getKey() === 'wp_capabilities';
        });

        if ($metas->isEmpty()) {
            return array();
        }

        $capabilities = $metas->first()->getValue();

        if (!is_array($capabilities)) {
            return array();
        }

        foreach ($capabilities as $role => $value) {
            $roles[] = 'ROLE_WP_'.strtoupper($role);
        }

        return $roles;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string The salt
     */
    public function getSalt()
    {

    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     *
     * @return void
     */
    public function eraseCredentials()
    {

    }

    /**
     * Returns whether or not the given user is equivalent to *this* user.
     *
     * The equality comparison should neither be done by referential equality
     * nor by comparing identities (i.e. getId() === getId()).
     *
     * However, you do not need to compare every attribute, but only those that
     * are relevant for assessing whether re-authentication is required.
     *
     * @param $user User to compare
     *
     * @return Boolean
     */
    public function equals(User $user)
    {
        return $this->getUsername() === $user->getUsername();
    }

    /**
     * Serializes the user.
     *
     * The serialized data have to contain the fields used by the equals method and the username.
     *
     * @return string
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
        ));
    }

    /**
     * Unserializes the user.
     *
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        // add a few extra elements in the array to ensure that we have enough keys when unserializing
        // older data which does not include all properties.
        $data = array_merge($data, array_fill(0, 2, null));

        list(
            $this->id,
            $this->username,
            ) = $data;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->registeredDate = new \DateTime('now');
    }
}
