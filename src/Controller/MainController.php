<?php

namespace App\Controller;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $eventRepository = $entityManager->getRepository(Event::class);
        $currentEvents = $eventRepository->findCurrentEvents();
        $upcomingEvents = $eventRepository->findUpcomingEvents();
        $previousEvents = $eventRepository->findPreviousEvents();

        return $this->render('main/index.html.twig', [
            'currentEvents' => $currentEvents,
            'upcomingEvents' => $upcomingEvents,
            'previousEvents' => $previousEvents,
        ]);
    }
}
