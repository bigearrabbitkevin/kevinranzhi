/*!
 * keivn js class
 * GitHub: https://github.com/bigearrabbitkevin/kevinzentao/trunk/kevindefine/www/js/kevin
 * version 1.0.0.1
 * Copyright kevin; 
 * Licensed MIT
 */

function GetNewPeriodMonth(type,year,month)
{
	var currentDate;
	if("+1" === type){
		if(month=='12'){
			month = '01';
			year = ''+ ( parseInt(year) +1);
		}
		else{
			month = ''+ ( parseInt(month) +1)
			if(month.length== 1) month = '0' + month;
		}
		currentDate = 	year + month;
	}
	else if("-1" === type){
		if(month=='01'){
			month = '12';
			year =  ''+( parseInt(year) -1);
		}
		else{
			month = ''+ ( parseInt(month) -1);
			if(month.length== 1) month = '0' + month;
		}
		currentDate = 	year + month;
	}
	else{
		currentDate = 	year + month;
	}
	return currentDate;
}

//kendogrid 公共变量
var kevin_kendogrid_div; //grid 变量表示$("#grid")
var kevin_kendogrid; //grid 变量表示$("#grid").kendoGrid
var kevin_kendogrid_columnsSets = '';//表格数据
var kevin_kendogrid_DataArray = []; //global data array
var kevin_kendogrid_fileName = '';//输出文件名的 公共变量
var kevin_kendogrid_dataItem = '';//表格行内数据
var kevin_kendogrid_toolbar = '';//toolbar

var kevin_today = '';//today string

var kevin_ModelWnd; //模态窗口
var kevin_detailsTemplate;//详细模版

/**
 * 初始化参数
 * 2019-06-02
 */
function kevin_base_initialParam() {
	kevin_kendogrid_div = $("#grid"); //referrer to grid
	kevin_today = kevin_GetTodayStr(); // 具体日期。
	kevin_kendogrid_fileName = 'Kendo_UI_Grid_Export_' + kevin_today + '.xlsx';
}

//获得今天日期字符串
function kevin_GetTodayStr() {
	 var date = new Date();
	 var month = date.getMonth() + 1;
	 var strDate = date.getDate();
	 if (month >= 1 && month <= 9) {
			 month = "0" + month;
		 }
	 if (strDate >= 0 && strDate <= 9) {
			strDate = "0" + strDate;
		 }
	 var currentDate = date.getFullYear() + '-' + month + '-' + strDate;
	 return currentDate;
}

/**
 * 定义kevin_ERROR的类变量
 * 2019-05-07
 */
var kevin_ERROR = {
	createNew: function () {
		var err = {};
		err.errcode = 0;
		err.errmsg = '';
		err.data = [];
		err.isError = function () {
			return 0 != err.errcode;
		};
		return err;
	}
};

/**
 * Check input object is kevin_ERROR or not
 * 2019-05-07
 * @param kevin_ERROR iData
 * @return kevin_ERROR
 */
function kevin_CheckJsonReturnData(iData) {
	var ret = kevin_ERROR.createNew();
	ret.errcode = 1;

	if (iData === null || !(iData.constructor === Object)) {
		ret.errmsg = 'It is not an Object : ' + iData;
		return ret;
	}

	if (!(iData.hasOwnProperty("errcode"))) {
		ret.errmsg = 'Error! No errcode property in json!';
		return ret;
	}
	else if (iData.errcode > 0) {
		ret.errmsg = (iData.hasOwnProperty("errmsg")) ? iData.errmsg : 'Unkown Error!';
		return ret;
	}
	if (!(iData.hasOwnProperty("data"))) {
		ret.errmsg = 'Error! No Data property in json!';
		return ret;
	}
	//获得code
	ret.errcode = iData.errcode;
	if (iData.errcode > 0) {
		ret.errmsg = ((iData.hasOwnProperty("errmsg")) ? iData.errmsg : 'Unkown Error!');
	}
	return ret; //no error
}

 /**
 * 展示kendogrid数据
 * kevin_kendogrid_DataArray = null表示全部显示列
 * 设定kevin_kendogrid的引用
 * 2019-05-07
 */
function kevin_kendogrid_updateGridData() {
	var record = 0;
	$("#grid").data("kendoGrid").destroy();
	$("#grid").html("");
	$("#grid").kendoGrid({
		columnMenu: true,
		columns: kevin_kendogrid_columnsSets,
		dataSource: kevin_kendogrid_DataArray,
		dataBinding: function () {
			kevin_kendogrid = this;
			record = (this.dataSource.page() - 1) * this.dataSource.pageSize();
		},
		selectable: "row",
		groupable: true,
		sortable: true,
		pageable: {
			pageSize: 15,
			pageSizes: [10, 15, 20, 50, 100, 200, 500, 1000, 2000],
			messages: {
				display: "{0}-{1} 共{2}个",
				empty: "无数据",
				itemsPerPage: "项每页"
			}
		},
		resizable: true,
		toolbar: kevin_kendogrid_toolbar,
		excel: {
			fileName: kevin_kendogrid_fileName,
			allPages: true//是否导出所有页中的数据
		}
	});
}
