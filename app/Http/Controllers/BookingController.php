<?php

namespace App\Http\Controllers;

use App\Core\Application\UseCases\BookingCreator;
use App\Core\Application\ValidationRequests\BookingCreatorValidationRequest;
use Exception;
use Illuminate\Http\Response;

class BookingController extends Controller {
    public function __construct(
        private readonly BookingCreator $bookingCreator
    ) {}

    /**
     * @param BookingCreatorValidationRequest $request
     * @return Response
     * @throws Exception
     */
    public function store(BookingCreatorValidationRequest $request): Response {
        $this->bookingCreator->perform($request->toDto());

        return response('', 201);
    }
}
