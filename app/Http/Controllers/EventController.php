<?php

namespace App\Http\Controllers;

use App\Core\Application\UseCases\EventFetcher;
use App\Http\Presenters\EventsPresenter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventController extends Controller {

    /**
     * @param EventFetcher $eventFetcher
     */
    public function __construct(
        private readonly EventFetcher $eventFetcher
    ) {}

    /**
     * @param string $eventId
     * @return JsonResponse
     */
    public function show(string $eventId): JsonResponse {
        $event = $this->eventFetcher->perform($eventId);

        return response()->json(EventsPresenter::present($event));
    }
}
