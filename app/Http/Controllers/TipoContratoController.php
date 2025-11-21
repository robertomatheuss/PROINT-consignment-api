<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoContrato;

class TipoContratoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tipos = TipoContrato::orderBy('nome', 'asc')->get();

        return response()->json($tipos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'                         => ['required', 'string', 'max:255', 'unique:tipo_contratos,nome'],
            'prazo_meses'                  => ['required', 'integer', 'min:1'],
            'tempo_nova_oportunidade_dias' => ['required', 'integer', 'min:0'],
            'ativo'                        => ['boolean'],
        ]);

        if (! array_key_exists('ativo', $data)) {
            $data['ativo'] = true;
        }

        $tipo = TipoContrato::create($data);

        return response()->json($tipo, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tipo = TipoContrato::findOrFail($id);

        return response()->json($tipo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tipo = TipoContrato::findOrFail($id);

        $data = $request->validate([
            'nome'                         => ['sometimes', 'string', 'max:255', 'unique:tipo_contratos,nome,' . $tipo->id],
            'prazo_meses'                  => ['sometimes', 'integer', 'min:1'],
            'tempo_nova_oportunidade_dias' => ['sometimes', 'integer', 'min:0'],
            'ativo'                        => ['sometimes', 'boolean'],
        ]);

        $tipo->update($data);

        return response()->json($tipo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tipo = TipoContrato::findOrFail($id);

        $tipo->ativo = false;
        $tipo->save();

        return response()->json(null, 204);
    }
}
