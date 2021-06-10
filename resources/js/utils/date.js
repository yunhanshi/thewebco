/**
 * 获取今天前指定天数的日期列表
 * 
 * @param {Number} dates 天数
 */
export function getDaysBefore(dates) {
  const today = getTodayBegin();
  const msOneDate = 24 * 3600 * 1000;
  var curTs = today.getTime() - msOneDate * dates;

  var days = [];
  for (let i = 0; i < dates; i++) {
    days.push(dateFormat('YYYY-mm-dd', new Date(curTs)));
    curTs += msOneDate;
  }
  return days;
}

export function getTodayBegin() {
  const today = new Date();
  const date = today.getDate();
  const month = today.getMonth();
  const year = today.getFullYear();
  return new Date(year, month, date);
}

export function dateFormat(fmt, date) {
  let ret;
  const opt = {
    'Y+': date.getFullYear().toString(),        // 年
    'm+': (date.getMonth() + 1).toString(),     // 月
    'd+': date.getDate().toString(),            // 日
    'H+': date.getHours().toString(),           // 时
    'M+': date.getMinutes().toString(),         // 分
    'S+': date.getSeconds().toString(),         // 秒
    // 有其他格式化字符需求可以继续添加，必须转化成字符串
  };
  for (const k in opt) {
    ret = new RegExp('(' + k + ')').exec(fmt);
    if (ret) {
      fmt = fmt.replace(ret[1], (ret[1].length === 1) ? (opt[k]) : (opt[k].padStart(ret[1].length, '0')));
    }
  }
  return fmt;
}
