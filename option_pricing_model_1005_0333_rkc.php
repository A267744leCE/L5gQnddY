<?php
// 代码生成时间: 2025-10-05 03:33:31
 * is structured to be easy to understand and maintain.
 */

require_once 'vendor/autoload.php';

use Symfony\Component\Validator\Constraints as Assert;

class OptionPricingModel {

    /**
     * Calculate the price of a European call option using the Black-Scholes formula.
     *
     * @param float $s The current price of the underlying asset.
     * @param float $k The strike price of the option.
     * @param float $t The time to expiration of the option (in years).
     * @param float $r The risk-free interest rate (as a decimal).
     * @param float $sigma The volatility of the underlying asset (as a decimal).
     * @return float The price of the option.
     * @throws InvalidArgumentException If any of the input parameters are invalid.
     */
    public function calculateCallPrice($s, $k, $t, $r, $sigma) {
        // Validate input parameters
        $this->validateParameters($s, $k, $t, $r, $sigma);

        // Calculate d1 and d2
        $d1 = (log($s / $k) + ($r + ($sigma ** 2) / 2) * $t) / ($sigma * sqrt($t));
        $d2 = $d1 - $sigma * sqrt($t);

        // Calculate the call price
        $callPrice = $s * exp(-$r * $t) * cnd($d1) - $k * exp(-$r * $t) * cnd($d2);

        return $callPrice;
    }

    /**
     * Calculate the price of a European put option using the Black-Scholes formula.
     *
     * @param float $s The current price of the underlying asset.
     * @param float $k The strike price of the option.
     * @param float $t The time to expiration of the option (in years).
     * @param float $r The risk-free interest rate (as a decimal).
     * @param float $sigma The volatility of the underlying asset (as a decimal).
     * @return float The price of the option.
     * @throws InvalidArgumentException If any of the input parameters are invalid.
     */
    public function calculatePutPrice($s, $k, $t, $r, $sigma) {
        // Validate input parameters
        $this->validateParameters($s, $k, $t, $r, $sigma);

        // Calculate d1 and d2
        $d1 = (log($s / $k) + ($r + ($sigma ** 2) / 2) * $t) / ($sigma * sqrt($t));
        $d2 = $d1 - $sigma * sqrt($t);

        // Calculate the put price
        $putPrice = $k * exp(-$r * $t) * cnd(-$d2) - $s * exp(-$r * $t) * cnd(-$d1);

        return $putPrice;
    }

    /**
     * Validate the input parameters for the Black-Scholes formula.
     *
     * @param float $s The current price of the underlying asset.
     * @param float $k The strike price of the option.
     * @param float $t The time to expiration of the option (in years).
     * @param float $r The risk-free interest rate (as a decimal).
     * @param float $sigma The volatility of the underlying asset (as a decimal).
     * @throws InvalidArgumentException If any of the input parameters are invalid.
     */
    private function validateParameters($s, $k, $t, $r, $sigma) {
        if ($s <= 0 || $k <= 0 || $t <= 0 || $r <= 0 || $sigma <= 0) {
            throw new InvalidArgumentException('All parameters must be positive.');
        }
    }

    /**
     * The cumulative distribution function of the standard normal distribution.
     *
     * @param float $x The value for which to calculate the CDF.
     * @return float The CDF value.
     */
    private function cnd($x) {
        $a1 = 0.319381530;
        $a2 = -0.356563782;
        $a3 = 1.781477937;
        $a4 = -1.821255978;
        $a5 = 1.330274429;
        $p = 0.231641933;
        $sign = 1;
        if ($x < 0) {
            $sign = -1;
        }
        $x = abs($x);
        $t = 1.0 / (1.0 + $p * $x);
        $y = 1.0 - (((($a5 * $t + $a4) * $t) + $a3) * $t + $a2) * $t * $exp(-$x * $x / 2.0);
        return (1.0 - $y) * $sign;
    }
}

// Example usage
try {
    $model = new OptionPricingModel();
    $callPrice = $model->calculateCallPrice(100, 110, 1, 0.05, 0.2);
    echo "The price of the call option is: "$callPrice"";

    $putPrice = $model->calculatePutPrice(100, 110, 1, 0.05, 0.2);
    echo "The price of the put option is: "$putPrice"";
} catch (InvalidArgumentException $e) {
    echo "Error: "$e->getMessage()"";
}
