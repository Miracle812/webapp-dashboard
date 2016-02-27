window.ParsleyConfig = {
  errorsWrapper: '<div class="errorMsg"></div>',
  errorTemplate: '<span></span>'
  };
var formIdent = $('#foogincidentform'),
runParsley = function(){ formIdent.parsley() };
$(document).ready(function(){
  formIdent.on('submit',function(){
    var valid = false,
      zorba = true;
      $('[data-parsley-required="true"]').each(function(){
        valid = $(this).parsley().validate() == true ? true : false;
        if(valid === false){
          zorba = false;
        }
      });
    return zorba ? true : false;
  })
  formIdent.bootstrapWizard({
      'nextSelector': '.next',
      'previousSelector': '.previous',
      'firstSelector': '.first',
      'lastSelector': '.last',
      'tabClass': 'nav nav-tabs',
      'onNext': function(tab, navigation, index) {
        var valid = false,
          zorba = true;
        $('[data-parsley-required="true"]', $( $(tab.html()).attr('href') )).each(function(){
          valid = $(this).parsley().validate() == true ? true : false;
          if(valid === false){
            zorba = false;
          }
        });
        return zorba ? true : false;
      },
      onTabClick: function(tab, navigation, index) {
        return false;
      },
      onTabShow: function(tab, navigation, index) {
        var $total = navigation.find('li').length;
        var $current = index+1;
        var $percent = ($current/$total) * 100;
        $('#foogincidentform').find('.progress-bar').css({width:$percent+'%'});
      }
    });
  });