<?php

namespace App\Http\Controllers\Api\V1\Editor;

use App\Http\Controllers\Controller;
use App\Http\Requests\TravelRequest;
use App\Http\Resources\TravelResource;
use App\Models\Travel;

class TravelController extends Controller
{
    public function update(Travel $travel, TravelRequest $request): TravelResource
    {
        $travel = $travel->update($request->validated());

        return new TravelResource($travel);
    }
}
