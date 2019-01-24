<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2019/1/12
 * Time: 10:51
 */

namespace app\admin\controller;
// DataTables PHP library

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
use think\Db,
    think\Config;
use think\Request;

class TrainPlan extends Base
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
    public function add(){
        return view();
    }

    /**
     * 处理数据
     * */
    public function getData(){
        // Build Editor instance and process the data coming from _POST
//        dump($_POST);
        $db = new Database( $this->sql_details);
        Editor::inst( $db, 'train_plan' )
            ->fields(
                Field::inst( 'date' )
                    ->validator( Validate::dateFormat(
                        'Y-m-d',
                        ValidateOptions::inst()
                            ->allowEmpty( false )
                    ) ),
                Field::inst( 'start_time')
                    ->validator( Validate::dateFormat(
                        'H:i',
                        ValidateOptions::inst()
                            ->allowEmpty( false )
                    ) )
                    ->getFormatter( Format::datetime( 'H:i:s', 'H:i' ) )
                    ->setFormatter( Format::datetime( 'H:i', 'H:i:s' ) ),
                Field::inst( 'duration' ),
                Field::inst( 'category' ),
                Field::inst( 'content' ),
                Field::inst( 'organization' ),
                Field::inst( 'expt_par' ),
                Field::inst( 'pers_in_char' ),
                Field::inst( 'location' ),
                Field::inst( 'remark' )
            )
            ->process( $_POST )
            ->json();
    }
}
