<?php

namespace App\Filament\Resources\TaskResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Filament\Resources\TaskResource;
use Filament\Resources\Pages\EditRecord;

class EditTask extends EditRecord
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $response = Http::post('http://smart-task-management-app.test/predict', [
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'due_date' => $data['due_date'] ?? null,
        ]);

        $data['priority'] = $response->successful() ? $response->json()['priority'] : 'Low';

        $data['user_id'] = Auth::id();
        return $data;
    }
}
