import defaultSettings from '@/settings';

const title = defaultSettings.title || 'TCCS';

export default function getPageTitle(key) {
  return `${title}`;
}
