 <script src="assets/extensions/dayjs/dayjs.min.js"></script>
  <script src="assets/extensions/apexcharts/apexcharts.min.js"></script>

  <script>
    var barOptions = {
      series: [{
          name: "<?= $title1 ?>",
          data: [<?= $arr1 ?>],
        },
        {
          name: "<?= $title2 ?>",
          data: [<?= $arr2 ?>],
        },
        {
          name: "<?= $title3 ?>",
          data: [<?= $arr3 ?>],
        },
      ],
      chart: {
        type: "bar",
        height: 350,
      },
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: "55%",
          endingShape: "rounded",
        },
      },
      dataLabels: {
        enabled: false,
      },
      stroke: {
        show: true,
        width: 2,
        colors: ["transparent"],
      },
      xaxis: {
        categories: ["Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct"],
      },
      yaxis: {
        title: {
          text: "$ (thousands)",
        },
      },
      fill: {
        opacity: 1,
      },
      tooltip: {
        y: {
          formatter: function(val) {
            return "$ " + val + " thousands";
          },
        },
      },
    };

    var bar = new ApexCharts(document.querySelector("#bar"), barOptions);
    bar.render();
  </script>