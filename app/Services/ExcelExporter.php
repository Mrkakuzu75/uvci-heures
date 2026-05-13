<?php

namespace App\Services;

/**
 * Générateur XLSX natif — zéro dépendance externe
 * Produit un fichier Excel 2007+ (.xlsx) valide
 */
class ExcelExporter
{
    private array  $sheets = [];
    private string $title;

    public function __construct(string $title = 'Export')
    {
        $this->title = $title;
    }

    public function addSheet(string $name, array $headers, array $rows): self
    {
        $this->sheets[] = compact('name', 'headers', 'rows');
        return $this;
    }

    public function download(string $filename): void
    {
        $xlsx = $this->build();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($xlsx));
        header('Cache-Control: max-age=0');
        echo $xlsx;
        exit;
    }

    // ════════════════════════════════════════════════════════
    private function build(): string
    {
        $files = [];

        // [Content_Types].xml
        $ct = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . "\n<Types xmlns=\"http://schemas.openxmlformats.org/package/2006/content-types\">"
            . "\n  <Default Extension=\"rels\" ContentType=\"application/vnd.openxmlformats-package.relationships+xml\"/>"
            . "\n  <Default Extension=\"xml\"  ContentType=\"application/xml\"/>"
            . "\n  <Override PartName=\"/xl/workbook.xml\" ContentType=\"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml\"/>"
            . "\n  <Override PartName=\"/xl/styles.xml\"   ContentType=\"application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml\"/>"
            . "\n  <Override PartName=\"/xl/sharedStrings.xml\" ContentType=\"application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml\"/>";
        foreach ($this->sheets as $i => $_) {
            $n   = $i + 1;
            $ct .= "\n  <Override PartName=\"/xl/worksheets/sheet{$n}.xml\" ContentType=\"application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml\"/>";
        }
        $ct          .= "\n</Types>";
        $files['[Content_Types].xml'] = $ct;

        // _rels/.rels
        $files['_rels/.rels'] = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . "\n<Relationships xmlns=\"http://schemas.openxmlformats.org/package/2006/relationships\">"
            . "\n  <Relationship Id=\"rId1\" Type=\"http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument\" Target=\"xl/workbook.xml\"/>"
            . "\n</Relationships>";

        // xl/_rels/workbook.xml.rels
        $wr = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . "\n<Relationships xmlns=\"http://schemas.openxmlformats.org/package/2006/relationships\">";
        foreach ($this->sheets as $i => $_) {
            $n   = $i + 1;
            $wr .= "\n  <Relationship Id=\"rId{$n}\" Type=\"http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet\" Target=\"worksheets/sheet{$n}.xml\"/>";
        }
        $ns  = count($this->sheets);
        $wr .= "\n  <Relationship Id=\"rId" . ($ns + 1) . "\" Type=\"http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles\" Target=\"styles.xml\"/>";
        $wr .= "\n  <Relationship Id=\"rId" . ($ns + 2) . "\" Type=\"http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings\" Target=\"sharedStrings.xml\"/>";
        $wr .= "\n</Relationships>";
        $files['xl/_rels/workbook.xml.rels'] = $wr;

        // xl/workbook.xml
        $wb = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . "\n<workbook xmlns=\"http://schemas.openxmlformats.org/spreadsheetml/2006/main\""
            . " xmlns:r=\"http://schemas.openxmlformats.org/officeDocument/2006/relationships\">"
            . "\n  <sheets>";
        foreach ($this->sheets as $i => $sheet) {
            $n   = $i + 1;
            $nm  = htmlspecialchars($sheet['name'], ENT_XML1);
            $wb .= "\n    <sheet name=\"{$nm}\" sheetId=\"{$n}\" r:id=\"rId{$n}\"/>";
        }
        $wb                        .= "\n  </sheets>\n</workbook>";
        $files['xl/workbook.xml']   = $wb;
        $files['xl/styles.xml']     = $this->styles();

        // Construire les feuilles + shared strings
        $strings   = [];
        $sheetXmls = [];
        foreach ($this->sheets as $i => $sheet) {
            [$xml, $strings] = $this->buildSheet($sheet, $strings);
            $sheetXmls[$i]   = $xml;
        }

        // xl/sharedStrings.xml
        $ss = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . "\n<sst xmlns=\"http://schemas.openxmlformats.org/spreadsheetml/2006/main\""
            . " count=\"" . count($strings) . "\" uniqueCount=\"" . count($strings) . "\">";
        foreach ($strings as $str => $_) {
            $ss .= "\n  <si><t xml:space=\"preserve\">" . htmlspecialchars((string) $str, ENT_XML1) . "</t></si>";
        }
        $ss .= "\n</sst>";
        $files['xl/sharedStrings.xml'] = $ss;

        foreach ($sheetXmls as $i => $xml) {
            $files['xl/worksheets/sheet' . ($i + 1) . '.xml'] = $xml;
        }

        return $this->zip($files);
    }

    // ════════════════════════════════════════════════════════
    private function buildSheet(array $sheet, array &$strings): array
    {
        $headers = $sheet['headers'];
        $rows    = $sheet['rows'];
        $nbCols  = count($headers);

        $xml  = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $xml .= '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">';
        $xml .= '<sheetView workbookViewId="0"/>';
        $xml .= '<sheetFormatPr defaultRowHeight="17"/>';

        // Colonnes — largeur adaptative
        $xml .= '<cols>';
        for ($c = 0; $c < $nbCols; $c++) {
            $w   = $c === 0 ? 6 : ($c <= 1 ? 18 : ($c === 5 ? 32 : ($c >= 8 ? 16 : 20)));
            $xml .= '<col min="' . ($c + 1) . '" max="' . ($c + 1) . '" width="' . $w . '" customWidth="1"/>';
        }
        $xml .= '</cols>';
        $xml .= '<sheetData>';

        // ── Ligne d'en-tête (style 1) ──
        $xml .= '<row r="1" ht="20">';
        foreach ($headers as $ci => $h) {
            $col = $this->col($ci) . '1';
            $si  = $this->si($h, $strings);
            $xml .= "<c r=\"{$col}\" t=\"s\" s=\"1\"><v>{$si}</v></c>";
        }
        $xml .= '</row>';

        // ── Lignes de données ──
        foreach ($rows as $ri => $row) {
            $rowNum  = $ri + 2;
            $isTotal = isset($row['__total']) && $row['__total'];
            $values  = $isTotal ? array_values($row['values']) : array_values($row);
            $style   = $isTotal ? 4 : ($ri % 2 === 0 ? 2 : 3);

            $xml .= "<row r=\"{$rowNum}\" ht=\"16\">";
            foreach ($values as $ci => $val) {
                if ($ci >= $nbCols) break;
                $col = $this->col($ci) . $rowNum;
                if (is_numeric($val) && $val !== '' && !is_string($val)) {
                    $xml .= "<c r=\"{$col}\" s=\"{$style}\"><v>" . htmlspecialchars((string) $val, ENT_XML1) . "</v></c>";
                } else {
                    $si  = $this->si((string) $val, $strings);
                    $xml .= "<c r=\"{$col}\" t=\"s\" s=\"{$style}\"><v>{$si}</v></c>";
                }
            }
            $xml .= '</row>';
        }

        $xml .= '</sheetData>';
        $xml .= '<pageMargins left="0.7" right="0.7" top="0.75" bottom="0.75" header="0.3" footer="0.3"/>';
        $xml .= '<pageSetup orientation="landscape" fitToPage="1" fitToWidth="1" fitToHeight="0"/>';
        $xml .= '</worksheet>';

        return [$xml, $strings];
    }

    // ════════════════════════════════════════════════════════
    private function styles(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
  <fonts count="5">
    <font><sz val="10"/><name val="Calibri"/><color rgb="FF1A1A1A"/></font>
    <font><sz val="10"/><name val="Calibri"/><b/><color rgb="FFFFFFFF"/></font>
    <font><sz val="10"/><name val="Calibri"/><color rgb="FF1A1A1A"/></font>
    <font><sz val="10"/><name val="Calibri"/><b/><color rgb="FF009962"/></font>
    <font><sz val="10"/><name val="Calibri"/><b/><color rgb="FF0D1B2A"/></font>
  </fonts>
  <fills count="6">
    <fill><patternFill patternType="none"/></fill>
    <fill><patternFill patternType="gray125"/></fill>
    <fill><patternFill patternType="solid"><fgColor rgb="FF0D1B2A"/></patternFill></fill>
    <fill><patternFill patternType="solid"><fgColor rgb="FFE6FBF3"/></patternFill></fill>
    <fill><patternFill patternType="solid"><fgColor rgb="FFFAFBFC"/></patternFill></fill>
    <fill><patternFill patternType="solid"><fgColor rgb="FFD4F5E9"/></patternFill></fill>
  </fills>
  <borders count="2">
    <border><left/><right/><top/><bottom/><diagonal/></border>
    <border>
      <left style="thin"><color rgb="FFE2E8F0"/></left>
      <right style="thin"><color rgb="FFE2E8F0"/></right>
      <top style="thin"><color rgb="FFE2E8F0"/></top>
      <bottom style="thin"><color rgb="FFE2E8F0"/></bottom>
    </border>
  </borders>
  <cellStyleXfs count="1">
    <xf numFmtId="0" fontId="0" fillId="0" borderId="0"/>
  </cellStyleXfs>
  <cellXfs count="5">
    <!-- 0: Normal -->
    <xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0">
      <alignment vertical="center" wrapText="1"/>
    </xf>
    <!-- 1: Header navy fond blanc -->
    <xf numFmtId="0" fontId="1" fillId="2" borderId="1" xfId="0">
      <alignment vertical="center" horizontal="center"/>
    </xf>
    <!-- 2: Ligne paire fond vert clair -->
    <xf numFmtId="0" fontId="2" fillId="3" borderId="1" xfId="0">
      <alignment vertical="center"/>
    </xf>
    <!-- 3: Ligne impaire fond blanc cassé -->
    <xf numFmtId="0" fontId="2" fillId="4" borderId="1" xfId="0">
      <alignment vertical="center"/>
    </xf>
    <!-- 4: Total fond vert foncé -->
    <xf numFmtId="0" fontId="3" fillId="5" borderId="1" xfId="0">
      <alignment vertical="center" horizontal="right"/>
    </xf>
  </cellXfs>
</styleSheet>';
    }

    // ════════════════════════════════════════════════════════
    private function si(string $s, array &$strings): int
    {
        if (!isset($strings[$s])) {
            $strings[$s] = count($strings);
        }
        return $strings[$s];
    }

    private function col(int $idx): string
    {
        $col = '';
        $idx++;
        while ($idx > 0) {
            $idx--;
            $col  = chr(65 + ($idx % 26)) . $col;
            $idx  = intdiv($idx, 26);
        }
        return $col;
    }

    // ════════════════════════════════════════════════════════
    // ZIP natif PHP — aucune extension requise
    // ════════════════════════════════════════════════════════
    private function zip(array $files): string
    {
        $zip     = '';
        $offsets = [];

        foreach ($files as $name => $content) {
            $offsets[$name] = strlen($zip);
            $comp  = gzdeflate($content, 6);
            $crc   = crc32($content);
            $size  = strlen($content);
            $csize = strlen($comp);
            $dos   = $this->dos();
            $nlen  = strlen($name);

            $zip .= "\x50\x4b\x03\x04";
            $zip .= pack('v', 20);
            $zip .= pack('v', 0);
            $zip .= pack('v', 8);
            $zip .= pack('V', $dos);
            $zip .= pack('V', $crc);
            $zip .= pack('V', $csize);
            $zip .= pack('V', $size);
            $zip .= pack('v', $nlen);
            $zip .= pack('v', 0);
            $zip .= $name;
            $zip .= $comp;
        }

        $cdOff = strlen($zip);
        $cdLen = 0;

        foreach ($files as $name => $content) {
            $comp  = gzdeflate($content, 6);
            $crc   = crc32($content);
            $size  = strlen($content);
            $csize = strlen($comp);
            $dos   = $this->dos();
            $nlen  = strlen($name);

            $entry  = "\x50\x4b\x01\x02";
            $entry .= pack('v', 20);
            $entry .= pack('v', 20);
            $entry .= pack('v', 0);
            $entry .= pack('v', 8);
            $entry .= pack('V', $dos);
            $entry .= pack('V', $crc);
            $entry .= pack('V', $csize);
            $entry .= pack('V', $size);
            $entry .= pack('v', $nlen);
            $entry .= pack('v', 0);
            $entry .= pack('v', 0);
            $entry .= pack('v', 0);
            $entry .= pack('v', 0);
            $entry .= pack('V', 0);
            $entry .= pack('V', $offsets[$name]);
            $entry .= $name;
            $zip   .= $entry;
            $cdLen += strlen($entry);
        }

        $n    = count($files);
        $zip .= "\x50\x4b\x05\x06";
        $zip .= pack('v', 0) . pack('v', 0);
        $zip .= pack('v', $n) . pack('v', $n);
        $zip .= pack('V', $cdLen);
        $zip .= pack('V', $cdOff);
        $zip .= pack('v', 0);

        return $zip;
    }

    private function dos(): int
    {
        $t = getdate();
        return (($t['year'] - 1980) << 25) | ($t['mon'] << 21) | ($t['mday'] << 16)
             | ($t['hours'] << 11) | ($t['minutes'] << 5) | ($t['seconds'] >> 1);
    }
}
