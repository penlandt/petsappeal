import { createPlugin } from '@fullcalendar/core/index.js';
import premiumCommonPlugin from '@fullcalendar/premium-common/index.js';
import { ScrollGrid } from './internal.js';
import '@fullcalendar/core/internal.js';
import '@fullcalendar/core/preact.js';

var index = createPlugin({
    name: '@fullcalendar/scrollgrid',
    premiumReleaseDate: '2025-04-02',
    deps: [premiumCommonPlugin],
    scrollGridImpl: ScrollGrid,
});

export { index as default };
