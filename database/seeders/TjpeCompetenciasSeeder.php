<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Competencia;
use Illuminate\Database\Seeder;

class TjpeCompetenciasSeeder extends Seeder
{
    public function run(): void
    {
        $competencias = [

            // ── Comportamentais ───────────────────────────────────────────────
            [
                'tipo'     => 'comportamental',
                'nome'     => 'Comunicação',
                'descricao'=> 'Capacidade de transmitir informações de forma clara, objetiva e adequada ao público, utilizando os canais apropriados.',
            ],
            [
                'tipo'     => 'comportamental',
                'nome'     => 'Trabalho em Equipe',
                'descricao'=> 'Habilidade de colaborar com colegas, compartilhar conhecimentos e contribuir para o alcance de objetivos coletivos.',
            ],
            [
                'tipo'     => 'comportamental',
                'nome'     => 'Proatividade',
                'descricao'=> 'Iniciativa para identificar oportunidades de melhoria e agir antes mesmo de ser solicitado.',
            ],
            [
                'tipo'     => 'comportamental',
                'nome'     => 'Comprometimento',
                'descricao'=> 'Dedicação e responsabilidade no cumprimento das atribuições e prazos estabelecidos.',
            ],
            [
                'tipo'     => 'comportamental',
                'nome'     => 'Ética e Integridade',
                'descricao'=> 'Atuação pautada em princípios éticos, transparência e respeito às normas institucionais.',
            ],
            [
                'tipo'     => 'comportamental',
                'nome'     => 'Adaptabilidade',
                'descricao'=> 'Flexibilidade para se ajustar a mudanças, novas demandas e diferentes contextos de trabalho.',
            ],
            [
                'tipo'     => 'comportamental',
                'nome'     => 'Relacionamento Interpessoal',
                'descricao'=> 'Capacidade de manter relações cordiais, respeitosas e produtivas com colegas, gestores e jurisdicionados.',
            ],
            [
                'tipo'     => 'comportamental',
                'nome'     => 'Resolução de Conflitos',
                'descricao'=> 'Habilidade para mediar divergências e encontrar soluções equilibradas que atendam aos interesses das partes.',
            ],

            // ── Técnicas ──────────────────────────────────────────────────────
            [
                'tipo'     => 'técnica',
                'nome'     => 'Conhecimento Jurídico',
                'descricao'=> 'Domínio da legislação, jurisprudência e normas aplicáveis às atividades do Tribunal de Justiça.',
            ],
            [
                'tipo'     => 'técnica',
                'nome'     => 'Gestão Documental',
                'descricao'=> 'Capacidade de organizar, controlar e preservar documentos e processos físicos e eletrônicos conforme normas vigentes.',
            ],
            [
                'tipo'     => 'técnica',
                'nome'     => 'Uso de Sistemas Institucionais',
                'descricao'=> 'Proficiência na operação dos sistemas corporativos do TJPE, como PJe, SEI e demais ferramentas internas.',
            ],
            [
                'tipo'     => 'técnica',
                'nome'     => 'Redação Oficial',
                'descricao'=> 'Habilidade de produzir documentos oficiais (ofícios, memorandos, despachos) com clareza, precisão e adequação à norma culta.',
            ],
            [
                'tipo'     => 'técnica',
                'nome'     => 'Gestão Orçamentária e Financeira',
                'descricao'=> 'Conhecimento dos processos de planejamento, execução e controle do orçamento público.',
            ],
            [
                'tipo'     => 'técnica',
                'nome'     => 'Tecnologia da Informação',
                'descricao'=> 'Domínio de ferramentas de TI, infraestrutura, segurança da informação e desenvolvimento de sistemas.',
            ],
            [
                'tipo'     => 'técnica',
                'nome'     => 'Licitações e Contratos',
                'descricao'=> 'Conhecimento da legislação de licitações (Lei 14.133/2021) e gestão de contratos administrativos.',
            ],
            [
                'tipo'     => 'técnica',
                'nome'     => 'Gestão de Pessoas',
                'descricao'=> 'Capacidade de aplicar metodologias de recrutamento, desenvolvimento, avaliação e gestão do desempenho de equipes.',
            ],

            // ── Gerenciais ────────────────────────────────────────────────────
            [
                'tipo'     => 'gerencial',
                'nome'     => 'Liderança',
                'descricao'=> 'Capacidade de inspirar, orientar e engajar a equipe para o alcance de resultados institucionais.',
            ],
            [
                'tipo'     => 'gerencial',
                'nome'     => 'Planejamento e Organização',
                'descricao'=> 'Habilidade de definir prioridades, estruturar planos de ação e alocar recursos de forma eficiente.',
            ],
            [
                'tipo'     => 'gerencial',
                'nome'     => 'Tomada de Decisão',
                'descricao'=> 'Capacidade de analisar cenários, ponderar riscos e decidir com agilidade e responsabilidade.',
            ],
            [
                'tipo'     => 'gerencial',
                'nome'     => 'Gestão de Resultados',
                'descricao'=> 'Foco no monitoramento de indicadores e na adoção de ações corretivas para garantir o cumprimento de metas.',
            ],
            [
                'tipo'     => 'gerencial',
                'nome'     => 'Desenvolvimento de Equipes',
                'descricao'=> 'Comprometimento com a capacitação, o crescimento profissional e a motivação dos membros da equipe.',
            ],
            [
                'tipo'     => 'gerencial',
                'nome'     => 'Visão Estratégica',
                'descricao'=> 'Capacidade de compreender o contexto institucional e alinhar as ações da unidade aos objetivos estratégicos do TJPE.',
            ],
            [
                'tipo'     => 'gerencial',
                'nome'     => 'Inovação e Melhoria Contínua',
                'descricao'=> 'Estímulo à adoção de novas práticas, metodologias e tecnologias para aprimorar processos e serviços.',
            ],
        ];

        foreach ($competencias as $dados) {
            Competencia::firstOrCreate(
                ['nome' => $dados['nome']],
                ['tipo' => $dados['tipo'], 'descricao' => $dados['descricao'], 'ativo' => true],
            );
        }
    }
}
