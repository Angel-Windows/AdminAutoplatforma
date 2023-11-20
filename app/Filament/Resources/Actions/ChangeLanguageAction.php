<?php
namespace App\Filament\Resources\Actions;

use Filament\Tables\Actions\Action;
use Illuminate\Support\Collection;

class ChangeLanguageAction extends Action
{
    public function handle(Collection $records, $language): void
    {
        foreach ($records as $record) {
            $record->update(['content' => $record->getTranslation('content', $language)]);
        }
    }
}
