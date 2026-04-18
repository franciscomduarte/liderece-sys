<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Competencia;
use App\Models\ItemAvaliacao;
use Illuminate\Database\Seeder;

class CompetenciaSeeder extends Seeder
{
    public function run(): void
    {
        $areaTI   = Area::where('nome', 'Tecnologia da Informação')->first();
        $areaRH   = Area::where('nome', 'Recursos Humanos')->first();
        $areaComm = Area::where('nome', 'Comunicação')->first();
        $areaFin  = Area::where('nome', 'Financeiro')->first();
        $areaJur  = Area::where('nome', 'Jurídico')->first();

        $competencias = [
            [
                'nome' => 'Comunicação Efetiva',
                'tipo' => 'comportamental',
                'descricao' => 'Capacidade de se comunicar de forma clara, objetiva e assertiva',
                'areas' => [$areaTI, $areaRH, $areaComm],
                'itens' => [
                    'Expressa ideias de forma clara e objetiva em comunicações escritas',
                    'Adapta a linguagem ao público-alvo da comunicação',
                    'Ouve ativamente e demonstra compreensão nas interações',
                ],
            ],
            [
                'nome' => 'Trabalho em Equipe',
                'tipo' => 'comportamental',
                'descricao' => 'Capacidade de colaborar e contribuir para o trabalho coletivo',
                'areas' => [$areaTI, $areaRH, $areaComm, $areaFin, $areaJur],
                'itens' => [
                    'Colabora proativamente com os colegas para atingir objetivos comuns',
                    'Compartilha conhecimento e informações relevantes com a equipe',
                    'Respeita as diferenças e contribui para um ambiente inclusivo',
                ],
            ],
            [
                'nome' => 'Análise e Solução de Problemas',
                'tipo' => 'técnica',
                'descricao' => 'Capacidade de identificar problemas e propor soluções eficazes',
                'areas' => [$areaTI, $areaFin, $areaJur],
                'itens' => [
                    'Identifica a causa raiz dos problemas antes de propor soluções',
                    'Propõe soluções criativas e viáveis para os desafios apresentados',
                    'Avalia os impactos das soluções antes de implementá-las',
                ],
            ],
            [
                'nome' => 'Gestão do Tempo e Prioridades',
                'tipo' => 'comportamental',
                'descricao' => 'Capacidade de organizar e priorizar atividades para cumprir prazos',
                'areas' => [$areaTI, $areaRH, $areaComm, $areaFin, $areaJur],
                'itens' => [
                    'Organiza as atividades de forma a cumprir prazos estabelecidos',
                    'Prioriza tarefas de acordo com urgência e importância',
                    'Informa proativamente sobre riscos de atraso nas entregas',
                ],
            ],
            [
                'nome' => 'Competência Técnica Específica',
                'tipo' => 'técnica',
                'descricao' => 'Domínio técnico dos conhecimentos inerentes ao cargo',
                'areas' => [$areaTI, $areaFin, $areaJur],
                'itens' => [
                    'Demonstra domínio técnico adequado para exercer as funções do cargo',
                    'Atualiza-se continuamente sobre as inovações da sua área de atuação',
                    'Aplica os conhecimentos técnicos na resolução de problemas práticos',
                ],
            ],
            [
                'nome' => 'Liderança e Desenvolvimento de Pessoas',
                'tipo' => 'gerencial',
                'descricao' => 'Capacidade de liderar equipes e desenvolver talentos',
                'areas' => [$areaTI, $areaRH],
                'itens' => [
                    'Define metas claras e acompanha o desempenho da equipe',
                    'Fornece feedback construtivo e regular aos membros da equipe',
                    'Identifica e desenvolve o potencial dos colaboradores',
                ],
            ],
        ];

        foreach ($competencias as $data) {
            $competencia = Competencia::firstOrCreate(
                ['nome' => $data['nome']],
                ['tipo' => $data['tipo'], 'descricao' => $data['descricao'], 'ativo' => true]
            );

            // Criar itens de avaliação
            if ($competencia->itens()->count() === 0) {
                foreach ($data['itens'] as $ordem => $descricao) {
                    ItemAvaliacao::create([
                        'competencia_id' => $competencia->id,
                        'descricao'      => $descricao,
                        'ordem'          => $ordem + 1,
                        'ativo'          => true,
                    ]);
                }
            }

            // Associar áreas
            $areaIds = collect($data['areas'])->filter()->pluck('id')->toArray();
            $competencia->areas()->syncWithoutDetaching($areaIds);
        }
    }
}
