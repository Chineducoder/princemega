<?php

namespace App\Http\Controllers;

use App\Models\Hub;
use App\Support\Storefront;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShopContextController extends Controller
{
    public function setMode(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'mode' => ['required', 'in:retail,wholesale'],
        ]);

        $mode = Storefront::setMode($validated['mode']);

        return response()->json([
            'mode' => $mode,
            'message' => $mode === 'wholesale'
                ? 'Wholesale pricing engaged'
                : 'Retail pricing engaged',
        ]);
    }

    public function setHub(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'hub_id' => ['nullable', 'integer'],
        ]);

        $hubId = $validated['hub_id'] ?? null;

        if ($hubId !== null) {
            $hub = Hub::find($hubId);
            abort_if(! $hub, 404, 'Unknown hub');
            abort_if($hub->slug === 'all', 422, 'All Hubs is not a physical hub');
        }

        Storefront::setHub($hubId);

        return response()->json([
            'hub_id' => $hubId,
            'message' => $hubId
                ? 'Now shopping '.Hub::find($hubId)->short_code
                : 'Showing network-wide availability',
        ]);
    }
}
