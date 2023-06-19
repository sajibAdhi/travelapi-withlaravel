<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TravelResource;
use App\Models\Travel;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Public endpoint
 */
class TravelController extends Controller
{
    /**
     * GET Travel
     *
     * Get a list of public Travel records.
     *
     * @response {"data": [{"id": 123b13bn-2342-2342-2342-234234234234, "name": "Travel 1", "slug": "travel-1", "description": "Description of travel 1", "number_of_days": 5, "number_of_nights": 4}, {"id": 123b13bn-2342-2342-2342-234234234234, "name": "Travel 2", "slug": "travel-2", "description": "Description of travel 2", "number_of_days": 5, "number_of_nights": 4}]}
     */
    public function index(): AnonymousResourceCollection
    {
        $travels = Travel::where('is_public', true)->paginate();

        return TravelResource::collection($travels);
    }
}
