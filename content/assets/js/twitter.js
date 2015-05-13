!function(e,t){"function"==typeof define&&define.amd?define([],t):"object"==typeof exports?module.exports=t():t()}(this,function(){function e(e){return e.replace(/<b[^>]*>(.*?)<\/b>/gi,function(e,t){return t}).replace(/class=".*?"|data-query-source=".*?"|dir=".*?"|rel=".*?"/gi,"")}function t(e){e=e.getElementsByTagName("a");for(var t=e.length-1;t>=0;t--)e[t].setAttribute("target","_blank")}function n(e,t){for(var n=[],s=new RegExp("(^| )"+t+"( |$)"),i=e.getElementsByTagName("*"),a=0,l=i.length;l>a;a++)s.test(i[a].className)&&n.push(i[a]);return n}var s="",i=20,a=!0,l=[],r=!1,o=!0,c=!0,d=null,p=!0,m=!0,h=null,u=!0,w=!1,g=!0,f={fetch:function(e){if(void 0===e.maxTweets&&(e.maxTweets=20),void 0===e.enableLinks&&(e.enableLinks=!0),void 0===e.showUser&&(e.showUser=!0),void 0===e.showTime&&(e.showTime=!0),void 0===e.dateFunction&&(e.dateFunction="default"),void 0===e.showRetweet&&(e.showRetweet=!0),void 0===e.customCallback&&(e.customCallback=null),void 0===e.showInteraction&&(e.showInteraction=!0),void 0===e.showImages&&(e.showImages=!1),void 0===e.linksInNewWindow&&(e.linksInNewWindow=!0),r)l.push(e);else{r=!0,s=e.domId,i=e.maxTweets,a=e.enableLinks,c=e.showUser,o=e.showTime,m=e.showRetweet,d=e.dateFunction,h=e.customCallback,u=e.showInteraction,w=e.showImages,g=e.linksInNewWindow;var t=document.createElement("script");t.type="text/javascript",t.src="http://cdn.syndication.twimg.com/widgets/timelines/"+e.id+"?&lang="+(e.lang||"en")+"&callback=twitterFetcher.callback&suppress_response_codes=true&retweeted=true&rnd="+Math.random(),document.getElementsByTagName("head")[0].appendChild(t)}},callback:function(v){var b=document.createElement("div");b.innerHTML=v.body,"undefined"==typeof b.getElementsByClassName&&(p=!1),v=[];var y=[],T=[],x=[],C=[],_=[],k=0;if(p)for(b=b.getElementsByClassName("tweet");k<b.length;)C.push(0<b[k].getElementsByClassName("retweet-credit").length?!0:!1),(!C[k]||C[k]&&m)&&(v.push(b[k].getElementsByClassName("e-entry-title")[0]),_.push(b[k].getAttribute("data-tweet-id")),y.push(b[k].getElementsByClassName("p-author")[0]),T.push(b[k].getElementsByClassName("dt-updated")[0]),x.push(void 0!==b[k].getElementsByClassName("inline-media")[0]?b[k].getElementsByClassName("inline-media")[0]:void 0)),k++;else for(b=n(b,"tweet");k<b.length;)v.push(n(b[k],"e-entry-title")[0]),_.push(b[k].getAttribute("data-tweet-id")),y.push(n(b[k],"p-author")[0]),T.push(n(b[k],"dt-updated")[0]),x.push(void 0!==n(b[k],"inline-media")[0]?n(b[k],"inline-media")[0]:void 0),C.push(0<n(b[k],"retweet-credit").length?!0:!1),k++;for(v.length>i&&(v.splice(i,v.length-i),y.splice(i,y.length-i),T.splice(i,T.length-i),C.splice(i,C.length-i),x.splice(i,x.length-i)),b=[],k=v.length,C=0;k>C;){if("string"!=typeof d){var E=T[C].getAttribute("datetime"),N=new Date(T[C].getAttribute("datetime").replace(/-/g,"/").replace("T"," ").split("+")[0]),E=d(N,E);if(T[C].setAttribute("aria-label",E),v[C].innerText)if(p)T[C].innerText=E;else{var N=document.createElement("p"),I=document.createTextNode(E);N.appendChild(I),N.setAttribute("aria-label",E),T[C]=N}else T[C].textContent=E}E="",a?(g&&(t(v[C]),c&&t(y[C])),c&&(E+='<span class="tweethelper">'+e(y[C].innerHTML)+'</span><a href="https://twitter.com/triathresearch" class="timePosted">'+T[C].textContent+"</a>"),E+='<p class="onetweet">'+e(v[C].innerHTML)+"</p>",o&&(E+='<p class="timePosted"></p>')):v[C].innerText?(c&&(E+='<p class="user">'+y[C].innerText+"</p>"),E+='<p class="tweet">'+v[C].innerText+"</p>",o&&(E+='<p class="timePosted">'+T[C].innerText+"</p>")):(c&&(E+='<p class="user">'+y[C].textContent+"</p>"),E+='<p class="tweet">'+v[C].textContent+"</p>"),u&&(E+='<p class="interact"><a href="https://twitter.com/intent/tweet?in_reply_to='+_[C]+'" class="twitter_reply_icon"'+(g?' target="_blank">':">")+'Reply</a><a href="https://twitter.com/intent/retweet?tweet_id='+_[C]+'" class="twitter_retweet_icon"'+(g?' target="_blank">':">")+'Retweet</a><a href="https://twitter.com/intent/favorite?tweet_id='+_[C]+'" class="twitter_fav_icon"'+(g?' target="_blank">':">")+"Favorite</a></p>"),w&&void 0!==x[C]&&(N=x[C],void 0!==N?(N=N.innerHTML.match(/data-srcset="([A-z0-9%_\.-]+)/i)[0],N=decodeURIComponent(N).split('"')[1]):N=void 0,E+='<div class="media"><img src="'+N+'" alt="Image from tweet" /></div>'),b.push(E),C++}if(null===h){for(v=b.length,y=0,T=document.getElementById(s),x="<ul>";v>y;)x+="<li>"+b[y]+"</li>",y++;T.innerHTML=x+"</ul>"}else h(b);r=!1,0<l.length&&(f.fetch(l[0]),l.splice(0,1))}};return window.twitterFetcher=f});
/**
 * How to use TwitterFetcher's fetch function:
 * 
 * @function fetch(object) Fetches the Twitter content according to
 *     the parameters specified in object.
 * 
 * @param object {Object} An object containing case sensitive key-value pairs
 *     of properties below.
 * 
 * You may specify at minimum the following two required properties:
 * 
 * @param object.id {string} The ID of the Twitter widget you wish
 *     to grab data from (see above for how to generate this number).
 * @param object.domId {string} The ID of the DOM element you want
 *     to write results to.
 *
 * You may also specify one or more of the following optional properties
 *     if you desire:
 *
 * @param object.maxTweets [int] The maximum number of tweets you want
 *     to return. Must be a number between 1 and 20. Default value is 20.
 * @param object.enableLinks [boolean] Set false if you don't want
 *     urls and hashtags to be hyperlinked.
 * @param object.showUser [boolean] Set false if you don't want user
 *     photo / name for tweet to show.
 * @param object.showTime [boolean] Set false if you don't want time of tweet
 *     to show.
 * @param object.dateFunction [function] A function you can specify
 *     to format date/time of tweet however you like. This function takes
 *     a JavaScript date as a parameter and returns a String representation
 *     of that date.
 * @param object.showRetweet [boolean] *Set false if you don't want retweets
 *     to show.
 * @param object.customCallback [function] A function you can specify
 *     to call when data are ready. It also passes data to this function
 *     to manipulate them yourself before outputting. If you specify
 *     this parameter you must output data yourself!
 * @param object.showInteraction [boolean] Set false if you don't want links
 *     for reply, retweet and favourite to show.
 * @param object.showImages [boolean] Set true if you want images from tweet
 *     to show.
 * @param object.lang [string] The abbreviation of the language you want to use
 *     for Twitter phrases like "posted on" or "time ago". Default value
 *     is "en" (English).
 */
// HTML element with id "talk". Also automatically hyperlinks URLS and user
// mentions and hashtags but does not display time of post. We also make the
// request to Twitter specifiying we would like results where possible in
// English language.


/*
*
* BEGIN TO CONFIGURE IF U CANT JAVASCRIPT
*
*/

var TweeterLogin = 'Triathlon Research'; //Twitter name displaying
var showDate = new Date('Thu Jan 11 2015 00:00:00 GMT+0100 (Central Europe Standard Time)'); // From date
var domID = 'tweeets'; // ID of html element where tweets will be displayed

var config = {
  "id": '565131452925632512',
  "maxTweets": 10,
  "enableLinks": true, 
  "showUser": true,
  "showTime": true,
  "customCallback": handleTweets,
  "lang": 'en',
  "showRetweet": true,
  "showInteraction": false,
  "dateFunction": dateFormatter
};

/*
*
* END TO CONFIGURE IF U CANT JAVASCRIPT 
*
*/

twitterFetcher.fetch(config);
// For advanced example which allows you to customize how tweet time is
// formatted you simply define a function which takes a JavaScript date as a
// parameter and returns a string!
var monthNames = [ "Jan", "Feb", "Mar", "Apr", "May", "Jun",
    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ];
var dates = [];
var times = 0;

 function dateFormatter(date) {
  if(date.getTime()>showDate.getTime()){
    dates[times] = date.getDate()+ " " + monthNames[date.getMonth()];
  } else 
    dates[times] = false;
    times++;

    return date.getDate()+ " " + monthNames[date.getMonth()];
}

function handleTweets(tweets){
    var x = tweets.length;
    var n = 0;
    var element = document.getElementById(domID);
    var html = '';
    var times = 0;
    while(n < x) {
      if ( dates[n] != false &&tweets[n].indexOf(TweeterLogin) == -1 /*&& tweets[n].indexOf('@TriathResearch') >=0*/){
        html += '<div class="tweet">'+tweets[n]+'</div>';
      }
      n++;
    }
    element.innerHTML = html;
}