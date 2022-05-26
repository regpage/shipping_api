$(document).ready(function(){
  let hostname = window.location.protocol + '//' + window.location.hostname;
  $("#crm-deal-by-id-text-cleaner").hide();
  $("#mail-info-by-tn-text-cleaner").hide();
// service btns
    $("#crm-deal-by-id, #mail-info-by-tn, #complex-query-deal-id, #crm-active-task-by-id, #crm-log-deal-by-id, #crm-client-by-id").click(function(){
      let buttonId = '#' + this.id;
      let clearBtn = $(this).parent('div').find("[data-type='cleaner_btn']");
      if (!$(this).parent('div').find("[data-type='field_for_input']").val()) {
        $(this).parent('div').find("[data-type='field_for_input']").css("border-color","red");
        alert("Заполните поле");
        return;
      } else {
        $(this).parent('div').find("[data-type='field_for_input']").attr("style","border-color: light-dark");
      }
      let linkquery = hostname + $(this).data("link") + $(this).parent('div').find("[data-type='field_for_input']").val();
      fetch(linkquery)
      .then(response => response.text())
      .then(result => {
        $(buttonId).parent('div').find("[data-type='show_result']").text(result);
        $(buttonId).parent('div').find("[data-type='cleaner_btn']").show();
        clearBtn.click(function(){ //"#"+$(buttonId).parent('div').find("[data-type='cleaner_btn']").attr('id')
          $(buttonId).parent('div').find("[data-type='show_result']").text("---");
          $(buttonId).parent('div').find("[data-type='field_for_input']").val('');
          $(this).hide();
        });
      });
  });
  $("[data-type='cleaner_btn']").click(function () {
    $(this).parent("div").find("[data-type='field_for_input']").val("");
  });
});
