<?php

namespace app\components;

use DateInterval;
use DateTime;

/**
 * Esta classe foi criada para somar um campo ou um array de campor de um array de models
 */
class TempoUtil {


    public static function getHoras() {
        return ['08' => 0, '09' => 0, '10' => 0,
            '11' => 0, '12' => 0, '13' => 0, '14' => 0, '15' => 0, '16' => 0, '17' => 0, '18' => 0, '19' => 0, '20' => 0, '21' => 0, '22' => 0, '23' => 0, '00' => 0, '01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0];
    }

    public static function getDiasSemana() {
        return ['Seg' => 0, "Ter" => 0, "Qua" => 0, 'Qui' => 0, "Sex" => 0, "Sab" => 0, "Dom" => 0];
    }

    public static function getMeseDoAno() {
        return ['01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0,];
    }

    public static function convertWeekDayMySQLtoDiasSemana($lista) {
        $dePara = [0 => 'Seg', 1 => "Ter", 2 => "Qua", 3 => 'Qui', 4 => "Sex", 5 => "Sab", 6 => "Dom"];
        $resultado = [];
        foreach ($lista as $keyAGrup => $agrupador) {
            $resultado[$keyAGrup] = [];
            foreach ($agrupador as $dayWeek => $item) {
                $resultado[$keyAGrup][$dePara[$dayWeek]] = $item;
            }
        }
        return $resultado;
    }

    public static function getDiasNoPeriodo($inicio, $fim) {
        $resultado = [];
        $d_inicio = new DateTime($inicio);
        $d_fim = new DateTime($fim);
        $resultado[$d_inicio->format('d/m')] = 0;

        $intervalo = new DateInterval('P1D');
        while ($d_inicio < $d_fim) {
            $d_inicio->add($intervalo);
            $resultado[$d_inicio->format('d/m')] = 0;
        }


        return $resultado;
    }
    
}