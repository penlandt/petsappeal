import { createPlugin } from '@fullcalendar/core/index.js';
import premiumCommonPlugin from '@fullcalendar/premium-common/index.js';
import { TimelineView } from './internal.js';
import '@fullcalendar/core/internal.js';
import '@fullcalendar/core/preact.js';
import '@fullcalendar/scrollgrid/internal.js';

var index = createPlugin({
    name: '@fullcalendar/timeline',
    premiumReleaseDate: '2025-04-02',
    deps: [premiumCommonPlugin],
    initialView: 'timelineDay',
    views: {
        timeline: {
            component: TimelineView,
            usesMinMaxTime: true,
            eventResizableFromStart: true, // how is this consumed for TimelineView tho?
        },
        timelineDay: {
            type: 'timeline',
            duration: { days: 1 },
        },
        timelineWeek: {
            type: 'timeline',
            duration: { weeks: 1 },
        },
        timelineMonth: {
            type: 'timeline',
            duration: { months: 1 },
        },
        timelineYear: {
            type: 'timeline',
            duration: { years: 1 },
        },
    },
});

export { index as default };
