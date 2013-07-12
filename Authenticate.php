<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Authenticate
 *
 * @author weitzeilsl
 */
class Oath2 {
//    // OAuth 2 Authentication Example
//
//    private $clientId = 'ABCD-EFGH-JKLM-NOPQ-RSTU-VWXY-0123-4567';
//
//    /**
//     * Begin OAuth2 authentication by redirecting
//     * the user to the authorization endpoint
//     * where they will enter their username and password
//     */
//    
//    function OAuth2Authenticate() {
//
//      // The redirect will be set to this url of this page (minus the query string)
//      $redirect = window.location.protocol + '//' + window.location.host + window.location.pathname;
//
//      // URL of the OAuth2 authorization endpoint
//      $authURL = 'https://sandbox.familysearch.org/cis-web/oauth2/v3/authorization';
//
//      // Setup the correct OAuth parameters which includes:
//      //   response_type=code
//      //   client_id=YOUR-CLIENT-ID
//      //   redirect_uri=YOUR-REDIRECT-URI
//      $parameters = '?response_type=code&client_id=' + $clientId + '&redirect_uri=' + $redirect;
//
//      // Put it all together and redirect the user to the url
////      window.location.href = authURL + parameters;
//    }
//
//    /**
//     * After the user successfully authenticates
//     * with FamilySearch, they will be redirected
//     * to us with an authorization code. We will
//     * use that code to request a valid access token.
//     * We will store the resulting access token in
//     * a cookie.
//     */
//    function OAuth2AccessToken(code) {
//
//      // Setup the parameters that we'll be
//      // passing to the token endpoint
//      var parameters = '?grant_type=authorization_code&code=' + code + '&client_id=' + clientId;
//
//      // Show a spinner to indicate that we're waiting
//      $('#oauth2_spinner').show();
//
//      // Pass the code to the token endpoint
//      restAPI.post('/cis-web/oauth2/v3/token' + parameters).done(function(response){
//
//        // Store the access token
//        setCookie('fsdc-access-token', response.access_token);
//
//        // Reload the page without the query parameters,
//        // otherwise the query parameters cause some funny behavior
//        window.location.href = window.location.pathname;
//
//      });
//
//    }
//
//    // Process the different states we can be in during the OAuth2 process
//    $(document).ready(function(){
//
//      // Do we have an access token yet?
//      var accessToken = getAccessToken();
//      if( accessToken ) {
//
//        // Add the Authorization header to the rest service
//        restAPI.addOptions({ headers: {'Authorization' : 'Bearer ' + accessToken }});
//
//        // Enable the logout button
//        $('#logout').show();
//        $('#authenticate-btn').hide();
//
//        // Get user details
//        restAPI.get('/platform/users/current').done(function(response){
//          $('#username')
//            .html(response.users[0].contactName)
//            .attr('href', 'https://sandbox.familysearch.org/platform/users/current.json?access_token=' + accessToken);
//          $('#access-token').html(accessToken);
//          $('#userdetails').show();
//        }).fail(function(error){
//          OAuth2Logout();
//        });
//      }
//
//      // After the user authenticates and is redirected to our page,
//      // capture the code that is sent as a query parameter. We will
//      // send the code in step 3 with the access_token request
//      else if( getQueryParameterByName('code') ) {
//        var code = getQueryParameterByName('code');
//        OAuth2AccessToken(code);
//      }
//
//      // Check for an error
//      else if( getQueryParameterByName('error') ) {
//        displayOAuth2Error(getQueryParameterByName('error_description'));
//      }
//
//      // We have not begun the oauth2 process yet
//      else {
//        $('.oauth2-beginning').show();
//        $('#oauth2-authenticate').attr('disabled', false);
//      }
//
//    });
//
//    function OAuth2Logout() {
//      // Delete the access token
//      setCookie('fsdc-access-token', '');
//
//      // Reload the page
//      window.location.href = window.location.pathname;
//    }
//
//    function displayOAuth2Error(error) {
//      $('.oauth2').hide();
//      $('.oauth2-error').show();
//      $('#oauth2-error-message').html(error);
//    }
}


?>
