<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AlbumRepository")
 */
class Album implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $releaseDate;

    /**
     * @Assert\GreaterThan(0)
     * @ORM\Column(type="integer")
     */
    private $trackCount;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Album
     */
    public function setTitle($title): Album
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getReleaseDate(): ?\DateTime
    {
        return $this->releaseDate;
    }

    /**
     * @param \DateTime $releaseDate
     * @return Album
     */
    public function setReleaseDate($releaseDate): Album
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getTrackCount(): ?int
    {
        return $this->trackCount;
    }

    /**
     * @param int $trackCount
     * @return Album
     */
    public function setTrackCount($trackCount): Album
    {
        $this->trackCount = $trackCount;

        return $this;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'release_date' => $this->releaseDate->format(\DateTime::ATOM),
            'track_count' => $this->trackCount
        ];
    }
}
