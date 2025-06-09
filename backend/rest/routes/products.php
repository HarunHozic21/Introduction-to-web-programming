<?php
require_once __DIR__ . '/../../data/Roles.php';
/**
 * @OA\Get(
 *     path="/products",
 *     tags={"Products"},
 *     summary="Get all products",
 *     @OA\Response(response=200, description="List of products")
 * )
 */
Flight::route('GET /products', function () {

    $products = Flight::product_service()->getAll();
    Flight::json($products);
});
/**
 * @OA\Get(
 *     path="/products/available",
 *     tags={"Products"},
 *     summary="Get available products",
 *     @OA\Response(response=200, description="List of available products")
 * )
 */
Flight::route('GET /products/available', function () {

    $products = Flight::product_service()->getAllAvailable();
    Flight::json($products);
});
/**
 * @OA\Get(
 *     path="/products/{id}",
 *     tags={"Products"},
 *     summary="Get product by ID",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Product details"),
 *     @OA\Response(response=404, description="Not found")
 * )
 */
Flight::route('GET /products/@id', function ($id) {

    $product = Flight::product_service()->get($id);
    $product ? Flight::json($product) : Flight::halt(404, 'Not found');
});
/**
 * @OA\Post(
 *     path="/products",
 *     summary="Add a new product.",
 *     tags={"Products"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 required={"name", "brand", "price", "stock"},
 *                 @OA\Property(property="name", type="string", example="Air Max 90"),
 *                 @OA\Property(property="brand", type="string", example="Nike"),
 *                 @OA\Property(property="description", type="string", example="Classic sneakers"),
 *                 @OA\Property(property="price", type="number", format="float", example=129.99),
 *                 @OA\Property(property="stock", type="integer", example=50),
 *                 @OA\Property(property="image_url", type="string", example="https://example.com/image.jpg")
 *             )
 *         )
 *     ),
 *     @OA\Response(response=200, description="Product has been added."),
 *     @OA\Response(response=500, description="Internal server error.")
 * )
 */

Flight::route('POST /products', function () {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);    
    $data = Flight::request()->data->getData();
    try {
        $created = Flight::product_service()->create($data);
        Flight::json(['message' => 'Created', 'product' => $created]);
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

/**
 * @OA\Put(
 *     path="/products/{id}",
 *     tags={"Products"},
 *     summary="Update a product",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(@OA\JsonContent()),
 *     @OA\Response(response=200, description="Updated")
 * )
 */
Flight::route('PUT /products/@id', function ($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);    
    $data = Flight::request()->data->getData();
    $updated = Flight::product_service()->update($id, $data);
    Flight::json(['updated' => $updated]);
});
/**
 * @OA\Delete(
 *     path="/products/{id}",
 *     tags={"Products"},
 *     summary="Delete a product",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Deleted")
 * )
 */
Flight::route('DELETE /products/@id', function ($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);    
    Flight::product_service()->delete($id);
    Flight::json(["message" => "Product deleted"]);
});