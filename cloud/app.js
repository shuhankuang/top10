// 在Cloud code里初始化express框架
var http = require('http');
var express = require('express');
var request = require('request');
var cheerio = require('../node_modules/cheerio'); 
var avos = require('avoscloud-code');
var iconv = require('../node_modules/iconv-lite');
var fetchUrl = require("../node_modules/fetch").fetchUrl;
var moment = require('../node_modules/moment');
var cronJob = require('../node_modules/cron').CronJob;

var app = express();

// App全局配置
app.set('views','cloud/views');   //设置模板目录
app.set('view engine', 'ejs');    // 设置template引擎
app.use(express.bodyParser());    // 读取请求body的中间件


app.get('/c', function(req, res){
  //每隔5分钟就尝试更新一次。
  var num = 5;
  var min = 60000;
  res.render('mes', {message: "Updating ..."});
  setInterval(function(){
    createTodayVideo("NBA");
  }, num*min);
  createTodayVideo("NBA");
});

//更新NBA官方视频
function updateOfficialbest(today){
  var url = 'http://video.sina.com.cn/z/sports/nba/officialbest/';
  fetchUrl(url, function(error, meta, body){
      var items = [];
      $ = cheerio.load(body.toString());
      $('div .slide01_items').each(function(i, elem) {
        var obj = {};
        obj['datavid']= $(this).attr('data-vid');
        obj['dataurl']= $(this).attr('data-url');
        obj['datatitle']= removeString($(this).attr('data-title'));
        obj['datainfo']= $(this).attr('data-info');
        obj['image']= $(this).children('a').children('img').attr('src');
        obj['code']= $(this).children('a').children('code').text();
        obj['text']= $(this).find('p').text();
        obj['today'] = today;
        items.push(obj);
      });
      createOfficialbest(items);
  });

  
}

//创建NBA官方视频
function createOfficialbest(items){

	if (items.length == 0) {
    _log("Done! Waiting 5 Minutes. :P");
    return;
  };

  var item = items[0]
  items.shift();
  var Officialbest = AV.Object.extend("Officialbest");
  var officialbest = new Officialbest();
  //find the video
  var query = new AV.Query(Officialbest);
  query.equalTo("datavid", item['datavid']);
  query.find({
    success: function(results) {
      var t = results.length;
      if(t==0){
        //can't find the result, add to db
        officialbest.save(item, {
          success: function(result) {
            createOfficialbest(items);
            var _today = result.get('today');
            _today.increment('total');
            _today.save();
            _log("Save: "+ result['datatitle'] + "Success :P");
          },
          error: function(result, error) {
            _log('save offcialbest error: ' + error.message);
           }
        });

      }else{
        _log("已保存，Next...")
        createOfficialbest(items);
      }
    },
    error: function(error) {
      _log('error: '+ error.message);
    }
  });

}

//删除一些没用的字符
function removeString(str){
  var s = "日官方";
  var index = str.indexOf(s);
  str = str.substr((index+3),str.length);
  return str;
}

//创建今天的精彩视频 , type : NBA, Football....

function createTodayVideo(type){

  var Today = AV.Object.extend("Today");
  var today = new Today();
  var day = new Date();
  var dayStr = moment(day).format('YYYY-MM-DD');

  today.set('day', day);
  today.set('dayStr', dayStr);
  today.set('type',type);
  today.set('total', 0);

  var query =  new AV.Query(Today);
  query.equalTo("dayStr", dayStr);
  query.find({
    success: function(results) {
      var t = results.length;
      var _today;
      if(t==0){
        //can't find the result, add to db
        today.save(today, {
          success: function(result) {
            _today = result;
            _log('create today success!' );
            updateOfficialbest(_today);
          },
          error: function(result, error) {
            //error
            _log('create today error: ' + error.message);
           }
        });

      }else{
        _today = results[0];
        updateOfficialbest(_today);
      }
    },
    error: function(error) {
      _log('error: '+ error.message);
    }
  });
}

function _log(mes){
  var day = new Date().toString();
  var t = moment(day).format('YYYY-MM-DD h:mm:ss a');
  console.log(t +" : " +mes);
}


//最后，必须有这行代码来使express响应http请求
app.listen();