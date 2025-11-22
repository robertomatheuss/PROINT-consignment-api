<?php

namespace App\Http\Controllers;

use App\Models\Venda;
use Illuminate\Http\Request;

class VendaController extends Controller
{
    public function index()
    {
        $vendas = Venda::with(['cliente', 'vendedor', 'tipoContrato'])
            ->orderBy('data', 'desc')
            ->get();

        return response()->json($vendas);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'cliente_id'       => ['required', 'exists:clientes,id'],
            'tipo_contrato_id' => ['required', 'exists:tipo_contratos,id'],
            'valor'            => ['required', 'numeric', 'min:0'],
            'data'             => ['required', 'date'],
            'status'           => ['nullable', 'in:CRIADA,ATIVA,QUITADA,CANCELADA'],
        ]);

        $status = $data['status'] ?? 'CRIADA';

        $venda = Venda::create([
            'cliente_id'       => $data['cliente_id'],
            'vendedor_id'      => $user->id,
            'tipo_contrato_id' => $data['tipo_contrato_id'],
            'valor'            => $data['valor'],
            'data'             => $data['data'],
            'status'           => $status,
        ]);

        $venda->load(['cliente', 'vendedor', 'tipoContrato']);

        return response()->json($venda, 201);
    }

    public function show(Venda $venda)
    {
        $venda->load(['cliente', 'vendedor', 'tipoContrato', 'documentos']);

        return response()->json($venda);
    }

    public function update(Request $request, Venda $venda)
    {
        $data = $request->validate([
            'cliente_id'       => ['sometimes', 'exists:clientes,id'],
            'tipo_contrato_id' => ['sometimes', 'exists:tipo_contratos,id'],
            'valor'            => ['sometimes', 'numeric', 'min:0'],
            'data'             => ['sometimes', 'date'],
            'status'           => ['sometimes', 'in:CRIADA,ATIVA,QUITADA,CANCELADA'],
        ]);

        $venda->update($data);

        $venda->load(['cliente', 'vendedor', 'tipoContrato', 'documentos']);

        return response()->json($venda);
    }

    public function destroy(Venda $venda)
    {
        $venda->status = 'CANCELADA';
        $venda->save();

        return response()->json(null, 204);
    }
}
