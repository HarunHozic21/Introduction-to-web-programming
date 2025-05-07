<?php

/**
 * @OA\Get(
 *     path="/orders",
 *     tags={"Orders"},
 *     summary="Get all orders",
 *     @OA\Response(response=200, description="List of orders")
 * )
 */
 Flight::route('GET /orders', function () {
    Flight::json(Flight::get('order_service')->getAll());
});

/**
 * @OA\Get(
 *     path="/orders/{id}",
 *     tags={"Orders"},
 *     summary="Get order by ID",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Order details")
 * )
 */
Flight::route('GET /orders/@id', function ($id) {
    Flight::json(Flight::get('order_service')->get($id));
});

/**
 * @OA\Post(
 *     path="/orders",
 *     tags={"Orders"},
 *     summary="Create a new order",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/Order")
 *     ),
 *     @OA\Response(response=200, description="Order created")
 * )
 */
Flight::route('POST /orders', function () {
    Flight::json(Flight::get('order_service')->add(Flight::request()->data->getData()));
});

/**
 * @OA\Put(
 *     path="/orders/{id}",
 *     tags={"Orders"},
 *     summary="Update an order",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/Order")
 *     ),
 *     @OA\Response(response=200, description="Order updated")
 * )
 */
Flight::route('PUT /orders/@id', function ($id) {
    Flight::json(Flight::get('order_service')->update($id, Flight::request()->data->getData()));
});

/**
 * @OA\Delete(
 *     path="/orders/{id}",
 *     tags={"Orders"},
 *     summary="Delete an order",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Order deleted")
 * )
 */
Flight::route('DELETE /orders/@id', function ($id) {
    Flight::json(Flight::get('order_service')->delete($id));
});

/**
 * @OA\Schema(
 *     schema="Order",
 *     required={"user_id", "status"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="status", type="string", example="pending"),
 *     @OA\Property(property="created_at", type="string", format="date-time")
 * )
 */
