<?php

namespace WonderWp\Entity\WP;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Kayue\WordpressBundle\Annotation as Wordpress;
use Symfony\Component\Validator\Constraints as Constraints;

/**
 * @ORM\Table(name="term_taxonomy")
 * @ORM\Entity
 */
class Taxonomy
{
    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="term_taxonomy_id", type="bigint", length=20)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="taxonomy", type="string", length=32)
     * @Constraints\NotBlank()
     */
    protected $name;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="description", type="text")
     */
    protected $description = '';

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="parent", type="bigint", length=20)
     */
    protected $parent;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="count", type="bigint", length=20)
     */
    protected $count = 0;

    /**
     * {@inheritdoc}
     *
     * @ORM\OneToOne(targetEntity="WonderWp\Entity\WP\Term", inversedBy="taxonomy")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="term_id", referencedColumnName="term_id", unique=true)
     * })
     */
    protected $term;

    /**
     * {@inheritdoc}
     *
     * @ManyToMany(targetEntity="Post", mappedBy="taxonomies")
     */
    protected $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Taxonomy
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
     * Set description
     *
     * @param string $description
     * @return Taxonomy
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set parent
     *
     * @param int $parent
     * @return Taxonomy
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Get parent
     *
     * @return int
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set count
     *
     * @param int $count
     * @return Taxonomy
     */
    public function setCount($count)
    {
        $this->count = $count;
        return $this;
    }

    /**
     * Get count
     *
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set term
     *
     * @param Term $term
     * @return Taxonomy
     */
    public function setTerm(Term $term)
    {
        $this->term = $term;
        return $this;
    }

    /**
     * Get term
     *
     * @return Term
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * Add post
     *
     * @param Post $post
     * @return Taxonomy
     */
    public function addPosts(Post $post)
    {
        $this->posts[] = $post;
        return $this;
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
}
