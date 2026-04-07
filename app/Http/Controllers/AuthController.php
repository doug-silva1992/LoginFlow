<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Post(
        path: '/login',
        summary: 'Autenticação do usuário',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'user@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'secret'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Login realizado com sucesso',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'token', type: 'string', example: '1|abc123...')]
                )
            ),
            new OA\Response(response: 401, description: 'Credenciais inválidas'),
        ]
    )]
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Credenciais inválidas.'], 401);
        }

        $token = $request->user()->createToken('api-token')->plainTextToken;

        return response()->json(['token' => $token]);
    }

    #[OA\Post(
        path: '/logout',
        summary: 'Encerrar sessão do usuário',
        tags: ['Auth'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Logout realizado com sucesso'),
            new OA\Response(response: 401, description: 'Não autenticado'),
        ]
    )]
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout realizado com sucesso.']);
    }

    #[OA\Get(
        path: '/user',
        summary: 'Dados do usuário autenticado',
        tags: ['Auth'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Dados do usuário',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'name', type: 'string', example: 'João Silva'),
                        new OA\Property(property: 'email', type: 'string', example: 'user@example.com'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Não autenticado'),
        ]
    )]
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
