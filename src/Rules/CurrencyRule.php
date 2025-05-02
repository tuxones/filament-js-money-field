<?php

namespace Tuxones\JsMoneyField\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Money\Currency;
use Money\Currencies\ISOCurrencies;
use Money\Exception\ParserException;
use Money\Parser\DecimalMoneyParser;
use NumberFormatter;

class CurrencyRule implements ValidationRule
{
    protected string $currencyCode;
    protected string $locale;

    public function __construct(string $currencyCode, string $locale = 'en_US')
    {
        $this->currencyCode = strtoupper($currencyCode);
        $this->locale = $locale;
    }

    public function validate(string $attribute, mixed $value, \Closure $fail): void {
        $attributeName = str($attribute)->afterLast('.');

        try {
            $currencies = new ISOCurrencies();
            $currency = new Currency($this->currencyCode);

            if (!$currencies->contains($currency)) {
                $fail(__('The currency (:code) is not supported.', ['code' => $this->currencyCode]));
                return;
            }

            $formatter = new NumberFormatter($this->locale, NumberFormatter::DECIMAL);
            $normalized = $formatter->parse($value);

            if ($normalized === false) {
                $fail(__('The :attribute field must be a valid monetary value for currency :code.', [
                    'attribute' => $attributeName,
                    'code' => $this->currencyCode,
                ]));
                return;
            }

            $decimalValue = number_format((float)$normalized, 2, '.', '');

            $parser = new DecimalMoneyParser($currencies);
            $parser->parse($decimalValue, $currency);
        } catch (ParserException $e) {
            $fail(__('The :attribute field must be a valid monetary value for currency :code.', [
                'attribute' => $attributeName,
                'code' => $this->currencyCode,
            ]));
        } catch (\Throwable $e) {
            $fail(__('Unexpected error validating :attribute field for currency :code.', [
                'attribute' => $attributeName,
                'code' => $this->currencyCode,
            ]));
        }
    }
}
