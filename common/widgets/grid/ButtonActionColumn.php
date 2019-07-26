<?php

namespace common\widgets\grid;

use Closure;
use Yii;
use yii\grid\ActionColumn;
use yii\helpers\Html;

/**
 * 以按钮形式显示 操作列
 *
 * @author Administrator
 */
class ButtonActionColumn extends ActionColumn {

    public $pluginOptions = [];

    protected function initDefaultButtons() {
        $pluginOptions = [
            'view' => [
                'label' => Yii::t('app', 'View'),
                'url' => 'view',
            ],
            'update' => [],
            'delete' => [],
        ];
        $this->initDefaultButton('view', 'View', ['class' => 'btn btn-default']);
        $this->initDefaultButton('update', 'Update');
        $this->initDefaultButton('delete', 'Delete');
    }

    protected function initDefaultButton($name, $iconName, $additionalOptions = []) {
        if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
            $this->buttons[$name] = function ($url, $model, $key) use ($name, $iconName, $additionalOptions) {
                $title = ucfirst($name);
                $options = array_merge([
                    'title' => $title,
                    'aria-label' => $title,
                    'data-pjax' => '0',
                        ], $additionalOptions, $this->buttonOptions);
                $icon = Yii::t('app', $iconName);
                return Html::a($icon, $url, $options);
            };
        }
    }

    protected function renderDataCellContent($model, $key, $index) {
        return preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) use ($model, $key, $index) {
            $name = $matches[1];

            if (isset($this->visibleButtons[$name])) {
                $isVisible = $this->visibleButtons[$name] instanceof Closure ? call_user_func($this->visibleButtons[$name], $model, $key, $index) : $this->visibleButtons[$name];
            } else {
                $isVisible = true;
            }

            if ($isVisible && isset($this->buttons[$name])) {
                $url = $this->createUrl($name, $model, $key, $index);
                return call_user_func($this->buttons[$name], $url, $model, $key);
            }

            return '';
        }, $this->template);
    }

}
