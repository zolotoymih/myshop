<?php


namespace App\Services;


use App\Models\Currency;
use Carbon\Carbon;
use phpDocumentor\Reflection\Types\Self_;

class CurrencyConversion
{
    protected static $container;

    public static function loadContainer()
    {
        if (is_null(self::$container)) {
            $currencies = Currency::get();
            foreach ($currencies as $currency) {
                self::$container[$currency->code] = $currency;
            }
        }
    }

    public static function getCurrencies()
    {
        self::loadContainer();

        return self::$container;
    }

    public static function convert($sum, $originCurrencyCode = 'RUB', $targetCurrencyCode = null)
    {
        self::loadContainer();

        //$originCurrency = Currency::byCode($originCurrencyCode)->first();
        $originCurrency = self::$container[$originCurrencyCode];
//        dd($originCurrency->updated_at);
//        if ($originCurrency->updated_at== Null ){
        if ($originCurrency->rate != 0 || $originCurrency->updated_at->startOfDay() != Carbon::now()->startOfDay()) {
            CurrencyRates::getRates();
            self::loadContainer();
            $originCurrency = self::$container[$originCurrencyCode];
        }

        if (is_null($targetCurrencyCode)) {
            $targetCurrencyCode = session('currency', 'RUB');
        }
        //  $targetCurrency = Currency::byCode($targetCurrencyCode)->first();
        $targetCurrency = self::$container[$targetCurrencyCode];
//        if ($targetCurrency->updated_at== Null ){
        if ($targetCurrency->rate == 0 || $targetCurrency->updated_at->startOfDay() != Carbon::now()->startOfDay()) {
            CurrencyRates::getRates();
            self::loadContainer();
            $targetCurrency = self::$container[$targetCurrencyCode];

        }
        return $sum / $originCurrency->rate * $targetCurrency->rate;
    }

    public static function getCurrencySymbol()
    {
        self::loadContainer();
        $currencyFromSession = session('currency', 'RUB');
        // $currency = Currency::byCode();
        $currency = self::$container[$currencyFromSession];
        return $currency->symbol;
    }

    public static function getBaseCurrency()
    {
        self::loadContainer();

        foreach (self::$container as $code => $currency) {
            if ($currency->isMain()) {
                return $currency;
            }
        }

    }
}
