<?php

/**
 * Export data ke CSV (bisa dibuka di Excel)
 * Alternatif ringan tanpa perlu PhpSpreadsheet
 */
function exportToCSV($data, $headers, $filename, $title = '')
{
    // Set headers untuk download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    
    // Buka output stream
    $output = fopen('php://output', 'w');
    
    // Tambahkan BOM untuk UTF-8 agar Excel bisa baca karakter Indonesia
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Tulis title jika ada
    if ($title) {
        fputcsv($output, [$title]);
        fputcsv($output, []); // Baris kosong
    }
    
    // Tulis headers
    fputcsv($output, $headers);
    
    // Tulis data
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    
    fclose($output);
    exit;
}

/**
 * Export data ke Excel dengan PhpSpreadsheet (jika tersedia)
 * Fallback ke CSV jika PhpSpreadsheet tidak terinstall
 */
function exportToExcel($data, $headers, $filename, $title = '')
{
    // Cek apakah PhpSpreadsheet tersedia
    if (!class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
        // Fallback ke CSV
        $csvFilename = str_replace('.xlsx', '.csv', $filename);
        return exportToCSV($data, $headers, $csvFilename, $title);
    }
    
    // Gunakan PhpSpreadsheet jika tersedia
    try {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set title jika ada
        $startRow = 1;
        if ($title) {
            $sheet->setCellValue('A1', $title);
            $sheet->mergeCells('A1:' . chr(64 + count($headers)) . '1');
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $startRow = 3;
        }
        
        // Set headers
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $startRow, $header);
            $sheet->getStyle($col . $startRow)->getFont()->setBold(true);
            $sheet->getStyle($col . $startRow)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FF4472C4');
            $sheet->getStyle($col . $startRow)->getFont()->getColor()->setARGB('FFFFFFFF');
            $sheet->getStyle($col . $startRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $col++;
        }
        
        // Set data
        $row = $startRow + 1;
        foreach ($data as $item) {
            $col = 'A';
            foreach ($item as $value) {
                $sheet->setCellValue($col . $row, $value);
                $col++;
            }
            $row++;
        }
        
        // Auto size columns
        foreach (range('A', chr(64 + count($headers))) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Add borders
        $lastCol = chr(64 + count($headers));
        $lastRow = $row - 1;
        $sheet->getStyle('A' . $startRow . ':' . $lastCol . $lastRow)
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        
        // Output
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    } catch (\Exception $e) {
        // Jika error, fallback ke CSV
        $csvFilename = str_replace('.xlsx', '.csv', $filename);
        return exportToCSV($data, $headers, $csvFilename, $title);
    }
}
