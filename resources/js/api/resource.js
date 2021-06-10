import request from '@/utils/request';
import qs from 'qs';

/**
 * Simple RESTful resource class
 */
class Resource {
  constructor(uri) {
    this.uri = uri;
  }
  list(query) {
    return request({
      url: '/' + this.uri,
      method: 'get',
      params: query,
      paramsSerializer: params => {
        return qs.stringify(params, { indices: false });
      },
    });
  }
  autocomplete(query) {
    return request({
      url: '/' + this.uri + '/autocomplete',
      method: 'get',
      params: query,
      paramsSerializer: params => {
        return qs.stringify(params, { indices: false });
      },
    });
  }
  get(id) {
    return request({
      url: '/' + this.uri + '/' + id,
      method: 'get',
    });
  }
  store(resource) {
    return request({
      url: '/' + this.uri,
      method: 'post',
      data: resource,
    });
  }
  update(id, resource) {
    return request({
      url: '/' + this.uri + '/' + id,
      method: 'put',
      data: resource,
    });
  }
  destroy(id, force) {
    var url = '/' + this.uri + '/' + id;
    if (force) {
      url += '?force=1';
    }
    return request({
      url: url,
      method: 'delete',
    });
  }
}

export { Resource as default };
