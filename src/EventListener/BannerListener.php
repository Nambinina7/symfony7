<?php

namespace App\EventListener;

use Vich\UploaderBundle\Event\Event;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

class BannerListener
{
    private CacheManager $cacheManager;
    private string $mobileDir;

    public function __construct(CacheManager $cacheManager, string $mobileDir = "banners/images/mobile")
    {
        $this->cacheManager = $cacheManager;
        $this->mobileDir = $mobileDir;
    }


    public function onVichUploaderPostUpload(Event $event): void
    {
//        $object = $event->getObject();
        $mapping = $event->getMapping();
        $imagePath = $event->getObject()->getImage();
        $sourcePath = $mapping->getUploadDestination() . '/' . $imagePath;
        $imageFile = new File($sourcePath);


        // Apply LiipImagine filter for mobile
        $mobilePath = $this->cacheManager->getBrowserPath($sourcePath, 'mobile_filter');
        $this->moveFile($imageFile, $this->mobileDir . '/' . basename($mobilePath));


    }

    private function moveFile(File $sourceFile, string $destinationPath)
    {
        $filesystem = new Filesystem();
        $filesystem->copy($sourceFile->getPathname(), $destinationPath, true);
    }
}
