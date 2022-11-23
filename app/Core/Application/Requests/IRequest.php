<?php

namespace App\Core\Application\Requests;

interface IRequest {
    /**
     * @return array
     */
    public function toArray(): array;

    /**
     * @return array
     */
    public function rules(): array;
}