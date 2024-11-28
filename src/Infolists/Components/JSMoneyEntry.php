<?php

namespace Tuxones\JsMoneyField\Infolists\Components;

use Closure;
use Filament\Infolists\Components\TextEntry;

class JSMoneyEntry extends TextEntry
{
    protected string $view = 'filament-js-money-field::infolists.components.j-s-money-entry';

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
