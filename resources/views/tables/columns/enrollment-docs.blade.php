<div>
    @if($getRecord()->psa)
        <div class="flex items-center gap-2">
            <div>PSA</div>
            <x-filament::icon
                alias="panels::topbar.global-search.field"
                icon="heroicon-o-check-circle"
                wire:target="search"
                class="h-5 w-5"
                style="color: green;"
            />
        </div>
    @else
    <div class="flex items-center gap-2 ">
            <div>PSA</div>
            <x-filament::icon
                alias="panels::topbar.global-search.field"
                icon="heroicon-o-x-circle"
                wire:target="search"
                class="h-5 w-5"
                 style="color: orange;"
            />
        </div>
    @endif
    @if($getRecord()->form137)
        <div class="flex items-center gap-2">
            <div>Form 137</div>
            <x-filament::icon
                alias="panels::topbar.global-search.field"
                icon="heroicon-o-check-circle"
                wire:target="search"
                class="h-5 w-5"
                 style="color: green;"
            />
        </div>
    @else
    <div class="flex items-center gap-2 ">
            <div>Form 137</div>
            <x-filament::icon
                alias="panels::topbar.global-search.field"
                icon="heroicon-o-x-circle"
                wire:target="search"
                class="h-5 w-5"
                 style="color: orange;"
            />
        </div>
    @endif
    @if($getRecord()->report_card)
        <div class="flex items-center gap-2">
            <div>Report Card</div>
            <x-filament::icon
                alias="panels::topbar.global-search.field"
                icon="heroicon-o-check-circle"
                wire:target="search"
                class="h-5 w-5"
                 style="color: green;"
            />
        </div>
    @else
    <div class="flex items-center gap-2 ">
            <div>Report Card</div>
            <x-filament::icon
                alias="panels::topbar.global-search.field"
                icon="heroicon-o-x-circle"
                wire:target="search"
                class="h-5 w-5"
                 style="color: orange;"
            />
        </div>
    @endif
</div>
