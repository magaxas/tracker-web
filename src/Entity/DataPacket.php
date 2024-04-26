<?php

namespace App\Entity;

use App\Repository\DataPacketRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DataPacketRepository::class)]
class DataPacket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $deviceId = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 9, scale: 6)]
    private ?string $latitude = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 9, scale: 6)]
    private ?string $longitude = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 4)]
    private ?string $batteryVoltage = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 4, nullable: true)]
    private ?string $accelX = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 4, nullable: true)]
    private ?string $accelY = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 4, nullable: true)]
    private ?string $accelZ = null;

    #[ORM\Column(nullable: true)]
    private ?int $satNum = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeviceId(): ?int
    {
        return $this->deviceId;
    }

    public function setDeviceId(int $deviceId): static
    {
        $this->deviceId = $deviceId;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getBatteryVoltage(): ?string
    {
        return $this->batteryVoltage;
    }

    public function setBatteryVoltage(?string $batteryVoltage): static
    {
        $this->batteryVoltage = $batteryVoltage;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): static
    {
        $this->event = $event;

        return $this;
    }

    public function getAccelX(): ?string
    {
        return $this->accelX;
    }

    public function setAccelX(?string $accelX): static
    {
        $this->accelX = $accelX;

        return $this;
    }

    public function getAccelY(): ?string
    {
        return $this->accelY;
    }

    public function setAccelY(?string $accelY): static
    {
        $this->accelY = $accelY;

        return $this;
    }

    public function getAccelZ(): ?string
    {
        return $this->accelZ;
    }

    public function setAccelZ(?string $accelZ): static
    {
        $this->accelZ = $accelZ;

        return $this;
    }

    public function getSatNum(): ?int
    {
        return $this->satNum;
    }

    public function setSatNum(?int $satNum): static
    {
        $this->satNum = $satNum;

        return $this;
    }

}
