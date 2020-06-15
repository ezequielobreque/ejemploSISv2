<?php

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;



/**
 *
 * @ORM\Entity
 * @ORM\Table(name="portada")
 * @Vich\Uploadable
 **/
class Portada

{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     *
     * @OneToOne(targetEntity="User",inversedBy="Portada")
     * @JoinColumn(name="User_id", referencedColumnName="id",nullable=false)
     */
    protected $user;

    /**
     * @Vich\UploadableField(mapping="imagen_portada", fileNameProperty="imageName", size="imageSize")
     *
     * @var File
     */
    private $imageFile;


    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     *
     * @var string
     */
    private $imageName;

    /**
     * @ORM\Column(type="integer",nullable=true)
     *
     * @var integer
     */
    private $imageSize;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     *
     * @var \DateTime
     */
    private $updatedAt;


    /* es posible agregar mas atributos*/
    public function _construct()
    {
        parent::_construct();
    }
    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }


    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }


    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function setUser(User $user){
        $this->user= $user;

    }
    public function getId(){
        return $this->id;

    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }
}
