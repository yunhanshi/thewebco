import Resource from '@/api/resource';

class ProductResource extends Resource {
  constructor() {
    super('products');
  }
}

export { ProductResource as default };
