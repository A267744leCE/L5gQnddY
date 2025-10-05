<?php
// 代码生成时间: 2025-10-05 20:11:55
class TableSortFilter 
{

    /**
     * 存储表格数据
     *
     * @var array
     */
    private $data = [];

    /**
     * 存储排序的列
     *
     * @var string
     */
    private $sortColumn = '';

    /**
     * 存储排序的方向
     *
     * @var string
     */
    private $sortDirection = 'ASC'; // 默认为升序

    /**
     * 构造函数
     *
     * @param array $data 表格数据
     */
    public function __construct(array $data) 
    {
        $this->data = $data;
    }

    /**
     * 设置排序的列
     *
     * @param string $column 列名
     */
    public function setSortColumn($column) 
    {
        $this->sortColumn = $column;
    }

    /**
     * 设置排序的方向
     *
     * @param string $direction 排序方向（ASC或DESC）
     */
    public function setSortDirection($direction) 
    {
        if (!in_array(strtoupper($direction), ['ASC', 'DESC'])) {
            throw new InvalidArgumentException('Invalid sort direction.');
        }

        $this->sortDirection = strtoupper($direction);
    }

    /**
     * 获取排序后的表格数据
     *
     * @return array 排序后的表格数据
     */
    public function getSortedData() 
    {
        usort($this->data, function ($a, $b) {
            return $this->compare($a, $b);
        });

        return $this->data;
    }

    /**
     * 比较两个元素
     *
     * @param array $a 第一个元素
     * @param array $b 第二个元素
     * @return int 比较结果
     */
    private function compare($a, $b) 
    {
        $valueA = $a[$this->sortColumn] ?? null;
        $valueB = $b[$this->sortColumn] ?? null;

        if ($valueA === $valueB) {
            return 0;
        }

        if ($valueA < $valueB) {
            return $this->sortDirection === 'ASC' ? -1 : 1;
        } else {
            return $this->sortDirection === 'ASC' ? 1 : -1;
        }
    }

}

// 示例用法
$data = [
    ['name' => 'John', 'age' => 25],
    ['name' => 'Jane', 'age' => 30],
    ['name' => 'Peter', 'age' => 20]
];

$tableSortFilter = new TableSortFilter($data);
$tableSortFilter->setSortColumn('age');
$tableSortFilter->setSortDirection('DESC');
$sortedData = $tableSortFilter->getSortedData();

print_r($sortedData);
