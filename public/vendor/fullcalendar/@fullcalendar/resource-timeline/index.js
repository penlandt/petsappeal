import { createPlugin } from '@fullcalendar/core';
import premiumCommonPlugin from '@fullcalendar/premium-common';
import timelinePlugin from '@fullcalendar/timeline';
import resourcePlugin from '@fullcalendar/resource';
import { ResourceTimelineView } from './internal.js';
import { injectStyles } from '@fullcalendar/core/internal';
import '@fullcalendar/core/preact';
import '@fullcalendar/timeline/internal';
import '@fullcalendar/resource/internal';
import '@fullcalendar/scrollgrid/internal';

var css_248z = ".fc .fc-resource-timeline-divider{cursor:col-resize;width:3px}.fc .fc-resource-group{font-weight:inherit;text-align:inherit}.fc .fc-resource-timeline .fc-resource-group:not([rowspan]){background:var(--fc-neutral-bg-color)}.fc .fc-timeline-lane-frame{position:relative}.fc .fc-timeline-overlap-enabled .fc-timeline-lane-frame .fc-timeline-events{box-sizing:content-box;padding-bottom:10px}.fc-timeline-body-expandrows td.fc-timeline-lane{position:relative}.fc-timeline-body-expandrows .fc-timeline-lane-frame{position:static}.fc-datagrid-cell-frame-liquid{height:100%}.fc-liquid-hack .fc-datagrid-cell-frame-liquid{bottom:0;height:auto;left:0;position:absolute;right:0;top:0}.fc .fc-datagrid-header .fc-datagrid-cell-frame{align-items:center;display:flex;justify-content:flex-start;position:relative}.fc .fc-datagrid-cell-resizer{bottom:0;cursor:col-resize;position:absolute;top:0;width:5px;z-index:1}.fc .fc-datagrid-cell-cushion{overflow:hidden;padding:8px;white-space:nowrap}.fc .fc-datagrid-expander{cursor:pointer;opacity:.65}.fc .fc-datagrid-expander .fc-icon{display:inline-block;width:1em}.fc .fc-datagrid-expander-placeholder{cursor:auto}.fc .fc-resource-timeline-flat .fc-datagrid-expander-placeholder{display:none}.fc-direction-ltr .fc-datagrid-cell-resizer{right:-3px}.fc-direction-rtl .fc-datagrid-cell-resizer{left:-3px}.fc-direction-ltr .fc-datagrid-expander{margin-right:3px}.fc-direction-rtl .fc-datagrid-expander{margin-left:3px}";
injectStyles(css_248z);

var index = createPlugin({
    name: '@fullcalendar/resource-timeline',
    premiumReleaseDate: '2023-02-07',
    deps: [
        premiumCommonPlugin,
        resourcePlugin,
        timelinePlugin,
    ],
    initialView: 'resourceTimelineDay',
    views: {
        resourceTimeline: {
            type: 'timeline',
            component: ResourceTimelineView,
            needsResourceData: true,
            resourceAreaWidth: '30%',
            resourcesInitiallyExpanded: true,
            eventResizableFromStart: true, // TODO: not DRY with this same setting in the main timeline config
        },
        resourceTimelineDay: {
            type: 'resourceTimeline',
            duration: { days: 1 },
        },
        resourceTimelineWeek: {
            type: 'resourceTimeline',
            duration: { weeks: 1 },
        },
        resourceTimelineMonth: {
            type: 'resourceTimeline',
            duration: { months: 1 },
        },
        resourceTimelineYear: {
            type: 'resourceTimeline',
            duration: { years: 1 },
        },
    },
});

export { index as default };
