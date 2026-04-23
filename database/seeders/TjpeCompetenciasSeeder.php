<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Competencia;
use App\Models\ItemAvaliacao;
use Illuminate\Database\Seeder;

class TjpeCompetenciasSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->dados() as $dado) {
            $competencia = Competencia::firstOrCreate(
                ['nome' => $dado['nome']],
                ['tipo' => $dado['tipo'], 'descricao' => $dado['descricao'], 'ativo' => true],
            );

            foreach ($dado['itens'] as $ordem => $descricao) {
                ItemAvaliacao::firstOrCreate(
                    ['competencia_id' => $competencia->id, 'descricao' => $descricao],
                    ['ordem' => $ordem + 1, 'ativo' => true],
                );
            }
        }
    }

    private function dados(): array
    {
        return [

            // ── COMPORTAMENTAIS ───────────────────────────────────────────────

            [
                'tipo'     => 'organizacional',
                'nome'     => 'Comunicação',
                'descricao'=> 'Capacidade de transmitir informações de forma clara, objetiva e adequada ao público, utilizando os canais apropriados.',
                'itens'    => [
                    'Expressa ideias e informações de forma clara e objetiva, tanto oralmente quanto por escrito.',
                    'Adapta a linguagem ao perfil do interlocutor (técnico, gestor, jurisdicionado).',
                    'Ouve atentamente e demonstra compreensão antes de responder.',
                    'Utiliza os canais institucionais adequados para cada tipo de comunicação.',
                    'Fornece retorno (feedback) de forma construtiva e respeitosa.',
                ],
            ],
            [
                'tipo'     => 'organizacional',
                'nome'     => 'Trabalho em Equipe',
                'descricao'=> 'Habilidade de colaborar com colegas, compartilhar conhecimentos e contribuir para o alcance de objetivos coletivos.',
                'itens'    => [
                    'Colabora ativamente para o alcance dos objetivos da equipe.',
                    'Compartilha informações e conhecimentos relevantes com os colegas.',
                    'Respeita opiniões divergentes e contribui para decisões consensuais.',
                    'Apoia colegas em situações de sobrecarga ou dificuldade.',
                    'Reconhece e valoriza a contribuição dos demais membros da equipe.',
                ],
            ],
            [
                'tipo'     => 'organizacional',
                'nome'     => 'Proatividade',
                'descricao'=> 'Iniciativa para identificar oportunidades de melhoria e agir antes mesmo de ser solicitado.',
                'itens'    => [
                    'Identifica problemas e propõe soluções antes de ser demandado.',
                    'Antecipa demandas e se prepara adequadamente para atendê-las.',
                    'Busca aperfeiçoamento contínuo sem depender de orientação externa.',
                    'Toma a iniciativa diante de situações novas ou não previstas.',
                    'Sugere melhorias nos processos de trabalho da unidade.',
                ],
            ],
            [
                'tipo'     => 'organizacional',
                'nome'     => 'Comprometimento',
                'descricao'=> 'Dedicação e responsabilidade no cumprimento das atribuições e prazos estabelecidos.',
                'itens'    => [
                    'Cumpre suas atribuições dentro dos prazos estabelecidos.',
                    'Demonstra dedicação e empenho na execução das tarefas.',
                    'Assume responsabilidade pelos resultados do seu trabalho.',
                    'Mantém a qualidade das entregas mesmo sob pressão.',
                    'Está disponível para atender demandas prioritárias quando necessário.',
                ],
            ],
            [
                'tipo'     => 'organizacional',
                'nome'     => 'Ética e Integridade',
                'descricao'=> 'Atuação pautada em princípios éticos, transparência e respeito às normas institucionais.',
                'itens'    => [
                    'Age com honestidade e transparência em todas as situações.',
                    'Respeita as normas, regulamentos e o Código de Ética do servidor.',
                    'Mantém sigilo sobre informações sensíveis e dados protegidos.',
                    'Trata todos com isonomia, sem favoritismos ou discriminações.',
                    'Declina de situações que possam configurar conflito de interesses.',
                ],
            ],
            [
                'tipo'     => 'organizacional',
                'nome'     => 'Adaptabilidade',
                'descricao'=> 'Flexibilidade para se ajustar a mudanças, novas demandas e diferentes contextos de trabalho.',
                'itens'    => [
                    'Adapta-se com facilidade a mudanças de rotina, processos ou prioridades.',
                    'Mantém a produtividade diante de cenários de incerteza.',
                    'Aprende rapidamente novas ferramentas e metodologias.',
                    'Aceita feedbacks e ajusta seu comportamento conforme necessário.',
                    'Demonstra equilíbrio emocional em situações de pressão ou mudança.',
                ],
            ],
            [
                'tipo'     => 'organizacional',
                'nome'     => 'Relacionamento Interpessoal',
                'descricao'=> 'Capacidade de manter relações cordiais, respeitosas e produtivas com colegas, gestores e jurisdicionados.',
                'itens'    => [
                    'Trata colegas, gestores e jurisdicionados com cortesia e respeito.',
                    'Constrói relações de confiança dentro e fora da unidade.',
                    'Demonstra empatia ao lidar com situações que afetam outras pessoas.',
                    'Mantém postura profissional mesmo em situações de conflito.',
                    'Contribui para um ambiente de trabalho positivo e colaborativo.',
                ],
            ],
            [
                'tipo'     => 'organizacional',
                'nome'     => 'Resolução de Conflitos',
                'descricao'=> 'Habilidade para mediar divergências e encontrar soluções equilibradas que atendam aos interesses das partes.',
                'itens'    => [
                    'Identifica situações de conflito e age para solucioná-las de forma construtiva.',
                    'Ouve as partes envolvidas com imparcialidade antes de intervir.',
                    'Propõe acordos e soluções que contemplem os interesses legítimos das partes.',
                    'Evita escalada de conflitos ao comunicar discordâncias com assertividade.',
                    'Busca apoio da gestão quando o conflito ultrapassa sua capacidade de mediação.',
                ],
            ],

            // ── TÉCNICAS ──────────────────────────────────────────────────────

            [
                'tipo'     => 'técnica',
                'nome'     => 'Conhecimento Jurídico',
                'descricao'=> 'Domínio da legislação, jurisprudência e normas aplicáveis às atividades do Tribunal de Justiça.',
                'itens'    => [
                    'Demonstra conhecimento atualizado da legislação aplicável à sua área de atuação.',
                    'Aplica corretamente normas processuais e regulamentações internas.',
                    'Consulta jurisprudência e doutrina para embasar suas decisões e pareceres.',
                    'Interpreta corretamente atos normativos e os aplica ao caso concreto.',
                    'Mantém-se atualizado sobre alterações legislativas relevantes.',
                ],
            ],
            [
                'tipo'     => 'técnica',
                'nome'     => 'Gestão Documental',
                'descricao'=> 'Capacidade de organizar, controlar e preservar documentos e processos físicos e eletrônicos conforme normas vigentes.',
                'itens'    => [
                    'Organiza e classifica documentos conforme o plano de classificação institucional.',
                    'Garante a rastreabilidade e integridade dos documentos sob sua responsabilidade.',
                    'Observa os prazos de guarda e destinação previstos na tabela de temporalidade.',
                    'Utiliza corretamente os sistemas de gestão documental (SEI, PJe, etc.).',
                    'Aplica as boas práticas de segurança da informação no tratamento de documentos.',
                ],
            ],
            [
                'tipo'     => 'técnica',
                'nome'     => 'Uso de Sistemas Institucionais',
                'descricao'=> 'Proficiência na operação dos sistemas corporativos do TJPE, como PJe, SEI e demais ferramentas internas.',
                'itens'    => [
                    'Opera com autonomia os sistemas institucionais necessários à sua função.',
                    'Utiliza todos os recursos disponíveis nos sistemas para otimizar o trabalho.',
                    'Registra informações com precisão e no momento correto nos sistemas.',
                    'Identifica e reporta falhas ou inconsistências nos sistemas utilizados.',
                    'Orienta colegas no uso correto dos sistemas quando necessário.',
                ],
            ],
            [
                'tipo'     => 'técnica',
                'nome'     => 'Redação Oficial',
                'descricao'=> 'Habilidade de produzir documentos oficiais com clareza, precisão e adequação à norma culta.',
                'itens'    => [
                    'Produz documentos oficiais em conformidade com o Manual de Redação da Presidência da República.',
                    'Utiliza linguagem clara, concisa e adequada ao contexto institucional.',
                    'Estrutura os textos com coerência, coesão e argumentação lógica.',
                    'Revisa documentos para garantir correção gramatical e ortográfica.',
                    'Adapta o nível de formalidade ao tipo de documento e ao destinatário.',
                ],
            ],
            [
                'tipo'     => 'técnica',
                'nome'     => 'Gestão Orçamentária e Financeira',
                'descricao'=> 'Conhecimento dos processos de planejamento, execução e controle do orçamento público.',
                'itens'    => [
                    'Conhece os conceitos e etapas do ciclo orçamentário público.',
                    'Acompanha a execução orçamentária da unidade com regularidade.',
                    'Identifica desvios orçamentários e propõe medidas corretivas.',
                    'Assegura que as despesas estejam devidamente justificadas e documentadas.',
                    'Observa as normas de controle interno e externo aplicáveis.',
                ],
            ],
            [
                'tipo'     => 'técnica',
                'nome'     => 'Tecnologia da Informação',
                'descricao'=> 'Domínio de ferramentas de TI, infraestrutura, segurança da informação e desenvolvimento de sistemas.',
                'itens'    => [
                    'Demonstra domínio técnico nas ferramentas e tecnologias da sua área de atuação.',
                    'Adota boas práticas de segurança da informação no trabalho diário.',
                    'Contribui para a manutenção e evolução dos sistemas sob sua responsabilidade.',
                    'Documenta adequadamente soluções, configurações e processos técnicos.',
                    'Mantém-se atualizado sobre tendências e inovações tecnológicas relevantes.',
                ],
            ],
            [
                'tipo'     => 'técnica',
                'nome'     => 'Licitações e Contratos',
                'descricao'=> 'Conhecimento da legislação de licitações (Lei 14.133/2021) e gestão de contratos administrativos.',
                'itens'    => [
                    'Conhece e aplica corretamente a Lei 14.133/2021 e suas regulamentações.',
                    'Elabora ou analisa documentos de licitação com precisão e conformidade legal.',
                    'Acompanha a execução contratual, verificando prazos, entregas e qualidade.',
                    'Identifica irregularidades nos processos licitatórios e adota as providências cabíveis.',
                    'Assegura a publicidade e transparência dos atos relacionados a licitações e contratos.',
                ],
            ],
            [
                'tipo'     => 'técnica',
                'nome'     => 'Gestão de Pessoas',
                'descricao'=> 'Capacidade de aplicar metodologias de recrutamento, desenvolvimento, avaliação e gestão do desempenho.',
                'itens'    => [
                    'Aplica corretamente as normas e procedimentos de gestão de pessoas do TJPE.',
                    'Contribui para processos de recrutamento, seleção e integração de servidores.',
                    'Apoia ações de capacitação e desenvolvimento de competências da equipe.',
                    'Realiza o acompanhamento e registro de frequência com precisão.',
                    'Orienta servidores sobre direitos, deveres e benefícios funcionais.',
                ],
            ],

            // ── GERENCIAIS ────────────────────────────────────────────────────

            [
                'tipo'     => 'gerencial',
                'nome'     => 'Liderança',
                'descricao'=> 'Capacidade de inspirar, orientar e engajar a equipe para o alcance de resultados institucionais.',
                'itens'    => [
                    'Inspira a equipe pelo exemplo, demonstrando comprometimento e ética.',
                    'Define expectativas claras e acompanha o desempenho dos membros da equipe.',
                    'Delega tarefas de forma equilibrada, considerando competências e capacidade.',
                    'Reconhece e valoriza publicamente as contribuições da equipe.',
                    'Intervém de forma assertiva quando o desempenho da equipe está abaixo do esperado.',
                ],
            ],
            [
                'tipo'     => 'gerencial',
                'nome'     => 'Planejamento e Organização',
                'descricao'=> 'Habilidade de definir prioridades, estruturar planos de ação e alocar recursos de forma eficiente.',
                'itens'    => [
                    'Define objetivos, metas e indicadores claros para a unidade.',
                    'Elabora planos de ação com atividades, responsáveis e prazos definidos.',
                    'Prioriza demandas de forma estratégica, alocando recursos adequadamente.',
                    'Antecipa riscos e define planos de contingência.',
                    'Monitora regularmente o andamento das atividades planejadas.',
                ],
            ],
            [
                'tipo'     => 'gerencial',
                'nome'     => 'Tomada de Decisão',
                'descricao'=> 'Capacidade de analisar cenários, ponderar riscos e decidir com agilidade e responsabilidade.',
                'itens'    => [
                    'Analisa as informações disponíveis antes de tomar decisões relevantes.',
                    'Decide com agilidade quando a situação exige resposta imediata.',
                    'Pondera os riscos e impactos das decisões sobre a equipe e a instituição.',
                    'Assume a responsabilidade pelas decisões tomadas, incluindo suas consequências.',
                    'Consulta especialistas e partes interessadas quando a decisão envolve alta complexidade.',
                ],
            ],
            [
                'tipo'     => 'gerencial',
                'nome'     => 'Gestão de Resultados',
                'descricao'=> 'Foco no monitoramento de indicadores e na adoção de ações corretivas para garantir o cumprimento de metas.',
                'itens'    => [
                    'Monitora sistematicamente os indicadores de desempenho da unidade.',
                    'Identifica desvios em relação às metas e adota ações corretivas tempestivas.',
                    'Presta contas dos resultados da unidade de forma transparente.',
                    'Alinha o trabalho da equipe às prioridades e metas institucionais.',
                    'Propõe revisão de metas quando as condições operacionais se alterarem significativamente.',
                ],
            ],
            [
                'tipo'     => 'gerencial',
                'nome'     => 'Desenvolvimento de Equipes',
                'descricao'=> 'Comprometimento com a capacitação, o crescimento profissional e a motivação dos membros da equipe.',
                'itens'    => [
                    'Identifica as necessidades de desenvolvimento de cada membro da equipe.',
                    'Promove e apoia a participação da equipe em ações de capacitação.',
                    'Oferece feedbacks regulares, específicos e orientados ao crescimento.',
                    'Cria oportunidades para que os servidores ampliem suas responsabilidades.',
                    'Mantém um ambiente motivador, reconhecendo esforços e celebrando conquistas.',
                ],
            ],
            [
                'tipo'     => 'gerencial',
                'nome'     => 'Visão Estratégica',
                'descricao'=> 'Capacidade de compreender o contexto institucional e alinhar as ações da unidade aos objetivos estratégicos do TJPE.',
                'itens'    => [
                    'Compreende o planejamento estratégico do TJPE e como a unidade contribui para ele.',
                    'Alinha as decisões e prioridades da unidade às diretrizes institucionais.',
                    'Analisa o ambiente interno e externo para antecipar tendências e impactos.',
                    'Propõe iniciativas que agreguem valor à missão institucional do Tribunal.',
                    'Articula parcerias e sinergias com outras unidades para ampliar resultados.',
                ],
            ],
            [
                'tipo'     => 'gerencial',
                'nome'     => 'Inovação e Melhoria Contínua',
                'descricao'=> 'Estímulo à adoção de novas práticas, metodologias e tecnologias para aprimorar processos e serviços.',
                'itens'    => [
                    'Estimula a equipe a questionar processos e propor melhorias.',
                    'Implementa novas metodologias ou ferramentas que aumentem a eficiência da unidade.',
                    'Cria espaço seguro para que a equipe experimente e aprenda com os erros.',
                    'Monitora boas práticas de outras instituições e avalia sua aplicabilidade no TJPE.',
                    'Promove a cultura de melhoria contínua por meio de ciclos de avaliação e aprendizado.',
                ],
            ],
        ];
    }
}
