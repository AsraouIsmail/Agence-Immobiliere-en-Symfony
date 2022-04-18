<?php

namespace App\Entity;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Cocur\Slugify\Slugify;
use App\Repository\PropertyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PropertyRepository::class)
 * @UniqueEntity("title")
 * @Vich\Uploadable
 */
class Property
{

    const HEAT = [
        0 => 'Electrique',
        1 => 'Gaz'
    ];

    const TYPE = [
        0 => 'Villa',
        1 => 'Studio',
        2 => 'Duplex'
    ];

    const STATUS = [
        0 => 'A louer',
        1 => 'A vendre'
    ];

    const PARKING = [
        0 => 'oui',
        1 => 'Non'
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filename;

    /**
     * @var File|null
     * @Assert\Image(mimeTypes="image/jpeg")
     * @Vich\UploadableField(mapping="property_image", fileNameProperty="filename")
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Donnez un titre a votre bien")
     * @Assert\Length(min=4)
     */

    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Une brief description du bien est indispensable")
     * @Assert\Length(min=16)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank(message="La surperficie est obligatoire")
     * @Assert\Range(
     *     min = 60,
     *     max = 400,
     *     notInRangeMessage= "la surface c'est entre {{ min }} m2 and {{ max }} m2 au maximum"
     * )
     */
    private $surface;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank(message="Le nombre de pieces est obligatoire")
     */
    private $rooms;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank(message="Le nombre de chambres est obligatoire")
     */
    private $bedrooms;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank(message="il faut bien indiquer au quel etage")
     */
    private $floor;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $heat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="l'adresse est obligatoire")
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Regex("/^[0-9]{5}$/")
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Dites nous dans quelle ville se trouve le bien")
     */
    private $city;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": false})
     * @Assert\NotBlank(message="Mentionnez si c'est vendu ou pas / louer ou libre")
     */
    private $sold = false;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $parking;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank(message="Obligatoire")
     */
    private $status;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank(message="Mentionner le type de bien")
     */
    private $type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank(message="Mettez un prix de location ou d'achat")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Dites dans quel pays se trouve le bien")
     */
    private $country;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity=Option::class, inversedBy="properties")
     */
    private $options;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_time;
    


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): string
    {
        return (new Slugify())->slugify($this->title);
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSurface(): ?int
    {
        return $this->surface;
    }

    public function setSurface(?int $surface): self
    {
        $this->surface = $surface;

        return $this;
    }

    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    public function setRooms(?int $rooms): self
    {
        $this->rooms = $rooms;

        return $this;
    }

    public function getBedrooms(): ?int
    {
        return $this->bedrooms;
    }

    public function setBedrooms(?int $bedrooms): self
    {
        $this->bedrooms = $bedrooms;

        return $this;
    }

    public function getFloor(): ?int
    {
        return $this->floor;
    }

    public function setFloor(?int $floor): self
    {
        $this->floor = $floor;

        return $this;
    }

    public function getHeat(): ?int
    {
        return $this->heat;
    }

    public function setHeat(?int $heat): self
    {
        $this->heat = $heat;

        return $this;
    }

    public function getHeatType(): string
    {
        return self::HEAT[$this->heat];
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getSold(): ?bool
    {
        return $this->sold;
    }

    public function setSold(?bool $sold): self
    {
        $this->sold = $sold;

        return $this;
    }

    public function getParking(): ?int
    {
        return $this->parking;
    }

    public function setParking(?int $parking): self
    {
        $this->parking = $parking;

        return $this;
    }

    public function getParkingType(): string
    {
        return self::PARKING[$this->parking];
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStatusType(): string
    {
        return self::STATUS[$this->status];
    }



    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTypeType(): string
    {
        return self::TYPE[$this->type];
    }


    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

     public function getFormattedPrice(): string
    {
        return number_format($this->price, 0, '', ' ');
    }


    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }


    public function __construct() {
        $this->createdAt = new \DateTime;
        $this->updatedAt =  new \DateTime();
        $this->options = new ArrayCollection();
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }



    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Option>
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Option $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options[] = $option;
            $option->addProperty($this);
        }

        return $this;
    }

    public function removeOption(Option $option): self
    {
        if ($this->options->removeElement($option)) {
            $option->removeProperty($this);
        }

        return $this;
    }

    

    /**
     * Get mimeTypes="image/jpeg")
     *
     * @return  File|null
     */ 
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Set mimeTypes="image/jpeg")
     *
     * @param  File|null  $imageFile
     *
     * 
     */ 
    public function setImageFile(File $imageFile = null): Property
    {
        $this->imageFile = $imageFile;

        if ($this->imageFile instanceof UploadedFile) {
            $this->updated_time = new \DateTime('now');
        }

        return $this;
    }

    public function getUpdatedTime(): ?\DateTimeInterface
    {
        return $this->updated_time;
    }

    public function setUpdatedTime(\DateTimeInterface $updated_time): self
    {
        $this->updated_time = $updated_time;

        return $this;
    }

    /**
     * Get the value of filename
     *
     * @return  string|null
     */ 
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set the value of filename
     *
     * @param  string|null  $filename
     *
     * @return  self
     */ 
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }
}
