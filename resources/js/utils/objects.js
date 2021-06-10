export function getByKeys(obj, key, defaultValue) {
  const ts = key.split('.');
  let cur = obj;
  for (var i = 0; i < ts.length; i++) {
    if (cur === undefined || cur === null) {
      return defaultValue;
    }
    cur = cur[ts[i]];
  }
  return cur === undefined ? defaultValue : cur;
}

export function listToTree(arr) {
  var tree = [], mappedArr = {}, arrElem, mappedElem;

  // First map the nodes of the array to an object -> create a hash table.
  for (var i = 0, len = arr.length; i < len; i++) {
    arrElem = arr[i];
    mappedArr[arrElem.id] = arrElem;
    mappedArr[arrElem.id]['children'] = [];
  }

  for (var id in mappedArr) {
    if (mappedArr.hasOwnProperty(id)) {
      mappedElem = mappedArr[id];
      // If the element is not at the root level, add it to its parent array of children.
      if (mappedElem.parent_id) {
        mappedArr[mappedElem['parent_id']]['children'].push(mappedElem);
      } else {
      // If the element is at the root level, add it to first level elements array.
        tree.push(mappedElem);
      }
    }
  }
  return tree;
}
