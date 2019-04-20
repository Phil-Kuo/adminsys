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

use PhpOffice\PhpWord\PhpWord,
    PhpOffice\PhpWord\TemplateProcessor,
    PhpOffice\PhpWord\IOFactory;

use think\Db,
    think\Config;
use think\Request;

class TrainPlan extends Base
{
    // 数据库连接参数设置
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

        $db = new Database( $this->sql_details);

        Editor::inst( $db, 'train_plan' )

            ->where(function ($q){//查询当前日期的本周计划
                $q->where(function ($r){
                  $r->where('date', 'DATE_SUB(NOW(), INTERVAL WEEKDAY(NOW()) - 6 DAY)', '<=', false); //周日
                  $r->and_where('date', 'DATE_SUB(NOW(), INTERVAL WEEKDAY(NOW()) + 1 DAY)', '>=', false); //周一
                });
            })
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
                Field::inst( 'location' )
            )
            ->process( $_POST )
            ->json();
    }

    public function exportData(){
        //取出数据
        $dateRange = explode('—', $_POST['daterange_plan']);
        $startTime = $dateRange[0];
        $endTime = $dateRange[1];

        // 判断传入的时间是否有效
        if (empty($dateRange)){

        }else{// 根据时间段查询数据
            $data = Db::query('SELECT * FROM train_plan WHERE date BETWEEN ? AND ? ORDER BY date, start_time',[$startTime, $endTime]);
        }

        $numOfRows = count($data);

        $templateProcessor = new TemplateProcessor('static/templates/template_train_plan.docx');

        $intToCategory = ["1"=>"共同训练","2"=>"专业技术训练","3"=>"其他训练"];
        $intToWeekday = ["0"=>"日","1"=>"一","2"=>"二","3"=>"三","4"=>"四","5"=>"五","6"=>"六"];

        $year = date('Y', strtotime($startTime));
        $week = date('W', strtotime($startTime));

        $templateProcessor->cloneRow('start', $numOfRows);
        $templateProcessor->setValue('year', $year);
        $templateProcessor->setValue('week', $week);
        for ($i=1;$i<=$numOfRows;$i++){
            $templateProcessor->setValue('date#'.$i, date('n月j日', strtotime($data[$i-1]['date'])));
            $templateProcessor->setValue('weekday#'.$i, $intToWeekday[date('w', strtotime($data[$i-1]['date']))]);
            $templateProcessor->setValue('start#'.$i, date('H:i', strtotime($data[$i-1]['start_time'])));
            $templateProcessor->setValue('dur#'.$i, $data[$i-1]['duration']);
            $templateProcessor->setValue('cate#'.$i, $intToCategory[$data[$i-1]['category']]);
            $templateProcessor->setValue('content#'.$i, $data[$i-1]['content']);
            $templateProcessor->setValue('expt#'.$i, $data[$i-1]['expt_par']);
            $templateProcessor->setValue('org#'.$i, $data[$i-1]['organization']);
            $templateProcessor->setValue('char#'.$i, $data[$i-1]['pers_in_char']);
            $templateProcessor->setValue('loc#'.$i, $data[$i-1]['location']);
        }

        $fileName = '网校'.$year.'年第'.$week.'周训练计划.docx';
        $templateProcessor->saveAs('static/result/'.$fileName);

    }
}
