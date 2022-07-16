$(document).ready(function() {
   /*$('body').on('click', '.delete-role-o', function(e) {
       e.preventDefault();
       if(confirmAction($(this))) {
           $.post('/admin/role/delete', {roleName: $(this).attr('data-role')}, function(json) {
               window.location.reload();
           });
       }
   });

   function confirmAction(object) {
       return confirm(object.attr('data-confirm'));
   }*/


   $('body').on('click', '.role-row .role-name', function(e) {
       e.preventDefault();
       $(this).parents('.role-row').find('ul').slideToggle();
   });
});
