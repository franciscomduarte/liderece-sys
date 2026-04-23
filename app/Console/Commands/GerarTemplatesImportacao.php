<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;

class GerarTemplatesImportacao extends Command
{
    protected $signature   = 'sgc:gerar-templates';
    protected $description = 'Gera os templates Excel para importação de Áreas e Servidores';

    public function handle(): int
    {
        $destino = storage_path('app/templates');
        if (! is_dir($destino)) {
            mkdir($destino, 0755, true);
        }

        $this->gerarTemplateAreas($destino);
        $this->gerarTemplateServidores($destino);

        $this->info('Templates gerados em: ' . $destino);
        $this->line('  → template_areas.xlsx');
        $this->line('  → template_servidores.xlsx');

        return self::SUCCESS;
    }

    // ── Template Áreas ────────────────────────────────────────────────────────

    private function gerarTemplateAreas(string $destino): void
    {
        $sheet = $this->criarPlanilha('Áreas');

        // ── Instruções (linha 1) ──────────────────────────────────────────────
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1',
            'INSTRUÇÕES: Preencha uma linha por área. Campos com * são obrigatórios. ' .
            'Use a coluna "Sigla da Área Pai" para indicar a hierarquia (ex: PRESIDENCIA é pai de ECPBG). ' .
            'Mantenha os valores exatos nas colunas de lista.'
        );
        $this->estilizarInstrucao($sheet, 'A1:F1');

        // ── Cabeçalhos (linha 2) ──────────────────────────────────────────────
        $colunas = [
            'A' => ['Nome *',              'Exemplo: Diretoria de Planejamento e TI'],
            'B' => ['Sigla *',             'Exemplo: DPLTI  (única no sistema)'],
            'C' => ['Tipo',                'Valores aceitos: Meio  |  Finalística  |  (em branco)'],
            'D' => ['Descrição',           'Texto livre — até 500 caracteres'],
            'E' => ['Responsável',         'Nome do responsável pela área'],
            'F' => ['Sigla da Área Pai',   'Sigla de outra área desta mesma planilha (deixe em branco para nível raiz)'],
        ];

        foreach ($colunas as $col => [$header, $hint]) {
            $sheet->setCellValue("{$col}2", $header);
            $sheet->setCellValue("{$col}3", $hint);
        }

        $this->estilizarCabecalho($sheet, 'A2:F2');
        $this->estilizarDica($sheet, 'A3:F3');

        // ── Exemplos (linhas 4–6) ─────────────────────────────────────────────
        $exemplos = [
            ['PRESIDÊNCIA',                    'PRESIDENCIA',  '',            'Órgão de direção superior', 'Presidente', ''],
            ['Escola Corporativa e de Pesquisa','ECPBG',        'Finalística', 'Formação e capacitação',    '',           'PRESIDENCIA'],
            ['Gerência de Educação',            'GED',          'Finalística', '',                          '',           'ECPBG'],
        ];

        $row = 4;
        foreach ($exemplos as $ex) {
            $sheet->fromArray($ex, null, "A{$row}");
            $this->estilizarExemplo($sheet, "A{$row}:F{$row}");
            $row++;
        }

        // ── Linhas vazias para preenchimento (7–106) ──────────────────────────
        for ($r = 7; $r <= 106; $r++) {
            $this->estilizarLinhaDados($sheet, "A{$r}:F{$r}");
        }

        // ── Larguras ─────────────────────────────────────────────────────────
        $larguras = ['A' => 42, 'B' => 18, 'C' => 14, 'D' => 40, 'E' => 28, 'F' => 22];
        foreach ($larguras as $col => $w) {
            $sheet->getColumnDimension($col)->setWidth($w);
        }

        // ── Validação dropdown para Tipo (C7:C106) ────────────────────────────
        $this->adicionarDropdown($sheet, 'C7:C106', '"Meio,Finalística"');

        $writer = new Xlsx($sheet->getParent());
        $writer->save("{$destino}/template_areas.xlsx");
    }

    // ── Template Servidores ───────────────────────────────────────────────────

    private function gerarTemplateServidores(string $destino): void
    {
        $sheet = $this->criarPlanilha('Servidores');

        // ── Instruções ────────────────────────────────────────────────────────
        $sheet->mergeCells('A1:M1');
        $sheet->setCellValue('A1',
            'INSTRUÇÕES: Preencha uma linha por servidor. Campos com * são obrigatórios. ' .
            'A coluna "Sigla da Área" deve corresponder exatamente a uma sigla do template de Áreas. ' .
            'Datas no formato DD/MM/AAAA. O sistema gerará uma senha temporária automaticamente.'
        );
        $this->estilizarInstrucao($sheet, 'A1:M1');

        // ── Cabeçalhos (linha 2) ──────────────────────────────────────────────
        $colunas = [
            'A' => ['Nome Completo *',      'Exemplo: Maria Aparecida da Silva'],
            'B' => ['E-mail *',             'Exemplo: maria.silva@tce.pe.gov.br  (único no sistema)'],
            'C' => ['Matrícula *',          'Exemplo: 1234567  (única no sistema)'],
            'D' => ['Cargo *',              'Exemplo: Analista de Controle Externo'],
            'E' => ['Sigla da Área *',      'Deve constar no template de Áreas. Exemplo: DPLTI'],
            'F' => ['Perfil *',             'Valores aceitos: servidor  |  gestor  |  admin'],
            'G' => ['Data de Nascimento',   'Formato: DD/MM/AAAA'],
            'H' => ['Data de Ingresso',     'Formato: DD/MM/AAAA'],
            'I' => ['Escolaridade',         'Fundamental | Médio | Técnico | Superior | Especialização | Mestrado | Doutorado'],
            'J' => ['Gênero',               'Masculino | Feminino | Não binário | Outro | Prefiro não informar'],
            'K' => ['Raça / Cor',           'Branca | Preta | Parda | Amarela | Indígena | Prefiro não informar'],
        ];

        foreach ($colunas as $col => [$header, $hint]) {
            $sheet->setCellValue("{$col}2", $header);
            $sheet->setCellValue("{$col}3", $hint);
        }

        $this->estilizarCabecalho($sheet, 'A2:K2');
        $this->estilizarDica($sheet, 'A3:K3');

        // ── Exemplos (linhas 4–5) ─────────────────────────────────────────────
        $exemplos = [
            ['Maria Aparecida da Silva', 'maria.silva@tce.pe.gov.br',  '1234567', 'Analista de Controle Externo', 'DPLTI',    'servidor', '15/03/1985', '02/06/2010', 'Superior',       'Feminino',  'Parda'],
            ['João Carlos Mendes',       'joao.mendes@tce.pe.gov.br',  '9876543', 'Gerente',                      'ECPBG',    'gestor',   '22/11/1978', '15/01/2005', 'Especialização', 'Masculino', 'Branca'],
        ];

        $row = 4;
        foreach ($exemplos as $ex) {
            $sheet->fromArray($ex, null, "A{$row}");
            $this->estilizarExemplo($sheet, "A{$row}:K{$row}");
            $row++;
        }

        // ── Linhas vazias para preenchimento (6–205) ──────────────────────────
        for ($r = 6; $r <= 205; $r++) {
            $this->estilizarLinhaDados($sheet, "A{$r}:K{$r}");
        }

        // ── Larguras ─────────────────────────────────────────────────────────
        $larguras = [
            'A' => 36, 'B' => 34, 'C' => 14, 'D' => 34,
            'E' => 16, 'F' => 12, 'G' => 18, 'H' => 18,
            'I' => 22, 'J' => 22, 'K' => 22,
        ];
        foreach ($larguras as $col => $w) {
            $sheet->getColumnDimension($col)->setWidth($w);
        }

        // ── Validações dropdown ───────────────────────────────────────────────
        $this->adicionarDropdown($sheet, 'F6:F205', '"servidor,gestor,admin"');
        $this->adicionarDropdown($sheet, 'I6:I205', '"Fundamental,Médio,Técnico,Superior,Especialização,Mestrado,Doutorado"');
        $this->adicionarDropdown($sheet, 'J6:J205', '"Masculino,Feminino,Não binário,Outro,Prefiro não informar"');
        $this->adicionarDropdown($sheet, 'K6:K205', '"Branca,Preta,Parda,Amarela,Indígena,Prefiro não informar"');

        $writer = new Xlsx($sheet->getParent());
        $writer->save("{$destino}/template_servidores.xlsx");
    }

    // ── Helpers de estilo ─────────────────────────────────────────────────────

    private function criarPlanilha(string $titulo): \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($titulo);

        // Congelar painel abaixo da linha de dicas
        $sheet->freezePane('A4');

        return $sheet;
    }

    private function estilizarInstrucao(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet, string $range): void
    {
        $sheet->getStyle($range)->applyFromArray([
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['argb' => 'FF004395']],
            'font'      => ['color' => ['argb' => 'FFFFFFFF'], 'size' => 9, 'name' => 'Calibri'],
            'alignment' => ['wrapText' => true, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(42);
    }

    private function estilizarCabecalho(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet, string $range): void
    {
        $sheet->getStyle($range)->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['argb' => 'FF001A42']],
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 10, 'name' => 'Calibri'],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF2170E4']]],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(22);
    }

    private function estilizarDica(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet, string $range): void
    {
        $sheet->getStyle($range)->applyFromArray([
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['argb' => 'FFD8E2FF']],
            'font'      => ['italic' => true, 'color' => ['argb' => 'FF004395'], 'size' => 8, 'name' => 'Calibri'],
            'alignment' => ['wrapText' => true, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FF0058BE']]],
        ]);
        $sheet->getRowDimension(3)->setRowHeight(28);
    }

    private function estilizarExemplo(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet, string $range): void
    {
        $sheet->getStyle($range)->applyFromArray([
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['argb' => 'FFEFF6FF']],
            'font'      => ['italic' => true, 'color' => ['argb' => 'FF414657'], 'size' => 9, 'name' => 'Calibri'],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFC2C6D6']]],
        ]);
    }

    private function estilizarLinhaDados(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet, string $range): void
    {
        $sheet->getStyle($range)->applyFromArray([
            'font'      => ['size' => 10, 'name' => 'Calibri'],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFEAEEF2']]],
        ]);
        // Linhas alternadas levemente acinzentadas
        [$startCol, $startRow] = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::coordinateFromString(
            explode(':', $range)[0]
        );
        if ((int)$startRow % 2 === 0) {
            $sheet->getStyle($range)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFF6FAFE');
        }
    }

    private function adicionarDropdown(
        \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet,
        string $range,
        string $formula
    ): void {
        $validation = $sheet->getCell(explode(':', $range)[0])->getDataValidation();
        $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
        $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
        $validation->setAllowBlank(true);
        $validation->setShowDropDown(false);
        $validation->setFormula1($formula);
        $validation->setSqref($range);
    }
}
