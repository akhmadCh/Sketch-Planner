<?php

namespace App\Services;

class SapImportService
{
    /**
     * Parse CSV hasil ekspor SAP2000.
     * Mencari kolom 'Joint' dan 'F3' / 'Vertical Load' secara case-insensitive
     *
     * @param string $filePath
     * @return array of objects
     */
    public function parseCsv(string $filePath): array
    {
        $loads = [];

        if (!file_exists($filePath) || !is_readable($filePath)) {
            return $loads;
        }

        $header = null;
        $jointIndex = -1;
        $f3Index = -1;

        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (!$header) {
                    $header = array_map([$this, 'normalizeHeader'], $row);

                    foreach ($header as $index => $colName) {
                        if (str_contains($colName, 'joint') || $colName === 'j') {
                            $jointIndex = $index;
                        } elseif (str_contains($colName, 'f3') || str_contains($colName, 'vertical load') || str_contains($colName, 'fz')) {
                            $f3Index = $index;
                        }
                    }
                    continue;
                }

                // Pastikan kolom ditemukan
                if ($jointIndex !== -1 && $f3Index !== -1 && isset($row[$jointIndex], $row[$f3Index])) {
                    $jointId = trim($row[$jointIndex]);
                    $verticalLoad = (float) trim($row[$f3Index]);

                    if ($jointId !== '') {
                        $loads[] = (object) [
                            'joint' => $jointId,
                            'vertical_load' => $verticalLoad,
                            'f3' => $verticalLoad
                        ];
                    }
                }
            }
            fclose($handle);
        }

        return $loads;
    }

    /**
     * Trim and lowercase the string to ensure robust case-insensitive matching
     */
    protected function normalizeHeader(string $value): string
    {
        // Hapus karakter BOM, whitespace, dan kutip jika ada
        return strtolower(trim($value, " \t\n\r\0\x0B\xEF\xBB\xBF\"'"));
    }
}
