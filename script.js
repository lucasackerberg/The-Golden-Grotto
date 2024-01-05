const priceForPool = 3;
const priceForMassage = 3;
const totalCostParent = document.getElementById("totalcostParent");
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
  let totaldays = picker.endDate.diff(picker.startDate, 'days') + 1;
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
  totalCostParent.innerHTML = '<h3 id="totalcosth3">Your total cost would be: $ <span id="totalcostvaluejs">' + totalprice + '</span></h3>';
  $('#totalCostInput').val(totalprice);
});

function applyDiscount() {
  // Get the discount code entered by the user
  const discountCode = document.getElementById('discountcodeInput').value;
  let totalvalueForDiscount = document.getElementById('totalcostvaluejs');

  // Define your valid discount codes
  const validDiscounts = ['DISCOUNT20', 'EMAILDISCOUNT', 'SPECIAL20'];

  // Check if the entered code is valid
  if (validDiscounts.includes(discountCode)) {
      totalvalueForDiscount = parseFloat(totalvalueForDiscount.textContent) * 0.8;
      totalCostParent.innerHTML = '<h3 id="totalcosth3">Your total cost would be: $ <span id="totalcostvaluejs">' + totalvalueForDiscount + '</span></h3>';
      $('#totalCostInput').val(totalvalueForDiscount);
      alert("Discount applied successfully!");
  } else {
      alert("Invalid discount code. Please try again.");
  }
}

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

function showPopup(discountCode) {
  document.getElementById('discountCode').innerText = discountCode;
  document.getElementById('overlay').style.display = 'block';
  document.getElementById('discountPopup').style.display = 'block';
}

function closePopup() {
  document.getElementById('overlay').style.display = 'none';
  document.getElementById('discountPopup').style.display = 'none';
}

function showBookingPopup(jsonContentForBookingPopup) {
  document.getElementById('jsonContent').textContent = jsonContentForBookingPopup;
  document.getElementById('bookingPopup').style.display = 'block';
  document.getElementById('overlay').style.display = 'block';
}

function closeBookingPopup() {
  document.getElementById('bookingPopup').style.display = 'none';
  document.getElementById('overlay').style.display = 'none';
}

if (jsvar == 1) {
  showPopup(discountCode);
}

if (bookingJS == true) {
  showBookingPopup(jsonContentForBookingPopup);
}

document.addEventListener('DOMContentLoaded', function () {
  const targetSection = window.location.hash.substring(1);
  const targetElement = document.getElementById(targetSection);
  
  if (targetElement) {
    targetElement.scrollIntoView({
      behavior: 'smooth',
      inline: 'nearest',
      marginBottom: 30
    });
  }
});

var swiper = new Swiper(".mySwiper", {
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
});