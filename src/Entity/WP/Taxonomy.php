<?php

namespace WonderWp\Entity\WP;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Kayue\WordpressBundle\Annotation as Wordpress;
use Symfony\Component\Validator\Constraints as Constraints;

/**
 * @Table(name="term_taxonomy")
 * @Entity
 * @Wordpress\WordpressTable
 */
class Taxonomy
{
    /**
     * {@inheritdoc}
     *
     * @Column(name="term_taxonomy_id", type="bigint", length=20)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * {@inheritdoc}
     *
     * @Column(name="taxonomy", type="string", length=32)
     * @Constraints\NotBlank()
     */
    protected $name;

    /**
     * {@inheritdoc}
     *
     * @Column(name="description", type="text")
     */
    protected $description = '';

    /**
     * {@inheritdoc}
     *
     * @Column(name="parent", type="bigint", length=20)
     */
    protected $parent;

    /**
     * {@inheritdoc}
     *
     * @Column(name="count", type="bigint", length=20)
     */
    protected $count = 0;

    /**
     * {@inheritdoc}
     *
     * @OneToOne(targetEntity="WonderWp\Entity\WP\Term", inversedBy="taxonomy")
     * @JoinColumns({
     *   @JoinColumn(name="term_id", referencedColumnName="term_id", unique=true)
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
     */
    public function setName($name)
    {
        $this->name = $name;
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
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
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
     */
    public function setCount($count)
    {
        $this->count = $count;
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
     */
    public function setTerm(Term $term)
    {
        $this->term = $term;
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
     */
    public function addPosts(Post $post)
    {
        $this->posts[] = $post;
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
