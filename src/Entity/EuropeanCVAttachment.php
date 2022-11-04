<?php

namespace Trexima\EuropeanCvBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'european_cv_attachment')]
#[ORM\Entity]
class EuropeanCVAttachment
{
    final public const TYPE_MOBILE = 1,
        TYPE_HOME = 2,
        TYPE_WORK = 3;

    final public const TYPES = [
        self::TYPE_MOBILE => 'Mobil',
        self::TYPE_HOME => 'Doma',
        self::TYPE_WORK => 'Práca'
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: \Trexima\EuropeanCvBundle\Entity\EuropeanCV::class, inversedBy: 'attachments')]
    #[ORM\JoinColumn(name: 'european_cv_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?\Trexima\EuropeanCvBundle\Entity\EuropeanCV $europeanCV = null;

    #[ORM\Column(type: 'string', length: 256, unique: true)]
    // #[Assert\NotBlank(message: 'Nahrajte prílohu prosím')]
    // #[Assert\File(maxSize: '2048k', mimeTypes: ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/rtf', 'image/jpeg', 'text/plain'], mimeTypesMessage: 'Nahrajte prosím súbor v správnom formáte')]
    private string|\Symfony\Component\HttpFoundation\File\File|null $file = null;

    #[ORM\Column(type: 'string', length: 256)]
    private ?string $name = null;

    /**
     * @var int|null
     */
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private $position;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getEuropeanCV(): EuropeanCV
    {
        return $this->europeanCV;
    }

    public function setEuropeanCV(EuropeanCV $europeanCV): void
    {
        $this->europeanCV = $europeanCV;
    }

    /**
     * @return null|string|File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param null|string|File $file
     */
    public function setFile($file): void
    {
        $this->file = $file;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): void
    {
        $this->position = $position;
    }

    public function __clone()
    {
        /*
         * If the entity has an identity, proceed as normal.
         * https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/cookbook/implementing-wakeup-or-clone.html
         */
        if ($this->id) {
            $this->setId(null);
        }
    }
}