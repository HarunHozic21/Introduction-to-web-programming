<?php
/**
 * @OA\PathItem(
 * path="/cart"
 * )
 */
/**
 * @OA\Get(
 *     path="/cart",
 *     summary="Get all cart items",
 *     @OA\Response(response="200", description="List of cart items")
 */
Flight::route('GET /cart', function (): void {
    Flight::json(Flight::get('cart_service')->getAll());
});

/**
 * @OA\Get(
 *     path="/cart/user/{userId}",
 *     summary="Get cart items by user ID",
 *     @OA\Parameter(
 *         name="userId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response="200", description="User's cart items")
 */
Flight::route('GET /cart/user/@userId', function ($userId): void {
    Flight::json(Flight::get('cart_service')->getCartByUser($userId));
});

/**
 * @OA\Get(
 *     path="/cart/item/{userId}/{productId}",
 *     summary="Get specific item from user's cart",
 *     @OA\Parameter(name="userId", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="productId", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response="200", description="Single cart item")
 */
Flight::route('GET /cart/item/@userId/@productId', function ($userId, $productId): void {
    Flight::json(Flight::get('cart_service')->getItem($userId, $productId));
});

/**
 * @OA\Post(
 *     path="/cart",
 *     summary="Add a new cart item",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id","product_id","quantity"},
 *             @OA\Property(property="user_id", type="integer"),
 *             @OA\Property(property="product_id", type="integer"),
 *             @OA\Property(property="quantity", type="integer")
 *         )
 *     ),
 *     @OA\Response(response="200", description="Item added")
 */
Flight::route('POST /cart', function (): void {
    $data = Flight::request()->data->getData();
    Flight::json(['added' => Flight::get('cart_service')->addItem($data)]);
});

/**
 * @OA\Put(
 *     path="/cart/item/{userId}/{productId}/{quantity}",
 *     summary="Update quantity of cart item",
 *     @OA\Parameter(name="userId", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="productId", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Parameter(name="quantity", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response="200", description="Quantity updated")
 */
Flight::route('PUT /cart/item/@userId/@productId/@quantity', function ($userId, $productId, $quantity): void {
    Flight::json(['updated' => Flight::get('cart_service')->updateQuantity($userId, $productId, $quantity)]);
});

/**
 * @OA\Delete(
 *     path="/cart/{id}",
 *     summary="Delete item from cart",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response="200", description="Item deleted")
 */
Flight::route('DELETE /cart/@id', function ($id): void {
    Flight::json(['deleted' => Flight::get('cart_service')->delete($id)]);
});
