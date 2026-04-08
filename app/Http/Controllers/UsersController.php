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
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de usuários',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 1),
                            new OA\Property(property: 'name', type: 'string', example: 'João Silva'),
                            new OA\Property(property: 'email', type: 'string', example: 'joao@example.com'),
                            new OA\Property(property: 'is_admin', type: 'boolean', example: false),
                            new OA\Property(property: 'expiration_date', type: 'string', nullable: true, example: '2025-12-31'),
                            new OA\Property(property: 'created_at', type: 'string', example: '2024-01-01T00:00:00Z'),
                        ],
                        type: 'object'
                    )
                )
            ),
            new OA\Response(response: 401, description: 'Não autenticado'),
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

    #[OA\Post(
        path: '/create_user',
        summary: 'Cria um novo usuário',
        tags: ['Users'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email', 'password'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                    new OA\Property(property: 'email', type: 'string', example: 'john.doe@example.com'),
                    new OA\Property(property: 'password', type: 'string', example: 'password123'),
                    new OA\Property(property: 'expiration_date', type: 'string', nullable: true, example: '2025-12-31'),
                ],
                type: 'object'
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Usuário criado com sucesso',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Usuário criado com sucesso')
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 403, description: 'Acesso negado'),
        ]
    )]
    
    public function createUser(Request $request)
    {
        try {
            if (!Auth::check()) {
                throw new \Exception('Acesso negado');
            }

            $user = Auth::user()->create($request->only(['name', 'email', 'password', 'expiration_date']));
            return response()->json(['message' => 'Usuário criado com sucesso', 'user' => $user], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }

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
                    new OA\Property(property: 'email', type: 'string', example: 'john.doe@example.com'),
                    new OA\Property(property: 'expiration_date', type: 'string', example: '2024-12-31')

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

            $user->update($request->only(['name', 'email', 'expiration_date']));
            return response()->json(['message' => 'Usuário atualizado com sucesso']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }

    // Criar usuários com base em arquivo CSV
    #[OA\Post(
        path: '/import_users',
        summary: 'Importa usuários de um arquivo CSV',
        tags: ['Users'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'file',
                            type: 'string',
                            format: 'binary',
                            description: 'Arquivo CSV contendo os usuários'
                        )
                    ],
                    type: 'object'
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Usuários importados com sucesso',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Usuários importados com sucesso')
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 403, description: 'Acesso negado'),
        ]
    )]

    public function uploadCsv(Request $request)
    {
        $request->validate(['file' => ['required', 'file', 'max:5120']]);

        $file     = $request->file('file');
        $filename = date('Y-m-d_His') . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());

        // Lê diretamente do arquivo temporário (não depende de storeAs)
        $tmpPath = $file->getPathname();
        $handle  = fopen($tmpPath, 'r');

        if (!$handle) {
            return response()->json(['error' => 'Não foi possível ler o arquivo enviado.'], 500);
        }

        $header   = null;
        $inserted = 0;
        $skipped  = 0;
        $errors   = [];

        while (($row = fgetcsv($handle)) !== false) {
            if ($header === null) {
                $header = array_map('trim', array_map('strtolower', $row));
                // Remove BOM do primeiro campo se houver
                $header[0] = ltrim($header[0], "\xEF\xBB\xBF\x00");
                continue;
            }

            if (count($row) < count($header)) {
                $skipped++;
                continue;
            }

            $data  = array_combine($header, array_map('trim', $row));
            $email = $data['email'] ?? null;
            $name  = $data['name'] ?? $data['nome'] ?? null;

            if (!$email || !$name) {
                $skipped++;
                continue;
            }

            if (\App\Models\User::where('email', $email)->exists()) {
                $skipped++;
                continue;
            }

            $expiration = ($data['expiration_date'] ?? $data['expiry'] ?? '') ?: null;
            $isAdmin    = str_ends_with(strtolower($email), '@fontecred.com.br');
            $password   = ($data['password'] ?? '') ?: \Illuminate\Support\Str::random(12);

            try {
                \App\Models\User::create([
                    'name'            => $name,
                    'email'           => $email,
                    'password'        => $password,
                    'expiration_date' => $expiration,
                    'is_admin'        => $isAdmin,
                ]);
                $inserted++;
            } catch (\Throwable $e) {
                $errors[] = $email . ': ' . $e->getMessage();
            }
        }

        fclose($handle);

        // Salva cópia na pasta csv após processar
        \Illuminate\Support\Facades\Storage::put('csv/' . $filename, file_get_contents($tmpPath));

        return response()->json([
            'filename' => $filename,
            'inserted' => $inserted,
            'skipped'  => $skipped,
            'errors'   => $errors,
        ]);
    }
}