$(function(){
  var requiredFields = ['username', 'mobile', 'address', 'zip'];

  window.validateField = function(field) {
    var fieldName = field.attr('name');
    $.ajax({
      url: 'validate.php', 
      type: 'get',
      data: {'field': fieldName, 'value': field.val()}, 
      success: function(data){
        var fieldError = $('#' + fieldName + '_error');
        if (data != '') {
          fieldError.html(data).show();
        } else {
          fieldError.html('').hide();
        }
      },
      error: function(jq, text, message){
        alert(text);
        alert(message);
      }
    });
  }

  $('#f').submit(function(){ 
    var hasErrors = $('#f .error').filter(function(){
      return $(this).html() != ''
    }).length > 0;

    var hasEmptyFields = $.grep(requiredFields, function(fieldName){
      return $('input[name="' + fieldName + '"]').val() == '';
    }).length > 0;

    result = !hasErrors && !hasEmptyFields;
    if (!result) {
      $.each(requiredFields, function(index, fieldName){
        validateField($('input[name="' + fieldName + '"]'));
      });
    }

    if (result) {
      $(':submit', this).click(function() {
        return false;
      });
    }

    return result;
  }); 

  $.each(requiredFields, function(index, fieldName){
    $('input[name="' + fieldName + '"]').blur(function(){
      validateField($(this));
    });
  });
});
