<?php

namespace src\classes\utils;

class Utils{

    public static function formatarTelefone(string $telefone){
        $telefone = preg_replace('/\D/', '', $telefone);

        // Celular com 11 dígitos: (99) 99999-9999
        if (strlen($telefone) === 11) {
            return preg_replace(
                '/(\d{2})(\d{5})(\d{4})/',
                '($1) $2-$3',
                $telefone
            );
        }

        // Telefone com 10 dígitos: (99) 9999-9999
        if (strlen($telefone) === 10) {
            return preg_replace(
                '/(\d{2})(\d{4})(\d{4})/',
                '($1) $2-$3',
                $telefone
            );
        }

        // Telefone com 9 dígitos: 99999-9999
        if (strlen($telefone) === 9) {
            return preg_replace(
                '/(\d{5})(\d{4})/',
                '$1-$2',
                $telefone
            );
        }

        // Telefone com 8 dígitos: 999-9999
        if (strlen($telefone) === 8) {
            return preg_replace(
                '/(\d{4})(\d{4})/',
                '$1-$2',
                $telefone
            );
        }

        return $telefone;
    }

}