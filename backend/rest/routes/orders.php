<?php
require_once __DIR__ . '/../../data/Roles.php';
/**
 * @OA\Get(
 *     path="/orders",
 *     tags={"Orders"},
 *     summary="Get all orders",
 *     @OA\Response(response=200, description="List of orders")
 * )
 */
Flight::route('GET /orders', function () {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    Flight::json(Flight::order_service()->get_all());
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
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::order_service()->get_by_id($id));
});

/**
 * @OA\Post(
 *     path="/orders",
 *     summary="Add a new order.",
 *     tags={"Orders"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 required={"user_id", "order_status", "total_price"},
 *                 @OA\Property(property="user_id", type="integer", example=1),
 *                 @OA\Property(property="order_status", type="string", example="pending"),
 *                 @OA\Property(property="total_price", type="number", format="float", example=299.99)
 *             )
 *         )
 *     ),
 *     @OA\Response(response=200, description="Order has been added."),
 *     @OA\Response(response=500, description="Internal server error.")
 * )
 */

Flight::route('POST /orders', function () {
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::order_service()->create_order($data));
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
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::order_service()->update($id, Flight::request()->data->getData()));
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
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    Flight::json(Flight::order_service()->delete($id));
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
