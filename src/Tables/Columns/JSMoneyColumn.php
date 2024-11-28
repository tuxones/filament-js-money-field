<?php


namespace Tuxones\JsMoneyField\Tables\Columns;

use Closure;
use Filament\Tables\Columns\TextColumn;

class JSMoneyColumn extends TextColumn
{
    protected string $view = 'filament-js-money-field::tables.columns.j-s-money-column';

    protected string | Closure $currency = 'USD';

    protected string | Closure $locale = 'en-US';

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
