<?php

namespace ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Photo
 *
 * @ORM\Table(name="photo")
 * @ORM\Entity(repositoryClass="ServiceBundle\Repository\PhotoRepository")
 */

class Photo
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
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var int
     *
     * @ORM\Column(name="totalImpressions", type="integer")
     */
    private $totalImpressions;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     *
     * @ORM\OneToMany(targetEntity="Impression",mappedBy="photo")
     */
    private $impression;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

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
     * Set url
     *
     * @param string $url
     *
     * @return Photo
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
     * Set totalImpressions
     *
     * @param integer $totalImpressions
     *
     * @return Photo
     */
    public function setTotalImpressions($totalImpressions)
    {
        $this->totalImpressions = $totalImpressions;

        return $this;
    }

    /**
     * Get totalImpressions
     *
     * @return int
     */
    public function getTotalImpressions()
    {
        return $this->totalImpressions;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Photo
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
     * Add impression
     *
     * @param \ServiceBundle\Entity\Impression $impression
     *
     * @return Photo
     */
    public function addImpression(\ServiceBundle\Entity\Impression $impression)
    {
        $this->impression[] = $impression;

        return $this;
    }

    /**
     * Remove impression
     *
     * @param \ServiceBundle\Entity\Impression $impression
     */
    public function removeImpression(\ServiceBundle\Entity\Impression $impression)
    {
        $this->impression->removeElement($impression);
    }

    /**
     * Get impression
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImpression()
    {
        return $this->impression;
    }

    public function __toString()
    {
        return $this->url;
    }
}
