/**
 * 手机号 正则验证
 */
export const mobileReg = /^(13[0-9]|14[5-9]|15[012356789]|166|17[0-8]|18[0-9]|19[8-9])[0-9]{8}$/;

/**
 * 取得指定长度的字符串
 * @param {String} pStr 原文字符串
 * @param {Number} pLen 取字符长度
 * @return {Object} 截取结果
 */
export function fnCutString(pStr, pLen) {
  // 原字符串长度
  let _strLen = pStr.length;
  var _cutString = "";
  // 默认情况下，返回的字符串是原字符串的一部分
  var _cutFlag = true;
  var _lenCount = 0;
  var _ret = false;
  if (_strLen <= pLen / 2) {
    _cutString = pStr;
    _ret = true;
  }
  if (!_ret) {
    for (var i = 0; i < _strLen; i++) {
      if (pStr.charAt(i).charCodeAt(0) > 128) {
        _lenCount += 2;
      } else {
        _lenCount += 1;
      }

      if (_lenCount > pLen) {
        _cutString = pStr.substring(0, i);
        _ret = true;
        break;
      } else if (_lenCount == pLen) {
        _cutString = pStr.substring(0, i + 1);
        _ret = true;
        break;
      }
    }
  }

  if (!_ret) {
    _cutString = pStr;
    _ret = true;
  }

  if (_cutString.length == _strLen) {
    _cutFlag = false;
  }

  return {
    "cutstring": _cutString,
    "cutflag": _cutFlag
  };
}

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
 * 格式化时间 2019-12-03 20:12
 * @param {Date} time 时间对象
 * @return {Object} 格式化结果
 */
export function fnFormatLocalDate(time) {
  time = new Date(+time);
  let year = time.getUTCFullYear(); //年
  let month = time.getUTCMonth() + 1; //月
  let day = time.getDate(); //日
  let hh = time.getHours(); //时
  let mm = time.getUTCMinutes(); //分 
  // 个位补零
  month = month < 10 ? "0" + month : month
  day = day < 10 ? "0" + day : day
  hh = hh < 10 ? "0" + hh : hh
  mm = mm < 10 ? "0" + mm : mm
  return `${year}-${month}-${day} ${hh}:${mm}`;
}


/**
 * 格式化时间 下午 08:12 | 昨日 09:12 | 2019-12-03 20:12
 * @param {Date} time 输入Unix时间戳
 * @return {Object} 格式化结果
 */
 export function fnFormatTimeHeader(time) {
   // 格式化传入时间
   let date = new Date(parseInt(time)),
     year = date.getUTCFullYear(),
     month = date.getUTCMonth(),
     day = date.getDate(),
     hour = date.getHours(),
     minute = date.getUTCMinutes()
   // 获取当前时间
   let currentDate = new Date(),
     currentYear = date.getUTCFullYear(),
     currentMonth = date.getUTCMonth(),
     currentDay = currentDate.getDate()
   // 计算是否是同一天
   if (currentYear == year && currentMonth == month && currentDay == day) { //同一天直接返回
     if (hour > 12) {
       return `下午 ${hour}:${minute < 10 ? '0' + minute : minute}`
     } else {
       return `上午 ${hour}:${minute < 10 ? '0' + minute : minute}`
     }
   }
   // 计算是否是昨天
   let yesterday = new Date(currentDate - 24 * 3600 * 1000)
   if (year == yesterday.getUTCFullYear() && month == yesterday.getUTCMonth && day == yesterday.getDate()) { //昨天
     return `昨天 ${hour}:${minute < 10 ? '0' + minute : minute}`
   } else {
     return `${year}-${month + 1}-${day} ${hour}:${minute < 10 ? '0' + minute : minute}`
   }
 }
 
/**
 * 秒数转为时间 06:28:43 
 * @param {Number} time 秒时间 23323
 * @return {Object} 格式化结果
 */
export function fnSecondToTime(time) {
  var hour = 0;
  var minute = 0;
  var second = 0;
  if (time >= 60) {
    minute = Math.floor(time / 60);
    if (minute >= 60) {
      hour = Math.floor(minute / 60);
      minute = minute % 60;
    }
  }
  second = time % 60;
  // 个位补零
  hour = hour < 10 ? "0" + hour : hour
  minute = minute < 10 ? "0" + minute : minute
  second = second < 10 ? "0" + second : second
  return `${hour}时${minute}分${second}秒`;
}
