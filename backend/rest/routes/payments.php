<?php
require_once __DIR__ . '/../../data/Roles.php';
/**
 * @OA\Get(
 *     path="/payments",
 *     tags={"Payments"},
 *     summary="Get all payments",
 *     @OA\Response(response=200, description="List of payments")
 * )
 */
 Flight::route('GET /payments', function () {
    Flight::json(Flight::payment_service()->getAll());
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
 *     summary="Add a new payment.",
 *     tags={"Payments"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 required={"order_id", "user_id", "payment_method", "payment_status"},
 *                 @OA\Property(property="order_id", type="integer", example=1),
 *                 @OA\Property(property="user_id", type="integer", example=1),
 *                 @OA\Property(property="payment_method", type="string", example="credit_card"),
 *                 @OA\Property(property="payment_status", type="string", example="paid")
 *             )
 *         )
 *     ),
 *     @OA\Response(response=200, description="Payment has been recorded."),
 *     @OA\Response(response=500, description="Internal server error.")
 * )
 */
Flight::route('POST /payments', function () {
    Flight::json(Flight::payment_service()->add(Flight::request()->data->getData()));
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
    Flight::json(Flight::payment_service()->update($id, Flight::request()->data->getData()));
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
    Flight::json(Flight::payment_service()->delete($id));
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
