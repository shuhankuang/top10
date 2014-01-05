// 在Cloud code里初始化express框架
var http = require('http');
var express = require('express');
var request = require('request');
var cheerio = require('../node_modules/cheerio'); 
var avos = require('avoscloud-code');
var iconv = require('../node_modules/iconv-lite');


var app = express();

// App全局配置
app.set('views','cloud/views');   //设置模板目录
app.set('view engine', 'ejs');    // 设置template引擎
app.use(express.bodyParser());    // 读取请求body的中间件

//使用express路由API服务/hello的http GET请求
app.get('/hello', function(req, res) {
  res.render('hello', { message: 'Congrats, you just set up your app!' });
});

app.get('/c', function(req, res){

  //res.send("Start!");
	update(res);


});

function update(res){
  var url = 'http://video.sina.com.cn/z/sports/nba/officialbest';
  //var url = 'http://rss.sina.com.cn/sports/basketball/nba.xml';
  request(url, function (error, response, body) {
      if (!error && response.statusCode == 200) {
        var items = [];
        $ = cheerio.load(body);
        $('div .slide01_items').each(function(i, elem) {
          var obj = {};
          obj['datavid']= $(this).attr('data-vid');
          obj['dataurl']= $(this).attr('data-url');
          obj['datatitle']= $(this).attr('data-title');
          obj['datainfo']= $(this).attr('data-info');
          obj['image']= $(this).children('a').children('img').attr('src');
          obj['code']= $(this).children('a').children('code').text();
          obj['text']= $(this).find('p').text();
          items.push(obj);
          
          console.log('message: '+ iconv.decode(obj['datatitle'], 'gbk'));
      });
        console.log("你好!");
        //res.send('Update...');
        //createOfficialbest(items, res);
      }
  });

  
}

function createOfficialbest(items, res){

	if (items.length == 0) {
    res.render('mes', {message: "update ..." + new Date().toISOString()});
    res.end();
    return;
  };

  res.render('mes', {message: "update ..."});

  var item = items[0]
  items.shift();

  var Officialbest = AV.Object.extend("Officialbest");
  var officialbest = new Officialbest();
  officialbest.set("datavid", 'item[k]');
  officialbest.save(item, {
    success: function(result) {
      createOfficialbest(items, res);
    },
    error: function(result, error) {
      res.render('mes', {message: error.message});
     }
  });
}


//最后，必须有这行代码来使express响应http请求
app.listen();