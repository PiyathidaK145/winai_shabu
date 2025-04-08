let chartIngredients;
let chartImportCost;
let chartImportTimes;
let chartImportVolume;

document.addEventListener("DOMContentLoaded", () => {
    console.log("✅ Ingredient Analytics Loaded");

    initIngredientsChart();
    initImportCostChart();
    initImportTimesChart();
    initImportVolumeChart();

    const form = document.getElementById('ingredientFilterForm');
    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            // Debug: แสดงค่าที่ส่งไปยัง PHP
            for (let [key, value] of formData.entries()) {
                console.log(`📦 ${key}: ${value}`);
            }

            try {
                const response = await fetch('fetch_filtered_data.php', {
                    method: 'POST',
                    body: formData
                });

                const raw = await response.text();

                try {
                    const data = JSON.parse(raw);
                    console.log("✅ Filter Results:", data);

                    // แสดงผลสรุปตัวเลข
                    document.getElementById("count-ingredients").innerHTML = data.total_ingredients;
                    document.getElementById("count-imports").innerHTML = data.import_times;
                    document.getElementById("count-volume").innerHTML = data.total_volume;
                    document.getElementById("count-cost").innerHTML = data.total_cost;

                    // Debug ข้อมูลกราฟ
                    console.log("📊 Chart Labels:", data.chart_ingredients_count.labels);
                    console.log("📈 Chart Data:", data.chart_ingredients_count.datasets);

                    // อัปเดตกราฟ
                    updateIngredientsChart(data.chart_ingredients_count);

                    fetch('fetch_chart_import_cost.php', {
                        method: 'POST',
                        body: formData
                    })
                        .then(res => res.json())
                        .then(costChart => {
                            console.log("📉 Cost Chart:", costChart);
                            updateImportCostChart(costChart);
                        })

                    await fetch('fetch_chart_import_times.php', {
                        method: 'POST',
                        body: formData
                    })
                        .then(res => res.json())
                        .then(timesChart => {
                            console.log("📊 Times Chart:", timesChart);
                            updateImportTimesChart(timesChart);
                        });

                    await fetch('fetch_chart_import_volume.php', {
                        method: 'POST',
                        body: formData
                    })
                        .then(res => res.json())
                        .then(volumeChart => {
                            console.log("📦 Volume Chart:", volumeChart);
                            updateImportVolumeChart(volumeChart);
                        });

                    // ปิด Modal
                    const modalEl = document.getElementById('ingredientFilterModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();

                } catch (err) {
                    console.error("❌ Failed to parse JSON:", raw);
                }

            } catch (err) {
                console.error('❌ Error fetching filter data:', err);
            }
        });
    }
});


// ซ่อน/แสดงกราฟตามเมนู
function showChart(chartId) {
    const charts = ['ingredients', 'times', 'volume', 'cost'];
    charts.forEach(id => {
        const el = document.getElementById(`chart-${id}`);
        if (el) {
            el.classList.toggle('d-none', id !== chartId);
        }
    });
}



function initIngredientsChart() {
    const ctx = document.getElementById('chartIngredientsCount')?.getContext('2d');
    if (!ctx) return;

    chartIngredients = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: []
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'จำนวนวัตถุดิบ (g)'
                    },
                    ticks: {
                        callback: value => value.toLocaleString() + ' g'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'ช่วงเวลา'
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            const val = context.parsed.y || 0;
                            return `${context.dataset.label}: ${val.toLocaleString()} g`;
                        }
                    }
                }
            }
        }
    });
}

// อัปเดตกราฟเมื่อ filter
function updateIngredientsChart(chartData) {
    if (!chartIngredients) return;

    // ล้างข้อมูลเก่า
    chartIngredients.data.labels = chartData.labels;
    chartIngredients.data.datasets = [];

    // เพิ่ม datasets ใหม่
    chartData.datasets.forEach(ds => {
        const color = getRandomColor();
        chartIngredients.data.datasets.push({
            label: ds.label,
            data: ds.data,
            borderColor: color,
            backgroundColor: color.replace('rgb', 'rgba').replace(')', ', 0.1)'),
            tension: 0.25,
            fill: false,
            pointRadius: 3
        });
    });

    chartIngredients.update();
}

function initImportCostChart() {
    const ctx = document.getElementById('chartImportCost')?.getContext('2d');
    if (!ctx) return;

    chartImportCost = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: []
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: context => `${context.dataset.label}: ${context.parsed.y.toLocaleString()} บาท`
                    }
                }
            },
            scales: {
                y: {
                    title: {
                        display: true,
                        text: 'ค่าใช้จ่าย (บาท)'
                    },
                    beginAtZero: true
                },
                x: {
                    title: {
                        display: true,
                        text: 'วันที่'
                    }
                }
            }
        }
    });
}

function updateImportCostChart(chartData) {
    chartImportCost.data.labels = chartData.labels;
    chartImportCost.data.datasets = [];

    chartData.datasets.forEach(ds => {
        const color = getRandomColor();
        chartImportCost.data.datasets.push({
            label: ds.label,
            data: ds.data,
            borderColor: color,
            backgroundColor: color.replace('rgb', 'rgba').replace(')', ', 0.1)'),
            tension: 0.3,
            fill: false
        });
    });
    chartImportCost.update();

}

function initImportTimesChart() {
    const ctx = document.getElementById('chartImportTimes')?.getContext('2d');
    if (!ctx) return;

    chartImportTimes = new Chart(ctx, {
        type: 'line', // หรือ 'bar'
        data: {
            labels: [],
            datasets: []
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: context => `${context.dataset.label}: ${context.parsed.y.toLocaleString()} ครั้ง`
                    }
                }
            },
            scales: {
                y: {
                    title: {
                        display: true,
                        text: 'จำนวนครั้งที่นำเข้า'
                    },
                    beginAtZero: true
                },
                x: {
                    title: {
                        display: true,
                        text: 'วันที่'
                    }
                }
            }
        }
    });
}

function updateImportTimesChart(chartData) {
    if (!chartImportTimes) return;

    chartImportTimes.data.labels = chartData.labels;
    chartImportTimes.data.datasets = []; // ล้างของเก่า

    chartData.datasets.forEach(ds => {
        const color = getRandomColor();
        chartImportTimes.data.datasets.push({
            label: ds.label,
            data: ds.data,
            borderColor: color,
            backgroundColor: color.replace('rgb', 'rgba').replace(')', ', 0.1)'),
            tension: 0.3,
            fill: false
        });
    });

    chartImportTimes.update();
}

function initImportVolumeChart() {
    const ctx = document.getElementById('chartImportVolume')?.getContext('2d');
    if (!ctx) return;

    chartImportVolume = new Chart(ctx, {
        type: 'line',
        data: { labels: [], datasets: [] },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: context => `${context.dataset.label}: ${context.parsed.y.toLocaleString()} หน่วย`
                    }
                }
            },
            scales: {
                y: {
                    title: {
                        display: true,
                        text: 'ปริมาณนำเข้า'
                    },
                    beginAtZero: true
                },
                x: {
                    title: {
                        display: true,
                        text: 'วันที่'
                    }
                }
            }
        }
    });
}

function updateImportVolumeChart(chartData) {
    if (!chartImportVolume) return;
    chartImportVolume.data.labels = chartData.labels;
    chartImportVolume.data.datasets = [];

    chartData.datasets.forEach(ds => {
        const color = getRandomColor();
        chartImportVolume.data.datasets.push({
            label: ds.label,
            data: ds.data,
            borderColor: color,
            backgroundColor: color.replace('rgb', 'rgba').replace(')', ', 0.1)'),
            tension: 0.3,
            fill: false
        });
    });

    chartImportVolume.update();
}

function getRandomColor() {
    const r = Math.floor(Math.random() * 200);
    const g = Math.floor(Math.random() * 200);
    const b = Math.floor(Math.random() * 200);
    return `rgb(${r}, ${g}, ${b})`;
}











