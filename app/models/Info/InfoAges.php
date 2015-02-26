<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 26.02.2015
 * Time: 10:21
 */

namespace Info;

use Eloquent;

class InfoAges extends Eloquent {

    const BABY = 1;
    const KINDERGARDEN = 2;
    const PRESCHOOL = 3;
    const FIRST = 4;
    const SECOND = 5;
    const THIRD = 6;
    const FOURTH = 7;

    protected $table = 'info_ages';
    protected $timestamps = false;
}