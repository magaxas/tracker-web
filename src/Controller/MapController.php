<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Service\MapDataService;
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
        EventRepository $eventRepository,
        MapDataService $mapDataService,
        int $eventId
    ): Response {
        $event = $eventRepository->find($eventId);
        if (!$event) throw new \Exception('Event not found!');

        $data = json_encode($mapDataService->getEventData($event));
        $sliderMax = $event->getEndDate()->getTimestamp() - $event->getStartDate()->getTimestamp();

        return $this->render('map/index.html.twig', [
            'event' => $event,
            'data' => $data,
            'sliderMax' => $sliderMax
        ]);
    }
}
