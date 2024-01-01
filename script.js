const priceForPool = 3;
const priceForMassage = 3;
const totalCostParent = document.getElementById("bookingInformation");
const poolCheckbox = document.getElementById("poolCheckbox");
const massageCheckbox = document.getElementById("massageCheckbox");

$('#demo').daterangepicker({
  parentEl: "formWrapper",
  startDate: moment().startOf('day'), // Set to the current date's start
  endDate: moment().add(1, 'days').startOf('day'), // Set to the next day's start
  minDate: moment("01/01/2024", "MM/DD/YYYY"), // Use moment.js for consistent date parsing
  maxDate: moment("01/31/2024", "MM/DD/YYYY"), // Use moment.js for consistent date parsing
  isInvalidDate: function(date) {
    // Change date to morning to avoid weird timezone errors.
    var timestamp = date.valueOf();
    // Adjust for different timezones plus add extra marginal hours to avoid daylight savings problems.
    timestamp -= new Date().getTimezoneOffset() * 60 * 1000 * 7;

    for (var i = 0; i < bookedDates.length; i++) {
      var checkinDate = bookedDates[i].checkin_date;
      var checkoutDate = bookedDates[i].checkout_date;

      // Set the time part to match the date using moment.js
      var startTimestamp = moment(checkinDate, "YYYY-MM-DD").startOf('day').valueOf();
      var endTimestamp = moment(checkoutDate, "YYYY-MM-DD").startOf('day').valueOf();

      // Adjust for local timezone offset plus extra margin hours.
      startTimestamp -= new Date().getTimezoneOffset() * 60 * 1000;
      endTimestamp -= new Date().getTimezoneOffset() * 60 * 1000 * 7;

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
// Change from text to a float to use in the function beneath.
var basePriceText = $("#basePrice").text();
var basePrice = parseFloat(basePriceText);

$('#demo').on('apply.daterangepicker', function(ev, picker) {
  let totaldays = picker.endDate.diff(picker.startDate, 'days') +1;
  let totalprice = basePrice * totaldays;
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
  $('#totalCostInput').val(totalprice);
});

function handleMassageCheckbox() {
  const massageImage = document.getElementById("massageImage");
  const poolImage = document.getElementById("poolImage");
  const massageCheckmarkImg = document.getElementById("massageCheckmarkImg");
  const poolCheckmarkImg = document.getElementById("poolCheckmarkImg");

  if (massageCheckbox.checked) {
    // Add the 'checked' class when checkbox is checked
    massageImage.classList.add("checked");
    massageCheckmarkImg.style.opacity = 1;
  } else {
    // Remove the 'checked' class when checkbox is unchecked
    massageImage.classList.remove("checked");
    massageCheckmarkImg.style.opacity = 0;
  }

  if (poolCheckbox.checked) {
    // Add
    poolImage.classList.add("checked");
    poolCheckmarkImg.style.opacity = 1;
  } else {
    // Remove
    poolImage.classList.remove("checked");
    poolCheckmarkImg.style.opacity = 0;
  }
}

var swiper = new Swiper(".mySwiper", {
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
});

document.querySelectorAll('a[href^="#section-id"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();

    document.querySelector(this.getAttribute('href')).scrollIntoView({
      behavior: 'smooth'
    });
  });
});