function getChartColorsArray(elementId) {
    const element = document.getElementById(elementId);

    if (element !== null) {
        // Get colors data from the element's data-colors attribute
        let colors = element.getAttribute("data-colors");
        colors = JSON.parse(colors);

        return colors.map(color => {
            let colorName = color.replace(" ", "");
            // Check for color format
            if (colorName.indexOf(",") === -1) {
                return getComputedStyle(document.documentElement).getPropertyValue(colorName) || colorName;
            } else {
                const colorComponents = colorName.split(",");
                if (colorComponents.length === 2) {
                    return `rgba(${getComputedStyle(document.documentElement).getPropertyValue(colorComponents[0])},${colorComponents[1]})`;
                }
                return colorName;
            }
        });
    }
}

// Initialize charts
let updatedDonutChart;
const chartPieBasicColors = getChartColorsArray("simple_pie_chart");

// Create basic pie chart if colors are available
if (chartPieBasicColors) {
    const options = {
        series: [44, 55, 13, 43, 22],
        chart: { height: 300, type: "pie" },
        labels: ["Team A", "Team B", "Team C", "Team D", "Team E"],
        legend: { position: "bottom" },
        dataLabels: { dropShadow: { enabled: false } },
        colors: chartPieBasicColors
    };
    const chart = new ApexCharts(document.querySelector("#simple_pie_chart"), options);
    chart.render();
}

// Get donut chart colors
const chartDonutBasicColors = getChartColorsArray("simple_dount_chart");

// Create basic donut chart if colors are available
if (chartDonutBasicColors) {
    const options = {
        series: [44, 55, 41, 17, 15],
        chart: { height: 300, type: "donut" },
        legend: { position: "bottom" },
        dataLabels: { dropShadow: { enabled: false } },
        colors: chartDonutBasicColors
    };
    const chart = new ApexCharts(document.querySelector("#simple_dount_chart"), options);
    chart.render();
}

// Get updating donut chart colors
const chartDonutUpdatingColors = getChartColorsArray("updating_donut_chart");

// Initialize updating donut chart if colors are available
if (chartDonutUpdatingColors) {
    const options = {
        series: [44, 55, 13, 33],
        chart: { height: 280, type: "donut" },
        dataLabels: { enabled: false },
        legend: { position: "bottom" },
        colors: chartDonutUpdatingColors
    };

    updatedDonutChart = new ApexCharts(document.querySelector("#updating_donut_chart"), options);
    updatedDonutChart.render();

    // Event listeners for buttons
    document.querySelector("#randomize").addEventListener("click", function() {
        updatedDonutChart.updateSeries(randomize());
    });

    document.querySelector("#add").addEventListener("click", function() {
        updatedDonutChart.updateSeries(appendData());
    });

    document.querySelector("#remove").addEventListener("click", function() {
        updatedDonutChart.updateSeries(removeData());
    });

    document.querySelector("#reset").addEventListener("click", function() {
        updatedDonutChart.updateSeries(reset());
    });
}

// Create gradient donut chart
const chartPieGradientColors = getChartColorsArray("gradient_chart");
if (chartPieGradientColors) {
    const options = {
        series: [44, 55, 41, 17, 15],
        chart: { height: 300, type: "donut" },
        plotOptions: { pie: { startAngle: -90, endAngle: 270 } },
        dataLabels: { enabled: false },
        fill: { type: "gradient" },
        legend: { position: "bottom" },
        title: { text: "Gradient Donut with custom Start-angle", style: { fontWeight: 500 } },
        colors: chartPieGradientColors
    };
    const chart = new ApexCharts(document.querySelector("#gradient_chart"), options);
    chart.render();
}

// Create pattern donut chart
const chartPiePatternColors = getChartColorsArray("pattern_chart");
if (chartPiePatternColors) {
    const options = {
        series: [44, 55, 41, 17, 15],
        chart: { height: 300, type: "donut", dropShadow: { enabled: true, color: "#111", top: -1, left: 3, blur: 3, opacity: 0.2 } },
        stroke: { width: 0 },
        plotOptions: { pie: { donut: { labels: { show: true, total: { showAlways: true, show: true } } } } },
        labels: ["Comedy", "Action", "SciFi", "Drama", "Horror"],
        dataLabels: { dropShadow: { blur: 3, opacity: 0.8 } },
        fill: {
            type: "pattern",
            opacity: 1,
            pattern: { enabled: true, style: ["verticalLines", "squares", "horizontalLines", "circles", "slantedLines"] }
        },
        states: { hover: { filter: "none" } },
        theme: { palette: "palette2" },
        title: { text: "Favourite Movie Type", style: { fontWeight: 500 } },
        legend: { position: "bottom" },
        colors: chartPiePatternColors
    };

    const chart = new ApexCharts(document.querySelector("#pattern_chart"), options);
    chart.render();
}

// Create image pie chart
const chartPieImageColors = getChartColorsArray("image_pie_chart");
if (chartPieImageColors) {
    const options = {
        series: [44, 33, 54, 45],
        chart: { height: 300, type: "pie" },
        colors: ["#93C3EE", "#E5C6A0", "#669DB5", "#94A74A"],
        fill: { type: "image", opacity: 0.85, image: { src: ["./assets/images/small/img-1.jpg", "./assets/images/small/img-2.jpg", "./assets/images/small/img-3.jpg", "./assets/images/small/img-4.jpg"], width: 25, imagedHeight: 25 }},
        stroke: { width: 4 },
        dataLabels: { enabled: true, style: { colors: ["#111"] }, background: { enabled: true, foreColor: "#fff", borderWidth: 0 }},
        legend: { position: "bottom" }
    };

    const chart = new ApexCharts(document.querySelector("#image_pie_chart"), options);
    chart.render();
}

// Create monochrome pie chart
const monochromeOptions = {
    series: [25, 15, 44, 55, 41, 17],
    chart: { height: 300, type: "pie" },
    labels: ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
    theme: { monochrome: { enabled: true, color: "#405189", shadeTo: "light", shadeIntensity: 0.6 } },
    plotOptions: { pie: { dataLabels: { offset: -5 } } },
    title: { text: "Monochrome Pie", style: { fontWeight: 500 } },
    dataLabels: {
        formatter: function (value, { seriesIndex }) {
            return [this.w.globals.labels[seriesIndex], value.toFixed(1) + "%"];
        },
        dropShadow: { enabled: false }
    },
    legend: { show: false }
};

// Render the monochrome pie chart
