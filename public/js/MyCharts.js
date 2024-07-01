document.addEventListener("DOMContentLoaded", function () {
  var Pie = document.querySelectorAll(".Pie");
  Pie.forEach(function (Pie) {
    var labels = $("#" + Pie.id + "Label")
      .val()
      .split(",");
    var data = $("#" + Pie.id + "Data")
      .val()
      .split(",");
    var pieCtx = document.getElementById(Pie.id).getContext("2d");
    var pieChart = new Chart(pieCtx, {
      type: "pie",
      data: {
        labels: labels,
        datasets: [
          {
            backgroundColor: [
              " rgba(255, 99, 132, 1)",
              " rgba(54, 162, 235, 1)",
              " rgba(255, 206, 86, 1)",
              " rgb(16, 152, 152)",
              " rgba(153, 102, 255, 1)",
              " rgb(193, 143, 178)",
              " rgb(98, 178, 202)",
              " rgb(160, 114, 22)",
              " rgb(50, 166, 121)",
              " rgb(188, 37, 100)",
            ],
            borderColor: [
              " rgba(255, 99, 132, 1)",
              " rgba(54, 162, 235, 1)",
              " rgba(255, 206, 86, 1)",
              " rgb(16, 152, 152)",
              " rgba(153, 102, 255, 1)",
              " rgb(193, 143, 178)",
              " rgb(98, 178, 202)",
              " rgb(160, 114, 22)",
              " rgb(50, 166, 121)",
              " rgb(188, 37, 100)",
            ],
            borderWidth: 2,
            data: data,
          },
        ],
      },
    });
  });

  var lineCharts = document.querySelectorAll(".lineCharts");
  lineCharts.forEach(function (lineChart) {
    var labels = $("#" + lineChart.id + "Label")
      .val()
      .split(",");
    var data = $("#" + lineChart.id + "Data")
      .val()
      .split(",");
    var Hint = $("#" + lineChart.id + "Hint").val();

    var LineChartDiv = document.getElementById(lineChart.id).getContext("2d");

    // Create the line chart
    var MyLineChart = new Chart(LineChartDiv, {
      type: "line",
      data: {
        labels: labels,
        datasets: [
          {
            label: Hint,
            backgroundColor: "rgba(54, 162, 235, 0.2)",
            borderColor: "rgba(54, 162, 235, 1)",
            borderWidth: 2,
            data: data,
          },
        ],
      },
      options: {
        scales: {
          yAxes: [
            {
              ticks: {
                beginAtZero: true,
              },
            },
          ],
        },
      },
    });
  });

  var barCtxs = document.querySelectorAll(".barCtx");
  barCtxs.forEach(function (barCtx) {
    var labels = $("#" + barCtx.id + "Label")
      .val()
      .split(",");
    var data = $("#" + barCtx.id + "Data")
      .val()
      .split(",");
    var Hint = $("#" + barCtx.id + "Hint").val();
    var ctx = document.getElementById(barCtx.id).getContext("2d");
    var myChart = new Chart(ctx, {
      type: "bar",
      data: {
        labels: labels,
        datasets: [
          {
            label: Hint,
            data: data,
            backgroundColor: [
              "rgba(255, 99, 132, 1)",
              "rgba(54, 162, 235, 1)",
              "rgba(255, 206, 86, 1)",
              "rgba(75, 192, 192, 1)",
              "rgba(153, 102, 255, 1)",
              "rgba(255, 159, 64, 1)",
            ],
            borderColor: [
              "rgba(255, 99, 132, 1)",
              "rgba(54, 162, 235, 1)",
              "rgba(255, 206, 86, 1)",
              "rgba(75, 192, 192, 1)",
              "rgba(153, 102, 255, 1)",
              "rgba(255, 159, 64, 1)",
            ],
            borderWidth: 2,
          },
        ],
      },
      options: {
        scales: {
          yAxes: [
            {
              ticks: {
                beginAtZero: true,
              },
            },
          ],
        },
      },
    });
  });
});
