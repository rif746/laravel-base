// eslint-disable-next-line no-unused-vars

window.DataTableLockedQueue = window.DataTableLockedQueue || [];
window.DataTableLockedQueue.push(function() {
window.dispatchEvent(new CustomEvent('yajra:boot', {
detail: { id: "%1$s", config: %2$s }
}));
});

if (typeof window.checkAndFlushDataTables === 'function') {
window.checkAndFlushDataTables();
}
