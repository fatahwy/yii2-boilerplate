<?php

namespace app\helpers;

use yii\bootstrap4\Html;

class ChartHelper {

    public static function getPeriodCode($field, $code = 'Y-W') {
        if ($code == 'Y-W') {
            return "CONCAT(YEAR($field), '-', LPAD(WEEK($field),2,0))";
        } else {
            return "DATE_FORMAT(`$field`,'%Y-%m')";
        }
    }

    public static function colors() {
        $lists = [
            'rgba(255, 99, 132, 0.7)',
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 206, 86, 0.7)',
            '#B77474',
            'rgba(75, 192, 192, 0.7)',
            'rgba(153, 102, 255, 0.7)',
            'rgba(255, 159, 64, 0.7)',
            '#FFE5CC',
            '#E5FFCC',
            '#E24027',
            '#CCFFCC',
            '#CCE5FF',
            '#E0E0E0',
            '#92ECD1',
            '#8D7CB5',
            '#AB58AB',
            '#F5F5B3',
            '#B66DD5',
            '#6DD5D2',
        ];

        return $lists;
    }

    public static function addCtx($type = 'line', $selector = 'myChart') {
        return Html::tag('div', Html::tag('canvas', null, ['class' => $selector]), ['style' => 'height:' . ($type == 'line' ? 300 : 500) . 'px;', 'class' => 'mb-5 parent-' . $selector]);
    }

    public static function getDataset($label, $data, $type = 'line') {
        if ($type == 'line') {
            $dataset = [
                'label' => $label,
                'data' => $data,
                'borderColor' => self::colors(),
                'fill' => false,
                'tension' => 0.1
            ];
        } else if ($type == 'pie') {
            $dataset = [
                'label' => $label,
                'data' => $data,
                'backgroundColor' => self::colors(),
                'hoverOffset' => 4
            ];
        }

        return $dataset;
    }

}
