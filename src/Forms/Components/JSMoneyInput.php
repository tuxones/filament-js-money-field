<?php

namespace Tuxones\JsMoneyField\Forms\Components;

use Closure;
use Filament\Forms\Components\TextInput;

class JSMoneyInput extends TextInput
{
    protected string $view = 'filament-js-money-field::forms.components.j-s-money-input';

    protected string | Closure $currency = 'USD';

    protected string | Closure $locale = 'en-US';

    protected function setUp(): void
    {
        parent::setUp();

        $this->dehydrateStateUsing(function (JSMoneyInput $component, $state): ?string {
            return filter_var($state, FILTER_SANITIZE_NUMBER_INT);
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
