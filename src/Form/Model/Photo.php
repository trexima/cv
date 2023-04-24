<?php

namespace Trexima\EuropeanCvBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Photo
{
    #[Assert\When(
        expression: 'this.getFile() !== null',
        constraints: [
            new Assert\NotNull,
        ]
    )]
    private ?array $options = null;

    private ?UploadedFile $file = null;

    private ?int $existingFileId = null;

    private ?string $existingFileUrl = null;

    public function getOptions(): ?array
    {
        return $this->options;
    }

    public function setOptions(?array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    public function setFile(?UploadedFile $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getExistingFileId(): ?int
    {
        return $this->existingFileId;
    }

    public function setExistingFileId(?int $existingFileId): self
    {
        $this->existingFileId = $existingFileId;

        return $this;
    }

    public function getExistingFileUrl(): ?string
    {
        return $this->existingFileUrl;
    }

    public function setExistingFileUrl(?string $existingFileUrl): self
    {
        $this->existingFileUrl = $existingFileUrl;

        return $this;
    }
}
