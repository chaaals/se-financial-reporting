<?php
    /**
     * This is a config file exported Trial Balance files
    */

    return [
        "headers" => [
            "heading" => [
                "cell" => "C1",
                "mergeRange" => "C1:G1",
            ],
            "sub" => [
                "cell" => "C3",
                "mergeRange" => "C3:G3"
            ],
            "subData" => [
                "cell" => "C4",
                "mergeRange" => "C4:G4"
            ],
            "accountTitles" => [
                "cell" => "A6",
                "mergeRange" => "A6:E6",
            ],
            "debit" => [
                "cell" => "F6",
                "mergeRange" => "F6:G6"
            ],
            "credit" => [
                "cell" => "H6",
                "mergeRange" => "H6:I6"
            ]
        ],
        
        "startRow" => 8,
        "data" => [
            "accountTitles" => [
                "cell" => fn(int $row): string => "A$row",
                "mergeRange" => fn(int $row): string => "A$row:E$row"
            ],
            "debit" => [
                "cell" => fn(int $row): string => "F$row",
                "mergeRange" => fn(int $row): string => "F$row:G$row"
            ],
            "credit" => [
                "cell" => fn(int $row): string => "H$row",
                "mergeRange" => fn(int $row): string => "H$row:I$row"
            ]
        ]
    ];
?>