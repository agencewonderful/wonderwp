<?php

namespace WonderWp\Entity\WP;

use Doctrine\ORM\Mapping as ORM;
use Kayue\WordpressBundle\Annotation as Wordpress;
use Symfony\Component\Validator\Constraints as Constraints;

/**
 * @ORM\Table(name="terms")
 * @ORM\Entity
 */
class Term
{
    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="term_id", type="bigint", length=20)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="name", type="string", length=200)
     * @Constraints\NotBlank()
     */
    protected $name;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="slug", type="string", length=200)
     */
    protected $slug;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="term_group", type="bigint", length=10)
     */
    protected $group = 0;

    /**
     * {@inheritdoc}
     *
     * @ORM\OneToOne(targetEntity="Taxonomy", mappedBy="term")
     */
    protected $taxonomy;

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
     * @return Term
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
     * Set slug
     *
     * @param string $slug
     * @return Term
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set group
     *
     * @param int $group
     * @return Term
     */
    public function setGroup($group)
    {
        $this->group = $group;
        return $this;
    }

    /**
     * Get group
     *
     * @return int
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Set taxonomy
     *
     * @param Taxonomy $taxonomy
     * @return Term
     */
    public function setTaxonomy(Taxonomy $taxonomy)
    {
        $this->taxonomy = $taxonomy;
        return $this;
    }

    /**
     * Get taxonomy
     *
     * @return Taxonomy
     */
    public function getTaxonomy()
    {
        return $this->taxonomy;
    }
}
