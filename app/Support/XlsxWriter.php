<?php

namespace App\Support;

use DateTimeInterface;
use RuntimeException;
use ZipArchive;

// Writes a single-sheet .xlsx file using PHP's zip extension (no spreadsheet library
// is installed). Strings are written inline, so values such as "=SUM(...)" stay text.
//
// $columns: [['title' => 'Name', 'width' => 20, 'type' => 'string|number|datetime|month'], ...]
// $rows:    iterable of arrays whose values line up with $columns. Null leaves a cell empty;
//           datetime/month columns take DateTimeInterface values, shown in their own timezone.
class XlsxWriter
{
    const STYLE_HEADER = 1;
    const STYLE_DATETIME = 2;
    const STYLE_MONTH = 3;

    public static function write($path, $sheetName, array $columns, iterable $rows)
    {
        $sheetPath = tempnam(sys_get_temp_dir(), 'xlsx-sheet-');
        try {
            $rowCount = self::writeSheet($sheetPath, $columns, $rows);

            $zip = new ZipArchive();
            if ($zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                throw new RuntimeException("Could not create $path");
            }
            $zip->addFromString('[Content_Types].xml', self::contentTypes());
            $zip->addFromString('_rels/.rels', self::rootRels());
            $zip->addFromString('xl/workbook.xml', self::workbook($sheetName));
            $zip->addFromString('xl/_rels/workbook.xml.rels', self::workbookRels());
            $zip->addFromString('xl/styles.xml', self::styles());
            $zip->addFile($sheetPath, 'xl/worksheets/sheet1.xml');
            if (!$zip->close()) {
                throw new RuntimeException("Could not write $path");
            }

            return $rowCount;
        } finally {
            @unlink($sheetPath);
        }
    }

    private static function writeSheet($sheetPath, array $columns, iterable $rows)
    {
        $out = fopen($sheetPath, 'wb');
        $lastColumn = self::columnLetter(count($columns) - 1);

        // The dimension and filter ranges need the row count, so rows are written first
        // and the sheet is assembled around them.
        $body = fopen('php://temp', 'w+b');
        $cells = '';
        foreach ($columns as $i => $column) {
            $cells .= self::stringCell(self::columnLetter($i) . '1', $column['title'], self::STYLE_HEADER);
        }
        fwrite($body, '<row r="1">' . $cells . '</row>');

        $r = 1;
        foreach ($rows as $row) {
            $r++;
            $cells = '';
            foreach ($columns as $i => $column) {
                $cells .= self::cell(self::columnLetter($i) . $r, $row[$i] ?? null, $column['type'] ?? 'string');
            }
            fwrite($body, '<row r="' . $r . '">' . $cells . '</row>');
        }

        $range = 'A1:' . $lastColumn . $r;
        $cols = '';
        foreach ($columns as $i => $column) {
            $n = $i + 1;
            $cols .= '<col min="' . $n . '" max="' . $n . '" width="' . ($column['width'] ?? 15) . '" customWidth="1"/>';
        }

        fwrite($out, '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<dimension ref="' . $range . '"/>'
            . '<sheetViews><sheetView workbookViewId="0"><pane ySplit="1" topLeftCell="A2" activePane="bottomLeft" state="frozen"/></sheetView></sheetViews>'
            . '<sheetFormatPr defaultRowHeight="15"/>'
            . '<cols>' . $cols . '</cols>'
            . '<sheetData>');
        rewind($body);
        stream_copy_to_stream($body, $out);
        fclose($body);
        fwrite($out, '</sheetData><autoFilter ref="' . $range . '"/></worksheet>');
        fclose($out);

        return $r - 1;
    }

    private static function cell($ref, $value, $type)
    {
        if ($value === null || $value === '') {
            return '';
        }
        if (($type === 'datetime' || $type === 'month') && $value instanceof DateTimeInterface) {
            // Excel can't show dates before 1900 as dates, and its serials are off by one
            // before March 1900, so those are written as text.
            if ($value->format('Y-m-d') < '1900-03-01') {
                return self::stringCell($ref, $value->format($type === 'month' ? 'M Y' : 'd M Y H:i'));
            }
            $serial = 25569 + ($value->getTimestamp() + $value->getOffset()) / 86400;
            $style = $type === 'month' ? self::STYLE_MONTH : self::STYLE_DATETIME;

            return '<c r="' . $ref . '" s="' . $style . '"><v>' . self::number($serial) . '</v></c>';
        }
        if ($type === 'number' && is_numeric($value)) {
            return '<c r="' . $ref . '"><v>' . self::number($value) . '</v></c>';
        }

        return self::stringCell($ref, $value);
    }

    private static function stringCell($ref, $value, $style = 0)
    {
        $style = $style ? ' s="' . $style . '"' : '';

        return '<c r="' . $ref . '" t="inlineStr"' . $style . '><is><t xml:space="preserve">' . self::escape($value) . '</t></is></c>';
    }

    private static function escape($value)
    {
        $value = mb_scrub((string) $value, 'UTF-8');
        // Drop characters XML 1.0 doesn't allow (control characters other than tab/newlines).
        $value = preg_replace('/[^\x{9}\x{A}\x{D}\x{20}-\x{D7FF}\x{E000}-\x{FFFD}\x{10000}-\x{10FFFF}]/u', '', $value);

        return htmlspecialchars(mb_substr($value, 0, 32767), ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    private static function number($value)
    {
        return rtrim(rtrim(sprintf('%.10F', $value), '0'), '.');
    }

    // 0 => A, 25 => Z, 26 => AA
    private static function columnLetter($index)
    {
        $letter = '';
        for ($n = $index + 1; $n > 0; $n = intdiv($n - 1, 26)) {
            $letter = chr(65 + ($n - 1) % 26) . $letter;
        }

        return $letter;
    }

    private static function contentTypes()
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            . '</Types>';
    }

    private static function rootRels()
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '</Relationships>';
    }

    private static function workbook($sheetName)
    {
        // Sheet names: max 31 characters, none of []:*?/\
        $sheetName = mb_substr(str_replace(['[', ']', ':', '*', '?', '/', '\\'], '', $sheetName), 0, 31) ?: 'Sheet1';

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets><sheet name="' . self::escape($sheetName) . '" sheetId="1" r:id="rId1"/></sheets>'
            . '</workbook>';
    }

    private static function workbookRels()
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            . '</Relationships>';
    }

    // Style indexes match the STYLE_* constants.
    private static function styles()
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<numFmts count="2"><numFmt numFmtId="164" formatCode="dd mmm yyyy hh:mm"/><numFmt numFmtId="165" formatCode="mmm yyyy"/></numFmts>'
            . '<fonts count="2">'
            . '<font><sz val="11"/><name val="Calibri"/><family val="2"/></font>'
            . '<font><b/><sz val="11"/><name val="Calibri"/><family val="2"/></font>'
            . '</fonts>'
            . '<fills count="3">'
            . '<fill><patternFill patternType="none"/></fill>'
            . '<fill><patternFill patternType="gray125"/></fill>'
            . '<fill><patternFill patternType="solid"><fgColor rgb="FFD9E1F2"/><bgColor indexed="64"/></patternFill></fill>'
            . '</fills>'
            . '<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="4">'
            . '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
            . '<xf numFmtId="0" fontId="1" fillId="2" borderId="0" xfId="0" applyFont="1" applyFill="1"/>'
            . '<xf numFmtId="164" fontId="0" fillId="0" borderId="0" xfId="0" applyNumberFormat="1"/>'
            . '<xf numFmtId="165" fontId="0" fillId="0" borderId="0" xfId="0" applyNumberFormat="1"/>'
            . '</cellXfs>'
            . '<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>'
            . '</styleSheet>';
    }
}
