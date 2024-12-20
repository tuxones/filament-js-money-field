<?php


namespace Tuxones\JsMoneyField\Tables\Columns;

use Closure;
use Filament\Tables\Columns\TextColumn;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;

class JSMoneyColumn extends TextColumn
{

    protected string | Closure $currency = 'USD';

    protected string | Closure $locale = 'en-US';

    protected function setUp(): void
    {
        parent::setUp();
        $this->reloadFormatStateUsing();
    }

    private function reloadFormatStateUsing()
    {
        $this->formatStateUsing(function (JSMoneyColumn $column, string $state){
            $sanitized = filter_var($state, FILTER_SANITIZE_NUMBER_INT);
            $money = new Money($sanitized, new Currency($column->getCurrency()));
            $currencies = new ISOCurrencies();

            $numberFormatter = new \NumberFormatter($column->getLocale(), \NumberFormatter::CURRENCY);
            $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

            return $moneyFormatter->format($money);
        });
    }

    public function currency(string | Closure $condition): static
    {
        $this->currency = $condition;
        $this->reloadFormatStateUsing();
        return $this;
    }

    public function locale(string | Closure $condition): static
    {
        $this->locale = $condition;
        $this->reloadFormatStateUsing();
        return $this;
    }

    public function getCurrency(): string | null
    {
        return $this->evaluate($this->currency);
    }

    public function getLocale(): string | null
    {
        return $this->evaluate($this->locale);
    }
}
