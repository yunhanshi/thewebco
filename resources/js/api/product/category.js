import Resource from '@/api/resource';

class CategoryResource extends Resource {
  constructor() {
    super('categories');
  }
}

export { CategoryResource as default };
