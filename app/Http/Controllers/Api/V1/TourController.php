<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ToursListRequest;
use App\Http\Resources\TourResource;
use App\Models\Travel;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Public endpoint
 */
class TourController extends Controller
{
    /**
     * GET Travel Tours
     *
     * Get a list of Travel tours.
     *
     * @queryParam priceFrom integer The minimum price of the tour. Example: 1000
     * @queryParam priceTo integer The maximum price of the tour. Example: 2000
     * @queryParam dateFrom date The starting date of the tour. Example: 2021-01-01
     * @queryParam dateTo date The ending date of the tour. Example: 2021-01-10
     * @queryParam sortBy string The field to sort by. Example: price
     * @queryParam sortOrder string The order to sort by. Example: asc
     *
     * @response {"data": [{"id": 1, "name": "Tour 1", "starting_date": "2021-01-01", "ending_date": "2021-01-10", "price": "1000.00"}], "links": {"first": "http://localhost:8000/api/v1/travels/1/tours?page=1", "last": "http://localhost:8000/api/v1/travels/1/tours?page=1", "prev": null, "next": null}, "meta": {"current_page": 1, "from": 1, "last_page": 1, "links": [{"url": null, "label": "&laquo; Previous", "active": false}, {"url": "http://localhost:8000/api/v1/travels/1/tours?page=1", "label": 1, "active": true}, {"url": null, "label": "Next &raquo;", "active": false}], "path": "http://localhost:8000/api/v1/travels/1/tours", "per_page": 15, "to": 1, "total": 1}}
     * @response 422 {"message": "The given data was invalid.", "errors": {"priceFrom": ["The price from must be an integer."], "priceTo": ["The price to must be an integer."], "dateFrom": ["The date from is not a valid date."], "dateTo": ["The date to is not a valid date."], "sortBy": ["The selected sortBy is invalid."], "sortOrder": ["The selected sortOrder is invalid."]}}
     */
    public function index(Travel $travel, ToursListRequest $request): AnonymousResourceCollection
    {
        $tours = $travel->tours()
            ->when($request->priceFrom, function ($query) use ($request) {
                $query->where('price', '>=', $request->priceFrom * 100);
            })
            ->when($request->priceTo, function ($query) use ($request) {
                $query->where('price', '<=', $request->priceTo * 100);
            })
            ->when($request->dateFrom, function ($query) use ($request) {
                $query->where('starting_date', '>=', $request->dateFrom);
            })
            ->when($request->dateTo, function ($query) use ($request) {
                $query->where('starting_date', '<=', $request->dateTo);
            })
            ->when($request->sortBy && $request->sortOrder, function ($query) use ($request) {
                $query->orderBy($request->sortBy, $request->sortOrder);
            })
            ->orderBy('starting_date')
            ->paginate();

        return TourResource::collection($tours);
    }
}
