/**
 * Created by PanJiaChen on 16/11/18.
 */

/**
 * @param {string} path
 * @returns {Boolean}
 */
export function isExternal(path) {
  return /^(https?:|mailto:|tel:)/.test(path)
}

/**
 * @copyright chuzhixin 1204505056@qq.com
 * @description 校验密码是否小于6位
 * @param str
 * @returns {boolean}
 */
export function isPassword(str) {
  return str.length >= 6
}

/**
 * @copyright chuzhixin 1204505056@qq.com
 * @description 判断是否为数字
 * @param value
 * @returns {boolean}
 */
export function isNumber(value) {
  const reg = /^[0-9]*$/
  return reg.test(value)
}

/**
 * @copyright chuzhixin 1204505056@qq.com
 * @description 判断是否是名称
 * @param value
 * @returns {boolean}
 */
export function isName(value) {
  const reg = /^[\u4e00-\u9fa5a-zA-Z0-9]+$/
  return reg.test(value)
}

/**
 * @copyright chuzhixin 1204505056@qq.com
 * @description 判断是否为IP
 * @param ip
 * @returns {boolean}
 */
export function isIP(ip) {
  const reg = /^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/
  return reg.test(ip)
}

/**
 * @copyright chuzhixin 1204505056@qq.com
 * @description 判断是否是传统网站
 * @param url
 * @returns {boolean}
 */
export function isUrl(url) {
  const reg = /^(https?|ftp):\/\/([a-zA-Z0-9.-]+(:[a-zA-Z0-9.&%$-]+)*@)*((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])){3}|([a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(:[0-9]+)*(\/($|[a-zA-Z0-9.,?'\\+&%$#=~_-]+))*$/
  return reg.test(url)
}

/**
 * @copyright chuzhixin 1204505056@qq.com
 * @description 判断是否是小写字母
 * @param str
 * @returns {boolean}
 */
export function isLowerCase(str) {
  const reg = /^[a-z]+$/
  return reg.test(str)
}

/**
 * @copyright chuzhixin 1204505056@qq.com
 * @description 判断是否是大写字母
 * @param str
 * @returns {boolean}
 */
export function isUpperCase(str) {
  const reg = /^[A-Z]+$/
  return reg.test(str)
}

/**
 * @copyright chuzhixin 1204505056@qq.com
 * @description 判断是否是大写字母开头
 * @param str
 * @returns {boolean}
 */
export function isAlphabets(str) {
  const reg = /^[A-Za-z]+$/
  return reg.test(str)
}

/**
 * @param {string} url
 * @returns {Boolean}
 */
export function validURL(url) {
  const reg = /^(https?|ftp):\/\/([a-zA-Z0-9.-]+(:[a-zA-Z0-9.&%$-]+)*@)*((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])){3}|([a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(:[0-9]+)*(\/($|[a-zA-Z0-9.,?'\\+&%$#=~_-]+))*$/
  return reg.test(url)
}

/**
 * @param {string} str
 * @returns {Boolean}
 */
export function validLowerCase(str) {
  const reg = /^[a-z]+$/
  return reg.test(str)
}

/**
 * @param {string} str
 * @returns {Boolean}
 */
export function validUpperCase(str) {
  const reg = /^[A-Z]+$/
  return reg.test(str)
}

/**
 * @param {string} str
 * @returns {Boolean}
 */
export function validAlphabets(str) {
  const reg = /^[A-Za-z]+$/
  return reg.test(str)
}

/**
 * @param {string} email
 * @returns {Boolean}
 */
export function validEmail(email) {
  const reg = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
  return reg.test(email)
}

/**
 * @param {string} str
 * @returns {Boolean}
 */
export function isString(str) {
  if (typeof str === 'string' || str instanceof String) {
    return true
  }
  return false
}

/**
 * @param {Array} arg
 * @returns {Boolean}
 */
export function isArray(arg) {
  if (typeof Array.isArray === 'undefined') {
    return Object.prototype.toString.call(arg) === '[object Array]'
  }
  return Array.isArray(arg)
}

/**
 * @copyright chuzhixin 1204505056@qq.com
 * @description 判断是否是端口号
 * @param str
 * @returns {boolean}
 */
export function isPort(str) {
  const reg = /^([0-9]|[1-9]\d|[1-9]\d{2}|[1-9]\d{3}|[1-5]\d{4}|6[0-4]\d{3}|65[0-4]\d{2}|655[0-2]\d|6553[0-5])$/
  return reg.test(str)
}

/**
 * @copyright chuzhixin 1204505056@qq.com
 * @description 判断是否是手机号
 * @param str
 * @returns {boolean}
 */
export function isPhone(str) {
  const reg = /^1\d{10}$/
  return reg.test(str)
}

/**
 * @copyright chuzhixin 1204505056@qq.com
 * @description 判断是否是身份证号(第二代)
 * @param str
 * @returns {boolean}
 */
export function isIdCard(str) {
  const reg = /^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$/
  return reg.test(str)
}

/**
 * @copyright chuzhixin 1204505056@qq.com
 * @description 判断是否是邮箱
 * @param str
 * @returns {boolean}
 */
export function isEmail(str) {
  const reg = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/
  return reg.test(str)
}

/**
 * @copyright chuzhixin 1204505056@qq.com
 * @description 判断是否中文
 * @param str
 * @returns {boolean}
 */
export function isChina(str) {
  const reg = /^[\u4E00-\u9FA5]{2,4}$/
  return reg.test(str)
}

/**
 * @copyright chuzhixin 1204505056@qq.com
 * @description 判断是否为空
 * @param str
 * @returns {boolean}
 */
export function isBlank(str) {
  return (
    str == null ||
        false ||
        str === '' ||
        str.trim() === '' ||
        str.toLocaleLowerCase().trim() === 'null'
  )
}

/**
 * @copyright chuzhixin 1204505056@qq.com
 * @description 判断是否为固话
 * @param str
 * @returns {boolean}
 */
export function isTel(str) {
  const reg = /^(400|800)([0-9\\-]{7,10})|(([0-9]{4}|[0-9]{3})(-| )?)?([0-9]{7,8})((-| |转)*([0-9]{1,4}))?$/
  return reg.test(str)
}

/**
 * @copyright chuzhixin 1204505056@qq.com
 * @description 判断是否为数字且最多两位小数
 * @param str
 * @returns {boolean}
 */
export function isNum(str) {
  const reg = /^\d+(\.\d{1,2})?$/
  return reg.test(str)
}

/**
 * @copyright chuzhixin 1204505056@qq.com
 * @description 判断经度 -180.0～+180.0（整数部分为0～180，必须输入1到5位小数）
 * @param str
 * @returns {boolean}
 */
export function isLongitude(str) {
  const reg = /^[-|+]?(0?\d{1,2}\.\d{1,5}|1[0-7]?\d{1}\.\d{1,5}|180\.0{1,5})$/
  return reg.test(str)
}

/**
 * @copyright chuzhixin 1204505056@qq.com
 * @description 判断纬度 -90.0～+90.0（整数部分为0～90，必须输入1到5位小数）
 * @param str
 * @returns {boolean}
 */
export function isLatitude(str) {
  const reg = /^[-|+]?([0-8]?\d{1}\.\d{1,5}|90\.0{1,5})$/
  return reg.test(str)
}

/**
 * @copyright chuzhixin 1204505056@qq.com
 * @description rtsp校验，只要有rtsp://
 * @param str
 * @returns {boolean}
 */
export function isRTSP(str) {
  const reg = /^rtsp:\/\/([a-z]{0,10}:.{0,10}@)?(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/
  const reg1 = /^rtsp:\/\/([a-z]{0,10}:.{0,10}@)?(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5]):[0-9]{1,5}/
  const reg2 = /^rtsp:\/\/([a-z]{0,10}:.{0,10}@)?(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\//
  return reg.test(str) || reg1.test(str) || reg2.test(str)
}
