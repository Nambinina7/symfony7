<?php

namespace App\Controller;

use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class HolidayByUserController extends AbstractController
{
    public function getHolidays(): JsonResponse
    {
        $currentUser = $this->getUserOrThrow();

        $holidays = $currentUser->getHolydays();
        return $this->json($holidays, 200, [], ['groups' => ['holyday:read']]);
    }

    public function countHolidays(): JsonResponse
    {
        $currentUser = $this->getUserOrThrow();

        $holidays = $currentUser->getHolydays();
        $totalDays = $this->calculateTotalHolidayDays($holidays);

        return $this->json(['total_days' => $totalDays], 200);
    }

    private function getUserOrThrow()
    {
        $currentUser = $this->getUser();

        if (!$currentUser) {
            throw new AccessDeniedException('You must be logged in to access this resource.');
        }

        return $currentUser;
    }

    private function calculateTotalHolidayDays($holidays): int
    {
        $totalDays = 0;

        foreach ($holidays as $holiday) {
            $totalDays += $holiday->getTotal();
        }

        return $totalDays;
    }
}
