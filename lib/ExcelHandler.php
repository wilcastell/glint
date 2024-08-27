<?php

namespace Lib;

use Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Programa;
use App\Models\Capacitacion;
use App\Models\Modulo;
use App\Models\Verificador;
use App\Models\Ciudad;
use Carbon\Carbon;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Models\Cargamasiva;
use Lib\Validator;

class ExcelHandler
{
    private $queryBuilder;
    private $columns;
    private $filename;
    private $redirectUrl;
    private $validationRules;
    protected $data;
    protected $additionalRows = [];


    const TXT_FILA = "Fila ";
    const HORA = 'H:i:s';


    public function __construct()
    {
        $this->queryBuilder = null;
        $this->columns = [];
        $this->filename = 'export.xlsx';
        $this->redirectUrl = BASE_URL . '/';
        $this->validationRules = [];
    }

    /**
     * Establece los datos a exportar.
     *
     * @param array $data Los datos a exportar.
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Establece el constructor de consultas.
     *
     * @param mixed $queryBuilder El constructor de consultas.
     */
    public function setQueryBuilder($queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * Establece las columnas para la exportación.
     *
     * @param array $columns Las columnas a exportar.
     */
    public function setColumns(array $columns)
    {
        $this->columns = $columns;
    }

    /**
     * Establece las filas adicionales a exportar.
     *
     * @param array $rows Las filas adicionales.
     */
    public function setAdditionalRows(array $rows)
    {
        $this->additionalRows = $rows;
    }

    /**
     * Establece el nombre del archivo a exportar.
     *
     * @param string $filename El nombre del archivo.
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Establece la URL de redirección.
     *
     * @param string $url La URL de redirección.
     */
    public function setRedirectUrl($url)
    {
        $this->redirectUrl = $url;
    }

    /**
     * Establece las reglas de validación.
     *
     * @param array $rules Las reglas de validación.
     */
    public function setValidationRules(array $rules)
    {
        $this->validationRules = $rules;
    }

    /**
     * Exporta los datos a un archivo Excel.
     */
    public function export()
    {
        if (empty($this->columns)) {
            $this->redirect('/', ['error' => 'Columns must be set.']);
        }

        if ($this->data) {
            $result = $this->data;
        } elseif ($this->queryBuilder) {
            $result = $this->queryBuilder->get()->toArray();
        } else {
            $this->redirect('/', ['error' => 'No data to export.']);
        }

        $excel = new Spreadsheet();
        $hojaActiva = $excel->getActiveSheet();
        $hojaActiva->setTitle('Export Data');

        $columnIndex = 'A';
        foreach ($this->columns as $column) {
            $hojaActiva->getColumnDimension($columnIndex)->setWidth(20);
            $hojaActiva->setCellValue($columnIndex . '1', $column);
            $columnIndex++;
        }

        $fila = 2;
        foreach ($result as $row) {
            $columnIndex = 'A';
            foreach ($this->columns as $key => $column) {
                $hojaActiva->setCellValue($columnIndex . $fila, $row[$key] ?? '');
                $columnIndex++;
            }
            $fila++;
        }

        // Escribir las filas adicionales
        $rowIndex = count($this->data) + 5; // Comenzar después de los datos
        foreach ($this->additionalRows as $row) {
            $hojaActiva->fromArray($row, null, 'A' . $rowIndex);
            $this->applyAdditionalRowStyle($hojaActiva, $rowIndex);
            $rowIndex++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $this->filename . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($excel, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    /**
     * Aplica estilo a las filas adicionales.
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet La hoja de cálculo.
     * @param int $rowIndex El índice de la fila.
     */
    private function applyAdditionalRowStyle($sheet, $rowIndex)
    {
        $styleArray = [
            'font' => [
                'bold' => true,
                'color' => ['argb' => '333333'], // Color de texto rojo
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'f8f8f8'], // Color de fondo
            ],
        ];

        $sheet->getStyle('A' . $rowIndex . ':A' . $rowIndex)->applyFromArray($styleArray);
    }

    /**
     * Importa datos desde un archivo Excel.
     *
     * @param string $filePath La ruta del archivo Excel.
     * @param string $modelClass La clase del modelo para importar los datos.
     * @param array $uniqueFields Los campos únicos para evitar duplicados.
     * @param mixed $user El usuario que realiza la importación.
     */
    public function import($filePath, $modelClass, $uniqueFields = [], $user = null)
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();

        // Validar columnas del Excel
        $excelColumns = $worksheet->toArray()[0];
        $expectedColumns = array_values($this->columns);
        $errorMessages = [];

        if ($excelColumns !== $expectedColumns) {
            $errorMessages[] = "Las columnas del archivo Excel no coinciden con las columnas esperadas.";
            $this->setSessionMessage([
                'message' => 'Las columnas del archivo Excel no coinciden con las columnas esperadas',
                'errors' => $errorMessages
            ], 400);
            $this->redirect();
        }

        $rows = $worksheet->toArray();
        $importedCount = 0;
        $failedRows = [];

        foreach ($rows as $index => $row) {
            if ($index == 0) {
                $row[] = 'Observaciones';
                $worksheet->fromArray([$row], null, 'A' . ($index + 1));
                continue; // Saltar la fila de encabezado
            }

            $data = [];
            $columnIndex = 0;
            foreach ($this->columns as $key => $column) {
                $data[$key] = $row[$columnIndex];
                $columnIndex++;
            }

            // Usar nuestro Validator para validar los datos
            $validator = new Validator($data, $this->validationRules);
            if ($validator->fails()) {
                $failedRows[] = $index + 1;
                $errorMessages = array_merge(
                    $errorMessages,
                    array_map(function ($err) {
                        return implode(', ', (array) $err);
                    }, $validator->errors())
                );
                $row[] = implode(
                    ', ',
                    array_map(function ($err) {
                        return implode(', ', (array) $err);
                    }, $validator->errors())
                );
                $worksheet->fromArray([$row], null, 'A' . ($index + 1));
                continue;
            }

            if ($this->isDuplicate($modelClass, $data, $uniqueFields)) {
                $failedRows[] = $index + 1;
                $errorMessages[] = self::TXT_FILA . ($index + 1) . " duplicada. Registro omitido.";
                $row[] = "Duplicado";
                $worksheet->fromArray([$row], null, 'A' . ($index + 1));
                continue;
            }

            try {
                $modelClass::create($data);
                $importedCount++;
                $row[] = "Importado correctamente";
            } catch (Exception $e) {
                $failedRows[] = $index + 1;
                $errorMessages[] = self::TXT_FILA . ($index + 1) . " error: " . $e->getMessage();
                $row[] = $e->getMessage();
            }
            $worksheet->fromArray([$row], null, 'A' . ($index + 1));
        }

        $fileNameWithErrors = 'importado_' . date('YmdHis') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save(__DIR__ . '/../public/archive/' . $fileNameWithErrors);

        $this->setSessionMessage([
            'message' => 'Resultados de la carga masiva',
            'imported' => $importedCount,
            'failed' => $failedRows,
            'errors' => $errorMessages,
            'file_with_errors' => $fileNameWithErrors
        ], 200);

        $cargamasiva = new Cargamasiva([
            'registros' => count($rows) - 1,
            'correctos' => $importedCount,
            'errores' => count($failedRows),
            'importado_por' => $user,
            'file_name' => $fileNameWithErrors
        ]);
        $cargamasiva->save();

        $this->redirect();
    }

    /**
     * Verifica si los datos son duplicados.
     *
     * @param string $modelClass La clase del modelo.
     * @param array $data Los datos a verificar.
     * @param array $uniqueFields Los campos únicos.
     * @return bool Devuelve true si los datos son duplicados, de lo contrario false.
     */
    private function isDuplicate($modelClass, $data, $uniqueFields)
    {
        if (empty($uniqueFields)) {
            return false;
        }

        $query = $modelClass::query();
        foreach ($uniqueFields as $field) {
            if (isset($data[$field])) {
                $query->where($field, $data[$field]);
            }
        }

        return $query->exists();
    }

    /**
     * Establece un mensaje de sesión.
     *
     * @param array $message El mensaje de sesión.
     * @param int $status El estado de la respuesta.
     */
    private function setSessionMessage($message, $status)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['response_message'] = $message;
        $_SESSION['response_status'] = $status;
    }

    /**
     * Redirige a una URL específica.
     */
    private function redirect()
    {
        header('Location: ' . BASE_URL . $this->redirectUrl);
        exit;
    }

    /**
     * Importa datos desde un archivo Excel.
     *
     * @param string $filePath La ruta del archivo Excel.
     * @param mixed $user El usuario que realiza la importación.
     */
    public function importExcel($filePath, $user = null)
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();
        $importedCount = 0;
        $failedRows = [];
        $errorMessages = [];
        $rowsWithErrors = [];

        // Validar columnas del Excel
        $excelColumns = $worksheet->toArray()[0];
        $expectedColumns = array_values($this->columns);

        if ($excelColumns !== $expectedColumns) {
            $errorMessages[] = "Las columnas del archivo Excel no coinciden con las columnas esperadas.";
            $this->setSessionMessage([
                'message' => 'Las columnas del archivo Excel no coinciden con las columnas esperadas',
                'errors' => $errorMessages
            ], 400);
            $this->redirect();
        }

        foreach ($rows as $index => $row) {
            if ($index == 0) {
                $row[] = 'Observaciones';
                $rowsWithErrors[] = $row;
                continue; // Saltar la fila de encabezado
            }

            if (!empty($row[0]) && !empty($row[1])) {
                if ($this->isFullRow($row)) {
                    $result = $this->importCapacitacion($row);
                } elseif ($this->isOnlyOneAndTwo($row)) {
                    $result = $this->importPrograma($row);
                } else {
                    $failedRows[] = $index + 1;
                    $errorMessages[] = self::TXT_FILA . ($index + 1)
                        . " error: Código de módulo y Nombre de programa son obligatorios.";
                    $row[] = "error: Código de módulo y Nombre de programa son obligatorios.";
                    $rowsWithErrors[] = $row;
                    continue;
                }

                if ($result['success']) {
                    $importedCount++;
                } else {
                    $failedRows[] = $index + 1;
                    $errorMessages[] = self::TXT_FILA . ($index + 1) . " error: " . $result['message'];
                    $row[] = $result['message'];
                    $rowsWithErrors[] = $row;
                }
            } else {
                $failedRows[] = $index + 1;
                $errorMessages[] = self::TXT_FILA . ($index + 1)
                    . " error: Código de módulo y Nombre de programa son obligatorios.";
                $row[] = "Código de módulo y Nombre de programa son obligatorios.";
                $rowsWithErrors[] = $row;
            }
        }

        // Crear un nuevo archivo solo con las filas incorrectas
        $spreadsheetWithErrors = new Spreadsheet();
        $worksheetWithErrors = $spreadsheetWithErrors->getActiveSheet();
        $worksheetWithErrors->fromArray($rowsWithErrors);

        $fileNameWithErrors = 'importado_' . date('YmdHis') . '.xlsx';
        $writer = new Xlsx($spreadsheetWithErrors);
        $writer->save(__DIR__ . '/../public/archive/' . $fileNameWithErrors);

        $this->setSessionMessage([
            'message' => 'Resultados de la carga masiva',
            'imported' => $importedCount,
            'failed' => $failedRows,
            'errors' => $errorMessages,
            'file_with_errors' => $fileNameWithErrors
        ], 200);

        $cargamasiva = new Cargamasiva([
            'registros' => count($rows) - 1,
            'correctos' => $importedCount,
            'errores' => count($failedRows),
            'importado_por' => $user,
            'file_name' => $fileNameWithErrors
        ]);
        $cargamasiva->save();

        $this->redirect();
    }

    /**
     * Verifica si una fila está completamente llena.
     *
     * @param array $row La fila a verificar.
     * @return bool Devuelve true si la fila está completamente llena, de lo contrario false.
     */
    private function isFullRow($row)
    {
        // Verificar que todas las columnas desde la tercera estén llenas
        for ($i = 2; $i < count($row); $i++) {
            if (empty($row[$i])) {
                return false;
            }
        }
        return true;
    }

    /**
     * Verifica si solo las dos primeras columnas están llenas.
     *
     * @param array $row La fila a verificar.
     * @return bool Devuelve true si solo las dos primeras columnas están llenas, de lo contrario false.
     */
    private function isOnlyOneAndTwo($row)
    {
        // Verificar que todas las columnas desde la tercera estén vacías
        for ($i = 2; $i < count($row); $i++) {
            if (!empty($row[$i])) {
                return false;
            }
        }
        return true;
    }

    /**
     * Importa un programa desde una fila.
     *
     * @param array $row La fila a importar.
     * @return array El resultado de la importación.
     */
    private function importPrograma($row)
    {
        $codigoModulo = $row[0];
        $nombrePrograma = $row[1];

        // Verificar que el código del módulo exista
        $modulo = Modulo::where('id', $codigoModulo)->first();
        if (!$modulo) {
            return ['success' => false, 'message' => "El código de módulo no existe."];
        }

        // Verificar que el nombre del programa sea único
        $programaExistente = Programa::where('tema', $nombrePrograma)->first();
        if ($programaExistente) {
            return ['success' => false, 'message' => "El nombre del programa ya existe."];
        }

        // Crear el nuevo programa
        try {
            Programa::create([
                'tema' => mb_strtoupper(trim($nombrePrograma)),
                'modulo_id' => $modulo->id,
                'status' => 1
            ]);
            return ['success' => true, 'message' => "Programa creado correctamente."];
        } catch (Exception $e) {
            return ['success' => false, 'message' => "Error al crear el programa: " . $e->getMessage()];
        }
    }

    /**
     * Importa una capacitación desde una fila.
     *
     * @param array $row La fila a importar.
     * @return array El resultado de la importación.
     */
    private function importCapacitacion($row)
    {
        $codigoModulo = $row[0];
        $nombrePrograma = $row[1];
        $verificador = $row[2];
        $ciudad = $row[3];
        $fecha = $row[4];
        $direccion = $row[5];
        $cupos = $row[6];
        $horaInicio = $row[7];
        $horaFinal = $row[8];

        // Verificar que el código del módulo exista
        $modulo = Modulo::where('id', $codigoModulo)->first();
        if (!$modulo) {
            return ['success' => false, 'message' => "El código de módulo no existe."];
        }

        // Buscar o crear el programa
        $programa = Programa::firstOrCreate(
            ['tema' =>  mb_strtoupper(cleanText($nombrePrograma))],
            ['modulo_id' => $modulo->id, 'status' => 1]
        );

        $ciudadObj = Ciudad::where('name', $ciudad)->where('status', 1)->first();
        if (!$ciudadObj) {
            return ['success' => false, 'message' => "La ciudad no existe o no está habilitada."];
        }

        // Buscar o crear el verificador
        $posibleverificador = Verificador::firstOrCreate(
            ['name' => cleanText($verificador), 'ciudad_id' => $ciudadObj->id],
            ['status' => 1]
        );

        // Verificar que el ID del verificador esté disponible
        if (!$posibleverificador->id) {
            return ['success' => false, 'message' => "No se pudo obtener el ID del verificador."];
        }

        // Generar código de ingreso
        $lastcap = Capacitacion::orderBy('id', 'desc')->first();
        $newCode = $lastcap ? 1000 + $lastcap->id + 1 : 1001;

        // Convertir fecha y calcular intensidad
        $fechaFormateada = Carbon::createFromFormat('d/m/y', $fecha)->format('Y-m-d');

        // Validar que la fecha no sea anterior a hoy
        $fechaHoy = Carbon::now()->startOfDay();
        if (Carbon::parse($fechaFormateada)->lt($fechaHoy)) {
            return ['success' => false, 'message' => "La fecha de la capacitación no puede ser anterior a hoy."];
        }

        $horaInicio = Carbon::createFromFormat(self::HORA, $horaInicio);
        $horaFinal = Carbon::createFromFormat(self::HORA, $horaFinal);
        $intensidad = abs($horaFinal->diffInMinutes($horaInicio));

        // Crear la capacitación
        try {
            Capacitacion::create([
                'codigo_ingreso' => $newCode,
                'modulo_id' => $programa->modulo_id,
                'ciudad_id' => $ciudadObj->id,
                'cupos' => $cupos,
                'direccion' => cleanText($direccion),
                'verificador_id' => $posibleverificador->id,
                'fecha' => $fechaFormateada,
                'hora_inicio' => $horaInicio->format(self::HORA),
                'hora_final' => $horaFinal->format(self::HORA),
                'modalidad' => 'Presencial',
                'intensidad' => $intensidad,
                'programa_id' => $programa->id,
                'status' => 1
            ]);
            return ['success' => true, 'message' => "Capacitación creada correctamente."];
        } catch (Exception $e) {
            return ['success' => false, 'message' => "Error al crear la capacitación: " . $e->getMessage()];
        }
    }
}
