<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;

use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use Yii;
use yii\bootstrap4\Html;

class ButtonActionColumn extends ActionColumn {

    public $dropButtons;

    public function init() {
        $this->initColumnSettings([
            'hiddenFromExport' => true,
            'mergeHeader' => false,
            'hAlign' => GridView::ALIGN_LEFT,
            'vAlign' => GridView::ALIGN_MIDDLE,
            'width' => '100px',
        ]);
        $this->_isDropdown = ($this->grid->bootstrap && $this->dropdown);
        if (!isset($this->header)) {
            $this->header = Yii::t('kvgrid', '');
        }
        $this->parseFormat();
        $this->parseVisibility();
        parent::init();
        $this->initDefaultButtons();
        $this->setPageRows();

        //custom
        $this->contentOptions = StyleHelper::buttonActionStyle();
    }

    protected function initDefaultButtons() {
        if (!isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'View'),
                    'aria-label' => Yii::t('yii', 'View'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-primary btn-sm',
                    'data-toggle' => 'tooltip',
                    'style' => 'margin: 2px;'
                        ], $this->buttonOptions);
                return Html::a('<i class="fa fa-search"></i>', $url, $options);
            };
        }
        if (!isset($this->buttons['update'])) {
            $this->buttons['update'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Update'),
                    'aria-label' => Yii::t('yii', 'Update'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-primary btn-sm',
                    'data-toggle' => 'tooltip',
                    'style' => 'margin: 2px;'
                        ], $this->buttonOptions);
                return Html::a('<span class="fas fa-pencil-alt"></span>', $url, $options);
            };
        }
        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Delete'),
                    'aria-label' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                    'class' => 'btn btn-danger btn-sm',
                    'data-toggle' => 'tooltip',
                    'style' => 'margin: 2px;'
                        ], $this->buttonOptions);
                return Html::a('<span class="fa fa-trash"></span>', $url, $options);
            };
        }
        if (!isset($this->buttons['printview'])) {
            $this->buttons['printview'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Cetak'),
                    'aria-label' => Yii::t('yii', 'Cetak'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-success btn-sm',
                    'style' => 'margin: 2px;'
                        ], $this->buttonOptions);
                return Html::a('<span class="fa fa-print"></span>', $url, $options);
            };
        }
        if (!isset($this->buttons['print'])) {
            $this->buttons['print'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Cetak'),
                    'aria-label' => Yii::t('yii', 'Cetak'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-default btn-sm',
                    'style' => 'margin: 2px;',
                    'onclick' => "print_report('$url'); return false;",
                        ], $this->buttonOptions);
                return Html::a('<span class="fa fa-print"></span>', '#', $options);
            };
        }
        if (!isset($this->buttons['download'])) {
            $this->buttons['download'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Cetak'),
                    'aria-label' => Yii::t('yii', 'Cetak'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-default btn-sm',
                    'style' => 'margin: 2px;',
                    'target' => '_blank',
                        ], $this->buttonOptions);
                return Html::a('<span class="fa fa-download"></span>', $url, $options);
            };
        }
        if (!isset($this->buttons['process'])) {
            $this->buttons['process'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Process'),
                    'aria-label' => Yii::t('yii', 'Process'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-primary btn-sm',
                    'data-toggle' => 'tooltip',
                    'style' => 'margin: 2px;'
                        ], $this->buttonOptions);
                return Html::a('<span class="fas fa-pencil-alt"></span>', $url, $options);
            };
        }
    }

}
