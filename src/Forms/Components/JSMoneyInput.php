<?php

namespace Tuxones\JsMoneyField\Forms\Components;

use Closure;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Schema;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;
use Tuxones\JsMoneyField\Rules\CurrencyRule;

class JSMoneyInput extends TextInput
{
    protected string $view = 'filament-js-money-field::forms.components.j-s-money-input';

    protected string | Closure $currency = 'USD';

    protected string | Closure $locale = 'en-US';

    protected function setUp(): void
    {
        parent::setUp();

        $this->dehydrateStateUsing(function (JSMoneyInput $component, $state): ?string {
            $sanitized = ltrim(preg_replace('/\D/', '', $state), '0');
            if(!$sanitized) {
                return null;
            }
            if (in_array($this->getColumnType($component), ['decimal', 'float', 'double'])) {
                $currencies = new ISOCurrencies();
                $formatter = new DecimalMoneyFormatter($currencies);
                $money = new Money($sanitized, new Currency($component->getCurrency()));

                return (string) $formatter->format($money);
            }

            return (string) $sanitized;
        });

        $this->rules([
            function() {
                return new CurrencyRule($this->getCurrency(), $this->getLocale());
            },
        ]);
    }

    private function getColumnType(JSMoneyInput $component)
    {
        $model = $this->getModelInstance();
        if (str_contains($component->name, '.')) {
            $relationshipName = \Str::beforeLast($component->name, '.');
            $columnName = \Str::afterLast($component->name, '.');

            if (method_exists($model, $relationshipName)) {
                $relatedModel = $model->{$relationshipName}()->getRelated();
                return Schema::getColumnType($relatedModel->getTable(), $columnName);
            }
        }
        return Schema::getColumnType($model->getTable(), $component->name);
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
