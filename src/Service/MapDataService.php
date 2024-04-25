<?php

namespace App\Service;

use App\Entity\DataPacket;
use App\Entity\Event;
use Doctrine\Persistence\ManagerRegistry;

class MapDataService
{
    public function __construct(
        private ManagerRegistry $registry
    ) {
    }

    public function getEventData(Event $event) {
        $dpRepository = $this->registry->getRepository(DataPacket::class);
        $data = [];

        foreach ($event->getParticipants() as $p) {
            $pData = [
                'deviceId' => $p->getDeviceId(),
                'name' => $p->getName(),
                'surname' => $p->getSurname(),
                'data' => []
            ];

            $dataPackets = $dpRepository->findBy(
                ['event' => $event, 'deviceId' => $p->getDeviceId()],
                ['date' => 'DESC', 'id' => 'ASC']
            );
            foreach ($dataPackets as $packet) {
                $pData['data'][$packet->getDate()->format('Y-m-d H:i:s')] = [
                    $packet->getLatitude(),
                    $packet->getLongitude(),
                    $packet->getBatteryVoltage()
                ];
            }

            $data[] = $pData;
        }

        return $data;
    }

}
