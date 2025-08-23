<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['user_id', 'parent_id', 'title', 'description', 'due_date', 'order', 'priority', 'status', 'task_type_id'];

    protected $casts = [
        'due_date' => 'datetime',
        'priority' => 'string',
        'status' => 'string',
    ];

    /**
     * Normalize various CSV/Excel date formats into a Carbon instance.
     */
    public function setDueDateAttribute($value): void
    {
        if ($value === null || $value === '') {
            $this->attributes['due_date'] = null;
            return;
        }

        // If it's already a Carbon/DateTime or valid timestamp-like string, let Carbon try first
        try {
            if ($value instanceof \DateTimeInterface) {
                $this->attributes['due_date'] = $value;
                return;
            }
        } catch (\Throwable $e) {
            // fallthrough to parsing attempts
        }

        $stringValue = is_string($value) ? trim($value) : (string) $value;

        // Common explicit formats we want to accept
        $formats = [
            'Y-m-d H:i:s', // Laravel typical
            'Y-m-d',       // ISO date
            'd/m/Y H:i',   // 31/12/2025 14:30
            'd/m/Y',       // 31/12/2025
            'm/d/Y H:i',   // 12/31/2025 14:30
            'm/d/Y',       // 12/31/2025
            'd-m-Y H:i',   // 31-12-2025 14:30
            'd-m-Y',       // 31-12-2025
            'm-d-Y H:i',   // 12-31-2025 14:30
            'm-d-Y',       // 12-31-2025
        ];

        foreach ($formats as $format) {
            try {
                $parsed = \Illuminate\Support\Carbon::createFromFormat($format, $stringValue);
                if ($parsed !== false) {
                    $this->attributes['due_date'] = $parsed;
                    return;
                }
            } catch (\Throwable $e) {
                // try next format
            }
        }

        // Excel serial number support (e.g., when CSV came from Excel)
        if (is_numeric($stringValue)) {
            try {
                $excelDate = (float) $stringValue;
                // Excel epoch starts 1899-12-30. Convert days to seconds.
                $dateTime = (new \DateTimeImmutable('1899-12-30'))
                    ->modify("+{$excelDate} days");
                $this->attributes['due_date'] = \Illuminate\Support\Carbon::instance(\DateTime::createFromImmutable($dateTime));
                return;
            } catch (\Throwable $e) {
                // ignore
            }
        }

        // Last resort: let Carbon try to parse loosely
        try {
            $this->attributes['due_date'] = \Illuminate\Support\Carbon::parse($stringValue);
            return;
        } catch (\Throwable $e) {
            // If parsing fails, set null to avoid validation errors
            $this->attributes['due_date'] = null;
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    public function dependents()
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    public function taskType()
    {
        return $this->belongsTo(TaskType::class, 'task_type_id');
    }
}
