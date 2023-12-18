const basepriceluxury = 15;
const priceForPool = 3;
const priceForMassage = 3;
const totalCostParent = document.getElementById("bookingInformation");
const poolCheckbox = document.getElementById("poolCheckbox");
const massageCheckbox = document.getElementById("massageCheckbox");
console.log(bookedDates);

$('#demo').daterangepicker({
  parentEl: "formWrapper",
  startDate: "1/1/2024",
  endDate: "1/2/2024",
  minDate: "01/01/2024",
  maxDate: "01/31/2024",
  isInvalidDate: function(date) {
    //Change date to morning to not get weord timezone errors.
    var timestamp = date.valueOf();
    // Adjust for different timezones plus add extra marginal hours for it not to fall into a daylight savings problem.
    timestamp -= new Date().getTimezoneOffset() * 60 * 1000 * 7;

    for (var i = 0; i < bookedDates.length; i++) {
      var checkinDate = bookedDates[i].checkin_date;
      var checkoutDate = bookedDates[i].checkout_date;
  
      console.log('Checkin Date String:', checkinDate);
      console.log('Checkout Date String:', checkoutDate);
  
      // Set the time part to match the date
      var startTimestamp = new Date(checkinDate).valueOf();
      var endTimestamp = new Date(checkoutDate).valueOf();
  
      // Adjust for local timezone offset plus extra margin hours.
      startTimestamp -= new Date().getTimezoneOffset() * 60 * 1000;
      endTimestamp -= new Date().getTimezoneOffset() * 60 * 1000 * 7;
  
      console.log('Start Timestamp:', startTimestamp);
      console.log('Timestamp', timestamp);
      console.log('End Timestamp:', endTimestamp);
  
      if (timestamp >= startTimestamp && timestamp <= endTimestamp) {
          console.log('This timestamp is now disabled:', timestamp);
          return true; // Date is booked, disable it
      }
    }  
    return false; // Date is not booked, enable it
  }
}, function(start, end, label) {
  console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
});

$('#demo').on('apply.daterangepicker', function(ev, picker) {
  let totaldays = picker.endDate.diff(picker.startDate, 'days');
  let totalprice = basepriceluxury * totaldays;

  // Check if pool checkbox is checked and add its price
  if (poolCheckbox.checked) {
    totalprice += priceForPool;
  }

  // Check if massage checkbox is checked and add its price
  if (massageCheckbox.checked) {
    totalprice += priceForMassage;
  }

  // Display or use the totalprice as needed
  console.log('Total Price:', totalprice);
  totalCostParent.innerHTML = '<h3 id="totalcosth3">Your total cost would be: $' + totalprice + '</h3>';
});

var swiper = new Swiper(".mySwiper", {
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
});
