// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// Pie Chart Example
var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: ["자유게시판", "일상게시판", "비밀게시판", "뻘글게시판", "컴퓨터게시판", "최진웅", "CCIT"],
    datasets: [{
      data: [1050, 553, 640, 357, 530, 470],
      backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#6956a4','#fbf232','#f62973'],
      hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#59498c','#dbcd14','#b60e4a'],
      hoverBorderColor: "rgba(234, 236, 244, 1)",
    }],
  },
  options: {
    maintainAspectRatio: false,
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
    },
    legend: {
      display: false
    },
    cutoutPercentage: 80,
  },
});
