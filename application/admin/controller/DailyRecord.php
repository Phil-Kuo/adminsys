<?php
/**
 * Created by PhpStorm.
 * User: Daly Dai
 * Date: 2019/1/24
 * Time: 20:44
 */

namespace app\admin\controller;

use
    DataTables\Database,
    DataTables\Editor,
    DataTables\Editor\Field,
    DataTables\Editor\Format,
    DataTables\Editor\Mjoin,
    DataTables\Editor\Options,
    DataTables\Editor\Upload,
    DataTables\Editor\Validate,
    DataTables\Editor\ValidateOptions;
include( VENDOR_PATH."autoload.php" );

use think\Request;

class DailyRecord extends Base
{
    protected $sql_details = array(
        "type" => "Mysql",     // Database type: "Mysql", "Postgres", "Sqlserver", "Sqlite" or "Oracle"
        "user" => "root",          // Database user name
        "pass" => "bingo",          // Database password
        "host" => "localhost", // Database host
        "port" => "",          // Database connection port (can be left empty for default)
        "db"   => "adminsys",          // Database name
        "dsn"  => "charset=utf8",          // PHP DSN extra information. Set as `charset=utf8mb4` if you are using MySQL
    );

    public function index(){
        return view();
    }

    public function edit(){
        $datePicked = $_POST['datepicked'];
//        dump($datePicked);
        $this->assign('datePicked',$datePicked);
        return view();
    }

    /**
     * 处理数据
     * */
    public function getData($date){
        // Build Editor instance and process the data coming from _POST

        $db = new Database( $this->sql_details);
        Editor::inst( $db, 'train_plan' )
            ->where('date',$date)
            ->fields(
                Field::inst( 'date' )
                    ->validator( Validate::dateFormat(
                        'Y-m-d',
                        ValidateOptions::inst()
                            ->allowEmpty( false )
                    ) ),
                Field::inst( 'duration' ),
                Field::inst( 'category' ),
                Field::inst( 'content' ),
                Field::inst( 'expt_par' ),
                Field::inst( 'act_par' ),
                Field::inst( 'location' ),
                Field::inst( 'remark' )
            )
            ->process( $_POST )
            ->json();
    }
}