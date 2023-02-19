"use strict";

$(document).ready(function () {
  $(".hamburger").click(function () {
    $("body").toggleClass("active");
    $(this).toggleClass("active");
    $("#menu").toggleClass("active");
  });
  $("table .sorting").click(function () {
    $("table .sorting").removeClass('sortAsc, sortDesc');
    $(this).toggleClass(function () {
      return $(this).is('.sortAsc, .sortDesc') ? 'sortAsc sortDesc' : 'sortAsc';
    });
  });
  var i = 2;
  $("#add").click(function () {
    var last = $(".form-groupe div").last();
    var appended = $("<div><input type=\"text\" name=\"nombtn" + i + "\" placeholder=\"Ecrivez le nom du bouton\"><span class=\"removeinput\"></span></div>");
    last.after(appended);
    $(".removeinput").click(function () {
      $(this).parent().remove();
      $("#add").show();
    });
    var n = $(".form-groupe div").length;

    if (n >= 4) {
      $(this).hide();
    }
  });
});