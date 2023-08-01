$(function() {
  'use strict';

  if($('#datePickerExample').length) {
    var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    $('#datePickerExample').datepicker({
      format: "dd/mm/yyyy",
      todayHighlight: false,
      autoclose: false
    });
    // $('#datePickerExample').datepicker('setDate', '');
  }
});