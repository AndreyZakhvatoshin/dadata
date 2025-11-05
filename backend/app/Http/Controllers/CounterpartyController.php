<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCounterpartyRequest;
use App\Http\Resources\CounterpartyResource;
use App\UseCases\AddCounterpartyCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CounterpartyController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();
        $counterparty = $user->counterparties()->paginate();

        return CounterpartyResource::collection($counterparty);
    }

    public function store(AddCounterpartyRequest $request, AddCounterpartyCase $addCounterpartyCase): JsonResponse
    {
        $data = $request->toData();
        $user = $request->user();

        try {
            $counterparty = $addCounterpartyCase($user, $data);
        } catch (\Exception $e) {
            Log::warning($e->getMessage());
            return response()->json(['message' => $e->getMessage()]);
        }

        return response()->json(new CounterpartyResource($counterparty), Response::HTTP_CREATED);
    }
}
