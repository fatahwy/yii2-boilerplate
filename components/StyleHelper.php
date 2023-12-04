<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;

/**
 * Description of StyleHelper
 *
 * @author bino
 */
class StyleHelper {

    public static function buttonActionStyle($style = "") {
        return [
            'style' => "white-space: nowrap;width:150px;$style",
//            'style'=>'max-width:150px; overflow: auto; white-space: normal; word-wrap: break-word;',
        ];
    }

}
