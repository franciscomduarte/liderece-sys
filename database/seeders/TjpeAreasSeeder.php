<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class TjpeAreasSeeder extends Seeder
{
    public function run(): void
    {
        // ── Raiz ──────────────────────────────────────────────────────────────
        $presidencia = $this->area('Presidência');

        // ── Nível 1 — filhos diretos da Presidência ───────────────────────────
        $corregedoria   = $this->area('Corregedoria Geral da Justiça', $presidencia);
        $this->area('1ª Vice-Presidência',               $presidencia);
        $this->area('2ª Vice-Presidência',               $presidencia);
        $this->area('Gabinete da Presidência',           $presidencia);
        $this->area('Assessoria Especial da Presidência',$presidencia);
        $this->area('Comitê Gestor do PJe',              $presidencia);
        $diretoriaGeral = $this->area('Diretoria Geral', $presidencia);

        // ── Corregedoria Geral da Justiça ─────────────────────────────────────
        $this->area('Escola Judicial',               $corregedoria);
        $this->area('Assessoria de Comunicação',     $corregedoria);
        $this->area('Assistência Policial Militar',  $corregedoria);
        $this->area('Consultoria Jurídica',          $corregedoria);
        $this->area('Secretaria de Auditoria Interna', $corregedoria);

        $foroCap = $this->area('Diretoria do Foro da Capital', $corregedoria);
        $this->area('Diretoria dos Foros do Interior',                  $foroCap);
        $this->area('Coordenadoria Geral dos Juizados Especiais',       $foroCap);
        $this->area('Coordenadoria da Infância e Juventude',            $foroCap);
        $this->area('Coordenadoria da Mulher',                          $foroCap);
        $this->area('NUPEMEC',                                          $foroCap);
        $this->area('Centro de Estudos Judiciários',                    $foroCap);
        $this->area('Ouvidoria Judiciária',                             $foroCap);
        $this->area('Comissões Permanentes e Especiais',                $foroCap);
        $this->area('Gabinete de Apoio Institucional',                  $foroCap);
        $this->area('Coordenadoria de Execuções Fiscais',               $foroCap);
        $this->area('Coordenadoria Criminal',                           $foroCap);
        $this->area('Coordenadoria de Governança Institucional',        $foroCap);
        $this->area('Coordenadoria Estadual da Família',                $foroCap);
        $this->area('Assessoria de Cerimonial',                         $foroCap);

        // ── Diretoria Geral — filhos diretos ──────────────────────────────────
        $this->area('Diretoria de Orçamento e Finanças',  $diretoriaGeral);
        $this->area('Assessoria Técnica Administrativa',  $diretoriaGeral);
        $this->area('Núcleo de Apoio Institucional',      $diretoriaGeral);
        $this->area('Canal do TJPE',                      $diretoriaGeral);
        $this->area('Diretoria de Terceirização',         $diretoriaGeral);
        $this->area('Diretoria de Saúde',                 $diretoriaGeral);
        $this->area('Diretoria de Contabilidade',         $diretoriaGeral);
        $this->area('Diretoria Financeira e de Armazenagem', $diretoriaGeral);
        $this->area('CENJUD',                             $diretoriaGeral);

        // ── Secretaria Judiciária ─────────────────────────────────────────────
        $secJud = $this->area('Secretaria Judiciária', $diretoriaGeral);
        $this->area('Diretoria Cível do 2º Grau',           $secJud);
        $this->area('Diretoria Criminal do 2º Grau',        $secJud);
        $this->area('Diretoria de Documentação Judiciária', $secJud);

        // ── Secretaria de Gestão de Pessoas ───────────────────────────────────
        $secGP = $this->area('Secretaria de Gestão de Pessoas', $diretoriaGeral);
        $this->area('DGF',                              $secGP);
        $this->area('DGPM',                             $secGP);
        $this->area('DDN',                              $secGP);
        $this->area('Assessoria Técnica Administrativa',$secGP);
        $this->area('Junta Médica',                     $secGP);

        // ── Secretaria de Tecnologia da Informação e Comunicação ──────────────
        $secTIC = $this->area('Secretaria de Tecnologia da Informação e Comunicação', $diretoriaGeral);
        $this->area('Diretoria de Sistemas',                       $secTIC);
        $this->area('Diretoria de Operações da TIC',               $secTIC);
        $this->area('Diretoria de Suporte Técnico da TIC',         $secTIC);
        $this->area('Assessoria Técnica Administrativa',            $secTIC);
        $this->area('Assessoria de Governança de Tecnologia',       $secTIC);

        $secInfo = $this->area('Assessoria de Gestão de Segurança da Informação', $secTIC);
        $this->area('Assessoria Técnica de Ciência de Dados', $secInfo);
        $this->area('Assessoria Técnica de Inovação de IA',   $secInfo);

        // ── Secretaria de Infraestrutura e Logística ──────────────────────────
        $secInfra = $this->area('Secretaria de Infraestrutura e Logística', $diretoriaGeral);
        $this->area('Diretoria de Engenharia e Arquitetura', $secInfra);
        $this->area('Diretoria de Manutenção e Serviços',    $secInfra);
        $this->area('Diretoria de Patrimônio e Suprimentos', $secInfra);
        $this->area('Assessoria Técnica de Engenharia',      $secInfra);

        // ── Secretaria de Planejamento e Gestão Estratégica ───────────────────
        $secPlan = $this->area('Secretaria de Planejamento e Gestão Estratégica', $diretoriaGeral);
        $this->area('Assessoria Técnica',                   $secPlan);
        $this->area('Comitê Gestor de Metas',               $secPlan);
        $this->area('Núcleo de Monitoramento Estratégico',  $secPlan);
        $this->area('Núcleo de Planejamento Estratégico',   $secPlan);
        $this->area('Núcleo de Sustentabilidade',           $secPlan);
        $this->area('Núcleo de Estatística',                $secPlan);
        $this->area('Escritório de Processos Corporativos', $secPlan);
        $this->area('Escritório de Projetos Corporativos',  $secPlan);

        // ── Secretaria de Administração ───────────────────────────────────────
        $secAdm = $this->area('Secretaria de Administração', $diretoriaGeral);
        $this->area('Assessoria Administrativa',                                                          $secAdm);
        $this->area('Núcleo de Licitações e Contratações Diretas',                                        $secAdm);
        $this->area('Núcleo de Apoio Administrativo',                                                     $secAdm);
        $this->area('Núcleo de Requisição, Repactuação, Reequilíbrio e Ajuste Econômico Financeiro dos Contratos', $secAdm);
        $this->area('Gerência de Pesquisa de Preço',                                                      $secAdm);
        $this->area('Gerência de Apoio à Gestão de Contratos e Convênios',                                $secAdm);
        $this->area('Gerência de Elaboração de Termos de Referência e Projeto Básico',                    $secAdm);
    }

    private function area(string $nome, ?Area $pai = null): Area
    {
        return Area::firstOrCreate(
            ['nome' => $nome, 'parent_id' => $pai?->id],
            ['descricao' => null, 'responsavel' => null],
        );
    }
}
