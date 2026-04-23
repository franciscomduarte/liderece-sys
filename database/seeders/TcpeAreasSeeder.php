<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class TcpeAreasSeeder extends Seeder
{
    public function run(): void
    {
        // ── Órgãos Originários ────────────────────────────────────────────────
        $tribunalPleno = $this->area('Tribunal Pleno', 'TRIBUNAL PLENO');
        $this->area('1ª Câmara', '1ª CÂMARA', $tribunalPleno);
        $this->area('2ª Câmara', '2ª CÂMARA', $tribunalPleno);

        // ── Presidência e órgãos vinculados ───────────────────────────────────
        $presidencia = $this->area('Presidência', 'PRESIDÊNCIA');
        $this->area('Vice-Presidência', 'VICE-PRESIDÊNCIA', $presidencia);

        $corregedoria = $this->area('Corregedoria', 'CORREGEDORIA', $presidencia);
        $this->area('CPAD', 'CPAD', $corregedoria);

        $this->area('Ouvidoria', 'OUVIDORIA', $presidencia);

        // ── Órgãos Especiais ──────────────────────────────────────────────────
        $this->area('Ministério Público de Contas', 'MPCORG', $presidencia);
        $this->area('Auditoria Geral', 'AUDITORIA GERAL', $presidencia);
        $this->area('Procuradoria Jurídica', 'PROC. JURÍDICA', $presidencia);

        // ── GCs — Gabinetes dos Conselheiros ──────────────────────────────────
        $this->area('Gabinetes dos Conselheiros', 'GCs', $presidencia);

        // ── ECPBG — Escola de Contas Públicas Prof. Barreto Guimarães ─────────
        $ecpbg = $this->area('Escola de Contas Públicas Prof. Barreto Guimarães', 'ECPBG', $presidencia);
        $this->area('Gerência Financeira', 'GFIN', $ecpbg);
        $this->area('Gerência Administrativa', 'GADM', $ecpbg);
        $this->area('Gerência de Ações Educacionais Corporativas', 'GAEC', $ecpbg);
        $this->area('Gerência de Pesquisa e Pós-Graduação', 'GPOS', $ecpbg);
        $this->area('Gerência de Ações Educacionais para o Controle Social e Cidadania', 'GECS', $ecpbg);
        $this->area('Gerência de Ações Educacionais para a Administração Pública', 'GEAP', $ecpbg);

        // ── GPRE — Gabinete da Presidência ────────────────────────────────────
        $gpre = $this->area('Gabinete da Presidência', 'GPRE', $presidencia);
        $this->area('Gerência de Controle de Expediente', 'GEXP', $gpre);
        $this->area('Gerência de Legislação', 'GLEG', $gpre);

        // ── DEX — Diretoria de Controle Externo ───────────────────────────────
        $dex = $this->area('Diretoria de Controle Externo', 'DEX', $presidencia);

        $this->area('Gerência de Informações Estratégicas e Inteligência', 'GINF', $dex);
        $this->area('Gerência de Padrões, Métodos e Qualidade', 'GQUALI', $dex);

        $deduc = $this->area('Departamento de Controle Externo da Educação e da Cidadania', 'DEDUC', $dex);
        $this->area('Gerência de Fiscalização da Educação 1', 'GEDU1', $deduc);
        $this->area('Gerência de Fiscalização da Educação 2', 'GEDU2', $deduc);
        $this->area('Gerência de Fiscalização da Cultura e da Cidadania', 'GCID', $deduc);
        $this->area('Gerência de Fiscalização da Segurança e da Administração Pública', 'GSEG', $deduc);

        $desau = $this->area('Departamento de Controle Externo da Economia e da Saúde', 'DESAU', $dex);
        $this->area('Gerência de Fiscalização da Saúde 1', 'GSAU1', $desau);
        $this->area('Gerência de Fiscalização da Saúde 2', 'GSAU2', $desau);
        $this->area('Gerência de Fiscalização do Desenvolvimento Econômico', 'GEDE', $desau);
        $this->area('Gerência de Fiscalização do Trabalho e da Agricultura', 'GETA', $desau);

        $dinfra = $this->area('Departamento de Controle Externo da Infraestrutura', 'DINFRA', $dex);
        $this->area('Gerência de Fiscalização de Transporte e Mobilidade', 'GTRAM', $dinfra);
        $this->area('Gerência de Fiscalização de Habitação, Urbanismo e Edificações', 'GHAB', $dinfra);
        $this->area('Gerência de Fiscalização em Desestatizações', 'GDES', $dinfra);
        $this->area('Gerência de Fiscalização de Saneamento, Meio Ambiente e Energia', 'GSAM', $dinfra);
        $this->area('Gerência de Estudos e Suporte à Fiscalização', 'GESF', $dinfra);
        $this->area('Gerência de Fiscalização de Obras Municipais Norte', 'GAON', $dinfra);
        $this->area('Gerência de Fiscalização de Obras Municipais Sul', 'GAOS', $dinfra);
        $this->area('Gerência de Fiscalização em Licitações de Obras', 'GLIO', $dinfra);

        $dplti = $this->area('Departamento de Controle Externo de Pessoal, Licitações e Tecnologia da Informação', 'DPLTI', $dex);
        $this->area('Gerência de Admissão de Pessoal', 'GAPE', $dplti);
        $this->area('Gerência de Inativos e Pensionistas', 'GIPE', $dplti);
        $this->area('Gerência de Controle de Pessoal', 'GECP', $dplti);
        $this->area('Gerência de Fiscalização de Procedimentos Licitatórios', 'GLIC', $dplti);
        $this->area('Gerência de Fiscalização de Tecnologia da Informação', 'GATI', $dplti);

        $dmacro = $this->area('Departamento de Macroavaliação Governamental', 'DMACRO', $dex);
        $this->area('Gerência de Contas de Governo Municipais', 'GEGM', $dmacro);
        $this->area('Gerência de Fiscalização da Previdência', 'GPREV', $dmacro);
        $this->area('Gerência de Fiscalização da Transparência e Gestão Fiscal', 'GTGF', $dmacro);
        $this->area('Gerência de Fiscalização dos Poderes Estaduais', 'GFPE', $dmacro);

        $dregio = $this->area('Departamento de Controle Externo Regional', 'DREGIO', $dex);
        $this->area('Gerência Regional Metropolitana Sul', 'GEMS', $dregio);
        $this->area('Gerência Regional Metropolitana Norte', 'GEMN', $dregio);
        $this->area('Inspetoria Regional de Arcoverde', 'IRAR', $dregio);
        $this->area('Inspetoria Regional de Bezerros', 'IRBE', $dregio);
        $this->area('Inspetoria Regional de Palmares', 'IRPA', $dregio);
        $this->area('Inspetoria Regional de Petrolina', 'IRPE', $dregio);
        $this->area('Inspetoria Regional de Surubim', 'IRSU', $dregio);
        $this->area('Inspetoria Regional de Garanhuns', 'IRGA', $dregio);

        // ── DP — Diretoria de Plenário ────────────────────────────────────────
        $dp = $this->area('Diretoria de Plenário', 'DP', $presidencia);

        $das = $this->area('Departamento de Apoio às Sessões', 'DAS', $dp);
        $this->area('Gerência de Atas', 'GEAT', $das);
        $this->area('Gerência de Expediente e Controle', 'GEEC', $das);
        $this->area('Gerência Técnica da Primeira Câmara', 'GET1', $das);
        $this->area('Gerência Técnica da Segunda Câmara', 'GET2', $das);

        $this->area('Departamento Técnico de Plenário', 'DTP', $dp);

        $gjur = $this->area('Departamento Jurídico', 'GJUR', $dp);
        $this->area('Gerência de Controle de Débitos e Multas', 'GCDM', $gjur);
        $this->area('Gerência de Controle de Deliberações', 'GCDE', $gjur);

        // ── DG — Diretoria Geral ──────────────────────────────────────────────
        $dg = $this->area('Diretoria Geral', 'DG', $presidencia);
        $cad = $this->area('Coordenadoria de Administração Geral', 'CAD', $dg);

        $dti = $this->area('Departamento de Tecnologia da Informação', 'DTI', $cad);
        $this->area('Gerência de Informação e Apoio Tecnológico', 'GIAT', $dti);
        $this->area('Gerência de Infraestrutura de Tecnologia da Informação', 'GITI', $dti);
        $this->area('Gerência de Processo Eletrônico', 'GPEL', $dti);
        $this->area('Gerência de Soluções de Inteligência Artificial', 'GSIA', $dti);
        $this->area('Gerência de Desenvolvimento de Sistemas de Informação', 'GDSI', $dti);

        $dip = $this->area('Departamento de Infraestrutura Predial', 'DIP', $cad);
        $this->area('Gerência de Obras e Serviços de Engenharia', 'GEOS', $dip);
        $this->area('Gerência de Manutenção de Bens Imóveis', 'GMBI', $dip);

        $dgp = $this->area('Departamento de Gestão de Pessoas', 'DGP', $cad);
        $this->area('Gerência de Registro Cadastral', 'GECD', $dgp);
        $this->area('Gerência de Folha de Pagamento', 'GEFP', $dgp);
        $this->area('Gerência de Saúde e Bem-Estar', 'GBEM', $dgp);
        $this->area('Gerência de Desenvolvimento de Pessoas e Desempenho Funcional', 'GDDF', $dgp);

        $dbs = $this->area('Departamento de Bens e Serviços', 'DBS', $cad);
        $this->area('Gerência de Segurança', 'GESG', $dbs);
        $this->area('Gerência de Transportes', 'GETR', $dbs);
        $this->area('Gerência de Materiais e Patrimônio', 'GEMP', $dbs);

        $dco = $this->area('Departamento de Contratações', 'DCO', $cad);
        $this->area('Gerência de Licitações e Contratações Diretas', 'GLCD', $dco);
        $this->area('Gerência de Formalização e Acompanhamento Contratual', 'GFAC', $dco);
        $this->area('Gerência de Planejamento das Contratações', 'GEPC', $dco);

        $ded = $this->area('Departamento de Expediente e Documentação', 'DED', $cad);
        $this->area('Gerência de Protocolo e Expedição', 'GEPE', $ded);
        $this->area('Gerência de Documentação e Memória Institucional', 'GEDM', $ded);
        $this->area('Gerência de Biblioteca', 'GEBI', $ded);

        $dcf = $this->area('Departamento de Contabilidade e Finanças', 'DCF', $cad);
        $this->area('Gerência de Tesouraria e Controle Financeiro', 'GETE', $dcf);
        $this->area('Gerência de Liquidação', 'GLID', $dcf);
        $this->area('Gerência de Orçamento', 'GEOR', $dcf);
        $this->area('Gerência de Controle e Prestação de Contas', 'GCPC', $dcf);

        // ── DGG — Diretoria de Gestão e Governança ────────────────────────────
        $dgg = $this->area('Diretoria de Gestão e Governança', 'DGG', $presidencia);
        $this->area('Gerência de Gestão Estratégica e de Projetos', 'GGEP', $dgg);
        $this->area('Gerência de Auditoria Interna', 'GAIN', $dgg);
        $this->area('Núcleo de Inovação', 'NUI', $dgg);
        $this->area('Gerência de Segurança da Informação e Privacidade de Dados', 'GSIP', $dgg);
        $this->area('Gerência do Escritório de Processos', 'GPRO', $dgg);

        // ── DC — Diretoria de Comunicação ─────────────────────────────────────
        $dc = $this->area('Diretoria de Comunicação', 'DC', $presidencia);
        $this->area('Gerência de Criação e Marketing', 'GCRI', $dc);
        $this->area('Gerência de Jornalismo', 'GEJO', $dc);
    }

    private function area(string $nome, string $sigla, ?Area $pai = null): Area
    {
        return Area::firstOrCreate(
            ['sigla' => $sigla],
            ['nome' => $nome, 'parent_id' => $pai?->id],
        );
    }
}
