$(document).ready(function () {

  const hamBurger = document.querySelector(".toggle-btn");

  hamBurger.addEventListener("click", function () {
    document.querySelector("#sidebar").classList.toggle("expand");
    document.querySelector("nav").classList.toggle("nav-collapse");
  });

  $("#dataTable1").DataTable();
  $("#dataTable2").DataTable();
  $("#dataTable3").DataTable();
  $("#dataTable4").DataTable();

});