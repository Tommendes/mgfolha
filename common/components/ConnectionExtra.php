<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\components;

use yii\db\Connection;

class ConnectionExtra extends Connection {

    public $dbname;
    public $host = '162.214.208.90';
    public $port = '3306';
    public $username = 'mgfolha_folha';
    public $password = 'E1ta que quebraram minha senha enorme';
    public $rpt_username = 'mgfolha_reports';
    public $rpt_password = 'fq6EnnX4AJ1W';
    public $charset = 'utf8';

}
