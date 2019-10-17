<?php


namespace App\EventListener;


use App\Entity\AttachedFile;
use App\Service\AttachedFileService;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

class AttachedFileListener
{
    /**
     * @var AttachedFileService
     */
    private $service;

    /**
     * AttachedFileListener constructor.
     * @param AttachedFileService $service
     */
    public function __construct(AttachedFileService $service)
    {
        $this->service = $service;
    }

    /**
     * @ORM\PostPersist()
     *
     * @param AttachedFile $file
     * @param LifecycleEventArgs $eventArgs
     */
    public function postPersistHandler(AttachedFile $file, LifecycleEventArgs $eventArgs): void
    {
        $response = $this->service->makeLegacyRequest($file);
    }

}