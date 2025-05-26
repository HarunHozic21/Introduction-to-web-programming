<?php
require_once __DIR__ . '/../../data/Roles.php';

/**
 * @OA\Get(
 * path="/users",
 * tags={"Users"},
 * summary="Get all users",
 * description="Retrieves a list of all users. Requires ADMIN role.",
 * security={
 * {"bearerAuth": {}}
 * },
 * @OA\Response(
 * response=200,
 * description="A list of users",
 * @OA\JsonContent(
 * type="array",
 * @OA\Items(ref="#/components/schemas/User")
 * )
 * ),
 * @OA\Response(
 * response=401,
 * description="Unauthorized - Token is missing or invalid"
 * ),
 * @OA\Response(
 * response=403,
 * description="Forbidden - User does not have the required ADMIN role"
 * )
 * )
 */
Flight::route('GET /users', function () {
    Flight::auth_middleware()->authorizeRoles(Roles::ADMIN);
    Flight::json(Flight::get('user_service')->getAll());
});

/**
 * @OA\Get(
 *     path="/users/{id}",
 *     tags={"Users"},
 *     summary="Get user by ID",
 *     security={{"ApiKeyAuth":{}}},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="User details"),
 *     @OA\Response(response=403, description="Forbidden")
 * )
 */
Flight::route('GET /users/@id', function ($id) {
    Flight::auth_middleware()->authorizeRoles(Roles::ADMIN);
    Flight::json(Flight::get('user_service')->get($id));
});

/**
 * @OA\Post(
 *     path="/users",
 *     summary="Add a new user.",
 *     tags={"Users"},
 *     security={{"ApiKeyAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 required={"first_name", "last_name", "email", "password_hash", "role"},
 *                 @OA\Property(property="first_name", type="string", example="John"),
 *                 @OA\Property(property="last_name", type="string", example="Doe"),
 *                 @OA\Property(property="email", type="string", example="john@example.com"),
 *                 @OA\Property(property="password_hash", type="string", example="$2y$10$abcdef..."),
 *                 @OA\Property(property="phone", type="string", example="+123456789"),
 *                 @OA\Property(property="address", type="string", example="123 Main St"),
 *                 @OA\Property(property="role", type="string", example="USER")
 *             )
 *         )
 *     ),
 *     @OA\Response(response=200, description="User has been added."),
 *     @OA\Response(response=403, description="Forbidden"),
 *     @OA\Response(response=500, description="Internal server error.")
 * )
 */
Flight::route('POST /users', function () {
    Flight::auth_middleware()->authorizeRoles(Roles::ADMIN);
    Flight::json(Flight::get('user_service')->add(Flight::request()->data->getData()));
});

/**
 * @OA\Put(
 *     path="/users/{id}",
 *     tags={"Users"},
 *     summary="Update a user",
 *     security={{"ApiKeyAuth":{}}},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="first_name", type="string"),
 *                 @OA\Property(property="last_name", type="string"),
 *                 @OA\Property(property="email", type="string"),
 *                 @OA\Property(property="phone", type="string"),
 *                 @OA\Property(property="address", type="string"),
 *                 @OA\Property(property="role", type="string")
 *             )
 *         )
 *     ),
 *     @OA\Response(response=200, description="Updated"),
 *     @OA\Response(response=403, description="Forbidden")
 * )
 */
Flight::route('PUT /users/@id', function ($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::get('user_service')->update($id, Flight::request()->data->getData()));
});

/**
 * @OA\Delete(
 *     path="/users/{id}",
 *     tags={"Users"},
 *     summary="Delete a user",
 *     security={{"ApiKeyAuth":{}}},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Deleted"),
 *     @OA\Response(response=403, description="Forbidden")
 * )
 */
Flight::route('DELETE /users/@id', function ($id) {
    Flight::auth_middleware()->authorizeRoles(Roles::ADMIN);
    Flight::json(Flight::get('user_service')->delete($id));
});

/**
 * @OA\Schema(
 * schema="User",
 * title="User",
 * required={"id", "email", "first_name", "last_name", "role"},
 * @OA\Property(property="id", type="integer", format="int64", description="User ID"),
 * @OA\Property(property="first_name", type="string", description="First name of the user"),
 * @OA\Property(property="last_name", type="string", description="Last name of the user"),
 * @OA\Property(property="email", type="string", format="email", description="Email of the user"),
 * @OA\Property(property="role", type="string", description="Role of the user", enum={"ADMIN", "USER"})
 * )
 */