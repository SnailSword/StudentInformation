/**
 * Created by Saniac on 2016/6/12.
 */

var currentSelect = 'geo';
var currentSelectTab = 'Info1';

$(document).ready(function () {

    $('#geoSelector').css("display", "block");
    $('#geo').css("display", "block");

    $('.navList>li').bind("click", function () {
        $(".active").removeClass("active");
        $(this).addClass("active");
        $('#' + currentSelect ).css("display", "none");
        $('#' + currentSelect + 'Selector').css("display", "none");
        currentSelect = $(this).attr('id').slice(0,3);
        $('#' + currentSelect ).css("display", "block");
        $('#' + currentSelect + 'Selector').css("display", "block");
    });
	$('#stu .clsContent .tabs li').bind("click", function () {
		$(".activeTab").removeClass("activeTab");
		$('.stu' + currentSelectTab).css("display", "none");
		$(this).addClass("activeTab");
		currentSelectTab = $(this).attr('class').slice(0,5);
		$('.stu' + currentSelectTab).css("display", "block");
	});
}
);

/*		var map;
		var visible=[];
		var layer=[];
		var symbol;
		//var graphic;
		//dojo代码
		require([
		"dojo/dom","dojo/on",
		"esri/map","esri/layers/ArcGISDynamicMapServiceLayer","esri/layers/FeatureLayer",
		"esri/tasks/query", "esri/tasks/QueryTask","esri/symbols/SimpleFillSymbol","esri/InfoTemplate", "esri/geometry/Extent","dojo/_base/Color","esri/SpatialReference","esri/geometry/Point",
		"esri/tasks/IdentifyTask","esri/tasks/IdentifyParameters",
		"esri/request","dojo/_base/json","dojo/domReady!"],
		function(dom,on,Map, ArcGISDynamicMapServiceLayer,FeatureLayer,Query, QueryTask,SimpleFillSymbol,InfoTemplate,Extent,Color,SpatialReference,Point,IdentifyTask,IdentifyParameters,esriRequest,dojoJson){
			//var startExtent=new Extent(70.866,17.462,136.586,59.656,new SpatialReference({ wkid:4326 }));
			map = new Map("mapshow");
			//map.centerAndZoom(new Point(108.972,31.81,new SpatialReference({ wkid:4326 })),0.5);
			layer = new ArcGISDynamicMapServiceLayer("http://localhost:6080/arcgis/rest/services/China/MapServer");
			//layer.initialExtent=new Extent(70.866,17.462,136.586,59.656,new SpatialReference({ wkid:4326 }));
            map.addLayer(layer);

			var queryTask = new QueryTask("http://localhost:6080/arcgis/rest/services/China/MapServer/0");
			  var query = new Query();
			   query.returnGeometry = true;
			    query.outFields=["Name"];
			
			symbol = new SimpleFillSymbol();
			 symbol.setStyle(SimpleFillSymbol.STYLE_SOLID);
			  symbol.setColor(new Color([255,255,0,1]));	
				
				//console.log(dom.byId("paint"));
			on(dom.byId("paint"),"click",sendRequest);
			on(map,"load",initIdentify);
			on(map,"click",doIdentify);
			on(layer,"load",loadLayerList);
			
			function initIdentify(){
			//alert("aaa");
				
				identifyTask=new IdentifyTask("http://localhost:6080/arcgis/rest/services/China/MapServer");
				identifyParams = new IdentifyParameters();
				identifyParams.tolerance = 3;
				//进行Identify的图层
				identifyParams.layerIds=[0];
				//进行Identify的图层为全部
				identifyParams.layerOption = IdentifyParameters.LAYER_OPTION_ALL;
			}
			
			function doIdentify(evt){
			//alert(evt);
			//Identify的geometry
				identifyParams.geometry=evt.mapPoint;
				//Identify范围
			identifyParams.mapExtent = map.extent;
			identifyTask.execute(identifyParams, function(idResults) { showIdentifyResults(idResults, evt); });
			}
	
				
				//发送ajax请求，返回json数组
			function sendRequest(){
				alert("a");
				//console.log(classname);
				var classname = dom.byId("select_class").value;
				var request = esriRequest({
				url:"./php/hometownsum.php?class="+classname,
				handleAs:"json"
				});
				
				request.then(function(data){
					//console.log(data);
					paintZhuantitu(data);  //调用绘制专题图函数
				},function(error){
					console.log("error:",error.message);
					//console.log("x");
				});
				}
			
			//查询生源地省份
			function paintZhuantitu(info){
				provinces = info;
				//console.log(provinces);
				map.graphics.clear();
				for (var i =0;i<provinces.length;i++){
				//console.log(provinces[i]);				
				query.text=provinces[i][0];
				queryTask.execute(query,showResults);
				}
			}
			
			//绘制出生源地专题图
			function showResults(results){
				  var graphic = results.features[0];
				  graphic.setSymbol(symbol);
				  //infoTemplate = new InfoTemplate();
				//graphic.setInfoTemplate(infoTemplate);
					   map.graphics.add(graphic);	
			}
			
			//显示identify的结果
			function showIdentifyResults(idResults,evt){
				idProvince=idResults[0].value;  //识别出来的省份信息
				//console.log(idProvince);
				for (var i =0;i<provinces.length;i++){
					if(provinces[i][0]==idProvince){
						var count = provinces[i][1];  //获取识别省份人数
					}
				}
				map.infoWindow.setTitle("详细信息");
				map.infoWindow.setContent("<b>省份：</b>"+idProvince+"<br><b>学生人数：</b>"+count);
				map.infoWindow.show(evt.screenPoint,map.getInfoWindowAnchor(evt.screenPoint));
				
			}
			
			
			function loadLayerList(layers)
			{
				//alert("aaa");
				//console.log(layers);
				var html="";
				var infos=layers.layer.layerInfos;
				
				//console.log(infos);
				//获取图层的信息
				for(var i =0;i<infos.length;i++){
					var info=infos[i];
					//console.log(info);
					if(info.defaultVisibility){
						visible.push(info.id);
					}
					//输出图层列表的html
					html=html+"<div><input id='"+info.id+"' name='layerList' class='listCss' type='checkbox' value='checkbox' "+(info.defaultVisibility ? "checked":"")+" />"+info.name+"</div>"
					//dom.byId("toc").innerHTML=html;
					
				}
				//设置可见图层
				layer.setVisibleLayers(visible);
				//console.log(html);
				dom.byId("toc").innerHTML+=html;
				//console.log(info.id);
				//console.log(dom.byId("'"+info.id+"'"));
				//var id="'"+info.id+"'";
				//console.log(id);
				//console.log(dom.byId("'"+0+"'"));
				
				//on(dom.byId("'"+info.id+"'"),"click",setLayerVisibility);
				
			}
			
			function setLayerVisibility(){
				alert(a);
					var inputs= dojo.query(".listCss");
					visible=[];
					for(var i=0;i<inputs.length;i++){
						if(inputs[i].checked){
							visible.push(inputs[i].id);
							}
					}
					layer.setVisibleLayers(visible);
			}
			
			
			});
*/


dojo.require("esri.map");
dojo.require("esri.tasks.query");

function init()
{
   map = new esri.Map("mapshow");

   //底图Tile图
   var imageryPrime = new esri.layers.ArcGISDynamicMapServiceLayer("http://localhost:6080/arcgis/rest/services/China/MapServer");
   map.addLayer(imageryPrime);
}

//搜索方法
function search(txt)
{
   if(txt!="")
   {
      //实例化QueryTask，查询图层2，也就是States图层
      queryTask = new esri.tasks.QueryTask("http://localhost:6080/arcgis/rest/services/China/MapServer/0");

      //查询参数
      query = new esri.tasks.Query();
      //需要返回Geometry
      query.returnGeometry = true;
      //需要返回的字段
      query.outFields = ["name"];
      //查询条件
      query.text = txt;

      //信息模板
      infoTemplate = new esri.InfoTemplate();
      //设置Title
      infoTemplate.setTitle("${name}");
      //设置Content
      infoTemplate.setContent("当前选择："+"${name}"+"<br/>该省人数：2人"+"<br/>男生：1人"+"<br/>女生：1人");

      //设置infoWindow的尺寸
      map.infoWindow.resize(245,125);
      //进行查询，完成后调用showResults方法

      queryTask.execute(query,showResults);
   }
   else
   {
      alert("请输入查询语句");
   }
}

//高亮显示查询结果
function showResults(results)
{
   //清除上一次的高亮显示
   map.graphics.clear();

   //高亮样式
   highlightSymbol = new esri.symbol.SimpleFillSymbol(esri.symbol.SimpleFillSymbol.STYLE_SOLID, new esri.symbol.SimpleLineSymbol(esri.symbol.SimpleLineSymbol.STYLE_SOLID, new dojo.Color([255,0,0]), 2), new dojo.Color([125,125,125,0.35]));

   //查询结果样式
   symbol = new esri.symbol.SimpleFillSymbol(esri.symbol.SimpleFillSymbol.STYLE_SOLID, new esri.symbol.SimpleLineSymbol(esri.symbol.SimpleLineSymbol.STYLE_SOLID, new dojo.Color([0,0,255,0.35]), 1),new dojo.Color([125,125,125,0.35]));

   var rExtent = new esri.geometry.Extent();

   //遍历查询结果
   for (var i=0;i<results.features.length; i++)
   {
      var graphic=results.features[i];

      //设置查询到的graphic的显示样式
      graphic.setSymbol(symbol);

      //设置graphic的信息模板
      graphic.setInfoTemplate(infoTemplate);

      //把查询到的结果添加map.graphics中进行显示
      map.graphics.add(graphic);

      //获取查询到的所有geometry的Extent用来设置查询后的地图显示
      if(i==0)
      {
         rExtent=graphic.geometry.getExtent();
      }
      else
      {
        rExtent=rExtent.union(graphic.geometry.getExtent());
      }  
   }

   //设置地图视图范围
   map.setExtent(rExtent);

   //启用map.graphics的鼠标事件
   map.graphics.enableMouseEvents();

   //map.graphics的鼠标移上去事件
   dojo.connect(map.graphics, "onMouseOver",showTip);

   //map.graphics的鼠标移开事件
   dojo.connect(map.graphics, "onMouseOut",hideTip);
}

//鼠标移上去事件
function showTip(evt)
{
   //获取当前graphic的信息内容
   var hgraphic=evt.graphic;
   var content =hgraphic.getContent();

   map.infoWindow.setContent(content);

   var title = evt.graphic.getTitle();
   map.infoWindow.setTitle(title);

   evt.graphic.setSymbol(highlightSymbol);

   map.infoWindow.show(evt.screenPoint,map.getInfoWindowAnchor(evt.screenPoint));
}


//鼠标移开事件
function hideTip(evt)
{
   //隐藏infoWindow
   map.infoWindow.hide();

   //查询结果取消红色高亮显示
   evt.graphic.setSymbol(symbol);
}

dojo.addOnLoad(init);