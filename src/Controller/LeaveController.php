<?php

namespace App\Controller;

use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Repository\BankHolidaysRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class LeaveController extends AbstractController
{
    private BankHolidaysRepository $bankHolidaysRepository;
    private Security $security;

    public function __construct(BankHolidaysRepository $bankHolidaysRepository, Security $security)
    {
        $this->bankHolidaysRepository = $bankHolidaysRepository;
        $this->security = $security;
    }

    /**
     * @throws Exception
     */
    public function calculateLeaveDays(Request $request): JsonResponse
    {
        $user = $this->security->getUser();

        if (!$user) {
            throw new AccessDeniedException('You must be logged in to access this resource.');
        }

        $data = json_decode($request->getContent(), true);
        $weekendOption = (bool)$data['weekendOption'];
        $startDate = new \DateTimeImmutable($data['startDate']);
        $endDate = new \DateTimeImmutable($data['endDate']);

        $bankHolidayDates = $this->bankHolidaysRepository->countBankHolidaysBetweenDates($startDate, $endDate);

        $totalDaysDifference = $this->calculateTotalLeaveDays($startDate, $endDate, $bankHolidayDates, $weekendOption);

        return new JsonResponse(['total_days' => $totalDaysDifference]);
    }

    /**
     * @throws Exception
     */
    private function calculateTotalLeaveDays(\DateTimeImmutable $startDate, \DateTimeImmutable $endDate, $bankHolidayDates, $weekendOption): int
    {
        $totalDaysDifference = 0;

        $startDate = new \DateTimeImmutable($startDate->format('Y-m-d'));
        $endDate = new \DateTimeImmutable($endDate->format('Y-m-d'));
        $interval = $startDate->diff($endDate);
        $days = $interval->days + 1;

        $totalDaysDifference += $days - $bankHolidayDates;

        if ($weekendOption === false) {
            $totalDaysDifference -= $this->countSaturdaysAndSundays($startDate, $endDate);
        }

        return $totalDaysDifference;
    }

    private function countSaturdaysAndSundays(\DateTimeImmutable $startDate, \DateTimeImmutable $endDate): int
    {
        $weekendCount = 0;

        $startDate = strtotime($startDate->format('Y-m-d'));
        $endDate = strtotime($endDate->format('Y-m-d'));

        for ($i = $startDate; $i <= $endDate; $i = strtotime('+1 day', $i)) {
            if (date('N', $i) == 6 || date('N', $i) == 7) {
                $weekendCount++;
            }
        }


        return $weekendCount;
    }
}
