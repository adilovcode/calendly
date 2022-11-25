<?php

namespace App\Http\Controllers;

use App\Core\Application\UseCases\BookingCreator;
use App\Core\Application\ValidationRequests\BookingCreatorValidationRequest;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

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
        DB::beginTransaction();
        try {

            $this->bookingCreator->perform($request->toDto());
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();

        return response('', 201);
    }
}
