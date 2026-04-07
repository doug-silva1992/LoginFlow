<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class UsersController extends Controller
{
    #[OA\Get(
        path: '/users',
        summary: 'Lista todos os usuários',
        tags: ['Users'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de usuários',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(type: 'object')
                )
            ),
            new OA\Response(response: 403, description: 'Acesso negado'),
        ]
    )]
    
    public function users(Request $request)
    {
        try {
            if (!Auth::check()) {
                throw new \Exception('Acesso negado');
            }

            $users = Auth::user()->all();
            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }

    #[OA\Delete(
        path: '/delete_user/{id}',
        summary: 'Deleta um usuário',
        tags: ['Users'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', example: 1))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Usuário deletado com sucesso',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Usuário deletado com sucesso')
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 403, description: 'Acesso negado'),
            new OA\Response(response: 404, description: 'Usuário não encontrado'),
        ]
    )]
    
    public function deleteUser(Request $request)
    {
        try {
            if (!Auth::check()) {
                throw new \Exception('Acesso negado');
            }

            $user = Auth::user()->find($request->id);
            if (!$user) { 
                throw new \Exception('Usuário não encontrado');
            }

            $user->delete();
            return response()->json(['message' => 'Usuário deletado com sucesso']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }

    // Atualizar usuário

    #[OA\Put(
        path: '/update_user/{id}',
        summary: 'Atualiza um usuário',
        tags: ['Users'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', example: 1))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                    new OA\Property(property: 'email', type: 'string', example: 'john.doe@example.com')
                ],
                type: 'object'
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Usuário atualizado com sucesso',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Usuário atualizado com sucesso')
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 403, description: 'Acesso negado'),
            new OA\Response(response: 404, description: 'Usuário não encontrado'),
        ]
    )]
    
    public function updateUser(Request $request)
    {
        try {
            if (!Auth::check()) {
                throw new \Exception('Acesso negado');
            }

            $user = Auth::user()->find($request->id);
            if (!$user) { 
                throw new \Exception('Usuário não encontrado');
            }

            $user->update($request->only(['name', 'email']));
            return response()->json(['message' => 'Usuário atualizado com sucesso']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }
}