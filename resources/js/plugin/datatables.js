import 'datatables.net-fixedcolumns';
import '../../scss/plugin/datatables.scss';

window.ModulesFullyLoaded = false;
window.DataTableLockedQueue = window.DataTableLockedQueue || [];

import('laravel-datatables-vite').then(() => {
    document.dispatchEvent(new CustomEvent('DOMContentLoaded'))
    window.ModulesFullyLoaded = true;
    window.checkAndFlushDataTables();
});

window.addEventListener('yajra:boot', (event) => {
    const { id, config } = event.detail;
    const selector = `#${id}`;
    requestAnimationFrame(() => {
        const $el = jQuery(selector);

        if ($el.length && !DataTable.isDataTable(selector)) {
            config.destroy = true;

            try {
                window.LaravelDataTables = window.LaravelDataTables || {};
                window.LaravelDataTables[id] = $el.DataTable(config);
            } catch (e) {
                console.error(`Failed to boot DataTable [${id}]:`, e);
            }
        }
    });
});

window.checkAndFlushDataTables = function () {
    if (window.ModulesFullyLoaded) {
        if (window.DataTableLockedQueue && window.DataTableLockedQueue.length) {
            window.DataTableLockedQueue.forEach(bootFunction => bootFunction());
            window.DataTableLockedQueue = [];
        }
    }
};

document.addEventListener('livewire:navigating', () => {
    Object.keys(window.LaravelDataTables).forEach(id => {
        const selector = `#${id}`;
        const $table = jQuery(selector);

        if (DataTable.isDataTable(selector)) {
            // 1. Grab the actual DataTables API instance
            const dtInstance = $table.DataTable();

            // OPTIMIZATION 1: Kill Ghost AJAX Requests
            // If the user navigates away while the table is still fetching data,
            // the callback will execute on a dead DOM, causing massive memory leaks.
            const settings = dtInstance.settings()[0];
            if (settings && settings.jqXHR) {
                settings.jqXHR.abort();
            }

            // OPTIMIZATION 2: Flush Internal Data Cache
            // Calling .clear() purges DataTables' internal array of row data BEFORE 
            // we destroy it, saving the browser's Garbage Collector from doing the heavy lifting.
            dtInstance.clear().destroy();

            // OPTIMIZATION 3: Deep jQuery Cleanup
            // Remove all data and unbind events attached to the table wrapper
            $table.off().removeData();
        }

        // OPTIMIZATION 4: Explicitly sever the reference
        // Using 'delete' tells the V8 JavaScript engine to immediately flag 
        // this specific object memory for sweeping, rather than waiting for reassignment.
        delete window.LaravelDataTables[id];
    });
    window.LaravelDataTables = {};
});