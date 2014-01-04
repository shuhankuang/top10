// 在Cloud code里初始化express框架
var express = require('express');
var request = require('request');
var cheerio = require('../node_modules/cheerio'); 
var avos = require('avoscloud-code');


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
	var url = 'http://video.sina.com.cn/z/sports/nba/officialbest';
	//var url = 'http://rss.sina.com.cn/sports/basketball/nba.xml';
	request(url, function (error, response, body) {
  		if (!error && response.statusCode == 200) {
  			var obj = {};
  			$ = cheerio.load(body);
    		$('div .slide01_items').each(function(i, elem) {
  				obj['data-vid']= $(this).attr('data-vid')+"</br>";
  				obj['data-url']= $(this).attr('data-url')+"</br>";
  				obj['data-title']= $(this).attr('data-title')+"</br>";
  				obj['data-info']= $(this).attr('data-info')+"</br>";
  				obj['image']= $(this).children('a').children('img').attr('src')+"</br>";
  				obj['code']= $(this).children('a').children('code')+"</br>";
  				obj['text']= $(this).find('p')+"</br>";
			});
			createBest(obj);
    		res.send('更新中...');
  		}
	});
})

function createBest(object){
	var GameScore = AV.Object.extend("GameScore");
var gameScore = new GameScore();
gameScore.set("score", 1337);
gameScore.set("playerName", "Sean Plott");
gameScore.set("cheatMode", false);
gameScore.save(null, {
  success: function(gameScore) {
    // Execute any logic that should take place after the object is saved.
    console.log('New object created with objectId: ' + gameScore.id);
  },
  error: function(gameScore, error) {
    // Execute any logic that should take place if the save fails.
    // error is a AV.Error with an error code and description.
    console.log('Failed to create new object, with error code: ' + error.description);
  }
});
}


//最后，必须有这行代码来使express响应http请求
app.listen();