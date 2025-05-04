<?php
/**
 * @OA\Get(
 *     path="/products",
 *     tags={"Products"},
 *     summary="Get all products",
 *     @OA\Response(response=200, description="List of products")
 * )
 */
Flight::route('GET /products', function () {

    $products = Flight::get('product_service')->getAll();
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

    $products = Flight::get('product_service')->getAllAvailable();
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

    $product = Flight::get('product_service')->get($id);
    $product ? Flight::json($product) : Flight::halt(404, 'Not found');
});
/**
 * @OA\Post(
 *     path="/products",
 *     tags={"Products"},
 *     summary="Create a new product",
 *     @OA\RequestBody(@OA\JsonContent()),
 *     @OA\Response(response=200, description="Created")
 * )
 */
Flight::route('POST /products', function () {
    $data = Flight::request()->data->getData();
    try {
        $created = Flight::get('product_service')->create($data);
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
    $data = Flight::request()->data->getData();
    $updated = Flight::get('product_service')->update($id, $data);
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
    $deleted = Flight::get('product_service')->delete($id);
    Flight::json(['deleted' => $deleted]);
});