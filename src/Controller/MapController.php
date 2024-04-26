<?php

namespace App\Controller;

use App\Entity\DataPacket;
use App\Entity\Event;
use App\Service\MapDataService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    /**
     * @throws \Exception
     */
    #[Route('/map/{eventId}', name: 'app_show_map')]
    public function showEventMap(
        ManagerRegistry $managerRegistry,
        MapDataService $mapDataService,
        int $eventId
    ): Response {
        /** @var Event $event */
        $event = $managerRegistry->getRepository(Event::class)->findOneBy(
            ['id' => $eventId, 'enabled' => true]
        );
        if (!$event) throw new \Exception('Event not found!');

        $data = json_encode($mapDataService->getEventData($event));
        $dateRange = $managerRegistry->getRepository(DataPacket::class)
            ->getEventDateRange($event);
        $event->setStartDate($dateRange['startDate']);
        $event->setEndDate($dateRange['endDate']);
        $sliderMax = $event->getEndDate()->getTimestamp() - $event->getStartDate()->getTimestamp();

        return $this->render('map/index.html.twig', [
            'event' => $event,
            'data' => $data,
            'sliderMax' => $sliderMax
        ]);
    }
}
