import './bootstrap';
import 'flyonui/flyonui';

// Charting
import Chart from 'chart.js/auto';
import ApexCharts from 'apexcharts';
window.Chart = Chart;
window.ApexCharts = ApexCharts;

// FilePond
import * as FilePond from 'filepond/dist/filepond.esm.js';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';

// Handle FilePond being a module object or a function
const fp = FilePond.default || FilePond;
window.FilePond = fp;

import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';

fp.registerPlugin(
    FilePondPluginImagePreview,
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize
);

// Function to destroy all FilePond instances on the page
const destroyAllPonds = () => {
    document.querySelectorAll('.filepond--root').forEach(el => {
        const instance = FilePond.find(el);
        if (instance) {
            instance.destroy();
        }
    });
};

// Livewire Hooks for FilePond Cleanup
document.addEventListener('livewire:navigating', destroyAllPonds);

document.addEventListener('livewire:init', () => {
    // Handle specific cleanup for component updates if needed
    Livewire.hook('component.deinit', ({ component, cleanup }) => {
        cleanup(destroyAllPonds);
    });
});
