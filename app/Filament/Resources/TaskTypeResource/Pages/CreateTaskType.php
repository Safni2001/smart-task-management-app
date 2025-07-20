<?php

namespace App\Filament\Resources\TaskTypeResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\TaskTypeResource;

class CreateTaskType extends CreateRecord
{
    protected static string $resource = TaskTypeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();
        return $data;
    }
}
