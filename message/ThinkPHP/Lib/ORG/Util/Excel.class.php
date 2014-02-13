<?php
/**
 * 返回 Excel 类
 */
class Excel extends Think {
    /**
     * 标题文字
     * @var array $title
     * @access private
     */
    private $header = array();
    protected $values = array();
    /**
     * 定义标题文字 <br/>
     * $headers数组格式要求: <br/>
     * array ( <br/>
     *         '字段名'    => '字段类型#标题', <br/>
     *         '字段名'    => '标题', <br/>
     * ); <br/>
     *
     * @param Array $headers 标题数组
     * @return AResponse
     */
    public function setHeader($headers) {
        foreach ($headers as $field => $header) {
            if (is_array($header)) {
                $this->header[$field] = $header;
            } else {
                if (strpos($field, '#') != false) {
                    list($name, $type) = explode('#', $field);
                } else {
                    $name = $field;
                    $type = '';
                }
                $this->header[$name]['title'] = $header;
                $this->header[$name]['type'] = $type;
            }
        }
        return $this;
    }
    /**
     * 设置或读取变量值
     * @param string $name 变量名,如果参数类型为数组,则为变量赋值,此时$value参数无效
     * @param mixed $value 变量值,如果该参数未指定,则返回变量值,否则设置变量值
     * @return AResponse 如果参数为NULL则返回Response对象本身,否则返回变量值
     */
    public function value($name, $value = NULL) {
        if ($value === NULL && !is_array($name)) { //取值
        	//var_dump($this->values[$name]);
            return $this->values[$name];
        } else { //赋值
            if (is_array($name)) { //如果是数组则批量变量赋值
                $this->values = array_merge($this->values, $name);
            } else {
                $this->values[$name] = & $value;
            }
            return $this;
        }
    }   
    /**
     * 响应内容输出 <br/>
     * 数据的输出格式化在 $values 的每一条需要格式化的记录中定义, 支持大部分的HTML<td>标签的属性, 如: <br/>
     * array ( <br/>
     *         '字段名'        => '字段值', <br/>
     *         '字段名 attr'   => array( //该字段的格式化属性<br/>
     *                         'colspan'      => 15, <br/>
     *                         'align'        => 'center', <br/>
     *         ), <br/>
     * ); <br/>
     * @param boolean $filename Excel文件名
     */
    public function output($filename='noname.xls') {
        header('Content-type:application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . urlencode($filename));
        header('Pragma: no-cache');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

        echo '<html><head><meta http-equiv="Content-Type" content="application/vnd.ms-excel; charset=utf-8" /></head><body><table>';

        if (!empty($this->values)) {
            $index = 0;
            foreach ($this->values as $row) {
                if ($index === 0 && !empty($this->header)) {
                    echo '<tr>';
                    foreach ($this->header as $value) {
                        echo '<th>' . $value['title'] . '</th>';
                    }
                    echo '</tr>';
                }
                $index++;
                echo '<tr>';
                $skipCol = 0;
                foreach ($this->header as $field => $value) {
                    if ($skipCol > 0) {
                        $skipCol--;
                        continue;
                    }
                    switch ($value['type']) {
                        case 'index':
                            $content = $index;
                            break;
                        case 'datetime':
                            $content = isset($row[$field]) ? date('Y-m-d H:i:s', intval($row[$field])) : '';
                            break;
                        case 'hash':
                            $content = $value['hash'][$row[$field]];
                            break;
                        default:
                            $content = $row[$field];
                            break;
                    }
                    if ($value['type'] == 'int' || $value['type'] == 'float') {
                        echo '<td';
                    } else {
                        echo '<td style="vnd.ms-excel.numberformat:@"';
                    }
                    if (isset($row[$field . ' attr']) && $attrs = $row[$field . ' attr']) {
                        if (gettype($attrs) == 'string') {
                            echo ' ' . $attrs;
                        } else {
                            foreach ($attrs as $attrName => $attrValue) {
                                echo ' ' . $attrName . '="' 
                                        . str_replace('"', '\"', $attrValue) . '"';
                                if ($attrName == 'colspan') {
                                    $skipCol = intval($attrValue);
                                }
                            }
                        }
                    }
                    echo '>' . $content . '</td>';
                }
                echo '</tr>';
            }
        }
        echo '</table></body></html>';
        return $this;
    }
}