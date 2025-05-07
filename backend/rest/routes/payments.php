<?php
/**
 * @OA\Get(
 *     path="/payments",
 *     tags={"Payments"},
 *     summary="Get all payments",
 *     @OA\Response(response=200, description="List of payments")
 * )
 */
 Flight::route('GET /payments', function () {
    Flight::json(Flight::get('payment_service')->getAll());
});

/**
 * @OA\Get(
 *     path="/payments/{id}",
 *     tags={"Payments"},
 *     summary="Get payment by ID",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Payment details")
 * )
 */
Flight::route('GET /payments/@id', function ($id) {
    Flight::json(Flight::get('payment_service')->get($id));
});

/**
 * @OA\Post(
 *     path="/payments",
 *     tags={"Payments"},
 *     summary="Create a payment",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/Payment")
 *     ),
 *     @OA\Response(response=200, description="Payment created")
 * )
 */
Flight::route('POST /payments', function () {
    Flight::json(Flight::get('payment_service')->add(Flight::request()->data->getData()));
});

/**
 * @OA\Put(
 *     path="/payments/{id}",
 *     tags={"Payments"},
 *     summary="Update a payment",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/Payment")
 *     ),
 *     @OA\Response(response=200, description="Payment updated")
 * )
 */
Flight::route('PUT /payments/@id', function ($id) {
    Flight::json(Flight::get('payment_service')->update($id, Flight::request()->data->getData()));
});

/**
 * @OA\Delete(
 *     path="/payments/{id}",
 *     tags={"Payments"},
 *     summary="Delete a payment",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Payment deleted")
 * )
 */
Flight::route('DELETE /payments/@id', function ($id) {
    Flight::json(Flight::get('payment_service')->delete($id));
});

/**
 * @OA\Schema(
 *     schema="Payment",
 *     required={"order_id", "amount", "status"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="order_id", type="integer"),
 *     @OA\Property(property="amount", type="number", format="float"),
 *     @OA\Property(property="status", type="string", example="completed"),
 *     @OA\Property(property="created_at", type="string", format="date-time")
 * )
 */
