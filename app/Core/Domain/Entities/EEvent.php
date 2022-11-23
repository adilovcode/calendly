<?php

namespace App\Core\Domain\Entities;

use App\Core\Domain\ValueObjects\Minute;
use Illuminate\Support\Str;

class EEvent implements IEntity {
    private string $id;

    public function __construct(
        private string $name,
        private string $description,
        private Minute $duration,
        private Minute $bufferTime,
        private string $endDate,
        private int $acceptPerSlot
    ) {
        $this->id = Str::uuid();
    }

    /**
     * @return int
     */
    public function getAcceptPerSlot(): int {
        return $this->acceptPerSlot;
    }

    /**
     * @param int $acceptPerSlot
     * @return self
     */
    public function setAcceptPerSlot(int $acceptPerSlot): self {
        $this->acceptPerSlot = $acceptPerSlot;
        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string {
        return $this->id;
    }

    /**
     * @param string $id
     * @return self
     */
    public function setId(string $id): self {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name): self {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string {
        return $this->description;
    }

    /**
     * @param string $description
     * @return self
     */
    public function setDescription(string $description): self {
        $this->description = $description;
        return $this;
    }

    /**
     * @return Minute
     */
    public function getDuration(): Minute {
        return $this->duration;
    }

    /**
     * @param Minute $duration
     * @return self
     */
    public function setDuration(Minute $duration): self {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @return Minute
     */
    public function getBufferTime(): Minute {
        return $this->bufferTime;
    }

    /**
     * @param Minute $bufferTime
     * @return self
     */
    public function setBufferTime(Minute $bufferTime): self {
        $this->bufferTime = $bufferTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndDate(): string {
        return $this->endDate;
    }

    /**
     * @param string $endDate
     * @return self
     */
    public function setEndDate(string $endDate): self {
        $this->endDate = $endDate;
        return $this;
    }

    public function getTotalDuration(): Minute {
        return Minute::from($this->getDuration()->getValue() + $this->getBufferTime()->getValue());
    }

    /**
     * @return array
     */
    public function toDBArray(): array {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'duration' => $this->getDuration()->getValue(),
            'buffer_time' => $this->getBufferTime()->getValue(),
            'end_date' => $this->getEndDate(),
            'accept_per_slot' => $this->getAcceptPerSlot()
        ];
    }
}