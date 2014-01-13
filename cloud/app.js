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
var async = require('async');
require("../node_modules/string_score");

var app = express();

// App全局配置
app.set('views', 'cloud/views'); //设置模板目录
app.set('view engine', 'ejs'); // 设置template引擎
app.use(express.bodyParser()); // 读取请求body的中间件

//现实后台内容
app.get('/index', function(req, res){
    var query = new AV.Query('News');
    query.limit(20);
    query.descending('createdAt');
    query.find({
        success:function(results){
            res.render('index', {results:results});
        },
        error:function(error){
            console.log("error: "+error);
        }
    });
});
app.get('/all', function(req, res){
    var query = new AV.Query('News');
    query.limit(20);
    query.descending('createdAt');
    query.find({
        success:function(results){
            res.render('all', {results:results});
        },
        error:function(error){
            console.log("error: "+error);
        }
    });
});



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


//updateSinaRollNewsToAvos();
//updateURLForPad();
function updateURLForPad() {

    var textURL = 'http://sports.sina.com.cn';
    var iosSlide = 'http://dp.sina.cn/dpool/hdpic/view.php?';
    var iosNews = 'http://dp.sina.cn/dpool/cms/jump.php?url=';
    var slideURL = 'http://slide.sports.sina.com.cn';
    var query = new AV.Query('News');
    query.limit(300);
    query.find({
        success: function(results) {
            var t = results.length;
            for (var i = 0; i < t; i++) {
                var news = results[i];
                var url = news.get('url');
                var _text = url.indexOf(iosNews);
                var _slide = url.indexOf(iosSlide);
                if (_text > -1) {
                    //url = iosNews+url;
                    //_log(url);
                    var sl = iosNews.length;
                    url = url.substr(sl, url.length);
                    _log("news: " + url);
                    //news.set('url', url);
                    //news.save();
                };
                if (_slide > -1) {
                    /*
                    _log(_slide + ":" + url);
                    var arr = url.split('/');
                    arr = arr[arr.length-1].split('.')[0].split('_');
                    var obj = {ch:arr[1],sid:arr[2],aid:arr[3]}
                    var params = qs.stringify(obj);
                    url = iosSlide+params;
                    */
                    var sl = iosSlide.length;
                    url = url.substr(sl, url.length);
                    _log("slide: " + url);
                    //news.set('url', url);
                    //news.save();
                };
            };
        },
        error: function() {
            _log('updateURLForPad error: ' + error.message);
        }
    })
}
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

//根据新闻的路径判断该新闻的类型
function getNewsTypeAndFormatURL(url) {
    //只是文字新闻
    var textURL = 'http://sports.sina.com.cn';
    //幻灯片（图文）
    var slideURL = 'http://slide.sports.sina.com.cn';
    //视频
    var videoURL = 'http://video.sina.com.cn/';
    //博文
    var blogURL = 'http://blog.sina.com.cn/'

    //用于转换地址的，方便在移动设备上查看.
    var mobileSlide = 'http://dp.sina.cn/dpool/hdpic/view.php?';
    var mobileNews = 'http://dp.sina.cn/dpool/cms/jump.php?url=';
    var mobileVideo = 'http://dp.sina.cn/dpool/video/pad/play.php?url='

    var _type = '';
    var _url = '';

    if (url.indexOf(textURL) > -1) {
        //直播的页面是没有
        if (url.score('live') > 0) {
            _type = 'live-text';
        } else {
            _type = 'text';
            _url = mobileNews + url;
        }
    };
    if (url.indexOf(slideURL) > -1) {
        _type = 'slide';
        var arr = url.split('/');
        arr = arr[arr.length - 1].split('.')[0].split('_');
        var obj = {
            ch: arr[1],
            sid: arr[2],
            aid: arr[3]
        }
        var params = qs.stringify(obj);
        _url = mobileSlide + params;
    };
    if (url.indexOf(videoURL) > -1) {
        _type = 'video';
        _url = mobileVideo + url;
    };
    if (url.indexOf(blogURL) > -1) {
        _type = 'blog';
        _url = url;
    };

    return {
        type: _type,
        url: _url
    };
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
            var time = $(this).parent().next().text();
            var url = $(this).attr('href');
            obj['title'] = title;
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
    var obj = getNewsTypeAndFormatURL(url);

    fetchUrl(url, function(error, meta, body) {
        $ = cheerio.load(body.toString());

        var _news = $('dl dt a').first();
        var title = _news.attr('title');
        var image = _news.children('img').attr('_src');
        var publish = news.time;
        var url = news.url;

        var mobileURL = obj['url'];
        var newsType = obj['type'];

        var News = AV.Object.extend('News');
        var newsObj = new News();
        newsObj.set('title', news['title']);
        newsObj.set('image', image);
        newsObj.set('newsType', newsType);
        newsObj.set('publish', publish);
        newsObj.set('url', url);
        newsObj.set('mobileUrl', mobileURL);
        newsObj.set('offical', 'NBA');
        newsObj.set('type', "basketball");

        if (newsType == 'video') {
            var intro = $('.intro').next().html();
            _log(intro);
            newsObj.set('intro', intro);
        } else {
            newsObj.set('intro', '');
        }

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
                _log('parseAndSaveNews error: ' + error.message);
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

/**
    抓取NBA赛程
**/

function getNBAScheduleFromSina(year, month, day, team, callback) {
    var dataUrl = 'http://nba.sports.sina.com.cn/match_result.php?';
    var _m = (month < 10) ? '0' + month : month;
    var obj = {
        years: year,
        months: _m,
        day: day,
        teams: team
    };
    var params = qs.stringify(obj);
    var url = dataUrl + params;
    _log('url: ' + url);

    //时间， 类型， 客队， 比分， 主队， 客队最高分， 主队最高分， 战报， 统计， 视频， 组图， 网络视频/电视直播
    fetchUrl(url, function(error, meta, body) {
        $ = cheerio.load(body.toString());

        var _totalMatches = 0;
        var _todayMatches = [];
        var _monthMatches = [];
        var _date = ''

        $('.text tr').each(function(i, elem) {
            var bgcolor = $(this).attr('bgcolor');
            if (bgcolor == '#FFD200') {
                //是日期拦
                if (_todayMatches.length != 0) {
                    _date = $(this).children().first().text();
                    _monthMatches.push({
                        date: _date,
                        matches: _todayMatches.concat()
                    });
                    _todayMatches = [];
                }

            } else {
                if (bgcolor == '#FFEFB6') {
                    //是比赛栏
                    var obj = {};
                    obj['time'] = $(this).children().first().text();
                    obj['type'] = $(this).children().first().next().text();
                    obj['kedui'] = $(this).children().first().next().next().text();
                    obj['bifen'] = $(this).children().first().next().next().next().text();
                    obj['zhudui'] = $(this).children().first().next().next().next().next().text();
                    obj['kdgaofen'] = $(this).children().first().next().next().next().next().next().text();
                    obj['zdgaofen'] = $(this).children().first().next().next().next().next().next().next().text();
                    obj['zhanbao'] = $(this).children().first().next().next().next().next().next().next().next().text();
                    obj['tongji'] = $(this).children().first().next().next().next().next().next().next().next().next().text();
                    obj['video'] = $(this).children().first().next().next().next().next().next().next().next().next().next().text();
                    obj['pics'] = $(this).children().first().next().next().next().next().next().next().next().next().next().next().text();
                    obj['tv'] = $(this).children().first().next().next().next().next().next().next().next().next().next().next().next().text();
                    _todayMatches.push(obj);
                };

                _totalMatches += 1;
            }
        });
        //添加最后一天
        _monthMatches.push({
            date: _date,
            matches: _todayMatches.concat()
        });

        callback(null, _monthMatches);
        /*
        for (var i = 0; i < _monthMatches.length; i++) {
            var _month = _monthMatches[i];
            saveTodayMatches(_month['date'], _month['matches']);
        };
        */

    });
}

//getNBAScheduleFromSina(2014, 1, 0, '');

function saveTodayMatches(date, matches) {

    var NBAMatch = AV.Object.extend('NBAMatch');
    var NBAMatchDay = AV.Object.extend('NBAMatchDay');

    var _NBAMatchDay = new NBAMatchDay();
    _NBAMatchDay.set('date', date);
    _NBAMatchDay.save({
        success: function(result) {
            var t = matches.length;
            for (var i = 0; i < t; i++) {
                var match = matches[i];

            };
        },
        error: function(error) {
            _log("saveTodayMatches error: " + error.message);
        }
    });
}

function saveMatch(matches, matchToday) {
    if (matches.length == 0) {
        return;
    };

    var query = new AV.Query('NBAMatchDay');
    query.equalTo('date', matchToday['date']);
    query.find({
        success: function(results) {

            var _NBAMatch = new NBAMatch();
            match['NBAMatchDay'] = matchToday;
            var match = matches[0];
            _NBAMatch.save(match, {
                success: function(res) {
                    matches.shift();
                    _log('保存 #' + matchToday['date'] + ":" + res['time'] + "# 成功")
                },
                error: function(err) {
                    _log('_NBAMatch save error: ' + err.message);
                }
            })
        },
        error: function(error) {
            //
        }
    })

}

var a = 1;

function _run() {
    async.waterfall([

            function(callback) {
                // do some stuff ...
                getNBAScheduleFromSina(2014, 1, 0, '', callback);
            },
            function(_monthMatches, callback) {
                // do some more stuff ...
                _log(_monthMatches);
                callback(null, 'done');
            }
        ],
        // optional callback
        function(err, results) {
            // results is now equal to ['one', 'two']
            if (!err) {
                _log(results);
            }
        });
}

//_run();





app.listen();