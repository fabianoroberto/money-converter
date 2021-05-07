<?php

declare(strict_types=1);

namespace App\Dto\Request;

use App\Dto\Traits\DescriptionPropertyTrait;
use App\Dto\Traits\NamePropertyTrait;
use App\Dto\Traits\PricePropertyTrait;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class ArticleUpdateRequest
{
    use DescriptionPropertyTrait;
    use NamePropertyTrait;
    use PricePropertyTrait;

    /**
     * @JMS\Type("string")
     * @Assert\Url
     */
    protected string $photoUrl = '';

    public function getPhoto(): ?UploadedFile
    {
        if ($this->photoUrl) {
            $file = $this->downloadFile($this->photoUrl);

            return new UploadedFile($file, $this->name);
        }

        return null;
    }

    private function downloadFile(string $url): ?string
    {
        $basePath = \sys_get_temp_dir() . '/articles';
        $fileName = \md5($url);
        $filePath = $basePath . '/' . $fileName;

        if (!\file_exists($basePath)) {
            \mkdir($basePath);
        }

        if (\file_exists($filePath)) {
            return $filePath;
        }

        $result = @\copy($url, $filePath);

        if ($result === false) {
            return null;
        }

        return $filePath;
    }
}
