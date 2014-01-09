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
var qs = require('querystring');
require("../node_modules/string_score");

var app = express();

// App全局配置
app.set('views', 'cloud/views'); //设置模板目录
app.set('view engine', 'ejs'); // 设置template引擎
app.use(express.bodyParser()); // 读取请求body的中间件


app.get('/c', function(req, res) {
    //每隔5分钟就尝试更新一次。
    var num = 5;
    var min = 60000;
    res.render('mes', {
        message: "Updating ..."
    });
    //从新郎官方网址抓取视频
    setInterval(function() {
        createTodayVideo("NBA");
    }, num * min);
    createTodayVideo("NBA");

    //从滚动网址抓取视频
    setInterval(function() {
        updateKeyWords();
    }, num * min);
});



/**
    抓取新浪的NBA最新消息，然后进行分类 ( updateSinaRollNewsToAvos )
    -updateKeyWords -> getTheLatestNewsInAvos --> getNewsFromSinaNewsRoll -> parseAndSaveNews -> 
**/

var keyWords = []; //['日官方最佳', '视频', '录播', '集锦', '10佳球', '5佳球', '统计', '实录', '图文', '最佳', '官方', '趣谈', '数据', '精彩', '扣篮','助攻', '盖帽', '过人'];
var latest;
updateSinaRollNewsToAvos();
/*
//只用于人手更新
var skip = 0;
function updateIntro() {
    var query = new AV.Query('News');
    _log('skip: '+skip);
    query.skip(skip);
    query.descending('createdAt');
    query.find({
        success: function(results) {
            var news = results[0];
            var url = news.get('url');
            var title = news.get('title');
            var mediaType = news.get('mediaType');
            if (mediaType == 'video') {
                fetchUrl(url, function(error, meta, body) {
                    var items = [];
                    $ = cheerio.load(body.toString());
                    var intro = $('.vedioinfo_inner em p').first().text();
                    intro = intro.substr(2, intro.length);
                    _log(intro);
                    skip++;
                    updateIntro();
                    news.set('intro', intro);
                    news.save();
                });
            } else {
                skip++;
                updateIntro();
            }

        },
        error: function(error) {
            _log("updateIntro error :" + error.message);
        }
    })
}
*/

function updateSinaRollNewsToAvos() {
    //每隔5分钟就尝试更新一次。
    var num = 5;
    var min = 60000;
    setInterval(function() {
        updateKeyWords();
    }, num * min);
    updateKeyWords();
}

//更新新闻关键字
function updateKeyWords() {
    var query = new AV.Query('KeyWords');
    query.find({
        success: function(results) {
            for (var i = 0; i < results.length; i++) {
                var w = results[i].get('string');
                keyWords.push(w);
            };
            _log('更新关键字完毕！')
            getTheLatestNewsInAvos();
        },
        error: function(error) {
            _log('error :' + error.message);
        }
    });
}
//通过标题取得关键字
function getNewsKeyWords(title) {
    var _keyWords = [];
    var t = keyWords.length;
    for (var i = 0; i < t; i++) {
        var word = keyWords[i];
        var n = title.score(word);
        if (n > 0) {
            _keyWords.push(word);
        };
    };
    return _keyWords;
}

function getTheLatestNewsInAvos(nextFunc) {
    var News = AV.Object.extend('News');
    var query = new AV.Query('News');
    query.limit(1);
    query.descending('createdAt');
    query.find({
        success: function(results) {
            if (results.length == 0) {
                _log('AVOS没有消息');
                getNewsFromSinaNewsRoll();
            } else {
                latest = results[0];
                _log('取得AVOS上最新的News!' + latest.get('title'));
                getNewsFromSinaNewsRoll();
            }
        },
        error: function(error) {
            _log('error: ' + error.message);
        }
    })
}

//更新新闻
function getNewsFromSinaNewsRoll() {
    var url = 'http://roll.sports.sina.com.cn/s_2002-2003NBA_all/index.shtml';
    _log('读取消息来源: ' + url);
    fetchUrl(url, function(error, meta, body) {
        var items = [];
        $ = cheerio.load(body.toString());
        $('li span a').each(function(i, elem) {
            var obj = {};
            var title = $(this).text();
            var mediaType = $(this).hasClass('videoNewsLeft') ? "video" : "text";
            var time = $(this).parent().next().text();
            var url = $(this).attr('href');
            obj['title'] = title;
            obj['mediaType'] = mediaType;
            obj['time'] = time;
            obj['url'] = url;
            items.push(obj);
        });

        //如果是第一次执行
        if (!latest) {
            _log('重新开始更新新浪NBA滚动新闻。');
            latest = items[0];
            items.reverse();
            //items = items.splice(0,5);
            parseAndSaveNews(items);
        } else {
            var t = items.length;
            var index = 0;
            for (var i = 0; i < t; i++) {
                var o = items[i]
                if (latest.get('url') == o['url']) {
                    index = i;
                    break;
                };
            };
            items.splice(index, t);
            items.reverse();
            parseAndSaveNews(items);
        }
    });
}
// 解析然后保存新闻
function parseAndSaveNews(newsArr) {
    if (newsArr.length == 0) {
        _log('本次更新完成！');
        return;
    };
    var news = newsArr[0];
    var url = news['url'];
    fetchUrl(url, function(error, meta, body) {
        $ = cheerio.load(body.toString());
        var _news = $('dl dt a').first();
        var title = _news.attr('title');
        var image = _news.children('img').attr('_src');
        var href = _news.attr('href')
        var mediaType = news.mediaType;
        var publish = news.time;
        var url = news.url;
        //
        var News = AV.Object.extend('News');
        var newsObj = new News();
        newsObj.set('title', news['title']);
        newsObj.set('image', image);
        newsObj.set('mediaType', mediaType);
        newsObj.set('publish', publish);
        newsObj.set('url', url);
        newsObj.set('offical', 'NBA');
        newsObj.set('type', "basketball");
        var kw = getNewsKeyWords(news['title']);
        var isNBAOffical = false;
        var _t = kw.length;
        for (var k = 0; k < _t; k++) {
            var _str = kw[k];
            if (_str == '日官方最佳' || _str == '日官方10' || _str == '日官方5') {
                isNBAOffical = true;
                break;
            };
        };

        newsObj.set('keyWords', kw);
        newsObj.set('isOffical', isNBAOffical);

        if (mediaType == 'video') {
            newsObj.set('href', news['url']);
        };
        newsObj.save(null, {
            success: function(result) {
                _log("保存 #" + result.get('title') + " #成功!");
                lastest = newsArr.shift();
                if (isNBAOffical == true) {
                    saveNewsToNBATodayBest(result);
                };
                parseAndSaveNews(newsArr);
            },
            error: function(result, error) {
                _log('error: ' + error.message);
            }
        });
    });
}

function saveNewsToNBATodayBest(news) {
    var query = new AV.Query("NBATodayBest");
    var today = moment(new Date().toString()).format("YYYY-MM-DD");
    query.equalTo('date', today);
    query.find({
        success: function(results) {
            var t = results.length;
            if (t > 0) {
                _log("添加今天最佳")
                var _best = results[0];
                news.set('NBATodayBest', _best);
                news.save();
            } else {
                //保存一个新的当日最佳
                _log("创建今天最佳!")
                var NBATodayBest = AV.Object.extend('NBATodayBest');
                var best = new NBATodayBest();
                best.set('date', today);
                best.save({
                    success: function(res) {
                        news.set('NBATodayBest', res);
                        news.save();
                    },
                    error: function(err) {
                        _log('error: ' + err.message);
                    }
                })
            }

        },
        error: function(error) {
            _log("saveNewsToNBATodayBest error: " + error.message);
        }
    })

}
/** End **/

//更新NBA官方视频
function updateOfficialbest(today) {
    var url = 'http://video.sina.com.cn/z/sports/nba/officialbest/';
    fetchUrl(url, function(error, meta, body) {
        var items = [];
        $ = cheerio.load(body.toString());
        $('div .slide01_items').each(function(i, elem) {
            var obj = {};
            obj['datavid'] = $(this).attr('data-vid');
            obj['dataurl'] = $(this).attr('data-url');
            obj['datatitle'] = removeString($(this).attr('data-title'));
            obj['datainfo'] = $(this).attr('data-info');
            obj['image'] = $(this).children('a').children('img').attr('src');
            obj['code'] = $(this).children('a').children('code').text();
            obj['text'] = $(this).find('p').text();
            obj['today'] = today;
            items.push(obj);
        });
        createOfficialbest(items);
    });


}

//创建NBA官方视频
function createOfficialbest(items) {

    if (items.length == 0) {
        _log("Done! Waiting 5 Minutes. :P");
        return;
    };

    var item = items[0];
    items.shift();
    var Officialbest = AV.Object.extend("Officialbest");
    var officialbest = new Officialbest();
    //find the video
    var query = new AV.Query(Officialbest);
    query.equalTo("datavid", item['datavid']);
    query.find({
        success: function(results) {
            var t = results.length;
            if (t == 0) {
                //can't find the result, add to db
                officialbest.save(item, {
                    success: function(result) {
                        createOfficialbest(items);
                        var _today = result.get('today');
                        _today.increment('total');
                        _today.save();
                        _log("Save: " + result['datatitle'] + "Success :P");
                    },
                    error: function(result, error) {
                        _log('save offcialbest error: ' + error.message);
                    }
                });

            } else {
                _log("已保存，Next...")
                createOfficialbest(items);
            }
        },
        error: function(error) {
            _log('error: ' + error.message);
        }
    });

}

//删除一些没用的字符
function removeString(str) {
    var s = "日官方";
    var index = str.indexOf(s);
    str = str.substr((index + 3), str.length);
    return str;
}

//创建今天的精彩视频 , type : NBA, Football....

function createTodayVideo(type) {

    var Today = AV.Object.extend("Today");
    var today = new Today();
    var day = new Date();
    var dayStr = moment(day).format('YYYY-MM-DD');

    today.set('day', day);
    today.set('dayStr', dayStr);
    today.set('type', type);
    today.set('total', 0);

    var query = new AV.Query(Today);
    query.equalTo("dayStr", dayStr);
    query.find({
        success: function(results) {
            var t = results.length;
            var _today;
            if (t == 0) {
                //can't find the result, add to db
                today.save(today, {
                    success: function(result) {
                        _today = result;
                        _log('create today success!');
                        updateOfficialbest(_today);
                    },
                    error: function(result, error) {
                        //error
                        _log('create today error: ' + error.message);
                    }
                });

            } else {
                _today = results[0];
                updateOfficialbest(_today);
            }
        },
        error: function(error) {
            _log('error: ' + error.message);
        }
    });
}

function _log(mes) {
    var day = new Date().toString();
    var t = moment(day).format('YYYY-MM-DD h:mm:ss a');
    console.log(t + " : " + mes);
}



/*
//getShortURL('http://zhenyouai.com/ssssss',_log);
function saveWeddingShortURL(code, wedding){
      AV.Cloud.httpRequest({
        url: 'http://126.am/api!shorten.action?key=d6d7c6618d0b426c9296a265ccd3dd68&longUrl=http://zhenyouai.com/wedding?code='+code,
        success: function(httpResponse) {
          var obj = JSON.parse(httpResponse.text);
          var surl = obj['url'];
          console.log('-->'+httpResponse.text);
        },
        error: function(httpResponse) {
          console.error('Request failed with response code ' + httpResponse.status);
        }
    });
  }
saveWeddingShortURL('999999',null);*/


//最后，必须有这行代码来使express响应http请求
app.listen();