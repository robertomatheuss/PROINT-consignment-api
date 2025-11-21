<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * GET /api/clientes
     */
    public function index()
    {
        $clientes = Cliente::orderBy('nome', 'asc')->get();

        return response()->json($clientes);
    }

    /**
     * Cria um novo cliente.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'            => ['required', 'string', 'max:255'],
            'cpf'             => ['required', 'string', 'max:14', 'unique:clientes,cpf'],
            'data_nascimento' => ['required', 'date'],
            'email'           => ['nullable', 'email', 'max:255'],
            'telefone'        => ['nullable', 'string', 'max:20'],

            'end_logradouro'  => ['required', 'string', 'max:255'],
            'end_numero'      => ['required', 'string', 'max:20'],
            'end_complemento' => ['nullable', 'string', 'max:255'],
            'end_bairro'      => ['required', 'string', 'max:255'],
            'end_cidade'      => ['required', 'string', 'max:255'],
            'end_uf'          => ['required', 'string', 'size:2'],
            'end_cep'         => ['required', 'string', 'max:9'],
        ]);

        $cliente = Cliente::create($data);

        return response()->json($cliente, 201);
    }
    /**
     * GET /api/clientes/{id}
     */
    public function show(Cliente $cliente)
    {
        $cliente->load('documentos');

        return response()->json($cliente);
    }

    
    /*
     * PUT /api/clientes/{id}
     */
    public function update(Request $request, string $id)
    {
        $cliente = Cliente::findOrFail($id);

        $data = $request->validate([
            'nome'            => ['sometimes', 'string', 'max:255'],
            'cpf'             => [
                'sometimes',
                'string',
                'max:14',
                'unique:clientes,cpf,' . $cliente->id,
            ],
            'data_nascimento' => ['sometimes', 'date'],
            'email'           => ['sometimes', 'nullable', 'email', 'max:255'],
            'telefone'        => ['sometimes', 'nullable', 'string', 'max:20'],

            'end_logradouro'  => ['sometimes', 'string', 'max:255'],
            'end_numero'      => ['sometimes', 'string', 'max:20'],
            'end_complemento' => ['sometimes', 'nullable', 'string', 'max:255'],
            'end_bairro'      => ['sometimes', 'string', 'max:255'],
            'end_cidade'      => ['sometimes', 'string', 'max:255'],
            'end_uf'          => ['sometimes', 'string', 'size:2'],
            'end_cep'         => ['sometimes', 'string', 'max:9'],
        ]);

        $cliente->update($data);

        return response()->json($cliente);
    }
    public function destroy(string $id){
        $cliente = Cliente::findOrFail($id);

        $documentos = $cliente->documentos;

        foreach ($documentos as $documento) {
            // Apaga o arquivo físico, se existir
            if ($documento->file_path && Storage::disk('public')->exists($documento->file_path)) {
                Storage::disk('public')->delete($documento->file_path);
            }

            $documento->delete();
        }

        $cliente->delete();

        return response()->json(null, 204);
}

}
