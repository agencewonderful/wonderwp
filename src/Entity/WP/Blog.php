<?php

namespace WonderWp\Entity\WP;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="blogs")
 * @ORM\Entity
 */
class Blog
{
    /**
     * @var int $id
     *
     * @ORM\Column(name="blog_id", type="bigint", length=20)
     * @ORM\Id
     */
    protected $id;

    /**
     * @var int $siteId
     *
     * @ORM\Column(name="site_id", type="bigint", length=20)
     */
    protected $siteId;

    /**
     * @var string $domain
     *
     * @ORM\Column(name="domain", type="string", length=200)
     */
    protected $domain;

    /**
     * @var string $path
     *
     * @ORM\Column(name="path", type="string", length=200)
     */
    protected $path;

    /**
     * @var \DateTime $registeredDate
     *
     * @ORM\Column(name="registered", type="datetime")
     */
    protected $registeredDate;

    /**
     * @var \DateTime $lastUpdatedDate
     *
     * @ORM\Column(name="last_updated", type="datetime")
     */
    protected $lastUpdatedDate;

    /**
     * @var integer $public
     *
     * @ORM\Column(name="public", type="smallint", length=2)
     */
    protected $public;

    /**
     * @var integer $archived
     *
     * @ORM\Column(name="archived", type="smallint")
     */
    protected $archived;

    /**
     * @var integer $mature
     *
     * @ORM\Column(name="mature", type="smallint", length=2)
     */
    protected $mature;

    /**
     * @var integer $spam
     *
     * @ORM\Column(name="spam", type="smallint", length=2)
     */
    protected $spam;

    /**
     * @var integer $deleted
     *
     * @ORM\Column(name="deleted", type="smallint", length=2)
     */
    protected $deleted;

    /**
     * @var integer $langId
     *
     * @ORM\Column(name="lang_id", type="integer", length=11)
     */
    protected $langId;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Blog
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * @param int $siteId
     * @return Blog
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
        return $this;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param string $domain
     * @return Blog
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return Blog
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getRegisteredDate()
    {
        return $this->registeredDate;
    }

    /**
     * @param \DateTime $registeredDate
     * @return Blog
     */
    public function setRegisteredDate($registeredDate)
    {
        $this->registeredDate = $registeredDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastUpdatedDate()
    {
        return $this->lastUpdatedDate;
    }

    /**
     * @param \DateTime $lastUpdatedDate
     * @return Blog
     */
    public function setLastUpdatedDate($lastUpdatedDate)
    {
        $this->lastUpdatedDate = $lastUpdatedDate;
        return $this;
    }

    /**
     * @return int
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * @param int $public
     * @return Blog
     */
    public function setPublic($public)
    {
        $this->public = $public;
        return $this;
    }

    /**
     * @return int
     */
    public function getArchived()
    {
        return $this->archived;
    }

    /**
     * @param int $archived
     * @return Blog
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;
        return $this;
    }

    /**
     * @return int
     */
    public function getMature()
    {
        return $this->mature;
    }

    /**
     * @param int $mature
     * @return Blog
     */
    public function setMature($mature)
    {
        $this->mature = $mature;
        return $this;
    }

    /**
     * @return int
     */
    public function getSpam()
    {
        return $this->spam;
    }

    /**
     * @param int $spam
     * @return Blog
     */
    public function setSpam($spam)
    {
        $this->spam = $spam;
        return $this;
    }

    /**
     * @return int
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param int $deleted
     * @return Blog
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
        return $this;
    }

    /**
     * @return int
     */
    public function getLangId()
    {
        return $this->langId;
    }

    /**
     * @param int $langId
     * @return Blog
     */
    public function setLangId($langId)
    {
        $this->langId = $langId;
        return $this;
    }

}
