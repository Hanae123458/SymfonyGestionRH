import Chart from 'chart.js/auto';

Chart.defaults.font.family = "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif";
Chart.defaults.color = '#34495e';

function initDashboardCharts() {
    if (typeof Chart === 'undefined') {
        console.error('Chart.js non chargé');
        return;
    }

    if (!window.chartData) {
        console.error('Données non disponibles', window.chartData);
        return;
    }

    const { distributionSalaires = [] } = window.chartData;

    // 1. Détruire les anciennes instances
    document.querySelectorAll('canvas').forEach(canvas => {
        if (canvas.chart) {
            canvas.chart.destroy();
        }
    });

    // 4. Diagramme Barres - Types de congé
    if (chartData.typesConge.length > 0) {
        new Chart(document.getElementById('typesCongeChart'), {
            type: 'bar',
            data: {
                labels: chartData.typesConge.map(item => item.type),
                datasets: [{
                    label: 'Nombre de demandes',
                    data: chartData.typesConge.map(item => item.nombre),
                    backgroundColor: '#66BB6A',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }

    // 5. Diagramme Barres - Distribution des salaires
    if (chartData.salaires.length > 0) {
        new Chart(document.getElementById('salairesChart'), {
            type: 'bar',
            data: {
                labels: chartData.salaires.map(item => item.salaire + ' €'),
                datasets: [{
                    label: "Nombre d'employés",
                    data: chartData.salaires.map(item => item.nombre),
                    backgroundColor: '#FFA726',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }


    
}

function createChart(id, config) {
    const canvas = document.getElementById(id);
    if (!canvas) {
        console.warn(`Canvas #${id} introuvable`);
        return null;
    }

    try {
        return new Chart(canvas.getContext('2d'), {
            ...config,
            options: {
                ...config.options,
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    tooltip: {
                        enabled: true,
                        mode: 'index',
                        intersect: false
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    } catch (error) {
        console.error(`Erreur création ${id}:`, error);
        return null;
    }
}

// Initialisation
if (document.readyState === 'complete') {
    initDashboardCharts();
} else {
    document.addEventListener('DOMContentLoaded', initDashboardCharts);
}

console.log('Données salaires:', {
    rawData: window.chartData.distributionSalaires,
    labels: window.chartData.distributionSalaires?.map(item => `${item.salaire} €`),
    values: window.chartData.distributionSalaires?.map(item => item.nombre)
});
