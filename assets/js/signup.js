$(function(){
  // Password validation function
  function validatePassword(password) {
    const checks = {
      length: password.length >= 8,
      uppercase: /[A-Z]/.test(password),
      lowercase: /[a-z]/.test(password),
      number: /[0-9]/.test(password),
      special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
    };
    return checks;
  }

  // Real-time password validation feedback
  $('#password').on('input', function(){
    const password = $(this).val();
    const checks = validatePassword(password);
    
    updateCheckUI('checkLength', checks.length);
    updateCheckUI('checkUpperCase', checks.uppercase);
    updateCheckUI('checkLowerCase', checks.lowercase);
    updateCheckUI('checkNumber', checks.number);
    updateCheckUI('checkSpecial', checks.special);
  });

  function updateCheckUI(elementId, isValid) {
    const $element = $('#' + elementId);
    if(isValid) {
      $element.removeClass('text-muted').addClass('text-success');
      $element.find('i').css('color', '#28a745');
    } else {
      $element.removeClass('text-success').addClass('text-muted');
      $element.find('i').css('color', '#ccc');
    }
  }

  $('#signupForm').on('submit', function(e){
    e.preventDefault();
    
    var firstName = $('#firstName').val().trim();
    var lastName = $('#lastName').val().trim();
    var email = $('#email').val().trim();
    var password = $('#password').val();
    var confirmPassword = $('#confirmPassword').val();
    var agreeTerms = $('#agreeTerms').is(':checked');

    // Validate password strength
    const passwordChecks = validatePassword(password);
    if(!passwordChecks.length || !passwordChecks.uppercase || !passwordChecks.lowercase || !passwordChecks.number || !passwordChecks.special) {
      $('#signupResult').html('<div class="alert alert-danger">Password does not meet all requirements. Please check the requirements above.</div>');
      return;
    }

    if(password !== confirmPassword){
      $('#signupResult').html('<div class="alert alert-danger">Passwords do not match</div>');
      return;
    }

    if(!agreeTerms){
      $('#signupResult').html('<div class="alert alert-danger">You must agree to the Terms and Conditions</div>');
      return;
    }

    $.ajax({
      url: 'api/register.php',
      method: 'POST',
      data: {
        first_name: firstName,
        last_name: lastName,
        email: email,
        password: password
      },
      dataType: 'json'
    }).done(function(resp){
      if(resp.success){
        $('#signupResult').html('<div class="alert alert-success">Registered successfully. Redirecting to login...</div>');
        setTimeout(function(){ window.location.href = 'login.html'; }, 1200);
      } else {
        $('#signupResult').html('<div class="alert alert-danger">'+(resp.message||'Error')+'</div>');
      }
    }).fail(function(){
      $('#signupResult').html('<div class="alert alert-danger">Network error</div>');
    });
  });
});
