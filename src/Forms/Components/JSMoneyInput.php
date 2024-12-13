<?php

namespace Tuxones\JsMoneyField\Forms\Components;

use Closure;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;

class JSMoneyInput extends TextInput
{
    protected string $view = 'filament-js-money-field::forms.components.j-s-money-input';

    protected string | Closure $currency = 'USD';

    protected string | Closure $locale = 'en-US';

    protected function setUp(): void
    {
        parent::setUp();

        $this->dehydrateStateUsing(function (JSMoneyInput $component, $state): ?string {
            $type = Schema::getColumnType($this->getModelInstance()->getTable(), $component->name);
            $sanitized = filter_var($state, FILTER_SANITIZE_NUMBER_INT);

            if (in_array($type, ['decimal', 'float', 'double'])) {
                $currencies = new ISOCurrencies();
                $formatter = new DecimalMoneyFormatter($currencies);
                $money = new Money($sanitized, new Currency($component->getCurrency()));

                return (string) $formatter->format($money);
            }

            return (string) $sanitized;
        });
    }


    public function currency(string | Closure $condition): static
    {
        $this->currency = $condition;

        return $this;
    }

    public function locale(string | Closure $condition): static
    {
        $this->locale = $condition;

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
