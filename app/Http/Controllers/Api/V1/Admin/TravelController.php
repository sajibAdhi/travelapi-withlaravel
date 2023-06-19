<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TravelRequest;
use App\Http\Resources\TravelResource;
use App\Models\Travel;

/**
 * @group Admin endpoint
 */
class TravelController extends Controller
{
    /**
     * POST Travel
     *
     * Create a new Travel record.
     *
     * @authenticated
     *
     * @response {"data": {"id": 123b13bn-2342-2342-2342-234234234234, "name": "Travel 1", "slug": "travel-1", "description": "Description of travel 1", "number_of_days": 5, "number_of_nights": 4}
     * @response 422 {"message": "The given data was invalid.", "errors": {"name": ["The name has already been taken."]}}
     */
    public function store(TravelRequest $request): TravelResource
    {
        $travel = Travel::create($request->validated());

        return new TravelResource($travel);
    }

    /**
     * PUT Travel
     *
     * Update an existing Travel record.
     *
     * @authenticated
     *
     * @response {"data": {"id": 123b13bn-2342-2342-2342-234234234234, "name": "Travel 2", "slug": "travel-1", "description": "Description of travel 1", "number_of_days": 5, "number_of_nights": 4}
     * @response 422 {"message": "The given data was invalid.", "errors": {"name": ["The name has already been taken."]}}
     */
    public function update(Travel $travel, TravelRequest $request): TravelResource
    {
        $travel->update($request->validated());

        return new TravelResource($travel);
    }
}
