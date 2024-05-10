<?php

namespace App\Http\Controllers;

use App\Exports\PriceExport;
use App\Imports\PriceImport;
use App\Models\Price;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;


class PriceController extends Controller
{
    //
    public function index(Request $request)
    {
        $isPost = $request->isMethod('POST');
        if ($isPost && request()->file('file')) {
            $inputFileName = request()->file('file')->getRealPath();
            try {
                $validated = $this->validateCells($inputFileName, 60);

                DB::transaction(function () use ($validated) {
                    foreach ($validated as $row) {
                        Price::query()->updateOrCreate(['minute' => $row['minute']], ['cost' => $row['cost']]);
                    }
                });

                return view('prices', [
                    'success' => true,
                    'message' => 'Успешно'
                ]);
            } catch (Exception $e) {
                return view('prices', [
                    'success' => false,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        $prices = Price::query()->get();

        return view('prices', [
            'message' => '',
            'prices' => $prices
        ]);
    }

    private function validateCells($inputFileName, $maxMins = 60): array
    {
        $spreadsheet = IOFactory::load($inputFileName);
        $prevMin = null; // Initialize to null for the first row check

        $result = [];

        for ($i = 2; $i <= $maxMins + 1; $i++) {
            // current cells
            $cA = $spreadsheet->getActiveSheet()->getCell("A{$i}")->getValue();
            $cB = $spreadsheet->getActiveSheet()->getCell("B{$i}")->getValue();

            // check if both cells are not numeric (headers row)
            if (!is_numeric($cA) && !is_numeric($cB)) {
                continue;
            }

            // check data type of current cells
            if (!is_numeric($cA) || !is_numeric($cB)) {
                Log::error('Неверный тип данных в ячейке');
                throw new Exception('Неверный тип данных в ячейке');
            }

            // check minute increment and previous row
            $currentMin = (int)$cA;
            if ($prevMin !== null && $currentMin !== $prevMin + 1) {
                Log::error('Не соблюдена последовательность минут', [
                    'a' => $currentMin,
                    'prevMin' => $prevMin
                ]);
                throw new Exception('Не соблюдена последовательность минут');
            }

            // check data length
            $lastRow = $spreadsheet->getActiveSheet()->getHighestDataRow();
            $rowsNumber = $maxMins + 1;

            if ($lastRow != $rowsNumber) {
                Log::error('Неверный размер строк');
                throw new Exception('Неверный размер строк');
            }

            // store validated data
            $prevMin = $currentMin;
            $result[] = ['minute' => $currentMin, 'cost' => (double)$cB];
        }

        return $result;
    }

}
