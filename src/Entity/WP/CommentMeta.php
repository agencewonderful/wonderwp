<?php

namespace WonderWp\Entity\WP;

use Doctrine\ORM\Mapping as ORM;
use Kayue\WordpressBundle\Annotation as Wordpress;
use Symfony\Component\Validator\Constraints as Constraints;

/**
 * @Table(name="commentmeta")
 * @Entity
 */
class CommentMeta
{
    /**
     * {@inheritdoc}
     *
     * @Column(name="meta_id", type="bigint", length=20)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * {@inheritdoc}
     *
     * @Column(name="meta_key", type="string", length=255, nullable=true)
     */
    protected $key;

    /**
     * @var string $value
     *
     * @Column(name="meta_value", type="wordpressmeta", nullable=true)
     */
    protected $value;

    /**
     * {@inheritdoc}
     *
     * @ManyToOne(targetEntity="WonderWp\Entity\WP\Comment", inversedBy="metas")
     * @JoinColumns({
     *   @JoinColumn(name="comment_id", referencedColumnName="comment_ID")
     * })
     */
    protected $comment;

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
     * Set key
     *
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set value
     *
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set comment
     *
     * @param Comment $comment
     */
    public function setComment(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get comment
     *
     * @return Comment
     */
    public function getComment()
    {
        return $this->comment;
    }
}
