<?php
require_once __DIR__ . '/../../data/Roles.php';
/**
 * @OA\Get(
 *     path="/order_items",
 *     tags={"OrderItems"},
 *     summary="Get all order items",
 *     @OA\Response(response=200, description="List of order items")
 * )
 */
Flight::route('GET /order-items', function () {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::orderItemService()->get_all());
});

/**
 * @OA\Get(
 *     path="/order_items/{id}",
 *     tags={"OrderItems"},
 *     summary="Get order item by ID",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Order item details")
 * )
 */
Flight::route('GET /order_items/@id', function ($id) {
    Flight::json(Flight::get('orderitems_service')->get($id));
});

/**
 * @OA\Post(
 *     path="/order_items",
 *     summary="Add a new order item.",
 *     tags={"OrderItems"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 required={"order_id", "product_id", "quantity", "subtotal"},
 *                 @OA\Property(property="order_id", type="integer", example=1),
 *                 @OA\Property(property="product_id", type="integer", example=2),
 *                 @OA\Property(property="quantity", type="integer", example=3),
 *                 @OA\Property(property="subtotal", type="number", format="float", example=149.97)
 *             )
 *         )
 *     ),
 *     @OA\Response(response=200, description="Order item has been added."),
 *     @OA\Response(response=500, description="Internal server error.")
 * )
 */
Flight::route('POST /order_items', function () {
    Flight::json(Flight::get('orderitems_service')->add(Flight::request()->data->getData()));
});

/**
 * @OA\Put(
 *     path="/order_items/{id}",
 *     tags={"OrderItems"},
 *     summary="Update order item",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/OrderItem")
 *     ),
 *     @OA\Response(response=200, description="Updated")
 * )
 */
Flight::route('PUT /order_items/@id', function ($id) {
    Flight::json(Flight::get('orderitems_service')->update($id, Flight::request()->data->getData()));
});

/**
 * @OA\Delete(
 *     path="/order_items/{id}",
 *     tags={"OrderItems"},
 *     summary="Delete order item",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Deleted")
 * )
 */
Flight::route('DELETE /order_items/@id', function ($id) {

    Flight::json(Flight::get('orderitems_service')->delete($id));
});

/**
 * @OA\Schema(
 *     schema="OrderItem",
 *     required={"order_id", "product_id", "quantity"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="order_id", type="integer"),
 *     @OA\Property(property="product_id", type="integer"),
 *     @OA\Property(property="quantity", type="integer")
 * )
 */
