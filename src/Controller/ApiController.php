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
        $params = $request->request;

        if (!$params->has('authorizationToken') || !$params->has('batteryVoltage') || !$params->has('latitude') ||
            !$params->has('longitude') || !$params->has('eventId') || !$params->has('deviceId')) {
            return new Response('Wrong parameters');
        }

        if ($this->getParameter('app.api_key') !== $params->get('authorizationToken')) {
            return new Response('Unauthorized');
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
        if ($params->has('accelX')) $dataPacket->setAccelX($params->get('accelX'));
        if ($params->has('accelY')) $dataPacket->setAccelY($params->get('accelY'));
        if ($params->has('accelZ')) $dataPacket->setAccelZ($params->get('accelZ'));
        if ($params->has('satNum')) $dataPacket->setSatNum((int)$params->get('satNum'));

        $em->persist($dataPacket);
        $em->flush();
        return new Response('OK');
    }

    #[Route('/api/dataPackets', name: 'app_api_create_data_packets', methods: ['post'])]
    public function createDataPackets(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $params = $request->request;

        if (!$params->has('authorizationToken') || !$params->has('batteryVoltages') || !$params->has('latitudes') ||
            !$params->has('longitudes') || !$params->has('eventId') || !$params->has('deviceId') ||
            !$params->has('dataPacketsAmount') || !$params->has('dates')) {
            return new Response('Wrong parameters');
        }

        if ($this->getParameter('app.api_key') !== $params->get('authorizationToken')) {
            return new Response('Unauthorized');
        }

        $em = $managerRegistry->getManager();
        $event = $em->getRepository(Event::class)->findOneBy([
            'id' => $params->get('eventId') //TODO: check if event is live
        ]);
        if ($event == null) {
            return new Response('Event not found');
        }

        $dataPacketsAmount = $params->get('dataPacketsAmount');
        $latitudes = explode(';', $params->get('latitudes'));
        $longitudes = explode(';', $params->get('longitudes'));
        $batteryVoltages = explode(';', $params->get('batteryVoltages'));
        $dates = explode(';', $params->get('dates'));

        if ($params->has('accelX')) $accelX = explode(';', $params->get('accelX'));
        if ($params->has('accelY')) $accelY = explode(';', $params->get('accelY'));
        if ($params->has('accelZ')) $accelZ = explode(';', $params->get('accelZ'));
        if ($params->has('satNum')) $satNum = explode(';', $params->get('satNum'));

        if (count($latitudes) != $dataPacketsAmount || count($longitudes) != $dataPacketsAmount ||
            count($dates) != $dataPacketsAmount || count($batteryVoltages) != $dataPacketsAmount) {
            return new Response('DataPackets amount mismatch');
        }

        for ($i = 0; $i < $dataPacketsAmount; $i++) {
            $dataPacket = new DataPacket();
            $dataPacket
                ->setDate((new \DateTime($dates[$i]))->setTimezone(new \DateTimeZone('Europe/Vilnius')))
                ->setBatteryVoltage($batteryVoltages[$i])
                ->setLatitude($latitudes[$i])
                ->setLongitude($longitudes[$i])
                ->setDeviceId($params->get('deviceId'))
                ->setEvent($event)
            ;
            if (isset($accelX)) $dataPacket->setAccelX($accelX[$i]);
            if (isset($accelY)) $dataPacket->setAccelY($accelY[$i]);
            if (isset($accelZ)) $dataPacket->setAccelZ($accelZ[$i]);
            if (isset($satNum)) $dataPacket->setSatNum($satNum[$i]);

            $em->persist($dataPacket);
            $em->flush();
        }

        return new Response('OK');
    }
}
