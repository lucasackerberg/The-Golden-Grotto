$('#demo').daterangepicker({
  "parentEl": "formWrapper",
  "startDate": "1/1/2024",
  "endDate": "1/2/2024",
  "minDate": "01/01/2024",
  "maxDate": "01/31/2024"
}, function(start, end, label) {
console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
});

var swiper = new Swiper(".mySwiper", {
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
});