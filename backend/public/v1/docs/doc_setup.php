<?php

/**
 * @OA\Info(
 *     title="API",
 *     description="Sneaker Store API",
 *     version="1.0",
 *     @OA\Contact(
 *         email="web2001programming@gmail.com",
 *         name="Web Programming"
 *     )
 * )
 */

/**
 * @OA\Server(
 *      url="https://localhost/project/Introduction-to-web-programming/backend/",
 *      description="API server"
 * )
*/

/**
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 *      description="Enter JWT Bearer token. Example: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
 * )
 */