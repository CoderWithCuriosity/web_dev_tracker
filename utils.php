<?php
function findEmailByRef($ref) {
    $lines = file('data.txt');
    foreach ($lines as $line) {
        $data = explode('|', trim($line));
        if (isset($data[2]) && $data[2] === $ref) {
            return [
                'email' => $data[0],
                'ip' => $data[1],
                'ref' => $data[2],
                'username' => $data[3] ?? '',
                'pageTitle' => $data[4] ?? '',
                'pageDescription' => $data[5] ?? ''
            ];
        }
    }
    return false;
}