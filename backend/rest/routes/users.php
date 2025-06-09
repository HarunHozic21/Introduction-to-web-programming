<?php
require_once __DIR__ . '/../../data/Roles.php';

/**
 * @OA\Get(
 *     path="/users",
 *     tags={"Users"},
 *     summary="Get all users",
 *     description="Retrieves a list of all users. Requires ADMIN role.",
 *     security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Response(
 *         response=200,
 *         description="A list of users",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/User")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - Token is missing or invalid"
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden - User does not have the required ADMIN role"
 *     )
 * )
 */
Flight::route('GET /users', function () {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    Flight::json(Flight::user_service()->getAll());
});

/**
 * @OA\Get(
 *     path="/users/{id}",
 *     tags={"Users"},
 *     summary="Get a single user by ID",
 *     description="Retrieves the details of a specific user. Requires ADMIN role.",
 *     security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="The ID of the user to retrieve.",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User details",
 *         @OA\JsonContent(ref="#/components/schemas/User")
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - Token is missing or invalid"
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden - User does not have the required ADMIN role"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Not Found - No user found with the specified ID"
 *     )
 * )
 */
Flight::route('GET /users/@id', function ($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    Flight::json(Flight::user_service()->get($id));
});

/**
 * @OA\Post(
 *     path="/users",
 *     summary="Add a new user",
 *     description="Creates a new user account. Typically used for registration or by an admin. Note: This route uses the auth_service to handle password hashing.",
 *     tags={"Users"},
 *     security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\RequestBody(
 *         required=true,
 *         description="Data for the new user.",
 *         @OA\JsonContent(
 *             required={"first_name", "last_name", "email", "password", "role"},
 *             @OA\Property(property="first_name", type="string", example="John"),
 *             @OA\Property(property="last_name", type="string", example="Doe"),
 *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="strongpassword123"),
 *             @OA\Property(property="role", type="string", example="USER", enum={"USER", "ADMIN"})
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User created successfully",
 *         @OA\JsonContent(ref="#/components/schemas/User")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad Request - Invalid input data or email already exists"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - Token is missing or invalid"
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden - User does not have the required ADMIN role"
 *     )
 * )
 */
Flight::route('POST /users', function () {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::auth_service()->register($data));
});

/**
 * @OA\Put(
 *     path="/users/{id}",
 *     tags={"Users"},
 *     summary="Update an existing user",
 *     description="Updates the details of an existing user. An admin can update any user. A regular user can only update their own profile (requires additional logic in the service layer).",
 *     security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="The ID of the user to update.",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         description="Data to update for the user.",
 *         @OA\JsonContent(
 *             @OA\Property(property="first_name", type="string", example="John"),
 *             @OA\Property(property="last_name", type="string", example="Doe"),
 *             @OA\Property(property="phone", type="string", example="+15551234567")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User updated successfully",
 *         @OA\JsonContent(ref="#/components/schemas/User")
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - Token is missing or invalid"
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden - User does not have permission to update this profile"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Not Found - No user found with the specified ID"
 *     )
 * )
 */
Flight::route('PUT /users/@id', function ($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::user_service()->update($id, $data));
});

/**
 * @OA\Delete(
 *     path="/users/{id}",
 *     tags={"Users"},
 *     summary="Delete a user",
 *     description="Deletes a user from the database. Requires ADMIN role.",
 *     security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="The ID of the user to delete.",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - Token is missing or invalid"
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden - User does not have the required ADMIN role"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Not Found - No user found with the specified ID"
 *     )
 * )
 */
Flight::route('DELETE /users/@id', function ($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    Flight::json(Flight::user_service()->delete($id));
});