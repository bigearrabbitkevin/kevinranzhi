<?php
/**
 * The kevinchart class, to build chart tags.
 *
 * copyright:Kevin<3301647@qq.com>
 * http://kevincom.sourceforge.net/
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 * 
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

class kevinchart
{
	/**
     * The max x axis.
     * 
     * @var int
     * @access public
     */
    public $maxXaxis;
	
	/**
     * The max y axis.
     * 
     * @var int
     * @access public
     */
    public $maxYaxis;
	
	/**
     * colors.
     * 
     * @var array
     * @access private
     */
    private $colors = array(0 => 'AFD8F8'
							, 1 => 'F6BD0F'
							, 2 => '8BBA00'
							, 3 => 'FF8E46'
							, 4 => '008E8E'
							, 5 => 'D64646'
							, 6 => '8E468E'
							, 7 => '588526'
							, 8 => 'B3AA00'
							, 9 => '008ED6'
							, 10 => '9D080D'
							, 11 => 'A186BE'
							);
	/**
     * The construct function.
     * 
     * @param  int    $maxYaxis 
     * @param  int    $maxXaxis 
     * @access public
     * @return void
     */
    public function __construct($maxXaxis = 700, $maxYaxis = 700)
    {
        $this->setMaxXaxis($maxXaxis);
        $this->setMaxYaxis($maxYaxis);
    }
	/**
     * Set the maxXaxis property.
     * 
     * @param  int    $maxXaxis 
     * @access public
     * @return void
     */
    public function setMaxXaxis($maxXaxis = 700)
    {
        $this->maxXaxis = (int)$maxXaxis;
    }
	/**
     * Set the maxYaxis property.
     * 
     * @param  int    $maxYaxis 
     * @access public
     * @return void
     */
    public function setMaxYaxis($maxYaxis = 700)
    {
        $this->maxYaxis = (int)$maxYaxis;
    }
	/**
	 * Draw bar graph.
	 * 
	 * @param  array   $chartData  
	 * @access public
	 * @return string html
	 */
	public function drawBarGraph($chartData)
	{
		if(0 == count($chartData)) return '';
		$tempXaxis = 0;
		$tempYaxis = 0;
		$maxValue =  current($chartData)->value;
		foreach($chartData as $index => $currentObj)
		{
			if($currentObj->value > $maxValue) $maxValue = $currentObj->value;
		}
		$barGraph = "<svg width='100%' height='500'>";
		$barGraph .= "<g transform='translate(50,50) scale(0.5)'>";//设置比例为0.5,坐标偏移50
		//Now Draw the main X and Y axis
		$barGraph .= "<g style='stroke-width:5; stroke:black'>";
		//X Axis
		$barGraph .= "<path d='M 0 {$this->maxYaxis} L {$this->maxXaxis} {$this->maxYaxis} Z'/>";
		//Y Axis
		$barGraph .= "<path d='M 0 0 L 0 {$this->maxYaxis} Z'/></g>";
		
		$barGraph .= "<g style=\"fill:none; stroke:#B0B0B0; stroke-width:2; stroke-dasharray:2 4;text-anchor:end; font-size:30px\">";
		$levelArray = array();
		foreach($chartData as $index => $currentObj)
		{
			$tempHeight = ($currentObj->value/$maxValue)*$this->maxYaxis;
			$tempYaxis = $this->maxYaxis - $tempHeight;
			if(in_array($tempYaxis, $levelArray)) continue;
			else $levelArray[] = $tempYaxis;
			$barGraph .= "<path d=\"M 0 $tempYaxis L {$this->maxXaxis} $tempYaxis Z\"/>";//画虚线
			$barGraph .= "<text style='fill:black; stroke:none' x='-10' y=$tempYaxis>{$currentObj->value}</text>";//标识y值
		}
		//遍历显示条目名称
		$tempXaxis = 50;
		foreach($chartData as $index => $currentObj)
		{
			//此处为水平坐标显示名称, -60为逆时针旋转60度
			$barGraph .= "<text style='fill:black; stroke:none' x=$tempXaxis y='730' transform='rotate(-60 $tempXaxis, 730)'>{$currentObj->name}</text>";
			$tempXaxis += 110;
		}

		$barGraph .= "</g>";
		$tempXaxis = 0;
		$tempYaxis = 0;
		
		//此处采用report模块中的颜色
		$colors = $this->colors;
        $colorCount = count($colors);
        $i = 0;
		foreach($chartData as $index => $currentObj)
		{
			if($i == $colorCount) $i = 0;
            $color = $colors[$i];
            $i++;
			$tempHeight = ($currentObj->value/$maxValue)*$this->maxYaxis;
			$tempYaxis = $this->maxYaxis - $tempHeight;
			$barGraph .= "<rect x=$tempXaxis y=$tempYaxis width ='100' height=$tempHeight style='fill:#$color;'/> ";
			$tempXaxis += 110;
		}
		$barGraph .= "</g>";
		$barGraph .= "</svg>";
		return $barGraph;
	}
}