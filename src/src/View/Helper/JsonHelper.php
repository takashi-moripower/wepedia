<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of jsonHelper
 *
 * @author MoripoweDT
 */

namespace App\View\Helper;
use Cake\View\Helper;

class JsonHelper extends Helper {
        function safeEncode( $data ){
                return json_encode( $data , JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT );
        }
}
