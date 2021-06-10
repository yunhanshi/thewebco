
export function getByKeys(kvs, keys) {
  if (!kvs || !keys) {
    return {};
  }

  var res = {};
  keys.forEach(function(key) {
    if (key in kvs) {
      res[key] = kvs[key];
    }
  });
  return res;
}

/**
 * 从src合并key-value到dst。如有指定keys，则只合并指定key的value（如果在src中有的对应的key的话）。
 * @param {*} src 数据源
 * @param {*} dst 数据目标
 * @param {*} keys 指定的key
 */
export function mergeTo(src, dst, keys) {
  if (!src || dst === undefined || dst === null) {
    return;
  }

  if (keys) {
    keys.forEach(function(key) {
      if (key in src) {
        dst[key] = src[key];
      }
    });
  }

  for (const [key, value] of Object.entries(src)) {
    dst[key] = value;
  }
}
