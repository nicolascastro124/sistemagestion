<?php

namespace App\Http\Controllers;

use App\DatabaseConnection;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InfoController
{
    protected $productoController ;
    protected $ventaController ;

    public function __construct()
    {
        // Inicializar CategoriaController en el constructor
        $this->productoController = new ProductoController();
        $this->ventaController = new VentaController();

    }

    public function validarFechas(Request $request){
        // validador formulario
        $datos = $request->all();
        $validator = Validator::make($datos, [  
            'fechaInicio' => 'required|date|date_format:Y-m-d',
            'fechaTermino' => 'required|date|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return False;
        }
        $data = $validator->validated();
        return $data;

        
    }

    public function ventasFecha(){
        return view('info.ventasfecha');
    }

    public function ventasFechaGenerar(Request $request) {
        // Verificar con validator
        $data = $this->validarFechas($request);
        if (!$data) {
            $message = "Error en Fechas ingresadas";
            $url = url()->previous();
            return view('error', compact('message', 'url'));
        }
        $fechaInicio = $data['fechaInicio'];
        $fechaFin = $data['fechaTermino'];
    
        // Comparar las fechas
        if (strtotime($fechaFin) < strtotime($fechaInicio)) {
            return redirect()->back()->withErrors([
                'fechaTermino' => 'La fecha de término debe ser mayor o igual a la fecha de inicio.',
            ])->withInput();
        }
    
        // Obtener datos de ventas y productos
        $totales = $this->ventaController->ventasPorDia($fechaInicio, $fechaFin);
        $productos = $this->productoController->productoEnRango($fechaInicio, $fechaFin);
        // Combinar datos
        foreach ($totales as $index => $total) {
            $totales[$index]->producto_mas_vendido = $productos[$index]->producto ?? 'No hubo ventas';
            $totales[$index]->cantidad_venta_producto = $productos[$index]->total_vendido ?? 0;
        }

        return view('info.ventasfecharesult', compact('fechaInicio', 'fechaFin', 'totales'));
    }
    

    public function ventasFechaGenerarExcel(Request $request){
        //Verificar con validator
        $data = $this->validarFechas($request);
        if (!$data) {
            $message = "Error en Fechas ingresadas";
            $url = url()->previous();
            return view('error', compact('message', 'url'));
        }
        $fechaInicio = $data['fechaInicio'];
        $fechaFin = $data['fechaTermino'];

        // Comparar las fechas
        if (strtotime($fechaFin) < strtotime($fechaInicio)) {
            // Redirigir de vuelta con un mensaje de error
            return redirect()->back()->withErrors([
                'fechaTermino' => 'La fecha de término debe ser mayor o igual a la fecha de inicio.',
            ])->withInput();
        }

        $totales = $this->ventaController->ventasPorDia($fechaInicio,$fechaFin);
        $productos = $this->productoController->productoEnRango($fechaInicio,$fechaFin);

        // Crear el archivo Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Informe de Ventas Entre '.$fechaInicio. " y ". $fechaFin);
        $sheet->setCellValue('A2', 'Fecha');
        $sheet->setCellValue('B2', 'Total Ventas Diarias');
        $sheet->setCellValue('C2', 'Cantidad Ventas Diarias');
        $sheet->setCellValue('D2', 'Producto Mas Vendido');
        $sheet->setCellValue('E2', 'Cantidad Venta Producto');

        // Combinar las celdas
        $sheet->mergeCells('A1:E1');

        // Centrar el texto en las celdas combinadas
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Habilitar ajuste de texto
        $sheet->getStyle('A1')->getAlignment()->setWrapText(true);

        // Establecer una altura mayor para la fila del título
        $sheet->getRowDimension('1')->setRowHeight(50);
        
        // Aplicar estilos opcionales (negrita, tamaño de fuente)
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(20);


        // Aplicar estilo a los encabezados
        $sheet->getStyle('A2:E2')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4CAF50'], // Color verde
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Ajustar el ancho automático de las columnas
        foreach (range('A', 'E') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $fila = 3;
        foreach ($totales as $total) {
            $sheet->setCellValue('A' . $fila, $total->fecha);
            $sheet->setCellValue('B' . $fila, $total->total_ventas_diarias);
            $sheet->setCellValue('C' . $fila, $total->cantidad_ventas_diarias);
            $fila++;
        }

        $fila = 3;
        foreach ($productos as $producto) {
            $sheet->setCellValue('D' . $fila, $producto->producto);
            $sheet->setCellValue('E' . $fila, $producto->total_vendido);
            $fila++;
        }

        // Ajustar texto automáticamente
        $sheet->getStyle('A1:E' . ($fila - 1))->getAlignment()->setWrapText(true);

        // Crear respuesta para descargar el archivo
        $writer = new Xlsx($spreadsheet);
        $filename = 'ventas_' . $fechaInicio . '_al_' . $fechaFin . '.xlsx';

        ob_end_clean();

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
        
    }

    /************************************************************************************************************************ */
    public function categoriaProductos(){
        return view('info.productoscategoria');
    }

    public function categoriaProductosGenerar(Request $request){
        //Verificar con validator
        $data = $this->validarFechas($request);
        if (!$data) {
            $message = "Error en Fechas ingresadas";
            $url = url()->previous();
            return view('error', compact('message', 'url'));
        }
        $fechaInicio = $data['fechaInicio'];
        $fechaFin = $data['fechaTermino'];

        // Comparar las fechas
        if (strtotime($fechaFin) < strtotime($fechaInicio)) {
            // Redirigir de vuelta con un mensaje de error
            return redirect()->back()->withErrors([
                'fechaTermino' => 'La fecha de término debe ser mayor o igual a la fecha de inicio.',
            ])->withInput();
        }

        $productos = $this->productoController->categoriasProductos($fechaInicio,$fechaFin);
        return view('info.productoscategoriaresult', compact('fechaInicio', 'fechaFin', 'productos'));
    }


    public function categoriaProductosGenerarExcel(Request $request){
        //Verificar con validator
        $data = $this->validarFechas($request);
        if (!$data) {
            $message = "Error en Fechas ingresadas";
            $url = url()->previous();
            return view('error', compact('message', 'url'));
        }
        $fechaInicio = $data['fechaInicio'];
        $fechaFin = $data['fechaTermino'];

        // Comparar las fechas
        if (strtotime($fechaFin) < strtotime($fechaInicio)) {
            // Redirigir de vuelta con un mensaje de error
            return redirect()->back()->withErrors([
                'fechaTermino' => 'La fecha de término debe ser mayor o igual a la fecha de inicio.',
            ])->withInput();
        }

        $productos = $this->productoController->categoriasProductos($fechaInicio,$fechaFin);

        // Crear el archivo Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Informe de Productos Por Categoria Entre '.$fechaInicio. " y ". $fechaFin);
        $sheet->setCellValue('A2', 'Categoria');
        $sheet->setCellValue('B2', 'Cantidad Vendida');
        $sheet->setCellValue('C2', 'Total Ventas');
        
        // Combinar las celdas
        $sheet->mergeCells('A1:E1');

        // Centrar el texto en las celdas combinadas
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Habilitar ajuste de texto
        $sheet->getStyle('A1')->getAlignment()->setWrapText(true);

        // Establecer una altura mayor para la fila del título
        $sheet->getRowDimension('1')->setRowHeight(50);
        
        // Aplicar estilos opcionales (negrita, tamaño de fuente)
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(20);

        // Ajustar automáticamente el ancho de las columnas
        foreach (range('A', 'E') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Aplicar estilo a los encabezados
        $sheet->getStyle('A2:C2')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4CAF50'], // Color verde
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Ajustar el ancho automático de las columnas
        foreach (range('A', 'E') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $fila = 3;
        foreach ($productos as $producto) {
            $sheet->setCellValue('A' . $fila, $producto->categoria);
            $sheet->setCellValue('B' . $fila, $producto->cantidad_vendida);
            $sheet->setCellValue('C' . $fila, $producto->total_ventas);
            $fila++;
        }


        // Ajustar texto automáticamente
        $sheet->getStyle('A1:E' . ($fila - 1))->getAlignment()->setWrapText(true);

        // Crear respuesta para descargar el archivo
        $writer = new Xlsx($spreadsheet);
        $filename = 'Productos_Categoria' . $fechaInicio . '_al_' . $fechaFin . '.xlsx';

        ob_end_clean();

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);

    }

    /************************************************************************************************************************ */
    public function rentabilidadProductos(){
        return view('info.rentabilidadproductos');
    }

    public function rentabilidadProductosGenerar(Request $request){
        //Verificar con validator
        $data = $this->validarFechas($request);
        if (!$data) {
            $message = "Error en Fechas ingresadas";
            $url = url()->previous();
            return view('error', compact('message', 'url'));
        }
        $fechaInicio = $data['fechaInicio'];
        $fechaFin = $data['fechaTermino'];

        // Comparar las fechas
        if (strtotime($fechaFin) < strtotime($fechaInicio)) {
            // Redirigir de vuelta con un mensaje de error
            return redirect()->back()->withErrors([
                'fechaTermino' => 'La fecha de término debe ser mayor o igual a la fecha de inicio.',
            ])->withInput();
        }

        $productos = $this->productoController->rentabilidadProductos($fechaInicio,$fechaFin);
        return view('info.rentabilidadproductosresult', compact('fechaInicio', 'fechaFin', 'productos'));

    }

    public function rentabilidadProductosGenerarExcel(Request $request){
        //Verificar con validator
        $data = $this->validarFechas($request);
        if (!$data) {
            $message = "Error en Fechas ingresadas";
            $url = url()->previous();
            return view('error', compact('message', 'url'));
        }
        $fechaInicio = $data['fechaInicio'];
        $fechaFin = $data['fechaTermino'];

        // Comparar las fechas
        if (strtotime($fechaFin) < strtotime($fechaInicio)) {
            // Redirigir de vuelta con un mensaje de error
            return redirect()->back()->withErrors([
                'fechaTermino' => 'La fecha de término debe ser mayor o igual a la fecha de inicio.',
            ])->withInput();
        }

        $productos = $this->productoController->rentabilidadProductos($fechaInicio,$fechaFin);

        // Crear el archivo Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Rentabilidad de Productos Entre '.$fechaInicio. " y ". $fechaFin);
        $sheet->setCellValue('A2', 'Producto');
        $sheet->setCellValue('B2', 'Cantidad Vendida');
        $sheet->setCellValue('C2', 'Costo Total');
        $sheet->setCellValue('D2', 'Ingreso Total');
        $sheet->setCellValue('E2', 'Ganancia Total');
        $sheet->setCellValue('F2', 'Margen Rentabilidad');

        // Combinar las celdas
        $sheet->mergeCells('A1:F1');

        // Centrar el texto en las celdas combinadas
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Habilitar ajuste de texto
        $sheet->getStyle('A1')->getAlignment()->setWrapText(true);

        // Establecer una altura mayor para la fila del título
        $sheet->getRowDimension('1')->setRowHeight(50);
        
        // Aplicar estilos opcionales (negrita, tamaño de fuente)
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(20);

        // Ajustar automáticamente el ancho de las columnas
        foreach (range('A', 'F') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Aplicar estilo a los encabezados
        $sheet->getStyle('A2:F2')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4CAF50'], // Color verde
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Ajustar el ancho automático de las columnas
        foreach (range('A', 'F') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $fila = 3;
        foreach ($productos as $producto) {
            $sheet->setCellValue('A' . $fila, $producto->producto);
            $sheet->setCellValue('B' . $fila, $producto->cantidad_vendida);
            $sheet->setCellValue('C' . $fila, $producto->costo_total);
            $sheet->setCellValue('D' . $fila, $producto->ingreso_total);
            $sheet->setCellValue('E' . $fila, $producto->ganancia_total);
            $sheet->setCellValue('F' . $fila, $producto->margen_rentabilidad);
            $fila++;
        }


        // Ajustar texto automáticamente
        $sheet->getStyle('A1:E' . ($fila - 1))->getAlignment()->setWrapText(true);

        // Crear respuesta para descargar el archivo
        $writer = new Xlsx($spreadsheet);
        $filename = 'Rentabilidad_Productos' . $fechaInicio . '_al_' . $fechaFin . '.xlsx';

        ob_end_clean();

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);

    }



}
