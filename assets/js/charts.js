/* assets/js/charts.js */

document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('taskChart');

    if (ctx && typeof PENDING_COUNT !== 'undefined' && typeof COMPLETED_COUNT !== 'undefined') {
        new Chart(ctx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Pendentes', 'Concluídas'],
                datasets: [{
                    data: [PENDING_COUNT, COMPLETED_COUNT],
                    backgroundColor: ['#dc3545', '#28a745'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                }
            }
        });
    }
});