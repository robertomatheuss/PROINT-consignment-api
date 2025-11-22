<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Documento;
use App\Models\Venda;
use App\Models\TipoContrato;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            // 1) Pega um usuário vendedor (ou admin, se não achar)
            $vendedor = User::where('email', 'vendedor@consignado.com')->first()
                ?? User::where('email', 'admin@consignado.com')->first();

            if (! $vendedor) {
                $this->command->warn('Nenhum usuário encontrado. Rode primeiro o UserSeeder.');
                return;
            }

            $tipoContrato = TipoContrato::create([
                'nome'                       => 'Empréstimo Consignado 36x',
                'prazo_meses'                => 36,
                'tempo_nova_oportunidade_dias' => 365,
                'ativo'                      => true,
            ]);

            $cliente = Cliente::create([
                'nome'           => 'Cliente de Teste',
                'cpf'            => '123.456.789-00',
                'data_nascimento'=> '1990-01-01',
                'email'          => 'cliente@teste.com',
                'telefone'       => '11999999999',
                'end_logradouro' => 'Rua de Teste',
                'end_numero'     => '123',
                'end_complemento'=> 'Apto 10',
                'end_bairro'     => 'Centro',
                'end_cidade'     => 'São Paulo',
                'end_uf'         => 'SP',
                'end_cep'        => '01000-000',
            ]);

            $documento = Documento::create([
                'cliente_id'             => $cliente->id,
                'tipo'                   => 'RG',
                'nome_arquivo'           => 'rg-cliente-teste.pdf',
                'file_path'              => 'documentos/rg-cliente-teste.pdf',
                'file_url'               => null,
                'tamanho_bytes'          => 0,
                'hash_conteudo'          => null,
                'enviado_por_usuario_id' => $vendedor->id,
                'enviado_em'             => now(),
            ]);

            $venda = Venda::create([
                'cliente_id'       => $cliente->id,
                'vendedor_id'      => $vendedor->id,
                'tipo_contrato_id' => $tipoContrato->id,
                'valor'            => 10000.00,
                'data'             => now()->toDateString(),
                'status'           => 'CRIADA',
            ]);

            // Liga o documento à venda (tabela venda_documentos)
            $venda->documentos()->attach($documento->id);

            DB::commit();

            $this->command->info('Dados de demonstração criados com sucesso.');
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->command->error('Erro ao criar dados de demonstração: '.$e->getMessage());
        }
    }
}
