<?php

namespace ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Impression
 *
 * @ORM\Table(name="impression")
 * @ORM\Entity(repositoryClass="ServiceBundle\Repository\ImpressionRepository")
 */

class Impression
{
    /**
     * @var UuidInterface
     *
     * @ORM\Column(name="id", type="uuid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Photo",inversedBy="impression")
     * @ORM\JoinColumn(name="photo", referencedColumnName="id")
     */
    private $photo;

    /**
     * @var string
     *
     * @ORM\Column(name="page", type="string", length=255)
     */
    private $page;

    /**
     * @var string
     *
     * @ORM\Column(name="ipAddress", type="string", length=255)
     */
    private $ipAddress;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

   
 


    /**
     * Get id
     *
     * @return uuid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return Impression
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set page
     *
     * @param string $page
     *
     * @return Impression
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return string
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set ipAddress
     *
     * @param string $ipAddress
     *
     * @return Impression
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get ipAddress
     *
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Impression
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
}
