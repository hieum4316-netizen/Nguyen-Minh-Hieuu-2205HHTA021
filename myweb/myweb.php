!DOCTYPE html
html lang=vi
head
    meta charset=UTF-8
    titleMy Web - Bảng PHPtitle
head
body

h2Danh sách sinh viênh2

table border=1 cellpadding=10 cellspacing=0
    tr
        thSTTth
        thMã SVth
        thHọ tênth
        thTuổith
    tr

php
$students = [
    [SV01, Nguyễn Văn A, 20],
    [SV02, Trần Thị B, 21],
    [SV03, Lê Văn C, 19]
];

$stt = 1;
foreach ($students as $sv) {
    echo tr;
    echo td$stttd;
    echo td$sv[0]td;
    echo td$sv[1]td;
    echo td$sv[2]td;
    echo tr;
    $stt++;
}


table

body
html
