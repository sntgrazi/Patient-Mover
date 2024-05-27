<template>
  <div class="grafico">
    <canvas id="myChart"></canvas>
  </div>
</template>
  
  <script>
import Chart from "chart.js/auto";

export default {
  name: "Chart",
  mounted() {
    const data = {
      labels: ["02/05", "03/05", "04/05", "05/05", "06/05", "07/05", "08/05"],
      datasets: [
        {
          label: "Incidentes p/dia",
          data: [12, 19, 3, 5, 2, 3, 8],
          backgroundColor: "#0670AC",
          borderWidth: 0,
          borderRadius: {
            topLeft: 10,
            topRight: 10,
          },
        },
      ],
    };

    const config = {
      type: "bar",
      data: data,
      options: {
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 5, // Set the interval to 5
            },
          },
        },
        plugins: {
          legend: {
            display: false,
          },
        },
        responsive: true,
        maintainAspectRatio: false,
        barThickness: 19,
      },
    };

    const ctx = document.getElementById("myChart").getContext("2d");
    new Chart(ctx, config);
  },
};
</script>
  
  <style>
.grafico {
  width: 90%;
  height: 80%;
}
</style>
  