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

use PhpOffice\PhpSpreadsheet\Spreadsheet,
    PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpWord\TemplateProcessor,
    PhpOffice\PhpWord\PhpWord,
    PhpOffice\PhpWord\IOFactory;
include( VENDOR_PATH."autoload.php" );

use think\Request;
use think\Db;

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

    /**
     * 导出数据到Excel
     * */
    public function exportXls(){
        $startTime = '2019-01-22';
        $endTime = '2019-01-29';
        $timeInterval = array($startTime, $endTime);
//        dump($timeInterval);
        if (false){

        }else{// 根据时间段查询数据
            $data = Db::query('SELECT * FROM train_plan WHERE date BETWEEN ? AND ? ORDER BY date, start_time',[$startTime, $endTime]);
            $groupData = $this->array_group_by($data, $key='date');
//            dump($groupData);
        }

        $phpWord = new PhpWord();

        $sectionStyle = array(
            'orientation' => 'landscape', // 页面布局为横向
        );
        $section = $phpWord->addSection( $sectionStyle);

        // 表格标题行的样式
        $fStyle = array('size' => 16, 'bold' => true);
        $pStyle = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER);

        foreach ($groupData as $date=>$dayData){
            //表格标题
            $section->addText(date('n 月 j 日', strtotime($date)).'训练登记', $fStyle, $pStyle);

            // 表格标题行
            $cellRowSpan = array('vMerge' => 'restart', 'valign' => 'center');
            $cellRowContinue = array('vMerge' => 'continue');
            $cellColSpan = array('gridSpan' => 2, 'valign' => 'center');
            $cellHCentered = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER);
            $cellVCentered = array('valign' => 'center');

            $tableStyle = array('borderSize' => 8);
            $table = $section->addTable($tableStyle);
            $table->addRow();

            $table->addCell(4000, $cellRowSpan)->addText('类别', null, $cellHCentered);
            $table->addCell(6000, $cellRowSpan)->addText('内容', null, $cellHCentered);
            $table->addCell(1000, $cellRowSpan)->addText('时间', null, $cellHCentered);
            $table->addCell(2000, $cellRowSpan)->addText('地点', null, $cellHCentered);

            $number = $table->addCell(4000, $cellColSpan);
            $textrun = $number->addTextRun($cellHCentered);
            $textrun->addText('人力');
            $table->addCell(1000, $cellRowSpan)->addText('参训率', null, $cellHCentered);
            $table->addCell(1000, $cellRowSpan)->addText('效果', null, $cellHCentered);
            $table->addCell(1000, $cellRowSpan)->addText('备注', null, $cellHCentered);

            $table->addRow();

            $table->addCell(null, $cellRowContinue);
            $table->addCell(null, $cellRowContinue);
            $table->addCell(null, $cellRowContinue);
            $table->addCell(null, $cellRowContinue);
            $table->addCell(2000, $cellVCentered)->addText('应训', null, $cellHCentered);
            $table->addCell(2000, $cellVCentered)->addText('实训', null, $cellHCentered);
            $table->addCell(null, $cellRowContinue);
            $table->addCell(null, $cellRowContinue);
            $table->addCell(null, $cellRowContinue);

            // 遍历得到表格内容
            $map = ["1"=>"共同训练","2"=>"专业技术训练","3"=>"其他训练"];
            foreach ( $dayData as $r=> $dataRow) {
                $table->addRow();
                $table->addCell()->addText($map[$dataRow['category']], null, $cellHCentered);
                $table->addCell()->addText($dataRow['content'], null, $cellHCentered);
                $table->addCell()->addText($dataRow['duration'], null, $cellHCentered);
                $table->addCell()->addText($dataRow['location'], null, $cellHCentered);
                $table->addCell()->addText($dataRow['expt_par'], null, $cellHCentered);
                $table->addCell()->addText($dataRow['act_par'], null, $cellHCentered);
                $table->addCell()->addText('', null, $cellHCentered);
                $table->addCell()->addText('', null, $cellHCentered);
                $table->addCell()->addText($dataRow['remark'], null, $cellHCentered);

            }
            $section->addPageBreak();
        }
        // Saving the document as OOXML file...
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('static/result/'.$startTime.'至'.$endTime.' 训练日登记.docx');

    }

        /**
         *
         * @param  [type] $arr [二维数组]
         * @param  [type] $key [键名]
         * @return [type]      [新的二维数组]
         */
        protected function array_group_by($arr, $key)
        {
            $grouped = array();
            foreach ($arr as $value)
            {
                $grouped[$value[$key]][] = $value;
            }
            if (func_num_args() > 2)
            {
                $args = func_get_args();
                foreach ($grouped as $key => $value)
                {
                    $parms = array_merge($value, array_slice($args, 2, func_num_args()));
                    $grouped[$key] = call_user_func_array('array_group_by', $parms);
                }
            }
            return $grouped;
        }

}