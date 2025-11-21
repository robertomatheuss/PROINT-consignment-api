<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class DocumentoController extends Controller
{
    /**
     * Lista todos os documentos de um cliente.
     */
    public function index(Cliente $cliente)
    {
        // Usa o relacionamento Cliente->documentos()
        $documentos = $cliente->documentos()
            ->orderBy('enviado_em', 'desc')
            ->get();

        return response()->json($documentos);
    }

    /*
     * POST /api/clientes/{cliente}/documentos
     * Tipo:
     * multipart/form-data
     */
    public function store(Request $request, Cliente $cliente)
    {
        $user = $request->user();

        $data = $request->validate([
            'tipo'    => ['required', 'in:RG,CPF,CNH,CONTRACHEQUE,COMP_RESIDENCIA,OUTROS'],
            'arquivo' => ['required', 'file', 'max:5120'], 
        ]);

        $file = $data['arquivo'];

        // Nome original e nome seguro
        $nomeOriginal = $file->getClientOriginalName();
        $extensao     = $file->getClientOriginalExtension();
        $nomeSeguro   = Str::uuid()->toString().'.'.$extensao;

        // Salva no disco "public" (storage/app/public/documentos)
        $filePath = $file->storeAs('documentos', $nomeSeguro, 'public');

        // URL pública (via storage:link)
        $fileUrl = Storage::disk('public')->url($filePath);

        // Metadados
        $tamanhoBytes = $file->getSize();
        $hashConteudo = hash_file('sha256', $file->getRealPath());

        $documento = Documento::create([
            'cliente_id'             => $cliente->id,
            'tipo'                   => $data['tipo'],
            'nome_arquivo'           => $nomeOriginal,
            'file_path'              => $filePath,
            'file_url'               => $fileUrl,
            'tamanho_bytes'          => $tamanhoBytes,
            'hash_conteudo'          => $hashConteudo,
            'enviado_por_usuario_id' => $user->id,
            'enviado_em'             => now(),
        ]);

        return response()->json($documento, 201);
    }

    public function updateFile(Request $request, Documento $documento){
        $user = $request->user();

        $data = $request->validate([
            'tipo'    => ['sometimes', 'in:RG,CPF,CNH,CONTRACHEQUE,COMP_RESIDENCIA,OUTROS'],
            'arquivo' => ['required', 'file', 'max:5120'], // 5MB, ajuste se quiser
        ]);

        // Apaga o arquivo antigo, se existir
        if ($documento->file_path && Storage::disk('public')->exists($documento->file_path)) {
            Storage::disk('public')->delete($documento->file_path);
        }

        $file = $data['arquivo'];

        // Novo nome e metadados
        $nomeOriginal = $file->getClientOriginalName();
        $extensao     = $file->getClientOriginalExtension();
        $nomeSeguro   = Str::uuid()->toString().'.'.$extensao;

        $filePath = $file->storeAs('documentos', $nomeSeguro, 'public');
        $fileUrl  = Storage::disk('public')->url($filePath);

        $tamanhoBytes = $file->getSize();
        $hashConteudo = hash_file('sha256', $file->getRealPath());

        // Atualiza os campos do documento
        $documento->nome_arquivo           = $nomeOriginal;
        $documento->file_path              = $filePath;
        $documento->file_url               = $fileUrl;
        $documento->tamanho_bytes          = $tamanhoBytes;
        $documento->hash_conteudo          = $hashConteudo;
        $documento->enviado_por_usuario_id = $user->id;
        $documento->enviado_em             = now();

        // Se vier um novo tipo, atualiza também
        if (isset($data['tipo'])) {
            $documento->tipo = $data['tipo'];
        }

        $documento->save();

        return response()->json($documento);
    }

    /**
     * Remove um documento (e o arquivo físico, se existir).
     * DELETE /api/documentos/{documento}
     */
    public function destroy(Documento $documento)
    {
        // Apaga arquivo do disco, se existir
        if ($documento->file_path && Storage::disk('public')->exists($documento->file_path)) {
            Storage::disk('public')->delete($documento->file_path);
        }

        $documento->delete();

        return response()->json(null, 204);
    }
}
