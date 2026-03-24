<?php

namespace PandaPHP;

use PandaPHP\Core\DataFrame;
use PandaPHP\Core\Series;

class PandaPHP
{
    /**
     * Create a DataFrame object.
     */
    public static function DataFrame($data = [], $index = null, $columns = null): DataFrame
    {
        return new DataFrame($data, $index, $columns);
    }

    /**
     * Create a Series object.
     */
    public static function Series($data, $index = null, string $name = ''): Series
    {
        return new Series($data, $index, $name);
    }

    /**
     * Read a comma-separated values (csv) file into DataFrame.
     */
    public static function read_csv(string $filepath, array $options = []): DataFrame
    {
        $delimiter = $options['delimiter'] ?? ',';
        $header = $options['header'] ?? 0; // row index to use as column names

        if (!file_exists($filepath)) {
            throw new \Exception("File not found: $filepath");
        }

        $file = fopen($filepath, 'r');
        $data = [];
        $columns = null;
        $row_idx = 0;

        while (($row = fgetcsv($file, 0, $delimiter)) !== false) {
            if ($header !== null && $row_idx === $header) {
                $columns = $row;
            } else {
                // Try to convert numeric strings to actual numbers
                $parsed_row = array_map(function($val) {
                    if (is_numeric($val)) {
                        return strpos($val, '.') !== false ? (float)$val : (int)$val;
                    }
                    return $val;
                }, $row);
                $data[] = $parsed_row;
            }
            $row_idx++;
        }
        fclose($file);

        return new DataFrame($data, null, $columns);
    }

    /**
     * Write object to a comma-separated values (csv) file.
     */
    public static function to_csv(DataFrame $df, string $filepath, array $options = [])
    {
        $delimiter = $options['delimiter'] ?? ',';
        $index = $options['index'] ?? true;

        $file = fopen($filepath, 'w');

        $shape = $df->shape();
        $cols = $df->getColumns($df->shape()[1] > 0 ? array_keys($df->iloc(0,1)->shape()[1] ?? []) : []); // Hack to get columns if they are not exposed

        // This relies on __toString or similar, let's implement a proper export in DataFrame later
        // For now, let's use the reflection or string output
        $reflection = new \ReflectionClass($df);
        $columnsProp = $reflection->getProperty('columns');
        $columnsProp->setAccessible(true);
        $columns = $columnsProp->getValue($df);

        $indexProp = $reflection->getProperty('index');
        $indexProp->setAccessible(true);
        $idx = $indexProp->getValue($df);

        $dataProp = $reflection->getProperty('data');
        $dataProp->setAccessible(true);
        $data = $dataProp->getValue($df);

        // Write header
        $header = $columns;
        if ($index) {
            array_unshift($header, '');
        }
        fputcsv($file, $header, $delimiter);

        // Write rows
        for ($i = 0; $i < $shape[0]; $i++) {
            $row = [];
            if ($index) {
                $row[] = $idx[$i];
            }
            foreach ($columns as $col) {
                $row[] = $data[$col]->getData()[$i];
            }
            fputcsv($file, $row, $delimiter);
        }

        fclose($file);
    }
}
