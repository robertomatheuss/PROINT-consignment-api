<?php

namespace App\Http\Controllers;

use App\Models\Venda;
use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VendaDocumentoController extends Controller
{
    /**
     * GET /api/vendas/{venda}/documentos
     */
    public function index(Venda $venda)
    {
        $venda->load('documentos');

        return response()->json($venda->documentos);
    }

    public function store(Request $request, Venda $venda)
    {
        $user = $request->user();

        $data = $request->validate([
            'tipo'    => ['required', 'in:RG,CPF,CNH,CONTRACHEQUE,COMP_RESIDENCIA,OUTROS'],
            'arquivo' => ['required', 'file', 'max:5120'], 
        ]);

        $file = $data['arquivo'];

        $nomeOriginal = $file->getClientOriginalName();
        $extensao     = $file->getClientOriginalExtension();
        $nomeSeguro   = Str::uuid()->toString().'.'.$extensao;

        $filePath = $file->storeAs('venda_documentos', $nomeSeguro, 'public');
        $fileUrl  = Storage::disk('public')->url($filePath);

        $tamanhoBytes = $file->getSize();
        $hashConteudo = hash_file('sha256', $file->getRealPath());

        $documento = Documento::create([
            'cliente_id'             => $venda->cliente_id,
            'tipo'                   => $data['tipo'],
            'nome_arquivo'           => $nomeOriginal,
            'file_path'              => $filePath,
            'file_url'               => $fileUrl,
            'tamanho_bytes'          => $tamanhoBytes,
            'hash_conteudo'          => $hashConteudo,
            'enviado_por_usuario_id' => $user->id,
            'enviado_em'             => now(),
        ]);

        $venda->documentos()->attach($documento->id);

        return response()->json($documento, 201);
    }

    
    public function destroy(Venda $venda, Documento $documento)
    {
        $venda->documentos()->detach($documento->id);

        if ($documento->file_path && Storage::disk('public')->exists($documento->file_path)) {
            Storage::disk('public')->delete($documento->file_path);
        }

        $documento->delete();

        return response()->json(null, 204);
    }
}
    