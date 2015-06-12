<?php
/*
                                  _oo0oo_
                              o8888888o
                              88" . "88
                              (| -_- |)
                              0\  =  /0
                            ___/`---'\___
                          .' \\|     |// '.
                         / \\|||  :  |||// \
                        / _||||| -:- |||||- \
                       |   | \\\  -  /// |   |
                       | \_|  ''\---/''  |_/ |
                       \  .-\___ '-' ___/-.  /
                   ____`. .'   /--.--\  `. .'____
                   ."" '< `.___\_<|>_/___.' >' "".
                  | | : `- \`.; \ _ /`;.`/ - ` : | |
                  \ \`_.   \_ ___\ / ___ _/  .-` / /
             =====`-.____`.____\_____/____.-`____.-`=====
                               '=---='
*/
/*
**********AuthRole.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-10-28**********
*/
class AuthRule extends LjhModel
{
    const RULE_URL = 1;
    const RULE_MAIN = 2;
    public function tableName()
    {
        return "{{auth_rule}}";
    }
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}