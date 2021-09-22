$('.result').hide();
$('#sumAdd-container').hide();

function validateForm() {
  $("#input-form").validate({
    rules: {
      startDate: {
        "required": true,
        date: true
      },
      term: {
        "required": true,
        range: [5, 60]
      },
      sum: {
        "required": true,
        range: [1000, 3000000]
      },
      percent: {
        "required": true,
        range: [3, 100]
      },
      sumAdd: {
        requred: "#checkbox:checked",
        range: [0, 3000000]
      }
    },
    messages: {
      //...
    }
  });
}

$().ready(validateForm())

$('#checkbox').click(function () {
  if ($('#checkbox').hasClass('checked')) {
    $('#checkbox').removeClass('checked');
    $('#sumAdd').val('');
    $('#sumAdd-container').hide();
    
  } else {
    $('#checkbox').addClass('checked');
    $('#sumAdd').val('');
    $('#sumAdd-container').show();
  }
});

$("#input-form").on('submit', function (e) {
  e.preventDefault();
  
  if (!$("#input-form").valid()) return;

  let data = {
    "startDate": $("#startDate").val(), // дата открытия вклада
    "sum": $("#sum").val(), // сумма вклада
    "term": $("#termOption option:selected").text() == 'месяц' ? $("#term").val() : $("#term").val() * 12, // срок вклада в месяцах
    "percent": $("#percent").val(), // процентная ставка, % годовых
    "sumAdd": $("#checkbox").is(":checked")? $("#sumAdd").val() : 0 // сумма ежемесячного пополнения вклада
  };

  $.ajax({
    type: 'POST',
    url: 'calc.php',
    data: data,
    success: function (number) {
      let text = `₽ ${Math.round(number).toLocaleString()}`;
      $('#result-container').show();
      $('#result').text(text);
    },
    error: function (msg) {
      console.log('err', msg);
    },
  });
});