<?php

namespace App\Core\Domain\Entities;

use Illuminate\Support\Str;

class ETimeOff implements IEntity {
    private string $id;

    public function __construct(
        private string $title,
        private string $eventId,
        private string $startTime,
        private string $endTime
    ) {
        $this->id = Str::uuid();
    }

    /**
     * @return string
     */
    public function getEventId(): string {
        return $this->eventId;
    }

    /**
     * @param string $eventId
     * @return self
     */
    public function setEventId(string $eventId): self {
        $this->eventId = $eventId;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string {
        return $this->title;
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
     * @param string $title
     * @return self
     */
    public function setTitle(string $title): self {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getStartTime(): string {
        return $this->startTime;
    }

    /**
     * @param string $startTime
     * @return self
     */
    public function setStartTime(string $startTime): self {
        $this->startTime = $startTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndTime(): string {
        return $this->endTime;
    }

    /**
     * @param string $endTime
     * @return self
     */
    public function setEndTime(string $endTime): self {
        $this->endTime = $endTime;
        return $this;
    }

    /**
     * @return array
     */
    public function toDBArray(): array {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'event_id' => $this->getEventId(),
            'start_time' => $this->getStartTime(),
            'end_time' => $this->getEndTime()
        ];
    }
}