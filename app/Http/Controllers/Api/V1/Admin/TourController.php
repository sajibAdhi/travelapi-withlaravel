<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TourRequest;
use App\Http\Resources\TourResource;
use App\Models\Travel;

/**
 * @group Admin endpoint
 */
class TourController extends Controller
{
    /**
     * POST Tour
     *
     * Create a new Tour record.
     *
     * @authenticated
     *
     * @response {"data": {"id": 1, "name": "Tour 1", "starting_date": "2021-01-01", "ending_date": "2021-01-10", "price": "1000.00"}}
     * @response 422 {"message": "The given data was invalid.", "errors": {"name": ["The name has already been taken."]}}
     */
    public function store(Travel $travel, TourRequest $request): TourResource
    {
        $tour = $travel->tours()->create($request->validated());

        return new TourResource($tour);
    }
}
