<?php
namespace App\Filament\Resources\Actions;

use Filament\Tables\Actions\Action;
use Illuminate\Support\Collection;

class ChangeLanguageAction extends Action
{
    public function handle(Collection $records, $language): void
    {
        // Здесь вы можете выполнить логику для смены языка вашего контента
        foreach ($records as $record) {
            // Пример: изменение языка колонки "content"
            $record->update(['content' => $record->getTranslation('content', $language)]);
        }
    }
}
