class ConstBase {
  /**
   * @param consts [[<key>, <code>, <name>], ...]
   */
  constructor(consts) {
    this.names = {};
    for (const item of consts) {
      const key = item[0];
      const code = item[1];
      const name = item[2];

      this[key] = code;
      this.names[code] = name;
    }
  }

  valid(code) {
    return !!this.names[code];
  }

  getName(code) {
    return this.names[code] || '未知';
  }

  codes() {
    return Object.keys(this.names);
  }

  toOptions() {
    const opts = [];
    for (const [code, name] of Object.entries(this.names)) {
      opts.push({
        value: parseInt(code),
        label: name,
      });
    }

    return opts;
  }
}

export { ConstBase as default };
