<?php

namespace DatasetPHP;

use DatasetPHP\Utils\Splitter;
use DatasetPHP\Loaders\CSVLoader;

class DatasetPHP
{
    /**
     * Split arrays or matrices into random train and test subsets.
     * Equivalent to sklearn.model_selection.train_test_split.
     *
     * @param array $X Input features.
     * @param array $y Target labels.
     * @param float $test_size Proportion of the dataset to include in the test split.
     * @return array [X_train, X_test, y_train, y_test]
     */
    public static function train_test_split(array $X, array $y, float $test_size = 0.25): array
    {
        return Splitter::train_test_split($X, $y, $test_size);
    }

    /**
     * Load a custom CSV dataset, returning features and labels separately.
     *
     * @param string $filepath Path to the CSV file.
     * @param string|int $target_column The column name or index representing the target variable.
     * @return array ['X' => [...], 'y' => [...]]
     */
    public static function load_csv(string $filepath, $target_column): array
    {
        return CSVLoader::load($filepath, $target_column);
    }
}
