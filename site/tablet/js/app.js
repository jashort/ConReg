// create our angular app and inject ngAnimate and ui-router 
// =============================================================================
angular.module('formApp', ['ngAnimate', 'ui.router'])

// configuring our routes 
// =============================================================================
.config(function($stateProvider, $urlRouterProvider) {
    
    $stateProvider
    
        // route to show our basic form (/form)
        .state('form', {
            url: '/form',
            templateUrl: 'form.html',
            controller: 'formController'
        })
        
        // nested states 
        // each of these sections will have their own view
        // url will be nested (/form/profile)
        .state('form.name', {
            url: '/name',
            templateUrl: 'form-name.html'
        })
                
        // url will be /form/contact
        .state('form.contact', {
            url: '/contact',
            templateUrl: 'form-contact.html'
        })

        // url will be /form/consent
        .state('form.consent', {
            url: '/consent',
            templateUrl: 'form-consent.html'
        })

        // url will be /form/passtype
        .state('form.passtype', {
            url: '/passtype',
            templateUrl: 'form-passtype.html'
        })

        // url will be /form/confirm
        .state('form.confirm', {
            url: '/confirm',
            templateUrl: 'form-confirm.html'
        });
       
    // catch all route
    // send users to the form page 
    $urlRouterProvider.otherwise('/form/name');
})

.directive('focus', function() {
  // Tag fields with "focus" to use the enter key to move to the next input field.
  return {
    restrict: 'A',
    link: function($scope, elem, attrs) {

      elem.bind('keydown', function(e) {
        var code = e.keyCode || e.which;
        if (code === 13 || code === 9) {		// Catch both enter and tab keys, because number input
 							// on android sends a tab instead of an enter
          e.preventDefault();
	  var nxt = elem.nextAll('input').first();				// Find next input in same div
          if (nxt.length == 0) { nxt = elem.parent().next().find('input'); }    // if none, find next input
          if (nxt.length == 0) { nxt = $('#nextbutton'); }			// or the next button
	  //elem.parent().next().find('input').focus();
          if (nxt.is('button')) {
            nxt.click();
          }
	  else if (nxt.is('input')) {
            nxt.focus();
          }
	}
      });
    }
  }
})

.directive('autoFocus', function($timeout) {
    return {
        restrict: 'AC',
        link: function(_scope, _element) {
            $timeout(function(){
		_element[0].focus();
                _element[0].click();
            }, 0);
        }
    };
})

// our controller for the form
// =============================================================================
.controller('formController', function($scope, $state, $http) {
    
    // we will store all of our form data in this object
    $scope.formData = {'PassType': 'weekend', 'Same': false};
    
    // function to process the form
    $scope.processForm = function(nextForm) {
        //alert('awesome!');  
        //$scope.clearForm();
	$state.go(nextForm);
    };

    $scope.copyContact = function() {
	if ($scope.formData['Same'] == true) {
            $scope.formData['PCFullName'] = $scope.formData['ECFullName'];
            $scope.formData['PCPhoneNumber'] = $scope.formData['ECPhoneNumber'];
        } else {
            $scope.formData['PCFullName'] = '';
            $scope.formData['PCPhoneNumber'] = '';
        }
    }

    $scope.submitForm = function() {
	$http({
		method  : 'POST',
		url     : 'tablet_add.php',
		data    : $.param($scope.formData),  // pass in data as strings
		headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  // set the headers so angular passing info as form data (not request payload)
	    })
		.success(function(data) {
		    console.log(data);

		    if (!data.success) {
		    	// if not successful, bind errors to error variables
		        $scope.message = data.message;
		        alert(data.message);
		    } else {
		    	// if successful, bind success message to message
		        $scope.message = data.message;
                alert(data.message);
                $scope.clearForm();
                $state.go('form.name');

		    }
		});
    }

    $scope.clearForm = function() {
        $scope.formData = {'PassType': 'weekend', 'Same': false};
	$state.go('form.name');

    };
    
});
