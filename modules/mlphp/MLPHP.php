<?php

namespace MLPHP;

use MLPHP\LinearModel\LinearRegression;
use MLPHP\LinearModel\LogisticRegression;
use MLPHP\Cluster\KMeans;
use MLPHP\Metrics\RegressionMetrics;
use MLPHP\Metrics\ClassificationMetrics;
use MLPHP\PreProcessing\StandardScaler;

class MLPHP
{
    /**
     * Create a Linear Regression Model.
     */
    public static function LinearRegression(): LinearRegression
    {
        return new LinearRegression();
    }

    /**
     * Create a Logistic Regression Classifier.
     */
    public static function LogisticRegression(): LogisticRegression
    {
        return new LogisticRegression();
    }

    /**
     * Create a K-Means Clustering Model.
     */
    public static function KMeans(int $n_clusters = 8, int $max_iter = 300): KMeans
    {
        return new KMeans($n_clusters, $max_iter);
    }

    /**
     * StandardScaler for normalizing data.
     */
    public static function StandardScaler(): StandardScaler
    {
        return new StandardScaler();
    }

    /**
     * Calculate Mean Squared Error
     */
    public static function mean_squared_error($y_true, $y_pred): float
    {
        return RegressionMetrics::mean_squared_error($y_true, $y_pred);
    }

    /**
     * Calculate Accuracy Score
     */
    public static function accuracy_score($y_true, $y_pred): float
    {
        return ClassificationMetrics::accuracy_score($y_true, $y_pred);
    }
}
