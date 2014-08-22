$(document).ready(function() {
     $("#btn-add-category").click(function(){
        $.colorbox({
            inline:true,
            width:"50%",
            open:true,
            href: '#form-add-category',
            onClosed: function() {
                 $('#form-add-category').hide();
            },
            onOpen: function() {
                 $('#form-add-category').show();
            }
        });
     });

     // $('#btn-submit-category').click(function(e){
     //    e.preventDefault();
     //    $.ajax({
     //        type: 'POST',
     //        url: '/appointment-scheduler/services/categories',
     //        data: {},
     //        dataType: 'json',
     //    }).success(function(data){
     //        console.log(data);
     //    });
     //    return false;
     // });
 });

// define angular module/app
var formApp = angular.module('formApp', [], function($interpolateProvider) {
    $interpolateProvider.startSymbol('<%');
    $interpolateProvider.endSymbol('%>');
});
// create angular controller and pass in $scope and $http
function formController($scope, $http) {
    // create a blank object to hold our form information
    // $scope will allow this to pass between controller and view
    $scope.formData = {};
    // process the form
    $scope.processForm = function() {
        $http({
            method: 'POST',
            url : '/appointment-scheduler/services/categories',
            data: $.param($scope.formData),  // pass in data as strings
            dataType: 'json'
        }).success(function(data) {
            console.log(data);
            if (!data.success) {
                // if not successful, bind errors to error variables
                $scope.errorName = data.errors.name;
            } else {
                // if successful, bind success message to message
                $scope.message = data.message;
            }
            return false;
        });
        return false;
    };
}
