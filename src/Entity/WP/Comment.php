<?php

namespace WonderWp\Entity\WP;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="comments")
 * @ORM\Entity
 */
class Comment
{
    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="comment_ID", type="bigint", length=20)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="comment_author", type="text")
     */
    protected $author;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="comment_author_email", type="string")
     */
    protected $authorEmail = '';

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="comment_author_url", type="string")
     */
    protected $authorUrl = '';

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="comment_author_IP", type="string")
     */
    protected $authorIp;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="comment_date", type="datetime")
     */
    protected $date;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="comment_date_gmt", type="datetime")
     */
    protected $dateGmt;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="comment_content", type="text")
     */
    protected $content;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="comment_karma", type="integer")
     */
    protected $karma = 0;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="comment_approved", type="string")
     */
    protected $approved = 1;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="comment_agent", type="string")
     */
    protected $agent;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="comment_type", type="string")
     */
    protected $type = '';

    /**
     * {@inheritdoc}
     *
     * @ORM\OneToOne(targetEntity="Comment")
     * @ORM\JoinColumn(name="comment_parent", referencedColumnName="comment_ID")
     */
    protected $parent;

    /**
     * {@inheritdoc}
     *
     * @ORM\OneToMany(targetEntity="WonderWp\Entity\WP\CommentMeta", mappedBy="comment")
     */
    protected $metas;

    /**
     * {@inheritdoc}
     *
     * @ORM\ManyToOne(targetEntity="WonderWp\Entity\WP\Post", inversedBy="comments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="comment_post_ID", referencedColumnName="ID", nullable=false)
     * })
     */
    protected $post;

    /**
     * {@inheritdoc}
     *
     * @ORM\ManyToOne(targetEntity="WonderWp\Entity\WP\User", inversedBy="comments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="ID")
     * })
     */
    protected $user;

    public function __construct()
    {
        $this->metas = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getContent();
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
     * @param mixed $author
     * @return Comment
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $authorEmail
     * @return Comment
     */
    public function setAuthorEmail($authorEmail)
    {
        $this->authorEmail = $authorEmail;
        return $this;
    }

    /**
     * Get authorEmail
     *
     * @return string
     */
    public function getAuthorEmail()
    {
        return $this->authorEmail;
    }

    /**
     * @param mixed $authorUrl
     * @return Comment
     */
    public function setAuthorUrl($authorUrl)
    {
        $this->authorUrl = $authorUrl;
        return $this;
    }

    /**
     * Get authorUrl
     *
     * @return string
     */
    public function getAuthorUrl()
    {
        return $this->authorUrl;
    }

    /**
     * @param mixed $authorIp
     * @return Comment
     */
    public function setAuthorIp($authorIp)
    {
        $this->authorIp = $authorIp;
        return $this;
    }

    /**
     * Get authorIp
     *
     * @return string
     */
    public function getAuthorIp()
    {
        return $this->authorIp;
    }

    /**
     * @param mixed $date
     * @return Comment
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $dateGmt
     * @return Comment
     */
    public function setDateGmt($dateGmt)
    {
        $this->dateGmt = $dateGmt;
        return $this;
    }

    /**
     * Get date_gmt
     *
     * @return \DateTime
     */
    public function getDateGmt()
    {
        return $this->dateGmt;
    }

    /**
     * @param mixed $content
     * @return Comment
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $karma
     * @return Comment
     */
    public function setKarma($karma)
    {
        $this->karma = $karma;
        return $this;
    }

    /**
     * Get karma
     *
     * @return integer
     */
    public function getKarma()
    {
        return $this->karma;
    }

    /**
     * Set approved
     *
     * @param string $approved
     * @return $this
     */
    public function setApproved($approved)
    {
        if (is_bool($approved)) {
            $this->approved = $approved ? 1 : 0;
        }

        $this->approved = $approved;
        return $this;
    }

    /**
     * Get approved
     *
     * @return string
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * @param mixed $agent
     * @return Comment
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * Get agent
     *
     * @return string
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * @param mixed $type
     * @return Comment
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set parent
     *
     * @param Comment $comment
     * @return Comment
     */
    public function setParent(Comment $comment)
    {
        $this->parent = $comment;
        return $this;
    }

    /**
     * Get parent
     *
     * @return Comment
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add meta
     *
     * @param CommentMeta $meta
     * @return Comment
     */
    public function addMeta(CommentMeta $meta)
    {
        $this->metas[] = $meta;
        return $this;
    }

    /**
     * Get metas
     *
     * @return CommentMeta[]
     */
    public function getMetas()
    {
        return $this->metas;
    }

    /**
     * Set post
     *
     * @param Post $post
     * @return Comment
     */
    public function setPost(Post $post)
    {
        $this->post = $post;
        return $this;
    }

    /**
     * Get post
     *
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return Comment
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        $this->author      = $user->getDisplayName();
        $this->authorUrl   = $user->getUrl();
        $this->authorEmail = $user->getEmail();
        return $this;
    }

    /**
     * @PrePersist
     */
    public function onPrePersist()
    {
        $this->date    = new \DateTime('now');
        $this->dateGmt = new \DateTime('now', new \DateTimeZone('GMT'));
    }

    /**
     * Get user
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->user instanceof Proxy) {
            try {
                // prevent lazy loading the user entity because it might not exist
                $this->user->__load();
            } catch (EntityNotFoundException $e) {
                // return null if user does not exist
                $this->user = null;
            }
        }

        return $this->user;
    }
}
