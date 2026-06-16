<?php

namespace App\Imports;

use App\Models\BoqItem;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Validation\Rule;

class BoqItemsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, WithBatchInserts, WithChunkReading
{
    protected int $boqId;
    public int $imported = 0;
    public array $failures = [];

    public function __construct(int $boqId)
    {
        $this->boqId = $boqId;
    }

    public function model(array $row): ?BoqItem
    {
        $qty = (float) ($row['quantity'] ?? 0);
        $unitPrice = (float) ($row['unit_price'] ?? 0);

        return new BoqItem([
            'boq_id'      => $this->boqId,
            'item_number' => $row['item_number'] ?? '',
            'description' => $row['description'] ?? '',
            'unit'        => $row['unit'] ?? 'ea',
            'quantity'    => $qty,
            'unit_price'  => $unitPrice,
            'total_price' => $qty * $unitPrice,
            'notes'       => $row['notes'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'item_number' => 'required|string|max:50',
            'description' => 'required|string',
            'unit'        => 'required|string|max:20',
            'quantity'    => 'required|numeric|min:0.0001',
            'unit_price'  => 'required|numeric|min:0',
            'notes'       => 'nullable|string',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'item_number.required' => 'Item number is required in each row.',
            'description.required' => 'Description is required in each row.',
            'unit.required'        => 'Unit is required in each row.',
            'quantity.required'    => 'Quantity is required in each row.',
            'quantity.min'         => 'Quantity must be greater than 0.',
            'unit_price.required'  => 'Unit price is required in each row.',
            'unit_price.min'       => 'Unit price must be 0 or greater.',
        ];
    }

    public function onFailure(Failure ...$failures): void
    {
        foreach ($failures as $failure) {
            $this->failures[] = [
                'row'    => $failure->row(),
                'errors' => $failure->errors(),
                'values' => $failure->values(),
            ];
        }
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
