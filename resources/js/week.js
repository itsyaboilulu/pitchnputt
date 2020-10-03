var chat_update = 0;
var colors = [
    "255,107,107",
    "0,41,83",
    "199,244,100",
    "149,212,243",
    "241,187,27",
    "127,0,255"
];
var nameToColor = {};
var colarr = [];
var i = 0;
const scoreTableTr = document
    .getElementsByClassName("score-table")[0]
    .getElementsByTagName("tr");
Object.entries(score_data).forEach(entry => {
    const [key, value] = entry;
    nameToColor[key] = colors[i];
    colarr.push("rgba(" + colors[i] + ",0.5)");
    i++;
});
var dt = document
    .getElementsByClassName("score-table")[0]
    .getElementsByTagName("tr");

const borderColor = "rgba(171, 217, 91,1)";
var sGraph = null;
function updateData($hole = "h") {
    updateScores($hole);

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
    if ($hole !== "h") {
        for ($i = 0; $i < scoreTableTr.length - 1; $i++) {
            scoreTableTr[$i].getElementsByTagName("td")[
                $hole + 1
            ].style.borderLeft = "2px solid " + borderColor;
            scoreTableTr[$i].getElementsByTagName("td")[
                $hole + 1
            ].style.borderRight = "2px solid " + borderColor;
        }
        scoreTableTr[$i].getElementsByTagName("th")[
            $hole + 1
        ].style.borderLeft = "2px solid " + borderColor;
        scoreTableTr[$i].getElementsByTagName("th")[
            $hole + 1
        ].style.borderRight = "2px solid " + borderColor;
    }
}

function updateScores($hole) {
    var dsets = [];
    var slabels = [];
    var smin = 12;
    var smax = 0;
    Object.entries(score_data).forEach(entry => {
        const [key, value] = entry;
        if ($hole != "h") {
            dsets.push(value[$hole]);
            if (value[$hole] < smin) {
                smin = value[$hole];
            }
            if (value[$hole] > smax) {
                smax = value[$hole];
            }
        } else {
            dsets.push({
                label: key,
                lineTension: 0.1,
                backgroundColor: "rgba(" + nameToColor[key] + ",0)",
                borderColor: "rgba(" + nameToColor[key] + ",0.6)",
                pointRadius: 0,
                pointBackgroundColor: "rgba(" + nameToColor[key] + ",1)",
                pointBorderColor: "rgba(" + nameToColor[key] + ",0.8)",
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(" + nameToColor[key] + ",1)",
                pointHitRadius: 50,
                pointBorderWidth: 2,
                data: value
            });
            if (Math.min.apply(null, value) < smin) {
                smin = Math.min.apply(null, value);
            }
            if (Math.max.apply(null, value) > smax) {
                smax = Math.max.apply(null, value);
            }
        }

        slabels.push(key);
    });

    if ($hole === "h") {
        slabels = [];
        for (i = 0; i < score_data[Object.keys(score_data)[0]].length; i++) {
            slabels.push(i + 1);
        }
    }

    if (chat_update) {
        sGraph.destroy();
    }
    if ($hole === "h") {
        sGraph = new Chart(document.getElementById("scoreChart"), {
            type: "line",
            data: {
                labels: slabels,
                datasets: dsets
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
                                min: smin - 1,
                                max: smax + 1,
                                maxTicksLimit: 5
                            },
                            gridLines: { color: "rgba(0, 0, 0, .125)" }
                        }
                    ]
                },
                legend: { display: false }
            }
        });
    } else {
        sGraph = new Chart(document.getElementById("scoreChart"), {
            type: "horizontalBar",
            data: {
                labels: slabels,
                datasets: [
                    {
                        label: "score",
                        backgroundColor: colarr,
                        borderColor: colarr,
                        data: dsets
                    }
                ]
            },
            options: {
                scales: {
                    xAxes: [
                        {
                            gridLines: {
                                display: false
                            },
                            ticks: {
                                min: smin - 1,
                                max: smax + 1,
                                maxTicksLimit: 5
                            }
                        }
                    ],
                    yAxes: [
                        {
                            time: {
                                unit: "Score"
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
}

function updatePar() {
    var pu = [],
        p = [],
        po = [];
    var pplabels = [];
    Object.entries(par_accuracy).forEach(entry => {
        const [key, value] = entry;
        pplabels.push(key == "total" ? "Average" : key);
        pu.push(value[0]);
        p.push(value[1]);
        po.push(value[2]);
    });
    var updateParchaty = new Chart(document.getElementById("parPercentChart"), {
        type: "bar",
        data: {
            labels: pplabels,
            datasets: [
                {
                    label: "Under",
                    backgroundColor: "rgba(40, 167, 69,0.8)",
                    borderColor: "rgba(40, 167, 69,0.8)",
                    data: pu
                },
                {
                    label: "Par",
                    backgroundColor: "rgba(0, 123, 255,0.8)",
                    borderColor: "rgba(0, 123, 255,0.8)",
                    data: p
                },
                {
                    label: "Over",
                    backgroundColor: "rgba(220, 53, 69,0.8)",
                    borderColor: "rgba(220, 53, 69,0.8)",
                    data: po
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
                            min: 0,
                            max: 100,
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

function updateConsistancy() {
    var cd = [],
        clabels = [];
    Object.entries(consistancy_data).forEach(entry => {
        const [key, value] = entry;
        clabels.push(key);
        cd.push(value[1]);
    });
    var updateConsistancychaty = new Chart(
        document.getElementById("consistancyGraph"),
        {
            type: "bar",
            data: {
                labels: clabels,
                datasets: [
                    {
                        label: "consistancy %",
                        backgroundColor: colarr,
                        borderColor: colarr,
                        data: cd
                    }
                ]
            },
            options: {
                scales: {
                    xAxes: [
                        {
                            time: {
                                unit: "score"
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
                                min: 0,
                                max: 100,
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
        }
    );
}

function updateRange() {
    var rdata = [];
    var rmin = 12,
        rmax = 0;
    var rlabel = [];
    Object.entries(range_data).forEach(entry => {
        const [key, value] = entry;
        rlabel = [];
        var vdata = [];
        Object.entries(value).forEach(eentry => {
            var [ekey, evalue] = eentry;
            rlabel.push(ekey);
            vdata.push(evalue);
        });
        if (Math.min.apply(null, vdata) < rmin) {
            rmin = Math.min.apply(null, vdata);
        }
        if (Math.max.apply(null, vdata) > rmax) {
            rmax = Math.max.apply(null, vdata);
        }
        rdata.push({
            label: key,
            lineTension: 0.5,
            backgroundColor: "rgba(" + nameToColor[key] + ",0)",
            borderColor: "rgba(" + nameToColor[key] + ",0.6)",
            pointRadius: 0,
            pointBackgroundColor: "rgba(" + nameToColor[key] + ",1)",
            pointBorderColor: "rgba(" + nameToColor[key] + ",0.8)",
            pointHoverRadius: 5,
            pointHoverBackgroundColor: "rgba(" + nameToColor[key] + ",1)",
            pointHitRadius: 50,
            pointBorderWidth: 2,
            data: vdata
        });
    });
    console.log(rdata);
    console.log(rlabel);
    rGraph = new Chart(document.getElementById("rangeGraph"), {
        type: "line",
        data: {
            labels: rlabel,
            datasets: rdata
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
                            min: rmin - 1,
                            max: rmax + 1,
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

updateData();
updatePar();
updateConsistancy();
updateRange();
chat_update = 1;
