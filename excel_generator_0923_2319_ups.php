<?php
// 代码生成时间: 2025-09-23 23:19:52
// 引入Symfony框架和PHPExcel库
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelGenerator
{
    private $spreadsheet;
    private $sheet;

    /**
     * 构造函数，初始化Excel文档和工作表
     */
    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
        $this->sheet = $this->spreadsheet->getActiveSheet();
    }

    /**
     * 设置Excel文件的标题
     *
     * @param string $title 文件标题
     */
    public function setTitle($title)
    {
        $this->spreadsheet->getProperties()->setTitle($title);
    }

    /**
     * 在工作表中添加一行数据
     *
     * @param array $rowData 要添加的行数据
     */
    public function addRow($rowData)
    {
        foreach ($rowData as $colIndex => $value) {
            $this->sheet->setCellValueByColumnAndRow($colIndex + 1, $this->sheet->getHighestRow() + 1, $value);
        }
    }

    /**
     * 保存Excel文件
     *
     * @param string $filePath 文件保存路径
     * @throws Exception 如果文件保存失败
     */
    public function save($filePath)
    {
        try {
            $writer = new Xlsx($this->spreadsheet);
            $writer->save($filePath);
        } catch (Exception $e) {
            throw new Exception("保存Excel文件失败: " . $e->getMessage());
        }
    }
}

// 使用示例
try {
    $excelGenerator = new ExcelGenerator();
    $excelGenerator->setTitle("示例Excel文件");

    // 添加行数据
    $excelGenerator->addRow(["姓名", "年龄", "职业"]);
    $excelGenerator->addRow(["张三", 30, "工程师"]);
    $excelGenerator->addRow(["李四", 25, "设计师"]);

    // 保存Excel文件
    $excelGenerator->save("example.xlsx");
    echo "Excel文件生成成功！";
} catch (Exception $e) {
    echo "发生错误: " . $e->getMessage();
}