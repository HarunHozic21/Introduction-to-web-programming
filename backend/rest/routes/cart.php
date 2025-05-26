<?php
require_once __DIR__ . '/../../data/Roles.php';
/**
 * @OA\Get(
 *     path="/cart",
 *     summary="Get all cart items",
 *     tags={"Cart"},
 *     @OA\Response(
 *         response="200",
 *         description="List of cart items",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="id", type="integer"),
 *                 @OA\Property(property="user_id", type="integer"),
 *                 @OA\Property(property="product_id", type="integer"),
 *                 @OA\Property(property="quantity", type="integer")
 *             )
 *         )
 *     )
 * )
 */
Flight::route('GET /cart', function (): void {
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    $userId = Flight::auth_middleware()->getUserId();
    Flight::json(Flight::cartService()->get_cart($userId));
});

/**
 * @OA\Get(
 *     path="/cart/user/{userId}",
 *     summary="Get cart items by user ID",
 *     tags={"Cart"},
 *     @OA\Parameter(
 *         name="userId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="User's cart items",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="id", type="integer"),
 *                 @OA\Property(property="user_id", type="integer"),
 *                 @OA\Property(property="product_id", type="integer"),
 *                 @OA\Property(property="quantity", type="integer")
 *             )
 *         )
 *     )
 * )
 */
Flight::route('GET /cart/user/@userId', function ($userId): void {
    Flight::json(Flight::get('cart_service')->getCartByUser($userId));
});

/**
 * @OA\Get(
 *     path="/cart/item/{userId}/{productId}",
 *     summary="Get specific item from user's cart",
 *     tags={"Cart"},
 *     @OA\Parameter(name="userId", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="productId", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(
 *         response="200",
 *         description="Single cart item",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="id", type="integer"),
 *             @OA\Property(property="user_id", type="integer"),
 *             @OA\Property(property="product_id", type="integer"),
 *             @OA\Property(property="quantity", type="integer")
 *         )
 *     )
 * )
 */
Flight::route('GET /cart/item/@userId/@productId', function ($userId, $productId): void {
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::get('cart_service')->getItem($userId, $productId));
});


/**
 * @OA\Post(
 *     path="/cart",
 *     summary="Add item to cart.",
 *     tags={"Cart"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 required={"user_id", "product_id", "quantity"},
 *                 @OA\Property(property="user_id", type="integer", example=1),
 *                 @OA\Property(property="product_id", type="integer", example=2),
 *                 @OA\Property(property="quantity", type="integer", example=2)
 *             )
 *         )
 *     ),
 *     @OA\Response(response=200, description="Item added to cart."),
 *     @OA\Response(response=500, description="Internal server error.")
 * )
 */
Flight::route('POST /cart', function (): void {
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    $userId = Flight::auth_middleware()->getUserId();
    $data = Flight::request()->data->getData();
    Flight::json(Flight::cartService()->add_to_cart($userId, $data));
});

/**
 * @OA\Put(
 *     path="/cart/item/{userId}/{productId}/{quantity}",
 *     summary="Update quantity of cart item",
 *     tags={"Cart"},
 *     @OA\Parameter(name="userId", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="productId", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="quantity", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(
 *         response="200",
 *         description="Quantity updated",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="updated", type="boolean")
 *         )
 *     )
 * )
 */
Flight::route('PUT /cart/item/@userId/@productId/@quantity', function ($userId, $productId, $quantity): void {
    Flight::json(['updated' => Flight::get('cart_service')->updateQuantity($userId, $productId, $quantity)]);
});

/**
 * @OA\Delete(
 *     path="/cart/{id}",
 *     summary="Delete item from cart",
 *     tags={"Cart"},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(
 *         response="200",
 *         description="Item deleted",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="deleted", type="boolean")
 *         )
 *     )
 * )
 */
Flight::route('DELETE /cart/@id', function ($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::cartService()->delete_from_cart($id);
    Flight::json(["message" => "Item removed from cart"]);
});