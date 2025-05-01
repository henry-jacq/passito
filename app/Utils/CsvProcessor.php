<?php

namespace App\Utils;

class CsvProcessor
{
    /**
     * Write data to a CSV file
     *
     * @param string $filePath Full path to the file
     * @param array $headers Array of column names
     * @param array $rows Array of row arrays
     * @return bool
     */
    public function writeToFile(string $filePath, array $headers, array $rows): bool
    {
        $handle = fopen($filePath, 'w');
        if (!$handle) return false;

        fputcsv($handle, $headers);
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);
        return true;
    }

    /**
     * Map structured data into array of CSV rows
     *
     * @param iterable $data
     * @param callable $mapper
     * @return array
     */
    public function mapDataToRows(iterable $data, callable $mapper): array
    {
        $rows = [];
        foreach ($data as $item) {
            $rows[] = $mapper($item);
        }
        return $rows;
    }

    /**
     * Read and parse CSV into array of rows (associative by header)
     *
     * @param string $filePath
     * @param bool $hasHeader
     * @param string $delimiter
     * @return array [ 'data' => [...], 'errors' => [...], 'headers' => [...] ]
     */
    public function readFromFile(string $filePath, bool $hasHeader = true, string $delimiter = ','): array
    {
        $data = [];
        $errors = [];
        $headers = [];

        if (!file_exists($filePath) || !is_readable($filePath)) {
            return ['data' => [], 'errors' => ['File not readable'], 'headers' => []];
        }

        if (($handle = fopen($filePath, 'r')) !== false) {
            $rowIndex = 0;
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if ($rowIndex === 0 && $hasHeader) {
                    $headers = $row;
                } else {
                    if ($hasHeader) {
                        if (count($row) !== count($headers)) {
                            $errors[] = "Row $rowIndex has invalid column count.";
                            continue;
                        }
                        $data[] = array_combine($headers, $row);
                    } else {
                        $data[] = $row;
                    }
                }
                $rowIndex++;
            }
            fclose($handle);
        }

        return ['data' => $data, 'errors' => $errors, 'headers' => $headers];
    }

    /**
     * Validate required headers in the CSV
     *
     * @param array $csvHeaders
     * @param array $requiredHeaders
     * @return array [isValid => bool, missing => []]
     */
    public function validateHeaders(array $csvHeaders, array $requiredHeaders): array
    {
        $missing = array_diff($requiredHeaders, $csvHeaders);
        return [
            'isValid' => empty($missing),
            'missing' => $missing
        ];
    }

    /**
     * Detect delimiter (rudimentary detection from first line)
     *
     * @param string $filePath
     * @return string
     */
    public function detectDelimiter(string $filePath): string
    {
        $delimiters = [",", ";", "\t", "|"];
        $line = fgets(fopen($filePath, 'r'));
        $counts = [];

        foreach ($delimiters as $delimiter) {
            $counts[$delimiter] = count(str_getcsv($line, $delimiter));
        }

        return array_search(max($counts), $counts) ?: ',';
    }
}
