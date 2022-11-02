<?php

namespace Config;


class ExcelStyles {

    protected static $style = [
        'heading1' => [
            'font' => [
                'bold' => true,
                'size' => 16,
            ],
        ],
        'heading2' => [
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
        ],
        'heading3' => [
            'font' => [
                'bold' => true,
                'size' => 10,
            ],
        ],
        'border' => [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ],
        'fill_yellow' => [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FFFFFF00',
                ]
            ],
        ],
        'fill_grey' => [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FFA9A9A9',
                ]
            ],
        ],
        'fill_blue' => [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FF2C3B49',
                ]
            ],
            'font' => [
                'color' => ['argb' => 'FFFFFFFF'],
            ],
        ],
    ];

    public static function heading1(){
        return self::$style['heading1'];
    }

    public static function heading2(){
        return self::$style['heading2'];
    }

    public static function heading3(){
        return self::$style['heading3'];
    }

    public static function border(){
        return self::$style['border'];
    }

    public static function fill_yellow(){
        return self::$style['fill_yellow'];
    }

    public static function fill_grey(){
        return self::$style['fill_grey'];
    }

    public static function fill_blue(){
        return self::$style['fill_blue'];
    }
}
