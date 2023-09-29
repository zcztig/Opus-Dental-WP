(function ($) {
  $.LoadingOverlaySetup({
    background      : "rgba(23, 44, 68, 0.70)",
    image           : '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M464 256A208 208 0 1 1 48 256a208 208 0 1 1 416 0zM0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM232 120V256c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2V120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"/></svg>',
    imageAnimation  : "1.5s fadein",
    imageColor      : "#77ad9d"
  });

  $(document).ajaxStart(function(){
    if (typeof($('#form-booking')[0]) !== 'undefined') {
      $.LoadingOverlay('show');
    }
    if (typeof($('#fallback-treatments')[0]) !== 'undefined') {
      $.LoadingOverlay('show');
    }
    
  });
  $(document).ajaxComplete(function(){
    $.LoadingOverlay('hide');
  });

  $('#form-treatments .accordion-item').on('hide.bs.collapse', function(){
    $('#form-clinicians').html('');
    $('#form-booking').html('');
    $('#form-hours').html('');
  });
  $('#select-treatment .accordion-item').on('show.bs.collapse', function(){
    var
    inputs = $(this).find('input').serialize(),
    send = {
      action: 'form_get_clinicians',
      inputs: inputs
    }
    console.log('Dette sender vi:');
    console.log(send);
    $.post(ajax.url, send, function(r){
      console.log('Dette får vi i retur');
      console.log(r);
      if (r.success) {
        $('#form-clinicians').html(r.data.html);
      } else {
        alert('Noe gikk galt');
        $('#form-clinicians').html(r.data.html);
      }
    });
  });

  $('body').on('hide.bs.collapse', '#select-clinician .accordion-item', function(){
    $('#form-booking').html('');
    $('#form-hours').html('');
  });
  $('body').on('show.bs.collapse', '#select-clinician .accordion-item', function(){
    var
    inputs = $(this).find('input').serialize(),
    send = {
      action: 'form_get_hours',
      inputs: inputs,
      treatment: $('#select-clinician').data('treatment')
    }
    console.log('Dette sender vi:');
    console.log(send);
    $.post(ajax.url, send, function(r) {
      console.log('Dette får vi i retur');
      console.log(r);
      if (r.data.dates) {
        $('#form-hours').html(r.data.html.calendar);
        var elements = {
          calendar: $('.booking-calendar')[0],
          events: $('.booking-events')[0]
        };
        console.log(elements);
        elements.title = document.createElement("div");
        elements.title.className = "title";
        elements.events.appendChild(elements.title);
        elements.subtitle = document.createElement("div");
        elements.subtitle.className = "subtitle";
        elements.events.appendChild(elements.subtitle);
        elements.list = document.createElement("div");
        elements.list.className = "list";
        elements.events.appendChild(elements.list);
        calendar = jsCalendar.new(elements.calendar, r.data.dates[0], {
          "navigatorPosition": "right",
          "monthFormat": "month YYYY",
          "dayFormat": "DDD",
          "firstDayOfTheWeek": "2",
          "language": "no"
        });
        calendar.select(r.data.dates);
        var events = r.data.hours;
        var date_format = "DD/MM/YYYY";
        var showEvents = function(date){
          // Date string
          var id = jsCalendar.tools.dateToString(date, date_format, "no");
          // Set date - allerede satt
          //current = new Date(date.getTime());
          // Set title
          elements.title.textContent = id;
          // Clear old events
          elements.list.innerHTML = "";
          // Add events on list
          if (events.hasOwnProperty(id) && events[id].length) {
            // Number of events
            elements.subtitle.textContent = events[id].length + " " + ((events[id].length > 1) ? "ledige timer" : "ledig time");
            var div;
            var link;
            // For each event
            for (var i = 0; i < events[id].length; i++) {
              div = document.createElement("div");
              link = document.createElement("span");
              div.dataset.starttime = events[id][i].returnvalue;
              div.className = "event-item";
              //div.textContent = (i + 1) + ". " + events[id][i].formatted;
              div.textContent = events[id][i].formatted;
              link.textContent = 'Velg tidspunkt';
              link.style.float = 'right';
              elements.list.appendChild(div);
              div.appendChild(link);
            }
          } else {
            elements.subtitle.textContent = "Ingen ledige timer";
          }
        };
        showEvents(jsCalendar.tools.stringToDate(r.data.dates[0]));
        calendar.onDateClick(function (e, d) {
          showEvents(d);
          if (calendar.isSelected(d)) {
            calendar.set(d);
            $('[data-date="' + jsCalendar.tools.dateToString(d, 'DD/MM/yyyy') + '"]').show();
          }
        });
        calendar.onMonthChange(function (event, date) {
          send.date = date.toISOString();
          $.post(ajax.url, send, function (re) {
            calendar.select(re.data.dates);
            calendar.set(re.data.dates[0]);
            showEvents(jsCalendar.tools.stringToDate(re.data.dates[0]));
          });
        });
      } else {
        alert('Fant ingen ledige datoer');
      }
    });
  });

  $('body').on('click', '.event-item', function(){
    $('#form-booking').html('');
    var 
    inputs = $('.accordion-collapse.collapse.show').find('input').serialize(),
    send = {
      action: 'get_booking_form',
      inputs: inputs,
      selectedtime: $(this).data('starttime')
    }
    console.log(send);
    $.post(ajax.url, send, function(r){
      $('#form-booking').html(r.data.html);
      $('html, body').animate({
        scrollTop: $('#form-booking').offset().top-50
      }, 500);
    });
  });

  $('body').on('submit', '#booking-form', function(e){
    e.preventDefault();
    var
    formdata = $(this).serialize(),
    validity = $(this)[0].checkValidity();
    if (validity === false) {
      $(this).addClass('was-validated');
      return;
    }
    send = {
      action: 'do_opus_booking',
      formdata: formdata
    };
    $.post(ajax.url, send, function(r){
      if (r.success) {
        $('#form-treatments').html('');
        $('#form-clinicians').html('');
        $('#form-hours').html('');
        $('#form-booking').html(r.data.html);
        $('html, body').animate({
          scrollTop: $('#form-booking').offset().top-50
        }, 500);
        gtag('event', 'conversion');
      } else {
        alert('Klarte ikke booke timen. Vennligst dobbeltsjekk at skjema (legg merke til at fødselsnummer må være riktig format) er korrekt utfylt og prøv på nytt. Hvis det fortsatt ikke går bør du laste inn siden på nytt.');
      }
    });
  });

  $('body').on('click', '#fallback-treatments .accordion-button', function(e){
    $(this).removeClass('collapsed');
    $('#fallback-treatments .accordion-button').not($(this)).addClass('collapsed');
    $('#fallback-clinicians').html('');
    $('#fallback-dayselect').html('');
    $('#fallback-timeselect').html('');
    var send = {
      action: 'opus_get_fallback_clinicians',
      treatment: $(this).text().trim()
    }
    $.post(ajax.url, send, function(r){
      if (r.success) {
        $('#fallback-clinicians').html(r.data);
        $('html, body').animate({
          scrollTop: $('#fallback-clinicians').offset().top-100
        }, 500);
      }
    });
  });

  $('body').on('click', '#fallback-clinicians .accordion-button', function(e){
    $(this).removeClass('collapsed');
    $('#fallback-clinicians .accordion-button').not($(this)).addClass('collapsed');
    $('#fallback-dayselect').html('');
    $('#fallback-timeselect').html('');
    var send = {
      action: 'opus_get_fallback_daytimes',
      clinician: $(this).text().trim()
    }
    $.post(ajax.url, send, function(r){
      if (r.success) {
        $('#fallback-dayselect').html(r.data.days);
        $('#fallback-timeselect').html(r.data.times);
        $('html, body').animate({
          scrollTop: $('#fallback-dayselect').offset().top-100
        }, 500);
      }
    });
  });

  $('#fallback-dayselect').on('change', 'input[type=checkbox]', function(){
    var target = $('#form-dynamic-days'),
    fields = $('#fallback-dayselect input[type=checkbox]:checked'),
    values = fields.map(function(){
      return $(this).val();
    }).get();
    if (target) {
      target.text(values.join(', '));
    }
  });

  $('#fallback-timeselect').on('change', 'input[type=checkbox]', function(){
    var target = $('#form-dynamic-times'),
    fields = $('#fallback-timeselect input[type=checkbox]:checked'),
    values = fields.map(function(){
      return $(this).val();
    }).get();
    if (target) {
      target.text(values.join(', '));
    }
  });

  $('body').on('click', '#opus-fallback-get-form', function(e){
    var button = $(this),
    send = {
      action: 'opus_get_fallback_form',
      treatment: $('input[name=fallback-treatment]').val(),
      clinician: $('input[name=fallback-clinician]').val(),
      daytime: $('#fallback-dayselect, #fallback-timeselect').find('input[type=checkbox]:checked').serialize()
    };
    $('input[type=checkbox]').prop('disabled', true);
    $.post(ajax.url, send, function(r){
      if (r.success) {
        button.remove();
        $('#fallback-form-outer').html(r.data.html);
        $('input[type=checkbox]').prop('disabled', false);
        $('html, body').animate({
          scrollTop: $('#fallback-booking-form').offset().top-100
        }, 500);
      } else {
        alert('Noe gikk galt!');
        console.log(r);
      }
    });
  });

  $('body').on('submit', '#fallback-booking-form', function(e){
    e.preventDefault();
    validity = this.checkValidity();
    if (validity === false) {
      $(this).addClass('was-validated');
      return;
    }
    var
    days = $('#fallback-dayselect input[type=checkbox]:checked').map(function(){
      return this.value;
    }).get(),
    timeofday = $('#fallback-timeselect input[type=checkbox]:checked').map(function(){
      return this.value;
    }).get(),
    send = {
      action: 'opus_submit_fallback_form',
      formdata: $(this).serialize(),
      days: days,
      timeofday: timeofday
    };
    $.post(ajax.url, send, function(r){
      console.log(r);
      if (r.success) {
        $('#fallback-form-outer').html(r.data.html);
      } else {
        alert('Noe gikk galt - du kan prøve på nytt. Får du fortsatt feilmelding anbefaler vi å laste inn siden på nytt.');
      }
    });
  });

  //sporingskode
  $('body').on('click', 'a[href^="tel:"]', function(){
    gtag('event', 'phonecall');
  });

})(jQuery)
