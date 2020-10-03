const scoreTableTr = document
    .getElementsByClassName("score-table")[0]
    .getElementsByTagName("tr");
const borderColor = "rgba(171, 217, 91,1)";
const colorToHole = {
    1: "#00b294",
    2: "#00bcf2",
    3: "#009e49",
    4: "#bad80a",
    5: "#00188f",
    6: "#68217a",
    7: "#ec008c",
    8: "#fff100",
    9: "#ff8c00",
    10: "#e81123"
};
var open_chart = 0;
var chart = null,
    PieChart = null,
    rGraph = null,
    hchart = null,
    wcChart = null;

function changeData($week = false, $hole = false) {
    changeGraph($week, $hole);
    changeRange($week);
    changePie($week, $hole);
    changeHalf($week);
    weekComparison($hole);

    for ($i = 0; $i < scoreTableTr.length; $i++) {
        scoreTableTr[$i].style.borderWidth = "0px";
        for (
            $j = 0;
            $j < scoreTableTr[$i].getElementsByTagName("td").length;
            $j++
        ) {
            scoreTableTr[$i].getElementsByTagName("td")[$j].style.borderColor =
                "#dee2e6";
            scoreTableTr[$i].getElementsByTagName("td")[$j].style.borderWidth =
                "1px";
        }
        for (
            $j = 0;
            $j < scoreTableTr[$i].getElementsByTagName("th").length;
            $j++
        ) {
            scoreTableTr[$i].getElementsByTagName("th")[$j].style.borderColor =
                "#dee2e6";
            scoreTableTr[$i].getElementsByTagName("th")[$j].style.borderWidth =
                "1px";
        }
    }

    if ($week !== false && $week != "t") {
        scoreTableTr[$week].style.border = "2px solid " + borderColor;
        scoreTableTr[$week].style.borderLeft = "1px solid " + borderColor;
        scoreTableTr[$week].style.borderRight = "1px solid " + borderColor;
        scoreTableTr[$week - 1].style.borderBottom = "2px solid " + borderColor;
    }
    if ($hole !== false) {
        if ($hole == "th") {
            $hole = scoreTableTr[0].getElementsByTagName("th").length - 2;
        }
        scoreTableTr[0].getElementsByTagName("th")[$hole + 1].style.borderLeft =
            "2px solid " + borderColor;
        scoreTableTr[0].getElementsByTagName("th")[
            $hole + 1
        ].style.borderRight = "2px solid " + borderColor;
        for ($i = 1; $i < scoreTableTr.length - 1; $i++) {
            scoreTableTr[$i].getElementsByTagName("td")[
                $hole + 1
            ].style.borderLeft = "2px solid " + borderColor;
            scoreTableTr[$i].getElementsByTagName("td")[
                $hole + 1
            ].style.borderRight = "2px solid " + borderColor;
        }
        scoreTableTr[$i].getElementsByTagName("td")[
            $hole + 1
        ].style.borderLeft = "2px solid " + borderColor;
        scoreTableTr[$i].getElementsByTagName("td")[
            $hole + 1
        ].style.borderRight = "2px solid " + borderColor;
    }
}

// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily =
    '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = "#292b2c";

function changeGraph($week = false, $hole = false) {
    return makeGraph(
        $hole !== false ? sgheader["hole"] : sgheader["week"],
        $hole !== false ? gdata["holes"][$hole] : gdata["weeks"][$week],
        $hole !== false
            ? Math.max.apply(null, gdata["holes"][$hole])
            : Math.max.apply(null, gdata["weeks"][$week]),
        $hole !== false
            ? Math.min.apply(null, gdata["holes"][$hole])
            : Math.min.apply(null, gdata["weeks"][$week])
    );
}

function makeGraph($header, $data, $t_max, $t_min) {
    var ctx = document.getElementById("myAreaChart");
    if (open_chart) {
        chart.destroy();
    }
    chart = new Chart(ctx, {
        type: "line",
        data: {
            labels: $header,
            datasets: [
                {
                    label: "Score",
                    lineTension: 0.3,
                    backgroundColor: "rgba(171, 217, 91,0.4)",
                    borderColor: "rgba(171, 217, 91,1)",
                    pointRadius: 5,
                    pointBackgroundColor: "rgba(171, 217, 91,1)",
                    pointBorderColor: "rgba(255,255,255,0.8)",
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(171, 217, 91,1)",
                    pointHitRadius: 50,
                    pointBorderWidth: 2,
                    data: $data
                }
            ]
        },
        options: {
            scales: {
                xAxes: [
                    {
                        time: { unit: "date" },
                        gridLines: { display: false },
                        ticks: { maxTicksLimit: 12 }
                    }
                ],
                yAxes: [
                    {
                        ticks: {
                            min: $t_min - 1,
                            max: $t_max + 1,
                            maxTicksLimit: 5
                        },
                        gridLines: { color: "rgba(0, 0, 0, .125)" }
                    }
                ]
            },
            legend: { display: false }
        }
    });
}

Chart.defaults.global.defaultFontFamily =
    '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = "#292b2c";

function changePie($week = false, $hole = false) {
    var HoleCountPieChart = document.getElementById("HoleCountPieChart");
    var parPercentPieChart = document.getElementById("parPercentPieChart");

    var ret_data =
        $week !== false ? cdata["week"][$week] : cdata["hole"][$hole];
    var pd = $week !== false ? pdata["week"][$week] : pdata["hole"][$hole];

    if ($week == "t" || $hole == "th") {
        var ret_data = cdata["total"];
        var pd = pdata["total"];
    }

    var labels = [];
    var cd = [];
    var colors = [];

    Object.entries(ret_data).forEach(entry => {
        const [key, value] = entry;
        labels.push(key);
        cd.push(value);
        colors.push(colorToHole[key]);
    });

    makePie(HoleCountPieChart, cd, labels, colors, 1);
    makePie(
        parPercentPieChart,
        pd,
        ["Under", "Par", "Over"],
        ["rgba(171, 217, 91,0.8)", "#007bff", "#dc3545"],
        2
    );
}
function makePie($canvas, $data, $labels, $colors, $pc) {
    if ($pc == 1) {
        if (open_chart) {
            PieChart1.destroy();
        }
        PieChart1 = new Chart($canvas, {
            type: "pie",
            data: {
                labels: $labels,
                datasets: [
                    {
                        data: $data,
                        backgroundColor: $colors
                    }
                ]
            }
        });
    } else {
        if (open_chart) {
            PieChart2.destroy();
        }
        PieChart2 = new Chart($canvas, {
            type: "pie",
            data: {
                labels: $labels,
                datasets: [
                    {
                        data: $data,
                        backgroundColor: $colors
                    }
                ]
            }
        });
    }
}

function changeRange($week = false) {
    var rangeGraph = document.getElementById("rangeGraph");

    var kv_rd =
        $week == "t" || $week == false
            ? crdata["total"]
            : crdata["week"][$week];
    var rd = [];

    Object.entries(kv_rd).forEach(entry => {
        const [key, value] = entry;
        rd.push(value);
    });
    var r_min = Math.min.apply(null, rd),
        r_max = Math.max.apply(null, rd);

    if (open_chart) {
        rGraph.destroy();
    }
    rGraph = new Chart(rangeGraph, {
        type: "line",
        data: {
            labels: crlabels,
            datasets: [
                {
                    label: "Score",
                    lineTension: 0.5,
                    backgroundColor: "rgba(171, 217, 91,0.4)",
                    borderColor: "rgba(171, 217, 91,1)",
                    pointRadius: 0,
                    pointBackgroundColor: "rgba(171, 217, 91,1)",
                    pointBorderColor: "rgba(255,255,255,0.8)",
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(171, 217, 91,1)",
                    pointHitRadius: 50,
                    pointBorderWidth: 2,
                    data: rd
                }
            ]
        },
        options: {
            scales: {
                xAxes: [
                    {
                        time: { unit: "date" },
                        gridLines: { display: false },
                        ticks: { maxTicksLimit: 12 }
                    }
                ],
                yAxes: [
                    {
                        ticks: {
                            min: r_min - 1,
                            max: r_max + 1,
                            maxTicksLimit: 5
                        },
                        gridLines: { color: "rgba(0, 0, 0, .125)" }
                    }
                ]
            },
            legend: { display: false }
        }
    });
}

Chart.defaults.global.defaultFontFamily =
    '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = "#292b2c";

function changeHalf($week = false) {
    if ($week === false || $week === "t") {
        $week = "a";
    }
    var fh = 0,
        sh = 0;

    var data = tdata["weeks"][$week];
    for ($i = 0; $i < data.length / 2; $i++) {
        fh += data[$i];
    }
    for ($i; $i < data.length; $i++) {
        sh += data[$i];
    }
    var $fh = fh / (data.length / 2);
    var $sh = sh / (data.length / 2);
    var hcBarChart = document.getElementById("hcBarChart");
    if (open_chart) {
        hchart.destroy();
    }
    hchart = new Chart(hcBarChart, {
        type: "bar",
        data: {
            labels: ["1st", "2nd"],
            datasets: [
                {
                    label: "avg Score",
                    backgroundColor: "rgba(171, 217, 91,0.8)",
                    borderColor: "rgba(171, 217, 91,0.8)",
                    data: [$fh, $sh]
                }
            ]
        },
        options: {
            scales: {
                xAxes: [
                    {
                        time: {
                            unit: "month"
                        },
                        gridLines: {
                            display: false
                        },
                        ticks: {
                            maxTicksLimit: 6
                        }
                    }
                ],
                yAxes: [
                    {
                        ticks: {
                            min: Math.min.apply(null, [$fh, $sh]) - 1,
                            max: Math.max.apply(null, [$fh, $sh]) + 1,
                            maxTicksLimit: 5
                        },
                        gridLines: {
                            display: true
                        }
                    }
                ]
            },
            legend: {
                display: false
            }
        }
    });
}
function weekComparison($hole = false) {
    if ($hole === false) {
        $hole = "th";
    }
    var wcdata = tdata["holes"][$hole];
    var wclabels = [];
    Object.entries(tdata["weeks"]).forEach(entry => {
        const [key, value] = entry;
        if (key !== "t") {
            if (key == "a") {
                wclabels.push("Average");
            } else {
                wclabels.push(key);
            }
        }
    });
    wcdata.push(wcdata.reduce((a, b) => a + b, 0) / wcdata.length);
    var wcBarChart = document.getElementById("wcChart");
    if (open_chart) {
        wcChart.destroy();
    }
    wcChart = new Chart(wcBarChart, {
        type: "bar",
        data: {
            labels: wclabels,
            datasets: [
                {
                    label: "score",
                    backgroundColor: "rgba(171, 217, 91,0.8)",
                    borderColor: "rgba(171, 217, 91,0.8)",
                    data: wcdata
                }
            ]
        },
        options: {
            scales: {
                xAxes: [
                    {
                        time: {
                            unit: "month"
                        },
                        gridLines: {
                            display: false
                        },
                        ticks: {
                            maxTicksLimit: 6
                        }
                    }
                ],
                yAxes: [
                    {
                        ticks: {
                            min: Math.min.apply(null, wcdata) - 1,
                            max: Math.max.apply(null, wcdata) + 1,
                            maxTicksLimit: 5
                        },
                        gridLines: {
                            display: true
                        }
                    }
                ]
            },
            legend: {
                display: false
            }
        }
    });
}

changeData("t");
open_chart = 1;
