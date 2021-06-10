import ConstBase from 'Consts/base.js';

class AppTypeConst extends ConstBase {
  constructor() {
    super([
      ['WECHAT_OFFICIAL', 1, 'wechat.official'],
      ['WECHAT_MP', 2, 'wechat.mp'],
    ]);
  }
}

const AppType = new AppTypeConst();
export { AppType as default };
