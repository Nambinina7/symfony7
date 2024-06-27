<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Permission;
use App\Entity\Holyday;
use App\Services\GoogleSheetsService;
use Google\Service\Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class EmployeeEntitiesSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly GoogleSheetsService $googleSheetsService,
        private readonly string $permissionSheetId,
        private readonly string $holydaySheetId,
    ) {

    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['writeDataSheets', EventPriorities::POST_WRITE],
        ];
    }

    /**
     * @throws Exception
     */
    public function writeDataSheets(ViewEvent $event): void
    {
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$this->isSupportedEntity($entity) || Request::METHOD_POST !== $method) {
            return;
        }

        $writeRange = 'A1';
        $values = $this->getEntityValues($entity);
        $spreadsheetId = $this->getSpreadsheetId($entity);
        $this->googleSheetsService->writeData($spreadsheetId, $writeRange, $values);
    }

    private function isSupportedEntity($entity): bool
    {
        return $entity instanceof Permission || $entity instanceof Holyday;
    }

    private function getEntityValues($entity): array
    {
        if ($entity instanceof Permission) {

            return [
                [
                    $entity->getStartDate()->format('d-m-y'),
                    $entity->getEndDate()->format('d-m-y'),
                    $entity->getBeginningHour()->format('H:i'),
                    $entity->getEndTime()->format('H:i'),
                    $entity->getUserPermissions()->getFirstName(),
                    $entity->getUserPermissions()->getLastName(),
                    $entity->getUserPermissions()->getEmail(),
                ]
            ];
        }
        if ($entity instanceof Holyday) {

            return [
                [
                    $entity->getRequestDate()->format('d-m-y'),
                    $entity->getStartDate()->format('d-m-y'),
                    $entity->getEndDate()->format('d-m-y'),
                    $entity->getUserHolydays()[0]->getFirstName(),
                    $entity->getUserHolydays()[0]->getLastName(),
                    $entity->getUserHolydays()[0]->getEmail(),
                ]
            ];
        }
        return [];
    }

    private function getSpreadsheetId($entity): string
    {
        if ($entity instanceof Permission) {
            return $this->permissionSheetId;
        }

        if ($entity instanceof Holyday) {
            return $this->holydaySheetId;
        }

        return '';
    }
}
