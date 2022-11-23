<?php

namespace App\Core\Domain\Entities;

interface IEntity {
    public function toDBArray(): array;
}