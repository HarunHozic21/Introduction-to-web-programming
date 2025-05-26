<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::group('/auth', function() {
    /**
     * @OA\Post(
     *     path="/auth/register",
     *     summary="Register new user.",
     *     description="Add a new user to the database.",
     *     tags={"auth"},
     *     security={
     *         {"ApiKey": {}}
     *     },
     *     @OA\RequestBody(
     *         description="Add new user",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"password", "email"},
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     example="some_password",
     *                     description="User password"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     example="demo@gmail.com",
     *                     description="User email"
     *                 ),
     *                 @OA\Property(
     *                     property="role",
     *                     type="string",
     *                     example="user",
     *                     description="roles"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User has been added."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error."
     *     )
     * )
     */
    Flight::route("POST /register", function () {
        // Get JSON data from request body
        $json_data = json_decode(Flight::request()->getBody(), true);
        
        // Fallback to form data if JSON parsing fails
        if (!$json_data) {
            $json_data = Flight::request()->data->getData();
        }
        
        // Validate that we have data
        if (!$json_data || !is_array($json_data)) {
            Flight::halt(400, 'Invalid request data');
            return;
        }
        
        $response = Flight::auth_service()->register($json_data);

        if ($response['success']) {
            Flight::json([
                'message' => 'User registered successfully',
                'data' => $response['data']
            ]);
        } else {
            Flight::halt(400, $response['error']);
        }
    });

    /**
     * @OA\Post(
     *      path="/auth/login",
     *      tags={"auth"},
     *      summary="Login to system using email and password",
     *      @OA\Response(
     *           response=200,
     *           description="Student data and JWT"
     *      ),
     *      @OA\RequestBody(
     *          description="Credentials",
     *          @OA\JsonContent(
     *              required={"email","password"},
     *              @OA\Property(property="email", type="string", example="demo@gmail.com", description="Student email address"),
     *              @OA\Property(property="password", type="string", example="some_password", description="Student password")
     *          )
     *      )
     * )
     */
    Flight::route('POST /login', function() {
        // Get JSON data from request body
        $json_data = json_decode(Flight::request()->getBody(), true);
        
        // Fallback to form data if JSON parsing fails
        if (!$json_data) {
            $json_data = Flight::request()->data->getData();
        }
        
        // Validate that we have data
        if (!$json_data || !is_array($json_data)) {
            Flight::halt(400, 'Invalid request data');
            return;
        }
        
        // Additional validation for required fields
        if (empty($json_data['email']) || empty($json_data['password'])) {
            Flight::halt(400, 'Email and password are required');
            return;
        }
        
        $response = Flight::auth_service()->login($json_data);

        if ($response['success']) {
            Flight::json([
                'message' => 'User logged in successfully',
                'data' => $response['data']
            ]);
        } else {
            Flight::halt(401, $response['error']);
        }
    });
});
?>