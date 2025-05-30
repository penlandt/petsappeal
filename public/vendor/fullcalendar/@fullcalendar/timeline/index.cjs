'use strict';

Object.defineProperty(exports, '__esModule', { value: true });

var index_cjs = require('@fullcalendar/core/index.cjs');
var premiumCommonPlugin = require('@fullcalendar/premium-common/index.cjs');
var internalCommon = require('./internal.cjs');
require('@fullcalendar/core/internal.cjs');
require('@fullcalendar/core/preact.cjs');
require('@fullcalendar/scrollgrid/internal.cjs');

function _interopDefaultLegacy (e) { return e && typeof e === 'object' && 'default' in e ? e : { 'default': e }; }

var premiumCommonPlugin__default = /*#__PURE__*/_interopDefaultLegacy(premiumCommonPlugin);

var index = index_cjs.createPlugin({
    name: '@fullcalendar/timeline',
    premiumReleaseDate: '2025-04-02',
    deps: [premiumCommonPlugin__default["default"]],
    initialView: 'timelineDay',
    views: {
        timeline: {
            component: internalCommon.TimelineView,
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

exports["default"] = index;
