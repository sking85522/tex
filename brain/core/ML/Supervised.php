<?php
namespace Core\ML;

require_once dirname(__DIR__) . '/Matrix/MatrixOps.php';

use Core\Matrix\MatrixOps;

class Supervised {
    // Keep stub namespace available maybe
}

class LinearRegression {
    private ?\NumPHP\Core\NDArray $weights = null;

    /**
     * Train the model using Ordinary Least Squares (OLS)
     * Formula: w = (X^T * X)^-1 * X^T * y
     * 
     * @param array $X The feature matrix (2D array)
     * @param array $y The target continuous values (1D array)
     */
    public function fit(array $X, array $y): void {
        // Convert input data to NDArrays
        $xMatrix = MatrixOps::create($X);
        
        // y needs to be converted into a column vector [n x 1]
        $yCol = [];
        foreach($y as $val) {
             $yCol[] = [$val];
        }
        $yMatrix = MatrixOps::create($yCol);

        // 1. Transpose X (X^T)
        $xT = MatrixOps::transpose($xMatrix);
        
        // 2. Multiply X^T * X
        $xTx = MatrixOps::dot($xT, $xMatrix);
        
        // 3. Inverse of (X^T * X)
        $xTx_inv = MatrixOps::inverse($xTx);
        
        // 4. Multiply inverse by X^T
        $xTx_inv_xT = MatrixOps::dot($xTx_inv, $xT);
        
        // 5. Final Weights: Multiply by y
        $this->weights = MatrixOps::dot($xTx_inv_xT, $yMatrix);
    }

    /**
     * Predict continuous values for given Feature matrix X
     */
    public function predict(array $X): array {
        if ($this->weights === null) {
            throw new \Exception("Model is not trained yet.");
        }

        $xMatrix = MatrixOps::create($X);
        
        // predictions = X * weights
        $preds = MatrixOps::dot($xMatrix, $this->weights);
        
        // Return flatten array (since preds is a column vector NDArray right now)
        // Extracting data array safely
        $rawPreds = $preds->getData(); 
        
        $flattened = [];
        foreach ($rawPreds as $row) {
            $flattened[] = is_array($row) ? $row[0] : $row;
        }

        return $flattened;
    }

    /**
     * Get the learned weights array
     */
    public function getWeights(): array {
        if ($this->weights === null) return [];
        
        $weightsData = $this->weights->getData();
        $flat = [];
        foreach ($weightsData as $row) {
            $flat[] = is_array($row) ? $row[0] : $row;
        }
        return $flat;
    }
}
