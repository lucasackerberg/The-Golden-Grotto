$('#demo').daterangepicker({
    "parentEl": "formWrapper",
    "startDate": "11/30/2023",
    "endDate": "12/06/2023",
    "minDate": "01/01/2024",
    "maxDate": "01/31/2024"
}, function(start, end, label) {
  console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
});