<?php

namespace Trexima\EuropeanCvBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Trexima\EuropeanCvBundle\Entity\Embeddable\YearRange;
use Trexima\EuropeanCvBundle\Entity\Enum\EducationTypeEnum;

/**
 * EuropeanCV education
 */
#[ORM\Table(name: 'european_cv_certificate')]
#[ORM\Entity]
class EuropeanCVCertificate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: EuropeanCV::class, inversedBy: 'educations')]
    #[ORM\JoinColumn(name: 'european_cv_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?EuropeanCV $europeanCV = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 500, maxMessage: 'trexima_european_cv.max_length_reached')]
    #[ORM\Column(type: 'string', length: 500, nullable: true)]
    private ?string $title = null;

    #[Assert\Length(max: 100, maxMessage: 'trexima_european_cv.max_length_reached')]
    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $institution = null;

    #[ORM\Embedded(class: YearRange::class)]
    private YearRange $yearRange;

    #[Assert\Length(max: 100, maxMessage: 'trexima_european_cv.max_length_reached')]
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    public function __construct()
    {
        $this->yearRange = new YearRange();
    }

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getInstitution(): ?string
    {
        return $this->institution;
    }

    public function setInstitution(?string $institution): void
    {
        $this->institution = $institution;
    }

    public function getYearRange(): YearRange
    {
        return $this->yearRange;
    }

    public function setYearRange(YearRange $yearRange): void
    {
        $this->yearRange = $yearRange;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
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