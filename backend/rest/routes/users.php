<?php
/**
 * @OA\Get(
 *     path="/users",
 *     tags={"Users"},
 *     summary="Get all users",
 *     @OA\Response(response=200, description="List of users")
 * )
 */
 Flight::route('GET /users', function () {
    Flight::json(Flight::get('user_service')->getAll());
});

/**
 * @OA\Get(
 *     path="/users/{id}",
 *     tags={"Users"},
 *     summary="Get user by ID",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="User details")
 * )
 */
Flight::route('GET /users/@id', function ($id) {
    Flight::json(Flight::get('user_service')->get($id));
});

/**
 * @OA\Post(
 *     path="/users",
 *     tags={"Users"},
 *     summary="Create a new user",
 *     @OA\RequestBody(@OA\JsonContent()),
 *     @OA\Response(response=200, description="Created")
 * )
 */
Flight::route('POST /users', function () {
    Flight::json(Flight::get('user_service')->add(Flight::request()->data->getData()));
});

/**
 * @OA\Put(
 *     path="/users/{id}",
 *     tags={"Users"},
 *     summary="Update a user",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(@OA\JsonContent()),
 *     @OA\Response(response=200, description="Updated")
 * )
 */
Flight::route('PUT /users/@id', function ($id) {
    Flight::json(Flight::get('user_service')->update($id, Flight::request()->data->getData()));
});

/**
 * @OA\Delete(
 *     path="/users/{id}",
 *     tags={"Users"},
 *     summary="Delete a user",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Deleted")
 * )
 */
Flight::route('DELETE /users/@id', function ($id) {
    Flight::json(Flight::get('user_service')->delete($id));
});