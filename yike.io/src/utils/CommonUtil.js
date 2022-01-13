/**
 * 格式化时间 4分钟前
 * @param {Date} time 时间对象
 * @return {Object} 格式化结果
 */
export function fnFormatDate(time) {
  var ago, curTime, diff, int;
  time -= 0;
  if (("" + time).length === 10) {
    time *= 1000;
  }
  int = parseInt;
  curTime = +new Date();
  diff = curTime - time;
  ago = "";
  if (1000 * 60 > diff) {
    ago = "刚刚";
  } else if (1000 * 60 <= diff && 1000 * 60 * 60 > diff) {
    ago = int(diff / (1000 * 60)) + "分钟前";
  } else if (1000 * 60 * 60 <= diff && 1000 * 60 * 60 * 24 > diff) {
    ago = int(diff / (1000 * 60 * 60)) + "小时前";
  } else if (1000 * 60 * 60 * 24 <= diff && 1000 * 60 * 60 * 24 * 30 > diff) {
    ago = int(diff / (1000 * 60 * 60 * 24)) + "天前";
  } else if (1000 * 60 * 60 * 24 * 30 <= diff && 1000 * 60 * 60 * 24 * 30 * 12 > diff) {
    ago = int(diff / (1000 * 60 * 60 * 24 * 30)) + "月前";
  } else {
    ago = int(diff / (1000 * 60 * 60 * 24 * 30 * 12)) + "年前";
  }
  return ago;
}

/**
 * [TimeTransformationDate]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:时间戳转换成日期格式
 * @englishAnnotation:
 * @param              {[int]}  unixTime  [时间戳]
 * @param              {[int]}  year_true [是否展示年]
 * @param              {[int]}  month_true [是否展示月份]
 * @param              {[int]}  day_true [是否展示今天是几号]
 * @param              {[Boolean]} isHour    [小时]
 * @param              {[Boolean]} isMinute  [分钟]
 * @param              {[Boolean]} hasSec    [秒]
 * @param              {[int]}  year      [年 拼接符号]
 * @param              {[int]}  month     [月 拼接符号]
 * @param              {[int]}  day       [日 拼接符号]
 * @param              {[int]}  weekday   [description]
 * @return             {[string]}
 */
export function TimeTransformationDate(unixTime, year_true, month_true, day_true, isHour, isMinute, hasSec, year, month, day, weekday) {
    unixTime = Math.abs(parseInt(unixTime));
    year_true = ((year_true == '' || year_true == undefined || year_true == "undefined" || isEmpty(year_true)) && year_true != false) ? true : year_true;
    month_true = (month_true == '' || month_true == undefined || month_true == "undefined" || isEmpty(month_true)) ? true : month_true;
    day_true = (day_true == '' || day_true == undefined || day_true == "undefined" || isEmpty(day_true)) ? true : day_true;
    isHour = (isHour == '' || isHour == undefined || isHour == "undefined" || isEmpty(isHour)) ? false : isHour;
    isMinute = (isMinute == '' || isMinute == undefined || isMinute == "undefined" || isEmpty(isMinute)) ? false : isMinute;
    hasSec = (hasSec == '' || hasSec == undefined || hasSec == "undefined" || isEmpty(hasSec)) ? false : hasSec;
    weekday = (weekday == '' || weekday == undefined || weekday == "undefined" || isEmpty(weekday)) ? false : weekday;
    year = year ? year : '-';
    month = month ? month : '-';
    day = day ? day : '';
    var time = new Date(unixTime * 1000), date_time = '';
    if (year_true != false) date_time += time.getFullYear() + year;
    if (month_true != false) date_time += Add_0((time.getMonth() + 1)) + month;
    if (day_true != false) date_time += Add_0(time.getDate()) + day;
    if (isHour != false) date_time += ' ' + Add_0(time.getHours());
    if (isMinute != false)  date_time += ':' + Add_0(time.getMinutes());
    if (hasSec != false) date_time += ':' + Add_0(time.getSeconds());
    if (weekday != false) date_time += '&nbsp;&nbsp;星期' + "日一二三四五六".charAt(time.getDay());
    return date_time;
}

/**
 * [Add_0]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:判断如果参数小于10  ，参数前面 + 0
 * @englishAnnotation:
 * @param              {[type]} data [description]
 * @return             {[string]}
 */
export function Add_0(data) {
    if (data < 10) data = '0' + data;
    return data;
}

/**
 * [isEmpty]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:检测数据是否为空/是否存在
 * @englishAnnotation:
 * @param              {[string|array|object]}  data [需要检测的数据，可以为字符串、数组、对象]
 * @return             {Boolean}      [true/false]
 */
export function isEmpty(data) {
    var type, data = $.trim(data);
    if (typeof data == undefined || typeof data == null || typeof data == '') return true;
    // else if (!data && data !== 0 && data !== '') return false;
    type = Object.prototype.toString.call(data).slice(8, -1);
    if (type.toLowerCase() == 'object') {
        if (Object.prototype.isPrototypeOf(data) && Object.keys(data).length === 0) return true;
        else return false;
    } else if (type.toLowerCase() == 'array') {
        if (Array.prototype.isPrototypeOf(data) && data.length === 0) return true;
        else return false;
    } else if (type.toLowerCase() == 'string') {
        if ($.trim(data).length === 0) return true;
        else return false;
    } else if (typeof type == 'boolean') return data;
    return false;
};