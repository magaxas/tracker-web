<?php

namespace App\Controller;

use App\Entity\DataPacket;
use App\Entity\Event;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api/dataPacket', name: 'app_api_create_data_packet', methods: ['post'])]
    public function createDataPacket(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $authorizationHeader = $request->headers->get('Authorization');
        $token = substr($authorizationHeader, 7); // skip beyond "Bearer "
        if ($this->getParameter('app.api_key') !== $token) {
            return new Response('Unauthorized');
        }

        $params = $request->request;
        if (!$params->has('batteryVoltage') || !$params->has('latitude') ||
            !$params->has('longitude') || !$params->has('eventId')) {
            return new Response('Wrong parameters');
        }

        $em = $managerRegistry->getManager();
        $event = $em->getRepository(Event::class)->findOneBy([
            'id' => $params->get('eventId') //TODO: check if event is live
        ]);
        if ($event == null) {
            return new Response('Event not found');
        }

        $dataPacket = new DataPacket();
        $dataPacket
            ->setDate((new \DateTime())->setTimezone(new \DateTimeZone('Europe/Vilnius')))
            ->setBatteryVoltage($params->get('batteryVoltage'))
            ->setDeviceId($params->get('deviceId'))
            ->setLatitude($params->get('latitude'))
            ->setLongitude($params->get('longitude'))
            ->setEvent($event)
        ;

        $em->persist($dataPacket);
        $em->flush();

        return new Response('OK');
    }
}
