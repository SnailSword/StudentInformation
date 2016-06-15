/**
 * Created by Saniac on 2016/6/12.
 */

var currentSelect = 'geo';

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

}
);

dojo.require("esri.map");
dojo.require("esri.tasks.query");

function init()
{
   map = new esri.Map("mapshow");

   //底图Tile图
   var imageryPrime = new esri.layers.ArcGISDynamicMapServiceLayer("http://localhost:6080/arcgis/rest/services//China/MapServer");
   map.addLayer(imageryPrime);
}

//搜索方法
function search(txt)
{
   if(txt!="")
   {
      //实例化QueryTask，查询图层2，也就是States图层
      queryTask = new esri.tasks.QueryTask("http://localhost:6080/arcgis/rest/services//China/MapServer/0");

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
      infoTemplate.setContent("<b>省份: </b>${name}<br/>"
                             + "<b>AREA: </b>${AREA}<br/>"
                             + "<b>STATE_NAME: </b>${STATE_NAME}<br/>"
                             + "<b>POP2000: </b>${POP2000}");

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